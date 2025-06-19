@extends('layouts.app')

@section('title', 'Chat with {{ $otherUser->name }} - Agricom')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <!-- Chat Header -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('chat.index') }}" class="btn btn-outline-light btn-sm me-3">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <div class="profile-info">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-light text-primary me-3">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $otherUser->name }}</h5>
                                            <small class="opacity-75">
                                                <i
                                                    class="fas fa-{{ $otherUser->role === 'farmer' ? 'seedling' : ($otherUser->role === 'veo' ? 'star' : 'shopping-cart') }}"></i>
                                                {{ ucfirst($otherUser->role) }}
                                                @if ($otherUser->village)
                                                    • {{ $otherUser->village }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-actions">
                                <span class="badge bg-light text-dark" id="online-status">
                                    <i class="fas fa-circle text-success"></i> Online
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="card mt-2">
                    <div class="card-body p-0">
                        <div class="chat-messages-container" id="chat-messages">
                            @forelse($messages as $message)
                                <div
                                    class="message-wrapper {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }} p-3">
                                    @if ($message->sender_id === auth()->id())
                                        <!-- Sent Message -->
                                        <div class="d-flex justify-content-end">
                                            <div class="message-bubble bg-primary text-white">
                                                <p class="mb-1">{{ $message->message }}</p>
                                                <div class="message-meta">
                                                    <small class="opacity-75">
                                                        <i class="fas fa-check"></i>
                                                        {{ $message->created_at->format('g:i A') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Received Message -->
                                        <div class="d-flex justify-content-start">
                                            <div class="d-flex">
                                                <div class="avatar-sm bg-secondary text-white me-2">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="message-bubble bg-light">
                                                    <div class="sender-name">
                                                        <small
                                                            class="text-primary fw-bold">{{ $message->sender->name }}</small>
                                                    </div>
                                                    <p class="mb-1">{{ $message->message }}</p>
                                                    <div class="message-meta">
                                                        <small class="text-muted">
                                                            {{ $message->created_at->format('g:i A') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No messages yet</h5>
                                    <p class="text-muted">Start the conversation with {{ $otherUser->name }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="card mt-2 sticky-bottom">
                    <div class="card-body">
                        <form id="message-form" method="POST" action="{{ route('chat.send') }}">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">

                            <div class="input-group">
                                <input type="text" name="message" id="message-input" class="form-control form-control-lg"
                                    placeholder="Type your message to {{ $otherUser->name }}..." autocomplete="off"
                                    required>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Quick Actions -->
                        <div class="quick-actions mt-3">
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-outline-success btn-sm quick-msg"
                                    data-message="Hello! How can I help you today?">
                                    <i class="fas fa-hand-wave"></i> Greeting
                                </button>

                                @if ($otherUser->role === 'farmer')
                                    <button class="btn btn-outline-info btn-sm quick-msg"
                                        data-message="What crops are you currently growing?">
                                        <i class="fas fa-seedling"></i> Ask about Crops
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm quick-msg"
                                        data-message="Are you experiencing any pest or disease issues?">
                                        <i class="fas fa-bug"></i> Pest Issues
                                    </button>
                                    <button class="btn btn-outline-primary btn-sm quick-msg"
                                        data-message="Do you have crops available for sale?">
                                        <i class="fas fa-shopping-cart"></i> Buying Interest
                                    </button>
                                @endif

                                @if ($otherUser->role === 'buyer')
                                    <button class="btn btn-outline-info btn-sm quick-msg"
                                        data-message="What type of crops are you looking for?">
                                        <i class="fas fa-search"></i> Crop Interest
                                    </button>
                                    <button class="btn btn-outline-success btn-sm quick-msg"
                                        data-message="I have fresh crops available. Are you interested?">
                                        <i class="fas fa-leaf"></i> Offer Crops
                                    </button>
                                @endif

                                <button class="btn btn-outline-secondary btn-sm quick-msg"
                                    data-message="Thank you for your time!">
                                    <i class="fas fa-heart"></i> Thank You
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Info Modal -->
    <div class="modal fade" id="userInfoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $otherUser->name }}'s Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="avatar-large bg-primary text-white mb-3">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $otherUser->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $otherUser->role === 'farmer' ? 'success' : 'info' }}">
                                            {{ ucfirst($otherUser->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Region:</strong></td>
                                    <td>{{ $otherUser->region ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Village:</strong></td>
                                    <td>{{ $otherUser->village ?? 'Not specified' }}</td>
                                </tr>
                                @if ($otherUser->role === 'farmer' && $otherUser->crops)
                                    <tr>
                                        <td><strong>Crops:</strong></td>
                                        <td>
                                            @foreach (json_decode($otherUser->crops) as $crop)
                                                <span class="badge bg-light text-dark me-1">{{ ucfirst($crop) }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Member Since:</strong></td>
                                    <td>{{ $otherUser->created_at->format('F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .chat-messages-container {
                height: 400px;
                overflow-y: auto;
                background: #f8f9fa;
            }

            .message-bubble {
                max-width: 70%;
                padding: 12px 16px;
                border-radius: 18px;
                margin-bottom: 8px;
                word-wrap: break-word;
            }

            .message-bubble.bg-primary {
                border-bottom-right-radius: 6px;
            }

            .message-bubble.bg-light {
                border-bottom-left-radius: 6px;
                border: 1px solid #dee2e6;
            }

            .avatar-circle {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .avatar-sm {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8em;
            }

            .avatar-large {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto;
            }

            .message-wrapper.sent {
                background: linear-gradient(to right, transparent 30%, rgba(0, 123, 255, 0.1));
            }

            .message-wrapper.received {
                background: linear-gradient(to left, transparent 30%, rgba(248, 249, 250, 0.5));
            }

            .sticky-bottom {
                position: sticky;
                bottom: 0;
                z-index: 1000;
            }

            .quick-actions {
                border-top: 1px solid #dee2e6;
                padding-top: 12px;
            }

            .quick-msg {
                cursor: pointer;
                transition: all 0.2s;
            }

            .quick-msg:hover {
                transform: translateY(-1px);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Auto-scroll to bottom
                scrollToBottom();

                // Handle form submission
                $('#message-form').on('submit', function(e) {
                    e.preventDefault();
                    sendMessage();
                });

                // Handle quick messages
                $('.quick-msg').on('click', function() {
                    const message = $(this).data('message');
                    $('#message-input').val(message);
                    $('#message-input').focus();
                });

                // Handle enter key
                $('#message-input').on('keypress', function(e) {
                    if (e.which === 13 && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });

                // Refresh messages periodically
                setInterval(refreshMessages, 5000);

                // Show user info on name click
                $('.profile-info').on('click', function() {
                    $('#userInfoModal').modal('show');
                });
            });

            function sendMessage() {
                const form = $('#message-form');
                const messageInput = $('#message-input');
                const message = messageInput.val().trim();

                if (!message) return;

                // Disable form temporarily
                form.find('button').prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Add message to chat
                        appendMessage(message, 'sent');
                        messageInput.val('');
                        scrollToBottom();
                    },
                    error: function(xhr) {
                        alert('Failed to send message. Please try again.');
                    },
                    complete: function() {
                        form.find('button').prop('disabled', false);
                        messageInput.focus();
                    }
                });
            }

            function appendMessage(message, type) {
                const chatContainer = $('#chat-messages');
                const now = new Date();
                const time = now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                let messageHtml = '';
                if (type === 'sent') {
                    messageHtml = `
                        <div class="message-wrapper sent p-3">
                            <div class="d-flex justify-content-end">
                                <div class="message-bubble bg-primary text-white">
                                    <p class="mb-1">${message}</p>
                                    <div class="message-meta">
                                        <small class="opacity-75">
                                            <i class="fas fa-check"></i> ${time}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    messageHtml = `
                        <div class="message-wrapper received p-3">
                            <div class="d-flex justify-content-start">
                                <div class="d-flex">
                                    <div class="avatar-sm bg-secondary text-white me-2">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="message-bubble bg-light">
                                        <div class="sender-name">
                                            <small class="text-primary fw-bold">{{ $otherUser->name }}</small>
                                        </div>
                                        <p class="mb-1">${message}</p>
                                        <div class="message-meta">
                                            <small class="text-muted">${time}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                chatContainer.append(messageHtml);
            }

            function scrollToBottom() {
                const chatContainer = $('#chat-messages');
                chatContainer.scrollTop(chatContainer[0].scrollHeight);
            }

            function refreshMessages() {
                // Implement AJAX call to refresh messages
                $.ajax({
                    url: window.location.href,
                    method: 'GET',
                    success: function(response) {
                        // Update messages if new ones are available
                        // This would require additional backend logic
                    }
                });
            }
        </script>
    @endpush
@endsection
