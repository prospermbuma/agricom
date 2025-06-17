<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->chatParticipants()
            ->with(['conversation.participants.user', 'conversation.chatMessages'])
            ->get()
            ->map(function ($participant) {
                return $participant->conversation;
            });

        $users = User::where('id', '!=', $user->id)
            ->where('is_active', true)
            ->get();

        return view('chat.index', compact('conversations', 'users'));
    }

    public function show(ChatConversation $conversation)
    {
        $this->authorize('view', $conversation);
        
        $messages = $conversation->chatMessages()
            ->with('user')
            ->latest()
            ->limit(50)
            ->get()
            ->reverse();

        // Mark messages as read
        $conversation->chatMessages()
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Update last read timestamp
        $conversation->participants()
            ->where('user_id', Auth::id())
            ->update(['last_read_at' => now()]);

        return view('chat.show', compact('conversation', 'messages'));
    }

    public function store(Request $request, ChatConversation $conversation)
    {
        $this->authorize('participate', $conversation);
        
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $conversation->chatMessages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'type' => 'text',
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($message)
            ->log('Message sent in chat');

        return response()->json([
            'message' => $message->load('user'),
            'success' => true,
        ]);
    }

    public function createConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $otherUser = User::findOrFail($request->user_id);

        // Check if conversation already exists
        $existingConversation = ChatConversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('participants', function ($query) use ($otherUser) {
            $query->where('user_id', $otherUser->id);
        })->where('type', 'private')->first();

        if ($existingConversation) {
            return redirect()->route('chat.show', $existingConversation);
        }

        // Create new conversation
        $conversation = ChatConversation::create([
            'type' => 'private',
            'created_by' => $user->id,
        ]);

        // Add participants
        $conversation->participants()->createMany([
            ['user_id' => $user->id],
            ['user_id' => $otherUser->id],
        ]);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($conversation)
            ->log('Chat conversation created');

        return redirect()->route('chat.show', $conversation);
    }
}