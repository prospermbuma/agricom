@extends('layouts.app')

@section('title', 'Chat - Agricom')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Left Sidebar -->
            <div class="col-lg-4">
                <!-- User Profile Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="avatar avatar-lg me-3">
                            <img src="{{ auth()->user()->avatar_url }}"
                                class="rounded-circle" alt="User Avatar">
                        </div>
                        <div>
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search users...">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-sliders-h"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chat Users List -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-users me-2 text-primary"></i> Available Users
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($users ?? [] as $user)
                                <a href="#" class="list-group-item list-group-item-action border-0 py-3 chat-user"
                                    data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <img src="{{ $user->avatar_url }}"
                                                class="rounded-circle avatar-sm" alt="{{ $user->name }}">
                                            <span
                                                class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                                style="width: 10px; height: 10px;"></span>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 text-truncate">{{ $user->name }}</h6>
                                            </div>
                                            <small class="text-muted text-truncate d-block">
                                                {{ ucfirst($user->role) }} • {{ $user->village ?? '' }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 text-center text-muted">
                                    <i class="fas fa-users-slash fa-2x mb-3 opacity-25"></i>
                                    <p class="mb-0">No users available for chat</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Chat Area -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <!-- Chat Header -->
                    <div class="card-header bg-transparent border-0 py-3" id="chat-header">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-comments me-2 text-primary"></i>
                                    <span id="chat-title">Select a user to start chatting</span>
                                </h6>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>View
                                            Profile</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-ban me-2"></i>Block
                                            User</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages Container -->
                    <div class="card-body p-0">
                        <div class="chat-container" id="chat-messages">
                            <div class="d-flex flex-column justify-content-center align-items-center h-100 text-center p-4">
                                <div class="bg-light rounded-circle p-4 mb-3">
                                    <i class="fas fa-comments fa-2x text-muted"></i>
                                </div>
                                <h5 class="fw-semibold">Welcome to Agricom Chat</h5>
                                <p class="text-muted mb-0">Select a user from the sidebar to start a conversation</p>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input Form -->
                    <div class="card-footer bg-transparent border-0 pt-0" id="chat-form" style="display: none;">
                        <form class="message-form">
                            @csrf
                            <input type="hidden" id="receiver-id">

                            <!-- Quick Actions -->
                            <div class="quick-actions mb-3">
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                        onclick="sendQuickMessage('Hello! How can I help you today?')">
                                        <i class="fas fa-hand-wave me-1"></i> Greeting
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3"
                                        onclick="sendQuickMessage('What crops are you currently growing?')">
                                        <i class="fas fa-seedling me-1"></i> Ask about Crops
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-3"
                                        onclick="sendQuickMessage('Are you experiencing any pest or disease issues?')">
                                        <i class="fas fa-bug me-1"></i> Pest Issues
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3"
                                        onclick="sendQuickMessage('Do you need information about new farming techniques?')">
                                        <i class="fas fa-tools me-1"></i> Techniques
                                    </button>
                                </div>
                            </div>

                            <!-- Message Input -->
                            <div class="input-group">
                                <button class="btn btn-outline-secondary border-end-0" type="button">
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <input type="text" id="message-input" class="form-control border-start-0 border-end-0"
                                    placeholder="Type your message..." autocomplete="off">
                                <button class="btn btn-outline-secondary border-start-0" type="button">
                                    <i class="far fa-smile"></i>
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #8BC34A;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
            --sent-bg: #e3f2fd;
            --received-bg: #f1f1f1;
        }

        body {
            background-color: #f5f7fb;
        }

        .card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 40px;
            height: 40px;
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
            object-fit: cover;
        }

        .avatar-lg {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .chat-container {
            height: 500px;
            overflow-y: auto;
            padding: 20px;
            background-color: #f8fafc;
            scroll-behavior: smooth;
        }

        .chat-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .chat-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .chat-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .message {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            margin-bottom: 12px;
            position: relative;
            word-wrap: break-word;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sent {
            background-color: var(--sent-bg);
            color: var(--dark-text);
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }

        .received {
            background-color: var(--received-bg);
            color: var(--dark-text);
            margin-right: auto;
            border-bottom-left-radius: 4px;
        }

        .message-time {
            font-size: 0.75rem;
            color: var(--light-text);
            margin-top: 4px;
            text-align: right;
        }

        .chat-user {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .chat-user:hover {
            background-color: rgba(76, 175, 80, 0.05);
            border-left-color: var(--primary-color);
        }

        .chat-user.active {
            background-color: rgba(76, 175, 80, 0.1);
            border-left-color: var(--primary-color);
        }

        .quick-actions button {
            transition: all 0.2s ease;
        }

        .quick-actions button:hover {
            transform: translateY(-2px);
        }

        .message-form .input-group {
            border-radius: 24px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .message-form .form-control {
            border: none;
            background-color: transparent;
        }

        .message-form .form-control:focus {
            box-shadow: none;
        }

        .message-form .btn-primary {
            border-radius: 0 24px 24px 0;
        }
    </style>
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
            $('#chat-form form').submit(function(e) {
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

            // Add presence channel logic to update the Available Users card
            window.Echo.join('online-users')
                .here((users) => {
                    console.log('Presence users:', users);
                    displayOnlineUsers(users);
                })
                .joining((user) => {
                    let currentUsers = getCurrentOnlineUsers();
                    currentUsers.push(user);
                    displayOnlineUsers(currentUsers);
                })
                .leaving((user) => {
                    let currentUsers = getCurrentOnlineUsers();
                    currentUsers = currentUsers.filter(u => u.id !== user.id);
                    displayOnlineUsers(currentUsers);
                });
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

            $('#chat-title').text(`Chat with ${userName}`);
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
                url: `/chat/${currentReceiverId}/messages`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    message: message,
                    type: 'text'
                },
                success: function(response) {
                    // Message sent successfully
                    scrollChatToBottom();
                },
                error: function(xhr) {
                    showToast('Failed to send message. Please try again.', 'error');
                }
            });
        }

        function sendQuickMessage(message) {
            if (!currentReceiverId) {
                showToast('Please select a user to chat with first.', 'warning');
                return;
            }

            $('#message-input').val(message);
            sendMessage();
        }

        function loadChatHistory() {
            if (!currentReceiverId) return;

            $.ajax({
                url: `/chat/${currentReceiverId}/messages`,
                method: 'GET',
                success: function(messages) {
                    displayMessages(messages);
                }
            });
        }

        function fetchMessages() {
            if (!currentReceiverId) return;

            $.ajax({
                url: `/chat/${currentReceiverId}/messages`,
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

            // Only clear if we have messages to display
            if (messages.length > 0) {
                chatContainer.empty();
            }

            if (messages.length === 0) {
                chatContainer.html(`
                <div class="d-flex flex-column justify-content-center align-items-center h-100 text-center p-4">
                    <div class="bg-light rounded-circle p-4 mb-3">
                        <i class="fas fa-comments fa-2x text-muted"></i>
                    </div>
                    <h5 class="fw-semibold">No messages yet</h5>
                    <p class="text-muted mb-0">Start the conversation with ${currentReceiverName}</p>
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
            const time = timestamp ? new Date(timestamp).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            }) : new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            let messageHtml = '';
            if (type === 'sent') {
                messageHtml = `
                <div class="message sent">
                    <div class="message-text">${message}</div>
                    <div class="message-time">${time}</div>
                </div>
            `;
            } else {
                messageHtml = `
                <div class="message received">
                    ${senderName ? `<div class="sender-name small text-primary fw-semibold mb-1">${senderName}</div>` : ''}
                    <div class="message-text">${message}</div>
                    <div class="message-time">${time}</div>
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
            const userList = $('.list-group.list-group-flush');
            userList.empty();

            if (!users.length) {
                userList.html(`
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-users-slash fa-2x mb-3 opacity-25"></i>
                        <p class="mb-0">No users available for chat</p>
                    </div>
                `);
                return;
            }

            users.forEach(function(user) {
                userList.append(`
                    <a href="#" class="list-group-item list-group-item-action border-0 py-3 chat-user"
                        data-user-id="${user.id}" data-user-name="${user.name}">
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <img src="${user.avatar_url}" class="rounded-circle avatar-sm" alt="${user.name}">
                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" style="width: 10px; height: 10px;"></span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-truncate">${user.name}</h6>
                                </div>
                                <small class="text-muted text-truncate d-block">
                                    ${user.role.charAt(0).toUpperCase() + user.role.slice(1)} • ${user.village ?? ''}
                                </small>
                            </div>
                        </div>
                    </a>
                `);
            });

            // Re-bind click event for new user elements
            $('.chat-user').off('click').on('click', function(e) {
                e.preventDefault();
                const userId = $(this).data('user-id');
                const userName = $(this).data('user-name');
                selectUser(userId, userName);
            });
        }

        function getCurrentOnlineUsers() {
            return $('.chat-user').map(function() {
                return {
                    id: $(this).data('user-id'),
                    name: $(this).data('user-name'),
                    avatar_url: $(this).find('img').attr('src'),
                    role: $(this).find('small').text().split('•')[0].trim().toLowerCase(),
                    village: $(this).find('small').text().split('•')[1]?.trim() || ''
                };
            }).get();
        }

        function showToast(message, type = 'success') {
            // Implement a toast notification system
            const toast = `<div class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>`;

            $('.toast-container').append(toast);
            $('.toast').toast('show');

            setTimeout(() => {
                $('.toast').toast('hide').on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }, 3000);
        }

        // Handle Enter key in message input
        $('#message-input').keypress(function(e) {
            if (e.which === 13) { // Enter key
                sendMessage();
            }
        });
    </script>
@endsection
