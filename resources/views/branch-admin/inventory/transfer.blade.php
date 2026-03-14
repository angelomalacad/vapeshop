@extends('layouts.branch-admin')

@section('title', 'Request Stock Transfer - Vape Expo')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Request Stock Transfer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('branch-admin.inventory.transfer.request') }}">
                        @csrf
                        
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            You are requesting stock FROM another branch TO <strong>{{ $currentBranch->name ?? Auth::user()->branch->name }}</strong> (your branch)
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">From Branch (Source) <span class="text-danger">*</span></label>
                                <select name="from_branch_id" id="fromBranchSelect" class="form-select" required>
                                    <option value="">Select source branch...</option>
                                    @foreach($sourceBranches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select the branch that has the stock you need</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">To Branch (Destination) <span class="text-danger">*</span></label>
                                <select name="to_branch_id" class="form-select" required>
                                    <option value="{{ Auth::user()->branch_id }}" selected>
                                        {{ Auth::user()->branch->name }} (Your Branch)
                                    </option>
                                </select>
                                <small class="text-muted">Stock will be transferred to your branch</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="productSelect" class="form-select" required>
                                <option value="">Select product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $selectedProduct && $selectedProduct->id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Flavor</label>
                            <select name="flavor_id" id="flavorSelect" class="form-select">
                                <option value="">All flavors</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                            <small class="text-muted" id="availableStock">Select source branch and product to check availability</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Reason for transfer..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('productSelect').addEventListener('change', function() {
    const productId = this.value;
    const flavorSelect = document.getElementById('flavorSelect');
    const fromBranch = document.getElementById('fromBranchSelect').value;
    
    // Clear current options
    flavorSelect.innerHTML = '<option value="">All flavors</option>';
    
    if (productId) {
        // Fetch flavors for this product
        fetch(`/api/products/${productId}/flavors`)
            .then(response => response.json())
            .then(flavors => {
                flavors.forEach(flavor => {
                    const option = document.createElement('option');
                    option.value = flavor.id;
                    option.textContent = flavor.name;
                    flavorSelect.appendChild(option);
                });
                
                // Check availability after loading flavors
                if (fromBranch) {
                    checkAvailability();
                }
            });
    }
});

document.getElementById('fromBranchSelect').addEventListener('change', function() {
    const productId = document.getElementById('productSelect').value;
    if (productId && this.value) {
        checkAvailability();
    }
});

document.getElementById('flavorSelect').addEventListener('change', function() {
    const fromBranch = document.getElementById('fromBranchSelect').value;
    const productId = document.getElementById('productSelect').value;
    if (fromBranch && productId) {
        checkAvailability();
    }
});

function checkAvailability() {
    const fromBranch = document.getElementById('fromBranchSelect').value;
    const productId = document.getElementById('productSelect').value;
    const flavorId = document.getElementById('flavorSelect').value;
    const quantityInput = document.getElementById('quantity');
    const availableStockSpan = document.getElementById('availableStock');
    
    if (!fromBranch || !productId) return;
    
    // Show loading
    availableStockSpan.innerHTML = 'Checking availability...';
    
    // Build URL with query parameters
    let url = `/api/inventory/check?branch_id=${fromBranch}&product_id=${productId}`;
    if (flavorId) {
        url += `&flavor_id=${flavorId}`;
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const available = data.available;
                availableStockSpan.innerHTML = `Max available: <strong class="text-primary">${available}</strong> units`;
                
                // Set max attribute for quantity input
                quantityInput.max = available;
                
                // Validate current quantity
                if (parseInt(quantityInput.value) > available) {
                    quantityInput.value = available;
                }
            } else {
                availableStockSpan.innerHTML = 'Error checking availability';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            availableStockSpan.innerHTML = 'Error checking availability';
        });
}

// Initialize on page load if values are pre-selected
document.addEventListener('DOMContentLoaded', function() {
    const fromBranch = document.getElementById('fromBranchSelect').value;
    const productId = document.getElementById('productSelect').value;
    if (fromBranch && productId) {
        checkAvailability();
    }
});
</script>
@endpush