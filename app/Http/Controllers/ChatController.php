<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $chats = $request->user()
            ->chats()
            ->with(['participants', 'latestMessage.user'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($chats);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:private,group',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // For private chats, check if chat already exists
        if ($request->type === 'private' && count($request->participants) === 1) {
            $existingChat = ChatConversation::where('type', 'private')
                ->whereHas('participants', function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                })
                ->whereHas('participants', function ($query) use ($request) {
                    $query->where('user_id', $request->participants[0]);
                })
                ->first();

            if ($existingChat) {
                return response()->json($existingChat->load(['participants', 'latestMessage']));
            }
        }

        $chat = ChatConversation::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        // Add participants
        $participants = array_merge($request->participants, [$request->user()->id]);
        $chat->participants()->attach(array_unique($participants));

        $this->activityLogService->log(
            'chat_created',
            "Chat created",
            $request->user(),
            $chat
        );

        return response()->json($chat->load(['participants', 'latestMessage']), 201);
    }

    public function show(Request $request, ChatConversation $chat)
    {
        // Check if user is participant
        if (!$chat->participants->contains($request->user()->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        // Mark messages as read
        $chat->messages()
            ->where('user_id', '!=', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'chat' => $chat->load('participants'),
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request, ChatConversation $chat)
    {
        // Check if user is participant
        if (!$chat->participants->contains($request->user()->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'type' => 'required|in:text,image,file',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $messageData = [
            'content' => $request->content,
            'type' => $request->type,
            'chat_id' => $chat->id,
            'user_id' => $request->user()->id,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('chat/files', $filename, 'public');
            $messageData['file_path'] = $path;
        }

        $message = ChatMessage::create($messageData);

        $this->activityLogService->log(
            'message_sent',
            "Message sent in chat",
            $request->user(),
            $message
        );

        // Update chat timestamp
        $chat->touch();

        return response()->json($message->load('user'), 201);
    }

    public function getUsers(Request $request)
    {
        $users = User::where('id', '!=', $request->user()->id)
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'role', 'region', 'village')
            ->get();

        return response()->json($users);
    }
}
