@extends('layouts.app')

@section('title', 'Forgot Password - Vape Expo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="50" class="mb-3" onerror="this.src='https://via.placeholder.com/50x50?text=VE'">
                    <h4 class="mb-1">Reset Password</h4>
                    <p class="text-white-50 mb-0 small">Enter your email to receive reset link</p>
                </div>

                <div class="card-body bg-dark p-4">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            @foreach($errors->all() as $error)
                                <p class="mb-0 small">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label text-white">
                                <i class="bi bi-envelope-fill me-2 text-primary"></i>Email Address
                            </label>
                            <input type="email" class="form-control bg-dark text-white border-secondary" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Enter your email address" required autofocus>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="bi bi-send me-2"></i>Send Password Reset Link
                            </button>
                            
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary py-2">
                                <i class="bi bi-arrow-left me-2"></i>Back to Login
                            </a>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-white-50 small mb-0">
                            <i class="bi bi-shop me-1"></i>
                            Vape Expo - 5 Branches in Calamba
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection