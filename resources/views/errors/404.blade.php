@extends('layouts.error')

@section('title', 'Page Not Found - Agricom')

@section('content')
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-10 col-lg-8 mx-auto text-center">
                <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <i class="fas fa-exclamation-triangle fa-6x text-warning-emphasis mb-3 animated-icon"></i>
                            <h1 class="display-1 fw-bold text-dark-emphasis mb-0">404</h1>
                        </div>
                        <h2 class="h2 fw-bold text-primary mb-3">Oops! Page Not Found</h2>
                        <p class="lead text-muted mb-4">
                            We can't seem to find the page you're looking for. It might have been moved or deleted.
                        </p>
                        <div class="d-grid gap-2 col-md-8 mx-auto">
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                                <i class="fas fa-home me-2"></i> Go to Homepage
                            </a>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill px-5">
                                <i class="fas fa-arrow-left me-2"></i> Go Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f0f2f5;
            font-family: "Outfit", sans-serif;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            flex-grow: 1;
        }

        .card {
            border-radius: 1.5rem;
        }

        .animated-icon {
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.1);
                opacity: 0;
            }

            60% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        .display-1 {
            font-size: 8rem;
            line-height: 1;
        }

        .btn {
            font-weight: 600;
            border-radius: 2rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 0.8rem 1.5rem rgba(0, 123, 255, 0.25) !important;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 0.8rem 1.5rem rgba(108, 117, 125, 0.25) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        /* .text-warning-emphasis {
            color: #ffc107 !important;
        } */
         
        .text-warning-emphasis {
            color: var(--primary-color) !important;
        }

        .text-dark-emphasis {
            color: #212529 !important;
        }
    </style>
@endsection
