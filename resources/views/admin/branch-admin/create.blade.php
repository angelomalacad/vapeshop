@extends('layouts.admin')

@section('title', 'Add Branch Personnel - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Add Branch Personnel</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-person-plus me-1"></i> Create new branch admin or driver account
                    </p>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex gap-2">
                <a href="{{ route('admin.branch-admin.index') }}" class="btn btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-person-gear me-2 text-primary"></i>
                            Account Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.branch-admin.store') }}" id="createForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        required autocomplete="off">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required autocomplete="off">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" id="role"
                                        class="form-select @error('role') is-invalid @enderror" required>
                                        <option value="">Select Role</option>
                                        <option value="branch_admin" {{ old('role') == 'branch_admin' ? 'selected' : '' }}>
                                            Branch Admin</option>
                                        <option value="driver" {{ old('role') == 'driver' ? 'selected' : '' }}>Driver
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="branch_id" class="form-label">Branch <span
                                            class="text-danger">*</span></label>
                                    <select name="branch_id" id="branch_id"
                                        class="form-select @error('branch_id') is-invalid @enderror" required>
                                        <option value="">Select Branch</option>
                                        <!-- All Branches option (only appears when Driver is selected) -->
                                        <option value="all" id="allBranchesOption" style="display: none;">All Branches
                                        </option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" class="branch-option"
                                                {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" id="branchHint"></small>
                                    @error('branch_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                                    <small class="text-muted" id="addressHint"></small>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required>
                                    <small class="text-muted">Minimum 8 characters</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('admin.branch-admin.index') }}" class="btn btn-outline-secondary px-4">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                    <i class="bi bi-save me-1"></i> Create Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle branch selection based on role and address field behavior
        (function() {
            const roleSelect = document.getElementById('role');
            const branchSelect = document.getElementById('branch_id');
            const allBranchesOption = document.getElementById('allBranchesOption');
            const addressField = document.getElementById('address');
            const addressHint = document.getElementById('addressHint');
            const branchHint = document.getElementById('branchHint');

            function handleRoleChange() {
                const selectedRole = roleSelect.value;

                if (selectedRole === 'driver') {
                    // Show "All Branches" option for drivers
                    if (allBranchesOption) {
                        allBranchesOption.style.display = '';
                    }
                    if (branchHint) {
                        branchHint.innerHTML =
                            '<i class="bi bi-info-circle me-1"></i>Select "All Branches" if this driver can accept deliveries from any branch';
                        branchHint.style.color = '#6c757d';
                    }
                    // If "All Branches" is selected, disable address field
                    if (branchSelect.value === 'all') {
                        if (addressField) {
                            addressField.value = '';
                            addressField.disabled = true;
                        }
                        if (addressHint) {
                            addressHint.innerHTML =
                                '<i class="bi bi-info-circle me-1"></i>Address not required for "All Branches" option.';
                            addressHint.style.color = '#6c757d';
                        }
                    } else {
                        if (addressField) {
                            addressField.disabled = false;
                        }
                        if (addressHint) {
                            addressHint.innerHTML = '';
                        }
                    }
                } else {
                    // Hide "All Branches" option for non-drivers
                    if (allBranchesOption) {
                        allBranchesOption.style.display = 'none';
                    }
                    if (branchHint) {
                        branchHint.innerHTML = '';
                    }
                    // Enable address field
                    if (addressField) {
                        addressField.disabled = false;
                    }
                    if (addressHint) {
                        addressHint.innerHTML = '';
                    }
                    // If "all" was selected, clear it
                    if (branchSelect.value === 'all') {
                        branchSelect.value = '';
                    }
                }
            }

            function handleBranchChange() {
                if (roleSelect.value === 'driver' && branchSelect.value === 'all') {
                    if (addressField) {
                        addressField.value = '';
                        addressField.disabled = true;
                    }
                    if (addressHint) {
                        addressHint.innerHTML =
                            '<i class="bi bi-info-circle me-1"></i>Address not required for "All Branches" option.';
                        addressHint.style.color = '#6c757d';
                    }
                } else {
                    if (addressField) {
                        addressField.disabled = false;
                    }
                    if (addressHint) {
                        addressHint.innerHTML = '';
                    }
                }
            }

            // Add event listeners
            if (roleSelect) {
                roleSelect.addEventListener('change', handleRoleChange);
                // Call on page load to set initial state
                handleRoleChange();
            }

            if (branchSelect) {
                branchSelect.addEventListener('change', handleBranchChange);
            }

            // Handle form submission via AJAX with global notification
            const createForm = document.getElementById('createForm');

            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate password match
                    const password = document.getElementById('password').value;
                    const confirm = document.getElementById('password_confirmation').value;

                    if (password !== confirm) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Password and Confirm Password do not match!', 'error');
                        } else {
                            alert('Password and Confirm Password do not match!');
                        }
                        return;
                    }

                    // If "All Branches" is selected, set branch_id to null for submission
                    if (branchSelect.value === 'all') {
                        // Create a hidden input to submit branch_id as null
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'branch_id';
                        hiddenInput.value = '';
                        createForm.appendChild(hiddenInput);

                        // Disable the original select so it doesn't submit
                        branchSelect.disabled = true;
                    }

                    const submitButton = document.getElementById('submitBtn');
                    const originalButtonText = submitButton.innerHTML;

                    // Show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating...';

                    const formData = new FormData(createForm);

                    fetch(createForm.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Use global notification
                                if (typeof window.showNotification === 'function') {
                                    window.showNotification(data.message, 'success');
                                } else {
                                    alert(data.message);
                                }

                                // Reset form
                                createForm.reset();

                                // Re-enable branch select and remove hidden input
                                branchSelect.disabled = false;
                                const hidden = createForm.querySelector(
                                    'input[name="branch_id"][type="hidden"]');
                                if (hidden) hidden.remove();

                                // Reset role change handler
                                handleRoleChange();

                                // Redirect to index page after 1.5 seconds
                                setTimeout(() => {
                                    window.location.href =
                                        '{{ route('admin.branch-admin.index') }}';
                                }, 1500);
                            } else {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalButtonText;
                                // Re-enable branch select
                                branchSelect.disabled = false;
                                const hidden = createForm.querySelector(
                                    'input[name="branch_id"][type="hidden"]');
                                if (hidden) hidden.remove();

                                if (typeof window.showNotification === 'function') {
                                    window.showNotification(data.message ||
                                        'Creation failed. Please try again.', 'error');
                                } else {
                                    alert(data.message || 'Creation failed. Please try again.');
                                }
                            }
                        })
                        .catch(error => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                            // Re-enable branch select
                            branchSelect.disabled = false;
                            const hidden = createForm.querySelector(
                                'input[name="branch_id"][type="hidden"]');
                            if (hidden) hidden.remove();
                            console.error('Error:', error);

                            if (typeof window.showNotification === 'function') {
                                window.showNotification('An error occurred. Please try again.', 'error');
                            } else {
                                alert('An error occurred. Please try again.');
                            }
                        });
                });
            }
        })();
    </script>
@endsection
