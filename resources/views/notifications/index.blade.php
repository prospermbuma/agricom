@extends('layouts.app')

@section('title', 'Notifications - Agricom')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Notifications</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-1"></i> Mark All as Read
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="clearAllNotifications()">
                        <i class="fas fa-trash me-1"></i> Clear All
                    </button>
                </div>
            </div>

            @if($notifications->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @foreach($notifications as $notification)
                            <div class="notification-item p-3 border-bottom {{ $notification->isUnread() ? 'bg-light' : '' }}" 
                                 data-notification-id="{{ $notification->id }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            @if($notification->isUnread())
                                                <span class="badge bg-primary me-2">New</span>
                                            @endif
                                            <h6 class="mb-0 fw-bold">{{ $notification->title }}</h6>
                                        </div>
                                        <p class="text-muted mb-2">{{ $notification->message }}</p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $notification->time_ago }}
                                            </small>
                                            <div class="d-flex gap-1">
                                                @if($notification->isUnread())
                                                    <button class="btn btn-sm btn-outline-primary" 
                                                            onclick="markAsRead({{ $notification->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteNotification({{ $notification->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No notifications</h4>
                    <p class="text-muted">You're all caught up!</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function markAllAsRead() {
    if (!confirm('Mark all notifications as read?')) return;
    
    fetch('/notifications/read-all', {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Delete this notification?')) return;
    
    fetch(`/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function clearAllNotifications() {
    if (!confirm('Clear all notifications? This action cannot be undone.')) return;
    
    fetch('/notifications', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection 