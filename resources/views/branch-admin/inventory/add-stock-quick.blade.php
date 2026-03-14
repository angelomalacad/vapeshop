@extends('layouts.branch-admin')

@section('page-title', 'Add Stock to Product')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add Stock to Product</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('branch-admin.inventory.quick-add-stock.post') }}" id="addStockForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select Product <span class="text-danger">*</span></label>
                            <select name="inventory_id" id="inventorySelect" class="form-select @error('inventory_id') is-invalid @enderror" required>
                                <option value="">Choose product...</option>
                                @foreach($branchInventory as $item)
                                    <option value="{{ $item->id }}" 
                                        data-product="{{ $item->product->name }}"
                                        data-flavor="{{ $item->flavor->name ?? 'No Flavor' }}"
                                        data-stock="{{ $item->quantity }}"
                                        {{ old('inventory_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->product->name }} 
                                        @if($item->flavor)
                                            - {{ $item->flavor->name }}
                                        @endif
                                        (Current: {{ $item->quantity }})
                                    </option>
                                @endforeach
                            </select>
                            @error('inventory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Product Info Display -->
                        <div class="alert alert-info mb-3" id="productInfo" style="display: none;">
                            <strong>Product:</strong> <span id="displayProduct"></span><br>
                            <strong>Flavor:</strong> <span id="displayFlavor"></span><br>
                            <strong>Current Stock:</strong> <span id="displayStock"></span>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Quantity to Add <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" value="{{ old('quantity', 1) }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Received from supplier">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="bi bi-plus-circle"></i> Add Stock
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
document.addEventListener('DOMContentLoaded', function() {
    const inventorySelect = document.getElementById('inventorySelect');
    const productInfo = document.getElementById('productInfo');
    const displayProduct = document.getElementById('displayProduct');
    const displayFlavor = document.getElementById('displayFlavor');
    const displayStock = document.getElementById('displayStock');
    const quantityInput = document.getElementById('quantity');
    
    // Show product info when selection changes
    inventorySelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        
        if (this.value) {
            // Get data from selected option
            const product = selected.dataset.product;
            const flavor = selected.dataset.flavor;
            const stock = selected.dataset.stock;
            
            // Display product info
            displayProduct.textContent = product;
            displayFlavor.textContent = flavor;
            displayStock.textContent = stock;
            productInfo.style.display = 'block';
        } else {
            productInfo.style.display = 'none';
        }
    });
    
    // Trigger change event if there's an old value
    if (inventorySelect.value) {
        inventorySelect.dispatchEvent(new Event('change'));
    }
    
    // Form validation
    document.getElementById('addStockForm').addEventListener('submit', function(e) {
        const inventoryId = inventorySelect.value;
        const quantity = parseInt(quantityInput.value);
        
        if (!inventoryId) {
            e.preventDefault();
            alert('Please select a product');
            return;
        }
        
        if (quantity < 1) {
            e.preventDefault();
            alert('Quantity must be at least 1');
            return;
        }
    });
});
</script>
@endpush