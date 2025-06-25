<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;

class ChatController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get conversations where user is an active participant
        $conversations = ChatConversation::forUser($user->id)
            ->with(['latestMessage.user', 'activeParticipants.user'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get available users for new conversations
        $users = User::where('id', '!=', $user->id)
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'role')
            ->get();

        return view('chat.index', compact('conversations', 'users'));
    }

    public function createConversation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:private,group',
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = $request->user();

        // For private chats, check if chat already exists
        if ($request->type === 'private' && count($request->participants) === 1) {
            $existingChat = ChatConversation::private()
                ->whereHas('participants', function ($query) use ($user) {
                    $query->where('user_id', $user->id)->whereNull('left_at');
                })
                ->whereHas('participants', function ($query) use ($request) {
                    $query->where('user_id', $request->participants[0])->whereNull('left_at');
                })
                ->first();

            if ($existingChat) {
                return redirect()->route('chat.show', $existingChat->id);
            }
        }

        // Create conversation
        $conversation = ChatConversation::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'created_by' => $user->id,
        ]);

        // Add participants
        $participants = array_merge($request->participants, [$user->id]);
        foreach (array_unique($participants) as $participantId) {
            $conversation->addParticipant($participantId, $participantId === $user->id);
        }

        $this->activityLogService->log(
            'chat_created',
            "Chat conversation created: {$conversation->name}",
            $user,
            $conversation
        );

        return redirect()->route('chat.show', $conversation->id)
            ->with('success', 'Chat created successfully.');
    }

    public function show(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        // Check if user is an active participant
        if (!$conversation->hasParticipant($user->id)) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $participants = $conversation->activeParticipants()->with('user')->get();

        return view('chat.show', compact('conversation', 'messages', 'participants'));
    }

    public function store(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        // Check if user is an active participant
        if (!$conversation->hasParticipant($user->id)) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required_without:file|string|max:1000',
            'type' => 'required|in:text,image,file',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $messageData = [
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'type' => $request->type,
        ];

        // Handle different message types
        if ($request->type === 'text') {
            $messageData['message'] = $request->message;
        } elseif ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat/files', $filename, 'public');
            
            $messageData['file_path'] = $path;
            $messageData['file_name'] = $file->getClientOriginalName();
            $messageData['message'] = $request->message ?? "Sent a file: {$file->getClientOriginalName()}";
        }

        $message = ChatMessage::create($messageData);

        // Broadcast the message event for real-time updates
        event(new MessageSent($message));

        // Update conversation's last message timestamp
        $conversation->updateLastMessage();

        $this->activityLogService->log(
            'message_sent',
            "Message sent in conversation: {$conversation->name}",
            $user,
            $message
        );

        return redirect()->route('chat.show', $conversation->id)
            ->with('success', 'Message sent successfully!');
    }

    public function getUsers(Request $request)
    {
        $users = User::where('id', '!=', $request->user()->id)
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'role', 'region', 'village')
            ->get();

        return view('chat.users', compact('users'));
    }

    public function leaveConversation(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        if (!$conversation->hasParticipant($user->id)) {
            abort(403, 'You are not a participant in this conversation.');
        }

        $conversation->removeParticipant($user->id);

        $this->activityLogService->log(
            'chat_left',
            "Left conversation: {$conversation->name}",
            $user,
            $conversation
        );

        return redirect()->route('chat.index')
            ->with('success', 'You have left the conversation.');
    }

    public function addParticipant(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();

        // Only group chat creators or admins can add participants
        if (!$conversation->isGroup() || 
            ($conversation->created_by !== $user->id && 
             !$conversation->participants()->where('user_id', $user->id)->where('is_admin', true)->exists())) {
            abort(403, 'You cannot add participants to this conversation.');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($conversation->hasParticipant($request->user_id)) {
            return redirect()->back()->with('error', 'User is already a participant.');
        }

        $conversation->addParticipant($request->user_id);

        $this->activityLogService->log(
            'participant_added',
            "Added participant to conversation: {$conversation->name}",
            $user,
            $conversation
        );

        return redirect()->back()->with('success', 'Participant added successfully.');
    }

    /**
     * Return a list of online/active users for chat (excluding the current user).
     */
    public function getOnlineUsers(Request $request)
    {
        $users = \App\Models\User::where('id', '!=', $request->user()->id)
            ->where('is_active', true)
            ->select('id', 'name', 'role', 'region', 'village')
            ->get()
            ->map(function ($user) {
                $user->avatar_url = $user->avatar_url;
                return $user;
            });
            
        return response()->json($users);
    }

    /**
     * Return messages for a conversation as JSON (AJAX endpoint)
     */
    public function messages(Request $request, ChatConversation $conversation)
    {
        $user = $request->user();
        if (!$conversation) {
            return response()->json(['error' => 'Conversation not found.'], 404);
        }
        if (!$conversation->hasParticipant($user->id)) {
            return response()->json(['error' => 'You are not a participant in this conversation.'], 403);
        }
        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->user_id,
                    'sender_name' => $message->user->name,
                    'created_at' => $message->created_at->toDateTimeString(),
                ];
            });
        return response()->json($messages);
    }
}
