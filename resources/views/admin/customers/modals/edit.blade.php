@extends('layouts.admin-modal')

@section('title', 'Edit Customer')

@section('content')
<div class="modal-header-minimal">
    <h5 class="modal-title">
        <i class="bi bi-pencil-square me-2"></i>Edit Customer
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form method="POST" action="{{ route('admin.customers.update', $customer) }}" id="editCustomerForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control-minimal" value="{{ old('name', $customer->name) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control-minimal" value="{{ old('email', $customer->email) }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Phone Number <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control-minimal" value="{{ old('phone', $customer->phone) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Status</label>
            <select name="is_active" class="form-select-minimal">
                <option value="1" {{ $customer->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$customer->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
            <small class="text-muted">Inactive customers cannot log in</small>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Birthdate <span class="text-danger">*</span></label>
            <input type="date" name="birthdate" class="form-control-minimal" value="{{ old('birthdate', $customer->birthdate ? \Carbon\Carbon::parse($customer->birthdate)->format('Y-m-d') : '') }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">City <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control-minimal" value="{{ old('city', $customer->city) }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Province <span class="text-danger">*</span></label>
            <input type="text" name="province" class="form-control-minimal" value="{{ old('province', $customer->province) }}" required>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Barangay <span class="text-danger">*</span></label>
            <input type="text" name="barangay" class="form-control-minimal" value="{{ old('barangay', $customer->barangay) }}" placeholder="Enter barangay/district" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">ZIP Code <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control-minimal" value="{{ old('zip_code', $customer->zip_code) }}" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label-minimal">Complete Address <span class="text-danger">*</span></label>
        <textarea name="address" class="form-control-minimal" rows="2" required>{{ old('address', $customer->address) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label-minimal">Landmark <span class="text-danger">*</span></label>
        <input type="text" name="landmark" class="form-control-minimal" value="{{ old('landmark', $customer->landmark) }}" placeholder="Nearby landmark for easy navigation" required>
        <small class="text-muted">Example: Near 7-Eleven, Beside Church, Across the Park</small>
    </div>

    <hr>

    <div class="alert alert-info small">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Note:</strong> Customer accounts can also be created through the registration form on the website.
    </div>

    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn-update" style="width: auto;">
            <i class="bi bi-save me-1"></i> Update Customer
        </button>
    </div>
</form>

<script>
    (function() {
        const form = document.getElementById('editCustomerForm');
        if (!form) return;
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modalElement = document.querySelector('.modal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();
                    }
                    
                    // Call parent window's showNotification function
                    if (window.parent && window.parent.showNotification) {
                        window.parent.showNotification(data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                    
                    // Reload parent page after short delay
                    setTimeout(() => {
                        window.parent.location.reload();
                    }, 1500);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    if (window.parent && window.parent.showNotification) {
                        window.parent.showNotification(data.message || 'Update failed', 'error');
                    } else {
                        alert(data.message || 'Update failed');
                    }
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                console.error('Error:', error);
                if (window.parent && window.parent.showNotification) {
                    window.parent.showNotification('An error occurred. Please try again.', 'error');
                } else {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    })();
</script>
@endsection