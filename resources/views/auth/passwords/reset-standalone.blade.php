<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Vape Expo</title>
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
        .reset-card {
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
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .form-control {
            background-color: #ffffff;
            border: 2px solid #e9ecef;
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
        .btn-reset {
            background: #0d6efd;
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-reset:hover {
            background: #0b5ed7;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
            border-radius: 8px;
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
        
        /* Password requirements */
        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            border-left: 4px solid #0d6efd;
        }
        .password-requirements p {
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .password-requirements i {
            color: #28a745;
            margin-right: 0.75rem;
        }
        
        .form-text {
            color: #6c757d;
        }
        
        .back-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }
        
        .footer-text {
            color: #6c757d;
        }
        .footer-text i {
            color: #0d6efd;
        }
        .footer-text strong {
            color: #0d6efd;
        }
        .invalid-feedback {
            color: #dc3545;
        }
        hr {
            border-color: #e9ecef !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card reset-card">
                    <div class="card-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="60" class="mb-3" onerror="this.src='https://via.placeholder.com/60x60?text=VE'">
                        <h4 class="mb-2">Create New Password</h4>
                        <p>Enter your new password below</p>
                        <div class="shop-badge mt-2">
                            <i class="bi bi-shop" style="color: #0d6efd;"></i>
                            <span>Vape Expo - 5 Branches in Calamba</span>
                        </div>
                    </div>

                    <div class="card-body">
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

                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <!-- IMPORTANT: Hidden email field from the reset link -->
                            <input type="hidden" name="email" value="{{ request()->email }}">

                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-2" style="color: #0d6efd;"></i>New Password
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Enter new password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimum 8 characters</div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">
                                    <i class="bi bi-lock-fill me-2" style="color: #0d6efd;"></i>Confirm Password
                                </label>
                                <input type="password" class="form-control" id="password-confirm" 
                                       name="password_confirmation" placeholder="Confirm new password" required>
                            </div>

                            <!-- Password Requirements -->
                            <div class="password-requirements">
                                <p><i class="bi bi-check-circle-fill"></i> Minimum 8 characters</p>
                                <p><i class="bi bi-check-circle-fill"></i> At least one uppercase letter</p>
                                <p><i class="bi bi-check-circle-fill"></i> At least one number</p>
                            </div>

                            <button type="submit" class="btn btn-reset w-100">
                                <i class="bi bi-check-circle me-2"></i>Reset Password
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="back-link">
                                <i class="bi bi-arrow-left me-1"></i>Back to Login
                            </a>
                        </div>

                        <hr class="my-4">

                        <p class="footer-text text-center mb-0">
                            <i class="bi bi-telephone"></i> Need help? Call <strong>0960 328 0432</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>