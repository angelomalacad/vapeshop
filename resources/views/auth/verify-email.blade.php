@extends('layouts.admin')

@section('title', 'Verify Email - Vape Expo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Verify Your Email Address</h5>
                </div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif

                    <p>Before proceeding, please check your email for a verification link.</p>
                    <p>If you did not receive the email,</p>
                    
                    <form method="POST" action="{{ route('admin.verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-envelope me-1"></i> Click here to request another
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection