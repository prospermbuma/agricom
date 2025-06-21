<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar shadow-sm p-3 sidebar-sticky">
    <div class="position-sticky">
        <h5 class="text-success mb-4"><i class="fas fa-seedling"></i> Agricom</h5>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('articles.index') ? 'active' : '' }}"
                    href="{{ route('articles.index') }}">
                    <i class="fas fa-newspaper me-2"></i> Articles
                </a>
            </li>
            @if (auth()->user()->role === 'veo')
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('articles.create') ? 'active' : '' }}"
                        href="{{ route('articles.create') }}">
                        <i class="fas fa-plus me-2"></i> New Article
                    </a>
                </li>
            @endif
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}"
                    href="{{ route('chat.index') }}">
                    <i class="fas fa-comments me-2"></i> Chat
                </a>
            </li>
            <li class="nav-item mb-2">
                <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}"
                    href="{{ route('profile.show') }}">
                    <i class="fas fa-user me-2"></i> Profile
                </a>
            </li>
            @if (in_array(auth()->user()->role, ['admin', 'veo']))
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('activity.logs') ? 'active' : '' }}"
                        href="{{ route('activity.logs') }}">
                        <i class="fas fa-history me-2"></i> Activity Logs
                    </a>
                </li>
            @endif
            <li class="nav-item mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

@section('styles')
<style>
    .sidebar-sticky {
    top: 1rem;
    height: 100vh;
    position: sticky;
    border-radius: 12px;
    background-color: #f8f9fa;
}

.sidebar .nav-link {
    color: #333;
    font-weight: 500;
    padding: 10px 15px;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.sidebar .nav-link.active,
.sidebar .nav-link:hover {
    background-color: #d4edda;
    color: #155724;
}
</style>
@endsection