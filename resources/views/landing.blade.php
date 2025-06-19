@extends('layouts.app')

@section('title', 'Welcome to Agricom')

@section('styles')
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        .hero-section {
            background: url('{{ asset('images/farm-hero.jpg') }}') center center / cover no-repeat;
            min-height: 85vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
            position: relative;
            border-radius: 50px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50px;
        }

        .hero-content {
            position: relative;
            text-align: center;
            max-width: 700px;
            padding: 2rem;
        }

        .hero-content h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        .hero-cta {
            margin-top: 3rem;
        }

        .hero-cta .register-btn {
            border: 2px solid #28a745;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
             background-color: #28a745;
            color: #fff;
        }

        .hero-cta .register-btn:hover {
            background-color: transparent;
        }

        .features-section {
            padding: 60px 15px;
            background-color: #f9f9f9;
        }

        .features-section h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            font-size: 40px;
            color: #1e7e34;
        }

        .features-section .card {
            border-radius: 30px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .features-section .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }


        .feature-icon {
            font-size: 2.5rem;
            color: #28a745;
        }

        .cta-footer {
            padding: 60px 30px;
            border-radius: 50px;
            background: linear-gradient(135deg, #28a745, #218838, #1e7e34);
            background-size: 300% 300%;
            animation: gradientFlow 6s ease infinite;
            color: #fff;
            text-align: center;
            margin-top: -30px;
            margin-bottom: 60px;
            box-shadow: 0 8px 24px rgba(40, 167, 69, 0.4);
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .cta-footer .btn {
            background: #fff;
            color: #28a745;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 30px;
            transition: 0.3s ease;
        }

        .cta-footer .btn:hover {
            background: #e2e2e2;
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="container">
        <section class="hero-section">
            <div class="hero-content">
                <h1>Connecting Farmers & VEOs</h1>
                <p>Empowering Tanzania's agriculture through digital communication.</p>

                <div class="hero-cta">
                    <a href="{{ route('register') }}" class="register-btn">
                        Get Started
                    </a>
                </div>
            </div>
        </section>
    </div>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="feature-text">What Agricom Offers</h2>
            <div class="row text-center mt-4">
                <div class="col-md-4 mb-4">
                    <div class="card p-5">
                        <i class="fas fa-seedling feature-icon"></i>
                        <h5 class="mt-3">Farmer-Centered Tools</h5>
                        <p>Record your crops, farm size, and get tailored insights for your region.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card p-5">
                        <i class="fas fa-user-tie feature-icon"></i>
                        <h5 class="mt-3">Support from VEOs</h5>
                        <p>Village Extension Officers can send updates, reply to queries, and log progress.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card p-5">
                        <i class="fas fa-comments feature-icon"></i>
                        <h5 class="mt-3">Real-time Chat</h5>
                        <p>Instant messaging to connect directly with farmers or officers across villages.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- CTA Footer -->
    <div class="container">
        <section class="cta-footer">
            <div data-aos="fade-up">
                <h3 class="mb-3">Join 1,000+ Tanzanian farmers & VEOs already using Agricom</h3>
                <a href="{{ route('register') }}" class="btn mt-2">
                    <i class="fas fa-arrow-right"></i> Get Started Now
                </a>
            </div>
        </section>
    </div>

@endsection

@section('scripts')
    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 120,
            once: true
        });
    </script>
@endsection
