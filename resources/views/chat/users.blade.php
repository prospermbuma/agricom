@extends('layouts.app')

@section('title', 'Chat Users - Agricom')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Chat Users</h2>
                <a href="{{ route('chat.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Chats
                </a>
            </div>

            <div class="row">
                @foreach($users as $user)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ $user->avatar_url }}" 
                                             class="rounded-circle border-2" 
                                             alt="{{ $user->name }}">
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $user->name }}</h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $user->village }}, {{ $user->region }}
                                    </small>
                                </div>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary flex-fill" 
                                            onclick="startPrivateChat({{ $user->id }})">
                                        <i class="fas fa-comment me-1"></i> Message
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($users->count() === 0)
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No users found</h4>
                    <p class="text-muted">There are no other active users to chat with.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Start Private Chat Modal -->
<div class="modal fade" id="startChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start Private Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="startChatForm" action="{{ route('chat.conversations.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="participants[]" id="selectedUserId">
                    <input type="hidden" name="type" value="private">
                    
                    <div class="mb-3">
                        <label class="form-label">Chat with:</label>
                        <p class="form-control-plaintext" id="selectedUserName"></p>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Initial Message (Optional)</label>
                        <textarea class="form-control" name="message" id="message" rows="3" 
                                  placeholder="Type your first message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Start Chat</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function startPrivateChat(userId) {
    // Get user info from the card
    const userCard = event.target.closest('.card');
    const userName = userCard.querySelector('h6').textContent;
    
    // Set modal values
    document.getElementById('selectedUserId').value = userId;
    document.getElementById('selectedUserName').textContent = userName;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('startChatModal'));
    modal.show();
}
</script>
@endpush
@endsection 