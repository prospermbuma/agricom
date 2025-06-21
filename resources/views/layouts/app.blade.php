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
        html {
            scrollbar-width: none;
            scroll-behavior: smooth;
        }

        body {
            font-family: "Outfit", sans-serif;
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
        $currentRoute = Route::currentRouteName();
        $hidePublicNav = in_array($currentRoute, ['dashboard', 'articles.index', 'chat.index', 'articles.create']);
    @endphp

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container px-md-5 py-2">
            <a class="navbar-brand" href="{{ route('home') }}">
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
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                                href="{{ route('about') }}">
                                About
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                                href="{{ route('contact') }}">
                                Contact
                            </a>
                        </li>
                    @endunless

                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('articles.index') ? 'active' : '' }}"
                                href="{{ route('articles.index') }}">
                                <i class="fas fa-newspaper"></i> Articles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}"
                                href="{{ route('chat.index') }}">
                                <i class="fas fa-comments"></i> Chat
                            </a>
                        </li>
                        @if (auth()->user()->role === 'veo')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('articles.create') ? 'active' : '' }}"
                                    href="{{ route('articles.create') }}">
                                    <i class="fas fa-plus"></i> New Article
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav">
                    @auth
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
                                        <a class="dropdown-item" href="{{ route('activity.logs') }}">
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

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="container-fluid my-5">
        @yield('content')
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
    </script>

    @yield('scripts')
</body>

</html>
