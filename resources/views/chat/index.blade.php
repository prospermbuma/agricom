@extends('layouts.app')

@section('title', 'Chat - Agricom')

@section('content')
<div class="row mt-4">
    <div class="col-md-4">
        <!-- Chat Users List -->
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-users"></i> Available Users</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($users ?? [] as $user)
                        <a href="#" class="list-group-item list-group-item-action chat-user" 
                           data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ ucfirst($user->role) }} - {{ $user->village }}</small>
                                </div>
                                <small class="text-muted">
                                    @if($user->role === 'veo')
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="fas fa-seedling text-success"></i>
                                    @endif
                                </small>
                            </div>
                        </a>
                    @empty
                        <div class="p-3 text-center text-muted">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>No users available for chat.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Online Users -->
        <div class="card mt-3">
            <div class="card-header">
                <h6><i class="fas fa-circle text-success"></i> Online Now</h6>
            </div>
            <div class="card-body">
                <div id="online-users">
                    <!-- Online users will be populated via JavaScript -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Chat Window -->
        <div class="card">
            <div class="card-header" id="chat-header">
                <h6><i class="fas fa-comments"></i> Select a user to start chatting</h6>
            </div>
            <div class="card-body">
                <!-- Chat Messages Container -->
                <div class="chat-container" id="chat-messages">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <h5>Welcome to Agricom Chat</h5>
                        <p>Select a user from the left to start a conversation</p>
                    </div>
                </div>
                
                <!-- Chat Input Form -->
                <form id="chat-form" class="mt-3" style="display: none;">
                    @csrf
                    <div class="input-group">
                        <input type="hidden" id="receiver-id">
                        <input type="text" id="message-input" class="form-control" 
                               placeholder="Type your message..." autocomplete="off">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-body">
                <h6><i class="fas fa-bolt"></i> Quick Actions</h6>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-outline-success btn-sm w-100 mb-2" onclick="sendQuickMessage('Hello! How can I help you today?')">
                            <i class="fas fa-hand-wave"></i> Greeting
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-info btn-sm w-100 mb-2" onclick="sendQuickMessage('What crops are you currently growing?')">
                            <i class="fas fa-seedling"></i> Ask about Crops
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-warning btn-sm w-100 mb-2" onclick="sendQuickMessage('Are you experiencing any pest or disease issues?')">
                            <i class="fas fa-bug"></i> Pest Issues
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-danger btn-sm w-100 mb-2" onclick="sendQuickMessage('Do you need information about new farming techniques?')">
                            <i class="fas fa-tools"></i> Techniques
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentReceiverId = null;
let currentReceiverName = null;

$(document).ready(function() {
    // Initialize chat
    setupChat();
    
    // Check for user parameter in URL
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('user');
    if (userId) {
        // Auto-select user if specified in URL
        selectUser(userId);
    }
});

function setupChat() {
    // Handle user selection
    $('.chat-user').click(function(e) {
        e.preventDefault();
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        
        selectUser(userId, userName);
    });
    
    // Handle chat form submission
    $('#chat-form').submit(function(e) {
        e.preventDefault();
        sendMessage();
    });
    
    // Auto-scroll chat to bottom
    scrollChatToBottom();
    
    // Fetch online users
    fetchOnlineUsers();
    
    // Set up periodic message fetching
    setInterval(fetchMessages, 3000); // Fetch every 3 seconds
    setInterval(fetchOnlineUsers, 30000); // Update online users every 30 seconds
}

function selectUser(userId, userName) {
    currentReceiverId = userId;
    
    // If userName not provided, get it from the user list
    if (!userName) {
        const userElement = $(`.chat-user[data-user-id="${userId}"]`);
        userName = userElement.data('user-name');
    }
    
    currentReceiverName = userName;
    
    // Update UI
    $('.chat-user').removeClass('active');
    $(`.chat-user[data-user-id="${userId}"]`).addClass('active');
    
    $('#chat-header').html(`<h6><i class="fas fa-comments"></i> Chat with ${userName}</h6>`);
    $('#receiver-id').val(userId);
    $('#chat-form').show();
    
    // Load chat history
    loadChatHistory();
}

function sendMessage() {
    const message = $('#message-input').val().trim();
    if (!message || !currentReceiverId) return;
    
    // Add message to UI immediately
    appendMessage(message, 'sent');
    $('#message-input').val('');
    
    // Send via AJAX
    $.ajax({
        url: '/chat/send',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            receiver_id: currentReceiverId,
            message: message
        },
        success: function(response) {
            // Message sent successfully
            scrollChatToBottom();
        },
        error: function(xhr) {
            alert('Failed to send message. Please try again.');
        }
    });
}

function sendQuickMessage(message) {
    if (!currentReceiverId) {
        alert('Please select a user to chat with first.');
        return;
    }
    
    $('#message-input').val(message);
    sendMessage();
}

function loadChatHistory() {
    if (!currentReceiverId) return;
    
    $.ajax({
        url: `/chat/history/${currentReceiverId}`,
        method: 'GET',
        success: function(messages) {
            displayMessages(messages);
        }
    });
}

function fetchMessages() {
    if (!currentReceiverId) return;
    
    $.ajax({
        url: `/chat/messages/${currentReceiverId}`,
        method: 'GET',
        success: function(messages) {
            // Only update if there are new messages
            if (messages.length > 0) {
                displayMessages(messages);
            }
        }
    });
}

function displayMessages(messages) {
    const chatContainer = $('#chat-messages');
    chatContainer.empty();
    
    if (messages.length === 0) {
        chatContainer.html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-comments fa-2x mb-2"></i>
                <p>No messages yet. Start the conversation!</p>
            </div>
        `);
        return;
    }
    
    messages.forEach(function(message) {
        const messageClass = message.sender_id == {{ auth()->id() }} ? 'sent' : 'received';
        appendMessage(message.message, messageClass, message.created_at, message.sender_name);
    });
    
    scrollChatToBottom();
}

function appendMessage(message, type, timestamp = null, senderName = null) {
    const chatContainer = $('#chat-messages');
    const time = timestamp ? new Date(timestamp).toLocaleTimeString() : new Date().toLocaleTimeString();
    
    let messageHtml = '';
    if (type === 'sent') {
        messageHtml = `
            <div class="d-flex justify-content-end mb-2">
                <div class="bg-primary text-white p-2 rounded" style="max-width: 70%;">
                    <p class="mb-0">${message}</p>
                    <small class="opacity-75">${time}</small>
                </div>
            </div>
        `;
    } else {
        messageHtml = `
            <div class="d-flex justify-content-start mb-2">
                <div class="bg-light p-2 rounded" style="max-width: 70%;">
                    ${senderName ? `<small class="text-primary fw-bold">${senderName}</small><br>` : ''}
                    <p class="mb-0">${message}</p>
                    <small class="text-muted">${time}</small>
                </div>
            </div>
        `;
    }
    
    chatContainer.append(messageHtml);
    scrollChatToBottom();
}

function scrollChatToBottom() {
    const chatContainer = $('#chat-messages');
    chatContainer.scrollTop(chatContainer[0].scrollHeight);
}

function fetchOnlineUsers() {
    $.ajax({
        url: '/chat/online-users',
        method: 'GET',
        success: function(users) {
            displayOnlineUsers(users);
        }
    });
}

function displayOnlineUsers(users) {
    const container = $('#online-users');
    container.empty();
    
    if (users.length === 0) {
        container.html('<small class="text-muted">No users online</small>');
        return;
    }
    
    users.forEach(function(user) {
        container.append(`
            <div class="d-flex align-items-center mb-1">
                <i class="fas fa-circle text-success me-2" style="font-size: 0.6em;"></i>
                <small>${user.name}</small>
            </div>
        `);
    });
}

// Handle Enter key in message input
$('#message-input').keypress(function(e) {
    if (e.which === 13) { // Enter key
        sendMessage();
    }
});
</script>
@endsection
