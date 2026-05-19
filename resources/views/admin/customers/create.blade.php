@extends('layouts.admin')

@section('title', 'Add Customer - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Add New Customer</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-person-plus me-1"></i> Create a new customer account
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Customers
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-person-plus me-2 text-primary"></i>
                        Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.customers.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}">
                                <small class="text-muted">Must be at least 18 years old</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city', 'Calamba') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Province</label>
                                <input type="text" name="province" class="form-control" value="{{ old('province', 'Laguna') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ZIP Code</label>
                                <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Complete Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" checked>
                            <label class="form-check-label" for="isActive">Active Account</label>
                            <small class="d-block text-muted">Inactive accounts cannot log in</small>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> New customers will receive a welcome email with their account details.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Create Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection