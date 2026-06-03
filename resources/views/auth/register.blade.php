<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Vape Expo</title>
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
        .register-card {
            max-width: 650px;
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
        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            color: #212529;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            color: #212529;
        }
        .form-control::placeholder {
            color: #adb5bd;
            opacity: 1;
        }
        .form-text {
            color: #6c757d;
            font-size: 0.8rem;
        }
        .btn-register {
            background: #0d6efd;
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        .btn-register:hover {
            background: #0b5ed7;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .btn-outline-secondary {
            border-color: #ced4da;
            color: #6c757d;
        }
        .btn-outline-secondary:hover {
            background-color: #e9ecef;
            color: #212529;
            border-color: #ced4da;
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
            margin-top: 0.5rem;
        }
        .shop-badge span {
            color: #6c757d;
            font-size: 0.85rem;
        }
        .password-requirements {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            border-left: 3px solid #0d6efd;
        }
        .password-requirements p {
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
        }
        .password-requirements i {
            color: #0d6efd;
            width: 20px;
        }
        .invalid-feedback {
            color: #dc3545;
        }
        .modal-content {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
        }
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
        .modal-title {
            color: #0d6efd;
        }
        .btn-close {
            filter: none;
        }
        .policy-content {
            max-height: 400px;
            overflow-y: auto;
            color: #212529;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .policy-content h6 {
            color: #0d6efd;
            margin-top: 1rem;
        }
        .policy-content h6:first-child {
            margin-top: 0;
        }
        .text-white-50 {
            color: #6c757d !important;
        }
        .border-secondary {
            border-color: #dee2e6 !important;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }
        .text-muted {
            color: #6c757d !important;
        }
        h6.text-white {
            color: #212529 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card register-card">
                    <!-- Header with Logo -->
                    <div class="card-header">
                        <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="60" class="mb-3" onerror="this.src='https://via.placeholder.com/60x60?text=VE'">
                        <h4 class="mb-2">Create Account</h4>
                        <p>Join Vape Expo and start ordering</p>
                        <div class="shop-badge">
                            <i class="bi bi-shop text-primary-custom me-2"></i>
                            <span>Vape Expo - 5 Branches in Calamba</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Registration failed!</strong>
                                @foreach($errors->all() as $error)
                                    <p class="mb-0 small">{{ $error }}</p>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <!-- Registration Form -->
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <!-- Personal Information -->
                            <h6 class="mb-3" style="color: #212529;"><i class="bi bi-person-fill text-primary-custom me-2"></i>Personal Information</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">
                                        <i class="bi bi-person-circle me-2 text-primary-custom"></i>Full Name *
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Enter your full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="bi bi-envelope-fill me-2 text-primary-custom"></i>Email Address *
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="Enter your email" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone-fill me-2 text-primary-custom"></i>Phone Number *
                                    </label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" 
                                           placeholder="e.g., 09123456789" required>
                                    <div class="form-text">Philippine mobile number (e.g., 09123456789)</div>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="birthdate" class="form-label">
                                        <i class="bi bi-calendar-date me-2 text-primary-custom"></i>Birthdate
                                    </label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" 
                                           value="{{ old('birthdate') }}">
                                    <div class="form-text">Must be 18+ to purchase vape products</div>
                                </div>
                            </div>

                            <!-- Gender Field -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="gender" class="form-label">
                                        <i class="bi bi-gender-ambiguous me-2 text-primary-custom"></i>Gender
                                    </label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <h6 class="mt-4 mb-3" style="color: #212529;"><i class="bi bi-geo-alt-fill text-primary-custom me-2"></i>Address Information</h6>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="bi bi-house-fill me-2 text-primary-custom"></i>Complete Address *
                                </label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" 
                                          placeholder="Street, Building, House Number" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barangay Field -->
                            <div class="mb-3">
                                <label for="barangay" class="form-label">
                                    <i class="bi bi-building me-2 text-primary-custom"></i>Barangay *
                                </label>
                                <input type="text" class="form-control @error('barangay') is-invalid @enderror" 
                                       id="barangay" name="barangay" value="{{ old('barangay') }}" 
                                       placeholder="Enter your barangay" required>
                                <div class="form-text">Your barangay/village/subdivision</div>
                                @error('barangay')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Landmark Field -->
                            <div class="mb-3">
                                <label for="landmark" class="form-label">
                                    <i class="bi bi-pin-map-fill me-2 text-primary-custom"></i>Landmark (Optional)
                                </label>
                                <input type="text" class="form-control" id="landmark" name="landmark" 
                                       value="{{ old('landmark') }}" 
                                       placeholder="e.g., Near 7-Eleven, Beside Jollibee">
                                <div class="form-text">Help our riders find your location easily</div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <select class="form-select" id="city" name="city">
                                        <option value="Calamba" selected>Calamba</option>
                                        <option value="Los Baños">Los Baños</option>
                                        <option value="Cabuyao">Cabuyao</option>
                                        <option value="Santa Rosa">Santa Rosa</option>
                                        <option value="Biñan">Biñan</option>
                                        <option value="San Pedro">San Pedro</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="province" class="form-label">Province</label>
                                    <input type="text" class="form-control" id="province" name="province" 
                                           value="Laguna" readonly>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="zip_code" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" 
                                           value="{{ old('zip_code') }}" placeholder="e.g., 4027">
                                </div>
                            </div>

                            <!-- Security Information -->
                            <h6 class="mt-4 mb-3" style="color: #212529;"><i class="bi bi-shield-lock-fill text-primary-custom me-2"></i>Security</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-lock-fill me-2 text-primary-custom"></i>Password *
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="Create password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="bi bi-lock-fill me-2 text-primary-custom"></i>Confirm Password *
                                    </label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirm password" required>
                                </div>
                            </div>

                            <!-- Password Requirements -->
                            <div class="password-requirements">
                                <p><i class="bi bi-check-circle-fill" style="color: #28a745;"></i> Minimum 8 characters</p>
                                <p><i class="bi bi-check-circle-fill" style="color: #28a745;"></i> At least one uppercase letter</p>
                                <p><i class="bi bi-check-circle-fill" style="color: #28a745;"></i> At least one number</p>
                            </div>

                            <!-- Terms and Conditions with Modals -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" style="color: #6c757d; font-size: 0.9rem;" for="terms">
                                    I agree to the 
                                    <a href="#" class="text-primary-custom" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> and 
                                    <a href="#" class="text-primary-custom" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>. 
                                    I confirm that I am at least 18 years old.
                                </label>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" checked>
                                <label class="form-check-label" style="color: #6c757d; font-size: 0.9rem;" for="newsletter">
                                    I want to receive updates about new products and promotions
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-register w-100">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                        </form>
                        
                        <!-- Divider -->
                        <div class="divider">
                            <span>Already have an account?</span>
                        </div>
                        
                        <!-- Login Link -->
                        <div class="text-center mb-3">
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In Instead
                            </a>
                        </div>
                        
                        <!-- Back to Home -->
                        <div class="text-center">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                                <i class="bi bi-arrow-left me-1"></i>Back to Home
                            </a>
                        </div>

                        <!-- Owner Information -->
                        <div class="mt-4 pt-3 text-center border-top" style="border-color: #dee2e6 !important;">
                            <p class="text-muted small mb-0">
                                <i class="bi bi-telephone me-1" style="color: #0d6efd;"></i> 0960 328 0432
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-clock me-1" style="color: #0d6efd;"></i> Store Hours: 9:00 AM - 10:00 PM Daily
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">
                        <i class="bi bi-file-text me-2"></i>Terms and Conditions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="policy-content">
                        <h6>1. Acceptance of Terms</h6>
                        <p>By accessing and using the Vape Expo website and services, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you may not use our services.</p>

                        <h6>2. Age Restriction</h6>
                        <p>You must be at least 18 years old to purchase or use any vape products from Vape Expo. By creating an account, you confirm that you are of legal age to purchase tobacco/vape products in the Philippines.</p>

                        <h6>3. Account Registration</h6>
                        <p>You are responsible for maintaining the confidentiality of your account credentials. You agree to provide accurate, current, and complete information during registration.</p>

                        <h6>4. Product Usage</h6>
                        <p>Vape products are intended for adult use only. We are not responsible for misuse of products. Always follow manufacturer guidelines and safety instructions.</p>

                        <h6>5. Orders and Payments</h6>
                        <p>All orders are subject to availability and confirmation. We accept various payment methods as indicated during checkout. Prices are in Philippine Peso (₱) and inclusive of applicable taxes.</p>

                        <h6>6. Shipping and Delivery</h6>
                        <p>We deliver to addresses within the Philippines. Delivery times may vary based on location. Risk of loss passes to you upon delivery.</p>

                        <h6>7. Returns and Refunds</h6>
                        <p>Due to the nature of vape products, we only accept returns for defective items. Please contact us within 24 hours of receiving damaged products.</p>

                        <h6>8. Prohibited Activities</h6>
                        <p>You agree not to: resell products, use our site for illegal purposes, interfere with site functionality, or attempt unauthorized access.</p>

                        <h6>9. Limitation of Liability</h6>
                        <p>Vape Expo shall not be liable for any indirect, incidental, or consequential damages arising from use of our products or services.</p>

                        <h6>10. Changes to Terms</h6>
                        <p>We reserve the right to modify these terms at any time. Continued use of our services constitutes acceptance of updated terms.</p>

                        <p class="mt-3 text-primary-custom"><small>Last updated: January 2025</small></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="document.getElementById('terms').checked = true;">
                        <i class="bi bi-check-lg me-2"></i>I Agree
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">
                        <i class="bi bi-shield-lock me-2"></i>Privacy Policy
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="policy-content">
                        <h6>1. Information We Collect</h6>
                        <p>We collect personal information you provide during registration including name, email address, phone number, delivery address, and birthdate for age verification.</p>

                        <h6>2. How We Use Your Information</h6>
                        <p>Your information is used to process orders, communicate with you, improve our services, and comply with legal requirements for age-restricted products.</p>

                        <h6>3. Data Protection</h6>
                        <p>We implement security measures to protect your personal information. However, no method of transmission over the internet is 100% secure.</p>

                        <h6>4. Sharing of Information</h6>
                        <p>We do not sell or rent your personal information to third parties. We may share information with delivery partners to fulfill orders or when required by law.</p>

                        <h6>5. Age Verification</h6>
                        <p>We are required by law to verify that customers are at least 18 years old. Your birthdate may be used for this purpose and will be handled confidentially.</p>

                        <h6>6. Marketing Communications</h6>
                        <p>With your consent, we may send promotional emails about new products and special offers. You can opt-out at any time.</p>

                        <h6>7. Cookies</h6>
                        <p>Our website uses cookies to enhance your browsing experience and analyze site traffic.</p>

                        <h6>8. Your Rights</h6>
                        <p>You have the right to access, correct, or delete your personal information. Contact us at https://www.facebook.com/vpxpo for assistance.</p>

                        <h6>9. Data Retention</h6>
                        <p>We retain your information as long as your account is active or as needed to provide services and comply with legal obligations.</p>

                        <h6>10. Contact Information</h6>
                        <p>For privacy-related concerns, contact the owner Carlo Caranto at https://www.facebook.com/vpxpo or call 0960 328 0432.</p>

                        <p class="mt-3 text-primary-custom"><small>Last updated: January 2025</small></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="document.getElementById('terms').checked = true;">
                        <i class="bi bi-check-lg me-2"></i>I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle modal agreement -->
    <script>
        // Optional: Auto-check terms when user clicks "I Agree" in modal
        document.addEventListener('DOMContentLoaded', function() {
            const termsModal = document.getElementById('termsModal');
            const privacyModal = document.getElementById('privacyModal');
            
            // When user clicks "I Agree" in terms modal, check the terms checkbox
            document.querySelectorAll('[data-bs-dismiss="modal"][onclick*="terms.checked"]').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('terms').checked = true;
                });
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>