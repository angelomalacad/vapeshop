<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .verify-card {
            max-width: 500px;
            margin: 0 auto;
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #212529;
            padding: 2rem 1.5rem;
            text-align: center;
            border-bottom: 3px solid #0d6efd;
        }
        .card-header img {
            margin-bottom: 1rem;
        }
        .card-header h4 {
            color: #0d6efd;
            font-weight: 600;
        }
        .card-body {
            background: #ffffff;
            padding: 2rem;
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #0b5ed7;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .btn-outline-secondary {
            background: transparent;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            font-weight: 500;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-outline-secondary:hover {
            background: #0d6efd;
            color: #ffffff;
        }
        .btn-outline-light {
            border: 1px solid #0d6efd;
            color: #0d6efd;
        }
        .btn-outline-light:hover {
            background: #0d6efd;
            color: #ffffff;
        }
        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
            border-radius: 8px;
        }
        .shop-badge {
            background: rgba(13, 110, 253, 0.1);
            border: 1px solid #0d6efd;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .shop-badge span {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .verification-icon {
            font-size: 4rem;
            color: #0d6efd;
            margin-bottom: 1.5rem;
        }
        .btn-group-vertical {
            gap: 0.5rem;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #6c757d;
            margin: 1.5rem 0;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .divider span {
            padding: 0 10px;
            font-size: 0.9rem;
        }
        .text-white {
            color: #212529 !important;
        }
        .text-white-50 {
            color: #6c757d !important;
        }
        .text-primary-custom {
            color: #0d6efd !important;
        }
        hr {
            border-color: #dee2e6 !important;
        }
        .btn-close {
            filter: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card verify-card">
                    <div class="card-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="60" class="mb-3" onerror="this.src='https://via.placeholder.com/60x60?text=VE'">
                        <h4 class="mb-2">Verify Your Email Address</h4>
                        <div class="shop-badge mt-2">
                            <i class="bi bi-shop"></i>
                            <span>Vape Expo - 5 Branches in Calamba</span>
                        </div>
                    </div>

                    <div class="card-body text-center">
                        <div class="verification-icon">
                            <i class="bi bi-envelope-check-fill"></i>
                        </div>

                        @if(session('resent'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                A fresh verification link has been sent to your email address.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <p class="mb-4" style="color: #212529;">
                            Thank you for registering with <strong class="text-primary-custom">Vape Expo</strong>!
                        </p>

                        <p class="mb-4" style="color: #6c757d;">
                            Before proceeding, please check your email for a verification link.
                            If you did not receive the email, click the button below.
                        </p>

                        <!-- Resend Button -->
                        <div class="d-grid gap-3 mb-4">
                            <form method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send me-2"></i>Resend Verification Email
                                </button>
                            </form>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="divider">
                            <span>Already verified?</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-house me-2"></i>Home
                                </a>
                            </div>
                        </div>

                        <!-- Alternative: Stacked buttons if you prefer -->
                        <!-- 
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Go to Login
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-2"></i>Back to Home
                            </a>
                        </div>
                        -->

                        <hr class="my-4">

                        <p class="small mb-0" style="color: #6c757d;">
                            <i class="bi bi-telephone me-1" style="color: #0d6efd;"></i> Need help? Call 0960 328 0432
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>