@extends('layouts.app')

@section('title', 'Reset Password - Vape Expo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-success text-white text-center py-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="50" class="mb-3" onerror="this.src='https://via.placeholder.com/50x50?text=VE'">
                    <h4 class="mb-1">Set New Password</h4>
                    <p class="text-white-50 mb-0 small">Create a new password for your account</p>
                </div>

                <div class="card-body bg-dark p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            @foreach($errors->all() as $error)
                                <p class="mb-0 small">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label text-white">
                                <i class="bi bi-envelope-fill me-2 text-success"></i>Email Address
                            </label>
                            <input type="email" class="form-control bg-dark text-white border-secondary" 
                                   id="email" name="email" value="{{ $email ?? old('email') }}" 
                                   readonly required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-white">
                                <i class="bi bi-lock-fill me-2 text-success"></i>New Password
                            </label>
                            <input type="password" class="form-control bg-dark text-white border-secondary" 
                                   id="password" name="password" placeholder="Enter new password" required>
                            <small class="text-white-50">Minimum 8 characters</small>
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label text-white">
                                <i class="bi bi-lock-fill me-2 text-success"></i>Confirm Password
                            </label>
                            <input type="password" class="form-control bg-dark text-white border-secondary" 
                                   id="password-confirm" name="password_confirmation" 
                                   placeholder="Confirm new password" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success py-2">
                                <i class="bi bi-check-circle me-2"></i>Reset Password
                            </button>
                            
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary py-2">
                                <i class="bi bi-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection