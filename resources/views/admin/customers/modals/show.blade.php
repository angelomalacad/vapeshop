@extends('layouts.admin-modal')

@section('title', 'Customer Information')

@section('content')
<div class="modal-header-minimal">
    <h5 class="modal-title">
        <i class="bi bi-person-circle me-2"></i>Customer Information
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="text-center mb-4">
    <div class="bg-info bg-opacity-10 rounded-circle p-4 d-inline-block">
        <i class="bi bi-person-circle fs-1 text-info"></i>
    </div>
    <h4 class="mt-3 mb-1 fw-bold">{{ $customer->name }}</h4>
    <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
        <i class="bi {{ $customer->is_active ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
        {{ $customer->is_active ? 'Active' : 'Inactive' }}
    </span>
    <p class="text-muted small mt-2">Customer ID: #{{ $customer->id }}</p>
</div>

<hr>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Full Name</label>
        <p class="fw-semibold mb-0">{{ $customer->name }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Email Address</label>
        <p class="fw-semibold mb-0">{{ $customer->email }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Phone Number</label>
        <p class="fw-semibold mb-0">{{ $customer->phone ?? 'N/A' }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Birthdate</label>
        <p class="fw-semibold mb-0">{{ $customer->birthdate ? \Carbon\Carbon::parse($customer->birthdate)->format('F d, Y') : 'N/A' }}</p>
    </div>
</div>

<hr>

<h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-info"></i>Address Information</h6>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Barangay</label>
        <p class="fw-semibold mb-0">{{ $customer->barangay ?? 'N/A' }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">City</label>
        <p class="fw-semibold mb-0">{{ $customer->city ?? 'N/A' }}</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Province</label>
        <p class="fw-semibold mb-0">{{ $customer->province ?? 'N/A' }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">ZIP Code</label>
        <p class="fw-semibold mb-0">{{ $customer->zip_code ?? 'N/A' }}</p>
    </div>
</div>

<div class="mb-3">
    <label class="form-label-minimal">Complete Address</label>
    <p class="fw-semibold mb-0">{{ $customer->address ?? 'N/A' }}</p>
</div>

<div class="mb-3">
    <label class="form-label-minimal">Landmark</label>
    <p class="fw-semibold mb-0">{{ $customer->landmark ?? 'N/A' }}</p>
</div>

<hr>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Registered Date</label>
        <p class="fw-semibold mb-0">{{ $customer->created_at->format('F d, Y h:i A') }}</p>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label-minimal">Last Updated</label>
        <p class="fw-semibold mb-0">{{ $customer->updated_at->format('F d, Y h:i A') }}</p>
    </div>
</div>

<div class="alert alert-info small mt-2">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Note:</strong> Customer accounts can be edited by clicking the Edit button.
</div>

<div class="d-flex gap-2 justify-content-end mt-3">
    <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">
        <i class="bi bi-x-circle me-1"></i> Close
    </button>
    <button type="button" class="btn-update" style="width: auto;" onclick="openEditModal({{ $customer->id }})" data-bs-dismiss="modal">
        <i class="bi bi-pencil me-1"></i> Edit Customer
    </button>
</div>

<script>
    function openEditModal(userId) {
        const url = '/admin/customers/' + userId + '/modal-edit';
        const editModal = document.getElementById('editModal');
        if (editModal) {
            const modalContent = editModal.querySelector('.modal-content');
            modalContent.innerHTML = `
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading form...</p>
                </div>
            `;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => { modalContent.innerHTML = html; })
                .catch(error => { console.error('Error:', error); });
            const bsModal = new bootstrap.Modal(editModal);
            bsModal.show();
        } else {
            window.location.href = '/admin/customers/' + userId + '/edit';
        }
    }
</script>
@endsection