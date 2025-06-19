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

    <!-- Vite: Include CSS and JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            scrollbar-width: none;
            scroll-behavior: smooth;
        }

        .navbar-brand {
            font-weight: bold;
            color: #28a745 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .3em;
        }

        .navbar .login-btn,
        .navbar .register-btn {
            border: 2px solid #28a745;
            padding: 10px 34px;
            border-radius: 50px;
            font-weight: 500;
            transition: all .4s;
        }

        .navbar .login-btn {
            background: transparent;
            color: #28a745;
        }

        .navbar .login-btn:hover {
            background: #28a745;
            color: #fff;
        }

        .navbar .register-btn {
            background: #28a745;
            color: #ffffff;
        }

        .navbar .register-btn:hover {
            background: transparent;
            color: #28a745;
        }

        @media only screen and (max-width: 768px) {

            .navbar .login-btn,
            .navbar .register-btn {
                border: 1px solid #28a745;
                padding: 6px 24px;
            }

        }

        .login-link,
        .register-link {
            text-decoration: none;
            color: #28a745;
            font-weight: 500;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .chat-container {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .activity-log {
            font-size: 0.9em;
            color: #666;
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid px-md-5 py-1 py-md-2">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="40px">
                {{-- <i class="fas fa-seedling"></i>  --}}
                Agricom
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('articles.index') }}">
                                <i class="fas fa-newspaper"></i> Articles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('chat.index') }}">
                                <i class="fas fa-comments"></i> Chat
                            </a>
                        </li>
                        @if (auth()->user()->role === 'veo')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('articles.create') }}">
                                    <i class="fas fa-plus"></i> Create Article
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
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user"></i> Profile
                                    </a>
                                </li>
                                @php
                                    $user = auth()->user();
                                @endphp

                                @if (in_array($user->role, ['veo', 'admin']))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('activity.logs') }}">
                                            <i class="fas fa-history"></i> Activity Logs
                                        </a>
                                    </li>
                                @endif
                                {{-- <li><a class="dropdown-item" href="{{ route('activity.logs') }}">
                                        <i class="fas fa-history"></i> Activity Logs
                                    </a></li>
                                <li> --}}
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
                            <span class="login-btn">Login</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <span class="register-btn">Register</span>
                        </a>
                    </li>
                @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <!-- Only show footer while on login/register routes -->
    @php
        $currentRoute = Route::currentRouteName();
    @endphp

    @if (in_array($currentRoute, ['login', 'register']))
        <footer class="bg-light text-center py-3 mt-5">
            <div class="container">
                <p class="mb-0">&copy; <span id="year"></span> Agricom - Tanzania</p>
            </div>
        </footer>
    @endif


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Get Year -->
    <script>
        const domYear = document.querySelector('#year');
        const today = new Date();
        domYear.innerText = today.getFullYear();
    </script>

    @yield('scripts')
</body>

</html>
