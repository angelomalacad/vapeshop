@extends('layouts.admin')

@section('title', 'Add Inventory - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Add Inventory Item</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-plus-circle me-1"></i> Add new stock to a branch
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
            <form method="POST" action="{{ route('admin.inventory.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Branch *</label>
                        <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Product *</label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" id="product_id" required>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-has-flavors="{{ $product->flavors->count() > 0 ? 'true' : 'false' }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->brand }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row" id="flavorRow" style="display: none;">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Flavor</label>
                        <select name="flavor_id" class="form-select @error('flavor_id') is-invalid @enderror" id="flavor_id">
                            <option value="">No Flavor</option>
                        </select>
                        @error('flavor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Quantity *</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 0) }}" min="0" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Low Stock Threshold *</label>
                        <input type="number" name="low_stock_threshold" class="form-control @error('low_stock_threshold') is-invalid @enderror" value="{{ old('low_stock_threshold', 10) }}" min="1" required>
                        @error('low_stock_threshold') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Reorder Point *</label>
                        <input type="number" name="reorder_point" class="form-control @error('reorder_point') is-invalid @enderror" value="{{ old('reorder_point', 20) }}" min="1" required>
                        @error('reorder_point') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Optimal Stock *</label>
                        <input type="number" name="optimal_stock" class="form-control @error('optimal_stock') is-invalid @enderror" value="{{ old('optimal_stock', 50) }}" min="1" required>
                        @error('optimal_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Last Purchase Price (₱)</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="last_purchase_price" class="form-control" value="{{ old('last_purchase_price') }}" min="0">
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="bi bi-check-circle me-1"></i> Add to Inventory
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Product flavors data
    const products = @json($products);
    
    document.getElementById('product_id').addEventListener('change', function() {
        const productId = this.value;
        const flavorRow = document.getElementById('flavorRow');
        const flavorSelect = document.getElementById('flavor_id');
        
        if (!productId) {
            flavorRow.style.display = 'none';
            return;
        }
        
        const selectedProduct = products.find(p => p.id == productId);
        
        if (selectedProduct && selectedProduct.flavors && selectedProduct.flavors.length > 0) {
            // Clear and populate flavors
            flavorSelect.innerHTML = '<option value="">Select Flavor</option>';
            selectedProduct.flavors.forEach(flavor => {
                flavorSelect.innerHTML += `<option value="${flavor.id}">${flavor.name}</option>`;
            });
            flavorRow.style.display = 'block';
        } else {
            flavorSelect.innerHTML = '<option value="">No Flavor</option>';
            flavorRow.style.display = 'block';
        }
    });
    
    // Trigger change on page load if old value exists
    @if(old('product_id'))
        document.getElementById('product_id').dispatchEvent(new Event('change'));
    @endif
</script>
@endpush