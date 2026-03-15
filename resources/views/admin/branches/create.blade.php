@extends('layouts.admin')

@section('title', 'Create Branch - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Create New Branch</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-plus-circle me-1"></i> Add a new Vape Expo branch
                </p>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.branches.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Branch Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Branch Code *</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" placeholder="e.g., MAJ, AS1, MCDC" required>
                        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Address *</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone Number *</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', '09603280432') }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Contact Number (Optional)</label>
                        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" placeholder="Alternative number">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Manager Name *</label>
                        <input type="text" name="manager_name" class="form-control @error('manager_name') is-invalid @enderror" value="{{ old('manager_name') }}" required>
                        @error('manager_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Opening Date</label>
                        <input type="date" name="opening_date" class="form-control" value="{{ old('opening_date') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_active">Active Branch</label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                <!-- Submit Buttons with Dashboard -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="bi bi-check-circle me-1"></i> Create Branch
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection