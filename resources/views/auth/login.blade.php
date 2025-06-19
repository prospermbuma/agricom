@extends('layouts.app')

@section('title', 'Login - Agricom')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="hero-text mb-5">
                <h1 class="text-center">
                    Agricom | Login
                </h1>
            </div>
            <div class="card shadow p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="{{ route('register') }}" class="register-link">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
