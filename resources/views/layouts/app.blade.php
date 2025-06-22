<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agricom')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Outfit:wght@100..900&display=swap"
        rel="stylesheet">
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #8BC34A;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
        }
        html {
            scrollbar-width: none;
            scroll-behavior: smooth;
        }

        body {
            font-family: "Outfit", sans-serif;
             background-color: #fff;
        }
        .navbar-brand {
            font-weight: bold;
            color: #28a745 !important;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .navbar .nav-link {
            font-weight: 500;
            color: #333 !important;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: #28a745 !important;
        }

        .navbar .login-btn,
        .navbar .register-btn {
            border: 2px solid #28a745;
            padding: 8px 30px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar .login-btn {
            background-color: transparent;
            color: #28a745;
        }

        .navbar .login-btn:hover {
            background-color: #28a745;
            color: #fff;
        }

        .navbar .register-btn {
            background-color: #28a745;
            color: #fff;
        }

        .navbar .register-btn:hover {
            background-color: transparent;
            color: #28a745;
        }

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

        .dropdown-menu {
            border-radius: 10px;
        }

        .login-link,
        .register-link {
            color: #28a745;
            font-weight: 500;
            text-decoration: none;
        }

        .login-link:hover,
        .register-link:hover {
            text-decoration: underline;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px 16px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
        }

        footer {
            border-top: 1px solid #eaeaea;
        }

        @media (max-width: 768px) {

            .navbar .login-btn,
            .navbar .register-btn {
                padding: 6px 18px;
                font-size: 0.9rem;
            }

            .navbar-brand {
                margin-left: 15px
            }

            .navbar-toggler {
                margin-right: 15px
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    @php
        use Illuminate\Support\Facades\Route;
        use Illuminate\Support\Facades\Auth;

        $currentRoute = Route::current();
        $middleware = $currentRoute ? $currentRoute->gatherMiddleware() : [];
        $hidePublicNav = in_array('auth', $middleware);
    @endphp

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="{{ $hidePublicNav ? 'container-fluid px-md-5 py-1' : 'container px-md-5 py-2' }}">
            <a class="navbar-brand" href="{{ $hidePublicNav ? route('dashboard') : route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="36">
                Agricom
            </a>


            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @unless ($hidePublicNav)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" {{-- href="{{ route('about') }}"> --}}
                                href="#">
                                About
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" {{-- href="{{ route('contact') }}"> --}}
                                href="#">
                                Contact
                            </a>
                        </li>
                    @endunless
                </ul>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <p class="text-muted mt-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </p>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user-circle"></i> Profile
                                    </a>
                                </li>
                                @if (in_array(auth()->user()->role, ['veo', 'admin']))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('activity-logs.index') }}">
                                            <i class="fas fa-history"></i> Activity Logs
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <span class="login-btn">Sign In</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <span class="register-btn">Get started</span>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="main-wrapper">
        @auth
            @if ($hidePublicNav)
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar shadow-sm p-3 sidebar-sticky">
                    <div class="position-sticky">
                        <h5 class="text-success mb-4"><i class="fas fa-seedling"></i> Agricom</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    <i class="fas fa-cog me-2"></i> Dashboard
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
                                        <i class="fas fa-plus-circle me-2"></i> New Article
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

                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item mb-2">
                                    <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                                        href="{{ route('users.index') }}">
                                        <i class="fas fa-users me-2"></i> Users
                                    </a>
                                </li>
                            @endif

                            @if (in_array(auth()->user()->role, ['admin', 'veo']))
                                <li class="nav-item mb-2">
                                    <a class="nav-link {{ request()->routeIs('activity-logs.index') ? 'active' : '' }}"
                                        href="{{ route('activity-logs.index') }}">
                                        <i class="fas fa-history me-2"></i> Activity Logs
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item mt-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link text-start text-danger w-100">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            @endif
        @endauth

        <!-- Page content wrapper -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Optional mobile sidebar toggler -->
            @auth
                @if ($hidePublicNav)
                    <button class="btn btn-outline-success d-md-none m-2" id="openSidebar">
                        <i class="fas fa-bars"></i> Menu
                    </button>
                @endif
            @endauth

            <!-- Flash messages -->
            <div class="container mt-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <!-- Main Content -->
            <main class="container-fluid py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer (Only on login & register pages) -->
    @if (in_array($currentRoute, ['login', 'register']))
        <footer class="bg-light text-center py-3 mt-auto">
            <div class="container">
                <p class="mb-0 text-muted">&copy; <span id="year"></span> Agricom - Tanzania</p>
            </div>
        </footer>
    @endif

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Year Script -->
    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
        document.getElementById('toggleSidebar')?.addEventListener('click', () => {
            document.getElementById('sidebar').style.display = 'none';
        });

        document.getElementById('openSidebar')?.addEventListener('click', () => {
            document.getElementById('sidebar').style.display = 'block';
        });
    </script>

    @yield('scripts')
</body>

</html>
