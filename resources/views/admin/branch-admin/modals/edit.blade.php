@extends('layouts.admin-modal')

@section('title', 'Edit Account')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>Edit Account
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body" style="padding: 0;">
        <form method="POST" action="{{ route('admin.branch-admin.update', $branchAdmin) }}" id="editForm">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-minimal">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control-minimal"
                        value="{{ old('name', $branchAdmin->name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-minimal">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control-minimal"
                        value="{{ old('email', $branchAdmin->email) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-minimal">Phone Number</label>
                    <input type="text" name="phone" class="form-control-minimal"
                        value="{{ old('phone', $branchAdmin->phone) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-minimal">Role</label>
                    <input type="text" class="form-control-minimal"
                        value="{{ $branchAdmin->role == 'branch_admin' ? 'Branch Admin' : ($branchAdmin->role == 'staff' ? 'Staff' : 'Driver') }}"
                        readonly disabled>
                    <small class="text-muted text-primary">Role cannot be changed</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label-minimal">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-select-minimal" id="branchSelect" required>
                        <option value="">Select Branch</option>
                        @if ($branchAdmin->role == 'driver')
                            <option value="all" {{ $branchAdmin->branch_id == null ? 'selected' : '' }}>All Branches
                            </option>
                        @endif
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ $branchAdmin->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($branchAdmin->role == 'driver')
                        <small class="text-muted">Select "All Branches" if this driver can accept deliveries from any
                            branch</small>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label-minimal">Status</label>
                    <select name="is_active" class="form-select-minimal">
                        <option value="1" {{ $branchAdmin->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$branchAdmin->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label-minimal">Address</label>
                <textarea name="address" id="addressField" class="form-control-minimal" rows="2">{{ old('address', $branchAdmin->address) }}</textarea>
                <small class="text-muted" id="addressHint"></small>
            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label-minimal">New Password <span class="text-muted">(Optional)</span></label>
                <input type="password" name="password" class="form-control-minimal"
                    placeholder="Leave blank to keep current password">
                <small class="text-muted">Minimum 8 characters</small>
            </div>

            <div class="mb-3">
                <label class="form-label-minimal">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control-minimal"
                    placeholder="Confirm new password">
            </div>

            <div class="modal-footer" style="padding: 1rem 0 0 0; border-top: 1px solid #eef2f6;">
                <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-update" form="editForm" style="width: auto;">
                    <i class="bi bi-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>

    <script>
        // Handle address field behavior when "All Branches" is selected
        (function() {
            const branchSelect = document.getElementById('branchSelect');
            const addressField = document.getElementById('addressField');
            const addressHint = document.getElementById('addressHint');

            if (branchSelect) {
                function handleBranchChange() {
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
                }

                handleBranchChange();
                branchSelect.addEventListener('change', handleBranchChange);
            }

            // Handle form submission via AJAX to keep modal and show success message
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // If "All Branches" is selected, clear address
                    if (branchSelect && branchSelect.value === 'all' && addressField) {
                        addressField.value = '';
                    }

                    const formData = new FormData(editForm);
                    const submitButton = editForm.closest('.modal-body').querySelector(
                        'button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;

                    // Show loading state
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...';

                    fetch(editForm.action, {
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
                                // Close modal
                                const modal = editForm.closest('.modal');
                                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                                bootstrapModal.hide();

                                // Use global notification from parent window
                                if (window.parent && typeof window.parent.showNotification === 'function') {
                                    window.parent.showNotification(data.message, 'success');
                                } else if (typeof window.showNotification === 'function') {
                                    window.showNotification(data.message, 'success');
                                } else {
                                    alert(data.message);
                                }

                                // Reload page after 1 second to refresh the table
                                setTimeout(() => {
                                    window.parent.location.reload();
                                }, 1000);
                            } else {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalButtonText;
                                if (window.parent && typeof window.parent.showNotification === 'function') {
                                    window.parent.showNotification(data.message ||
                                        'Update failed. Please try again.', 'error');
                                } else if (typeof window.showNotification === 'function') {
                                    window.showNotification(data.message ||
                                        'Update failed. Please try again.', 'error');
                                } else {
                                    alert(data.message || 'Update failed');
                                }
                            }
                        })
                        .catch(error => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                            console.error('Error:', error);
                            if (window.parent && typeof window.parent.showNotification === 'function') {
                                window.parent.showNotification('An error occurred. Please try again.',
                                    'error');
                            } else if (typeof window.showNotification === 'function') {
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
