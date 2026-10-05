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
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .reset-card {
            max-width: 450px;
            margin: 0 auto;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #198754 0%, #157347 100%);
            color: white;
            text-align: center;
            padding: 2rem;
            border-radius: 15px 15px 0 0;
        }
        .card-header img {
            margin-bottom: 1rem;
        }
        .card-header h4 {
            margin-bottom: 0.5rem;
        }
        .card-body {
            padding: 2rem;
        }
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        .form-control {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        .btn-success {
            background-color: #198754;
            border-color: #198754;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 500;
        }
        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }
        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
            padding: 0.75rem;
            border-radius: 8px;
        }
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
            border-radius: 8px;
        }
        .password-requirements {
            background-color: #f8f9fa;
            border-left: 3px solid #198754;
            padding: 0.75rem;
            border-radius: 4px;
            margin-top: 0.5rem;
        }
        .password-requirements p {
            margin-bottom: 0.35rem;
            font-size: 0.875rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s ease;
        }
        .password-requirements p:last-child {
            margin-bottom: 0;
        }
        .password-requirements i {
            width: 18px;
            font-size: 0.9rem;
            transition: color 0.2s ease;
            margin-right: 0;
        }
        /* ✅ Color states for requirement items */
        .password-requirements p.requirement-met {
            color: #198754;
            font-weight: 500;
        }
        .password-requirements p.requirement-met i {
            color: #198754 !important;
        }
        .password-requirements p.requirement-unmet {
            color: #6c757d;
        }
        .password-requirements p.requirement-unmet i {
            color: #adb5bd !important;
        }
        .password-requirements p.requirement-failed {
            color: #dc3545;
            font-weight: 500;
        }
        .password-requirements p.requirement-failed i {
            color: #dc3545 !important;
        }
        /* ✅ Password match indicator */
        .password-match-indicator {
            font-size: 0.85rem;
            margin-top: 0.35rem;
            display: none;
        }
        .password-match-indicator.show {
            display: block;
        }
        .password-match-indicator.match {
            color: #198754;
        }
        .password-match-indicator.no-match {
            color: #dc3545;
        }
        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }
            .card-header {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card reset-card">
                    <div class="card-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="50" class="mb-3" onerror="this.src='https://via.placeholder.com/50x50?text=VE'">
                        <h4 class="mb-1">Reset Password</h4>
                        <p class="mb-0 text-white-50">Create a new password for your account</p>
                    </div>

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                @foreach($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-fill me-2 text-success"></i>Email Address
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ $email ?? old('email') }}" 
                                       readonly
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock-fill me-2 text-success"></i>New Password
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter new password"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">
                                    <i class="bi bi-lock-fill me-2 text-success"></i>Confirm New Password
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password-confirm" 
                                       name="password_confirmation" 
                                       placeholder="Confirm new password"
                                       required>
                                <!-- ✅ Password match indicator -->
                                <div class="password-match-indicator" id="passwordMatchIndicator"></div>
                            </div>

                            <!-- ✅ Enhanced Password Requirements with live validation -->
                            <div class="password-requirements" id="passwordRequirements">
                                <p class="requirement-unmet" data-requirement="length">
                                    <i class="bi bi-circle"></i> Minimum 8 characters
                                </p>
                                <p class="requirement-unmet" data-requirement="uppercase">
                                    <i class="bi bi-circle"></i> At least one uppercase letter
                                </p>
                                <p class="requirement-unmet" data-requirement="number">
                                    <i class="bi bi-circle"></i> At least one number
                                </p>
                                <p class="requirement-unmet" data-requirement="special">
                                    <i class="bi bi-circle"></i> At least one special character (!@#$%^&*)
                                </p>
                            </div>

                            <div class="d-grid gap-3 mt-4">
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    <i class="bi bi-check-circle me-2"></i>Reset Password
                                </button>
                                
                                <a href="{{ route('login.show') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Login
                                </a>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted small mb-0">
                                <i class="bi bi-shop me-1"></i> Vape Expo - 5 Branches in Calamba
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ Live Password Validation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password-confirm');
            const requirements = document.querySelectorAll('#passwordRequirements p');
            const matchIndicator = document.getElementById('passwordMatchIndicator');
            const form = document.getElementById('resetPasswordForm');

            // Requirement checks
            function validatePassword(pw) {
                return {
                    length: pw.length >= 8,
                    uppercase: /[A-Z]/.test(pw),
                    number: /[0-9]/.test(pw),
                    special: /[!@#$%^&*(),.?":{}|<>_\-+=\[\]\\\/;'`~]/.test(pw)
                };
            }

            // Update requirement indicators
            function updateRequirements(pw) {
                const checks = validatePassword(pw);

                requirements.forEach(function(el) {
                    const key = el.dataset.requirement;
                    const icon = el.querySelector('i');

                    el.classList.remove('requirement-met', 'requirement-unmet', 'requirement-failed');

                    if (checks[key]) {
                        el.classList.add('requirement-met');
                        icon.className = 'bi bi-check-circle-fill';
                    } else {
                        // If user has typed something but this requirement isn't met, show red
                        if (pw.length > 0) {
                            el.classList.add('requirement-failed');
                            icon.className = 'bi bi-x-circle-fill';
                        } else {
                            el.classList.add('requirement-unmet');
                            icon.className = 'bi bi-circle';
                        }
                    }
                });
            }

            // Update password match indicator
            function updateMatchIndicator() {
                const pw = passwordInput.value;
                const confirm = confirmInput.value;

                if (confirm.length === 0) {
                    matchIndicator.classList.remove('show', 'match', 'no-match');
                    matchIndicator.textContent = '';
                    return;
                }

                matchIndicator.classList.add('show');
                if (pw === confirm) {
                    matchIndicator.classList.remove('no-match');
                    matchIndicator.classList.add('match');
                    matchIndicator.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Passwords match';
                } else {
                    matchIndicator.classList.remove('match');
                    matchIndicator.classList.add('no-match');
                    matchIndicator.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>Passwords do not match';
                }
            }

            // Real-time password validation
            passwordInput.addEventListener('input', function() {
                updateRequirements(this.value);
                updateMatchIndicator();
            });

            // Confirm password validation
            confirmInput.addEventListener('input', function() {
                updateMatchIndicator();
            });

            // Block form submission if password doesn't meet requirements
            form.addEventListener('submit', function(e) {
                const pw = passwordInput.value;
                const checks = validatePassword(pw);
                const allMet = Object.values(checks).every(v => v === true);

                if (!allMet) {
                    e.preventDefault();
                    passwordInput.focus();
                    updateRequirements(pw);
                    alert('Please make sure your password meets all the requirements before continuing.');
                    return false;
                }

                if (pw !== confirmInput.value) {
                    e.preventDefault();
                    confirmInput.focus();
                    alert('Passwords do not match.');
                    return false;
                }
            });

            // Initial state
            updateRequirements('');
        });
    </script>
</body>
</html>