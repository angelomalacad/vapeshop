@extends('layouts.app')

@section('title', 'Verify Email - Vape Expo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-shield-check me-2"></i>Verify Your Email Address
                </div>

                <div class="card-body">
                    @if(session('resent'))
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif

                    <p>Before proceeding, please check your email for a verification link.</p>
                    <p>If you did not receive the email:</p>
                    
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Request another verification email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection