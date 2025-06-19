@extends('layouts.app')

@section('title', 'Login - Agricom')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="hero-text mb-4">
                <h1 class="text-center">
                    <i class="fas fa-leaf"></i> Agricom | Login
                </h1>
            </div>

            <div class="card shadow form-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="john.doe@example.com" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                <span class="input-group-text" style="cursor: pointer;"
                                    onclick="togglePassword('password', this)">
                                    <i class="fas fa-eye text-secondary"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="loginBtn">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-muted">Don't have an account?
                            <a href="{{ route('register') }}" class="register-link">Register here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword(fieldId, iconElement) {
            const input = document.getElementById(fieldId);
            const icon = iconElement.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endsection

@section('styles')

    <style>
        .form-card {
            margin-top: 30px;
            border-radius: 30px;
            padding-top: 50px;
            padding-bottom: 35px;
            padding-left: 32px;
            padding-right: 32px;
        }

        .form-control,
        .loginBtn {
            padding: 12px 24px;
            border-radius: 10px;
        }


        .fas {
            color: #484747;
        }

        .fas.fa-leaf {
            color: #1e7e34;
        }

        .fas.fa-sign-in-alt {
            color: #fff;
        }


        .loginBtn {
            background-color: #1e7e34;
            color: #fff;
            transition: all ease .4s;
        }

        .loginBtn:hover {
            background-color: #146e29;
            cursor: pointer;
        }
    </style>

@endsection
