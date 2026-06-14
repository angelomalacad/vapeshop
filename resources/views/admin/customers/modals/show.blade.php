<div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h5 class="modal-title">
            <i class="bi bi-person-circle me-2"></i>Customer Information
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
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
                <label class="text-muted small fw-semibold text-uppercase">Full Name</label>
                <p class="fw-semibold mb-0">{{ $customer->name }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">Email Address</label>
                <p class="fw-semibold mb-0">{{ $customer->email }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">Phone Number</label>
                <p class="fw-semibold mb-0">{{ $customer->phone ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">Birthdate</label>
                <p class="fw-semibold mb-0">
                    {{ $customer->birthdate ? \Carbon\Carbon::parse($customer->birthdate)->format('F d, Y') : 'N/A' }}
                </p>
            </div>
        </div>

        <hr>

        <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-info"></i>Address Information</h6>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">Barangay</label>
                <p class="fw-semibold mb-0">{{ $customer->barangay ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">City</label>
                <p class="fw-semibold mb-0">{{ $customer->city ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">Province</label>
                <p class="fw-semibold mb-0">{{ $customer->province ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">ZIP Code</label>
                <p class="fw-semibold mb-0">{{ $customer->zip_code ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="mb-3">
            <label class="text-muted small fw-semibold text-uppercase">Complete Address</label>
            <p class="fw-semibold mb-0">{{ $customer->address ?? 'N/A' }}</p>
        </div>

        <div class="mb-3">
            <label class="text-muted small fw-semibold text-uppercase">Landmark</label>
            <p class="fw-semibold mb-0">{{ $customer->landmark ?? 'N/A' }}</p>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">Registered Date</label>
                <p class="fw-semibold mb-0">{{ $customer->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="text-muted small fw-semibold text-uppercase">Last Updated</label>
                <p class="fw-semibold mb-0">{{ $customer->updated_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="alert alert-info small mt-2">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Note:</strong> Customer accounts can be edited by clicking the Edit button.
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
        </button>
        <button type="button" class="btn btn-primary" onclick="openEditModal({{ $customer->id }})"
            data-bs-dismiss="modal">
            <i class="bi bi-pencil me-1"></i> Edit Customer
        </button>
    </div>
</div>
