@extends('layouts.customer')

@section('content')
<div class="container profile-container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 profile-header">
        <h4 class="profile-title">
            <i class="bi bi-person-gear"></i> Edit Profile
        </h4>
        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary rounded-pill back-btn">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Info alert -->
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please fix the following:</strong>
            @foreach ($errors->all() as $error)
                <div class="small">{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <!-- Profile Picture Section -->
        <div class="card shadow-sm border-0 profile-card mb-3">
            <div class="card-body text-center py-4">
                <div class="profile-picture-wrapper">
                    @if ($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture"
                             class="profile-picture-img" id="profilePicPreview">
                    @else
                        <img src="" alt="Profile Picture" class="profile-picture-img"
                             id="profilePicPreview" style="display: none;">
                        <div class="profile-picture-placeholder" id="profilePicPlaceholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                </div>

                <label for="profile_picture" class="btn btn-sm btn-outline-primary rounded-pill mt-3">
                    <i class="bi bi-pencil-square"></i> Edit Picture
                </label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="d-none">
                <div class="small text-muted mt-2">JPG, PNG, WEBP, GIF · Max 5MB</div>
            </div>
        </div>

        <!-- Name -->
        <div class="card shadow-sm border-0 profile-card mb-2">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-4 col-md-3">
                        <label class="profile-label">Name</label>
                    </div>
                    <div class="col-8 col-md-9">
                        <input type="text" name="name"
                               class="form-control profile-input @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender -->
        <div class="card shadow-sm border-0 profile-card mb-2">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-4 col-md-3">
                        <label class="profile-label">
                            Gender <i class="bi bi-info-circle small text-muted"></i>
                        </label>
                    </div>
                    <div class="col-8 col-md-9">
                        <select name="gender" class="form-select profile-input">
                            <option value="">Set Now</option>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="prefer_not_to_say" {{ old('gender', $user->gender) === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Birthdate -->
        <div class="card shadow-sm border-0 profile-card mb-2">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-4 col-md-3">
                        <label class="profile-label">
                            Birthday <i class="bi bi-info-circle small text-muted"></i>
                        </label>
                    </div>
                    <div class="col-8 col-md-9">
                        <input type="date" name="birthdate"
                               class="form-control profile-input @error('birthdate') is-invalid @enderror"
                               value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}">
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Phone -->
        <div class="card shadow-sm border-0 profile-card mb-2">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-4 col-md-3">
                        <label class="profile-label">Phone</label>
                    </div>
                    <div class="col-8 col-md-9">
                        <input type="text" name="phone"
                               class="form-control profile-input @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $user->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Email with verification status + pending change -->
        <div class="card shadow-sm border-0 profile-card mb-3">
            <div class="card-body py-3">
                <div class="row align-items-start">
                    <div class="col-4 col-md-3 pt-2">
                        <label class="profile-label">Email</label>
                    </div>
                    <div class="col-8 col-md-9">
                        <input type="email" name="email"
                               class="form-control profile-input @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Verification badge --}}
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-2">
                            @if ($user->hasVerifiedEmail())
                                <span class="badge bg-success-subtle text-success-emphasis">
                                    <i class="bi bi-check-circle-fill me-1"></i> Verified
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Not verified
                                </span>
                                <form method="POST" action="{{ route('customer.profile.resend-verification') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link p-0 text-warning text-decoration-none">
                                        Resend verification <i class="bi bi-arrow-right"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if (!$user->hasVerifiedEmail())
                            <div class="small text-muted mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Check your inbox (and spam folder) for the verification link.
                            </div>
                        @endif

                        {{-- ✅ Pending email change banner --}}
                        @if ($user->pending_email)
                            <div class="alert alert-warning small mt-3 mb-0">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-clock-history me-2 mt-1"></i>
                                    <div class="flex-grow-1">
                                        <strong>Pending change to:</strong> {{ $user->pending_email }}
                                        <br>
                                        <small>Your current email <strong>{{ $user->email }}</strong> still works until you verify.</small>

                                        <form method="POST" action="{{ route('customer.profile.cancel-pending-email') }}" class="mt-2">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-x-circle me-1"></i> Cancel Change
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 save-btn">
            <i class="bi bi-check-circle me-1"></i> Save Changes
        </button>
    </form>

    <!-- Logout Button -->
    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-3">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
    </form>
</div>

<style>
    .profile-container {
        padding-left: 14px;
        padding-right: 14px;
        max-width: 680px;
        margin: 0 auto;
    }

    .profile-header {
        margin-bottom: 1rem !important;
        gap: 0.75rem;
    }

    .profile-title {
        font-size: 1.15rem;
        margin-bottom: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .profile-title i {
        color: #e74c3c;
        font-size: 1.2rem;
    }

    .back-btn {
        padding: 0.4rem 0.75rem;
        font-size: 0.78rem;
    }

    .profile-card {
        border-radius: 14px;
    }

    .profile-card .card-body {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .profile-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0;
    }

    .profile-input {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.5rem 0.75rem;
        font-size: 0.88rem;
    }

    .profile-input:focus {
        border-color: #e74c3c;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    }

    .profile-picture-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
        border-radius: 50%;
        overflow: hidden;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-picture-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-picture-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #ef4444;
        font-size: 3rem;
    }

    .save-btn {
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        transition: all 0.2s ease;
    }

    .save-btn:active {
        transform: scale(0.98);
    }

    @media (min-width: 768px) {
        .profile-picture-wrapper {
            width: 120px;
            height: 120px;
        }
        .profile-picture-placeholder {
            font-size: 3.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('profile_picture');
        const preview = document.getElementById('profilePicPreview');
        const placeholder = document.getElementById('profilePicPlaceholder');

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.src = ev.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>
@endsection