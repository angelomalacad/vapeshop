<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
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
        
        /* Password input container styles */
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #0d6efd;
            background: transparent;
            border: none;
            z-index: 10;
        }
        .password-toggle:hover {
            color: #0a58ca;
        }
        .password-toggle i {
            font-size: 1.2rem;
        }
        
        .btn-login {
            background: #0d6efd;
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        .btn-login:hover {
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
            padding: 0.5rem 1rem;
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
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }
        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card login-card">
                    <!-- Header with Logo -->
                    <div class="card-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="60" class="mb-3" onerror="this.src='https://via.placeholder.com/60x60?text=VE'">
                        <h4 class="mb-2">Welcome Back!</h4>
                        <p>Sign in to access your account</p>
                        <div class="shop-badge mt-2">
                            <i class="bi bi-shop"></i>
                            <span>Vape Expo - 5 Branches in Calamba</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Login failed!</strong>
                                @foreach($errors->all() as $error)
                                    <p class="mb-0 small">{{ $error }}</p>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2 text-primary-custom"></i>Email Address
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                            </div>
                            
                            <!-- Password field with toggle visibility -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-2 text-primary-custom"></i>Password
                                </label>
                                <div class="password-container">
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Enter your password" required>
                                    <button type="button" class="password-toggle" id="togglePassword" onclick="togglePasswordVisibility()">
                                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted-custom" for="remember">
                                        Remember me
                                    </label>
                                </div>
                                <a href="{{ route('password.request') }}" class="text-primary-custom text-decoration-none small">
                                    <i class="bi bi-key me-1"></i>Forgot Password?
                                </a>
                            </div>
                            
                            <button type="submit" class="btn btn-login w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="divider">
                            <span>New to Vape Expo?</span>
                        </div>
                        
                        <!-- Register Link -->
                        <div class="text-center mb-3">
                            <a href="{{ route('register') }}" class="btn btn-outline-reset w-100">
                                <i class="bi bi-person-plus me-2"></i>Create New Account
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
    
    <!-- Password Toggle Script -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }
    </script>
</body>
</html>