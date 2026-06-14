<div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>Edit Customer
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" id="editCustomerForm">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $customer->email) }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="phone" class="form-control"
                        value="{{ old('phone', $customer->phone) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $customer->is_active ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$customer->is_active ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <small class="text-muted">Inactive customers cannot log in</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                    <input type="date" name="birthdate" class="form-control"
                        value="{{ old('birthdate', $customer->birthdate ? \Carbon\Carbon::parse($customer->birthdate)->format('Y-m-d') : '') }}"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}"
                        required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Province <span class="text-danger">*</span></label>
                    <input type="text" name="province" class="form-control"
                        value="{{ old('province', $customer->province) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Barangay <span class="text-danger">*</span></label>
                    <input type="text" name="barangay" class="form-control"
                        value="{{ old('barangay', $customer->barangay) }}" placeholder="Enter barangay/district"
                        required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">ZIP Code <span class="text-danger">*</span></label>
                    <input type="text" name="zip_code" class="form-control"
                        value="{{ old('zip_code', $customer->zip_code) }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Complete Address <span class="text-danger">*</span></label>
                <textarea name="address" class="form-control" rows="2" required>{{ old('address', $customer->address) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Landmark <span class="text-danger">*</span></label>
                <input type="text" name="landmark" class="form-control"
                    value="{{ old('landmark', $customer->landmark) }}"
                    placeholder="Nearby landmark for easy navigation" required>
                <small class="text-muted">Example: Near 7-Eleven, Beside Church, Across the Park</small>
            </div>

            <hr>

            {{-- <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">New Password <span class="text-muted">(Optional)</span></label>
                    <input type="password" name="password" class="form-control"
                        placeholder="Leave blank to keep current password">
                    <small class="text-muted">Minimum 8 characters</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Confirm new password">
                </div>
            </div> --}}

            <div class="alert alert-info small">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> Customer accounts can also be created through the registration form on the
                website.
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" form="editCustomerForm">
            <i class="bi bi-save me-1"></i> Update Customer
        </button>
    </div>
</div>

{{-- ADDED: JavaScript for AJAX submission with success notification --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('editCustomerForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';

                fetch(editForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(editForm)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(editForm.closest('.modal'));
                            modal.hide();
                            alert(data.message);
                            location.reload();
                        } else {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                            alert(data.message || 'Update failed');
                        }
                    })
                    .catch(error => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        alert('An error occurred');
                    });
            });
        }
    });
</script>
