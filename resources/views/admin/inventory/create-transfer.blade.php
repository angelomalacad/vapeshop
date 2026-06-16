@extends('layouts.admin')

@section('title', 'Create Transfer - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Create Stock Transfer</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-arrow-left-right me-1"></i> Transfer stock between branches
                </p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.inventory.create-transfer') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> New Transfer
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.store-transfer') }}" id="transferForm">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">From Branch *</label>
                        <select name="from_branch_id" class="form-select @error('from_branch_id') is-invalid @enderror" id="from_branch_id" required>
                            <option value="">Select Source Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('from_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">To Branch *</label>
                        <select name="to_branch_id" class="form-select @error('to_branch_id') is-invalid @enderror" id="to_branch_id" required>
                            <option value="">Select Destination Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('to_branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('to_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Product *</label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" id="product_id" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->brand }})
                                    @if($product->flavors->count() > 0)
                                        - {{ $product->flavors->count() }} flavor(s)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3" id="flavorRow" style="display: none;">
                        <label class="form-label fw-semibold">Flavor *</label>
                        <select name="flavor_id" class="form-select @error('flavor_id') is-invalid @enderror" id="flavor_id">
                            <option value="">Select Flavor</option>
                        </select>
                        @error('flavor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Quantity *</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" id="quantity" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted" id="availableStock"></small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Reason for transfer...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <hr class="my-4">
                
                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.inventory.transfers') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <div>
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="bi bi-check-circle me-1"></i> Create Transfer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
// Products data from controller
const products = @json($products);

console.log('JavaScript is RUNNING!');
console.log('Products loaded:', products);

// Function to check available stock
function checkAvailableStock() {
    const fromBranchId = document.getElementById('from_branch_id').value;
    const productId = document.getElementById('product_id').value;
    const flavorId = document.getElementById('flavor_id').value;
    const quantity = document.getElementById('quantity').value;
    const availableStock = document.getElementById('availableStock');

    if (!fromBranchId || !productId || !flavorId) {
        availableStock.innerHTML = '';
        return;
    }

    fetch(`/api/branches/${fromBranchId}/products/${productId}/stock?flavor_id=${flavorId}`)
        .then(response => response.json())
        .then(data => {
            if (data.available !== undefined) {
                availableStock.innerHTML = `Available: ${data.available} units`;
            } else {
                availableStock.innerHTML = 'No stock available';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            availableStock.innerHTML = 'Error checking stock';
        });
}

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    
    // Product change handler
    document.getElementById('product_id').addEventListener('change', function() {
        const productId = this.value;
        const flavorRow = document.getElementById('flavorRow');
        const flavorSelect = document.getElementById('flavor_id');
        
        console.log('Product selected:', productId);
        
        if (!productId) {
            flavorRow.style.display = 'none';
            return;
        }
        
        // Find the selected product
        const selectedProduct = products.find(p => p.id == productId);
        console.log('Selected product:', selectedProduct);
        
        if (selectedProduct && selectedProduct.flavors && selectedProduct.flavors.length > 0) {
            console.log('Product has', selectedProduct.flavors.length, 'flavors:', selectedProduct.flavors);
            
            // Clear and populate flavors
            flavorSelect.innerHTML = '<option value="">Select Flavor</option>';
            selectedProduct.flavors.forEach(flavor => {
                console.log('Adding flavor:', flavor.name);
                flavorSelect.innerHTML += `<option value="${flavor.id}">${flavor.name}</option>`;
            });
            
            // Show the flavor row
            flavorRow.style.display = 'block';
            
            // Auto-select if only one flavor
            if (selectedProduct.flavors.length === 1) {
                flavorSelect.value = selectedProduct.flavors[0].id;
                flavorSelect.dispatchEvent(new Event('change'));
            }
        } else {
            console.log('Product has no flavors');
            flavorSelect.innerHTML = '<option value="">No Flavors Available</option>';
            flavorRow.style.display = 'block';
        }
    });

    // Flavor change handler
    document.getElementById('flavor_id').addEventListener('change', function() {
        console.log('Flavor selected:', this.value);
        checkAvailableStock();
    });

    // Branch change handlers
    document.getElementById('from_branch_id').addEventListener('change', checkAvailableStock);
    document.getElementById('quantity').addEventListener('input', checkAvailableStock);

    // Trigger change if there's an old value
    @if(old('product_id'))
        document.getElementById('product_id').value = "{{ old('product_id') }}";
        document.getElementById('product_id').dispatchEvent(new Event('change'));
    @endif
});
</script>