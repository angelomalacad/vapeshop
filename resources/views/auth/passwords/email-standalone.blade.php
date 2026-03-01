<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Vape Expo</title>
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
        .forgot-card {
            max-width: 450px;
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
            margin-bottom: 0.5rem;
        }
        .card-header p {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        .card-body {
            background: #ffffff;
            padding: 2rem;
        }
        .form-label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-control {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            color: #212529;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            color: #212529;
        }
        .form-control::placeholder {
            color: #adb5bd;
            opacity: 1;
        }
        .btn-forgot {
            background: #0d6efd;
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-forgot:hover {
            background: #0b5ed7;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .btn-outline-reset {
            background: transparent;
            border: 1px solid #0d6efd;
            color: #0d6efd;
            font-weight: 500;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-outline-reset:hover {
            background: #0d6efd;
            color: #ffffff;
        }
        .text-muted-custom {
            color: #6c757d !important;
        }
        .text-primary-custom {
            color: #0d6efd !important;
        }
        .text-primary-custom:hover {
            color: #0a58ca !important;
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
        .shop-badge {
            background: rgba(13, 110, 253, 0.1);
            border: 1px solid #0d6efd;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            display: inline-block;
            margin-bottom: 1rem;
        }
        .shop-badge i {
            color: #0d6efd;
            margin-right: 0.5rem;
        }
        .shop-badge span {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .owner-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            border-left: 3px solid #0d6efd;
        }
        .owner-info p {
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
        }
        .owner-info i {
            color: #0d6efd;
            width: 20px;
        }
        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
            border-radius: 8px;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
            border-radius: 8px;
        }
        .alert-success i, .alert-danger i {
            color: inherit;
        }
        .btn-close {
            filter: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card forgot-card">
                    <!-- Header with Logo -->
                    <div class="card-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="60" class="mb-3" onerror="this.src='https://via.placeholder.com/60x60?text=VE'">
                        <h4 class="mb-2">Forgot Password?</h4>
                        <p>Enter your email to reset your password</p>
                        <div class="shop-badge mt-2">
                            <i class="bi bi-shop"></i>
                            <span>Vape Expo - 5 Branches in Calamba</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Success Message -->
                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Error!</strong>
                                @foreach($errors->all() as $error)
                                    <p class="mb-0 small">{{ $error }}</p>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <!-- Forgot Password Form -->
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2 text-primary-custom"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email') }}" placeholder="Enter your registered email" required autofocus>
                            </div>
                            
                            <button type="submit" class="btn btn-forgot w-100">
                                <i class="bi bi-send me-2"></i>Send Reset Link
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="divider">
                            <span>Remember your password?</span>
                        </div>
                        
                        <!-- Back to Login -->
                        <div class="text-center mb-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-reset w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Back to Login
                            </a>
                        </div>
                        
                        <!-- Back to Home -->
                        <div class="text-center">
                            <a href="{{ route('home') }}" class="text-muted-custom text-decoration-none small">
                                <i class="bi bi-arrow-left me-1"></i>Back to Home
                            </a>
                        </div>
                        
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>