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
                        
                        <!-- Hidden field for pre-selected product -->
                        @if(isset($preSelectedProductId) && $preSelectedProductId)
                            <input type="hidden" name="pre_selected_product" value="{{ $preSelectedProductId }}">
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Select Product <span class="text-danger">*</span></label>
                            <select name="inventory_id" id="inventorySelect" class="form-select @error('inventory_id') is-invalid @enderror" required>
                                <option value="">Choose product...</option>
                                @foreach($branchInventory as $item)
                                    <option value="{{ $item->id }}" 
                                        data-product="{{ $item->product->name }}"
                                        data-flavor="{{ $item->flavor->name ?? 'No Flavor' }}"
                                        data-stock="{{ $item->quantity }}"
                                        data-threshold="{{ $item->low_stock_threshold }}"
                                        {{ (request('inventory_id') == $item->id) ? 'selected' : '' }}
                                        {{ (isset($preSelectedInventoryId) && $preSelectedInventoryId == $item->id) ? 'selected' : '' }}
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
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Product:</strong> <span id="displayProduct"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Flavor:</strong> <span id="displayFlavor"></span>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <strong>Current Stock:</strong> <span id="displayStock" class="fw-bold"></span>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <strong>Low Stock Threshold:</strong> <span id="displayThreshold"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Quantity to Add <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" min="1" value="{{ old('quantity', 1) }}" required>
                            <small class="text-muted" id="newStockPreview"></small>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Purchase Price (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="purchase_price" id="purchasePrice" class="form-control" value="{{ old('purchase_price') }}" placeholder="0.00">
                            </div>
                            <small class="text-muted">Update the purchase price for cost tracking</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Received from supplier, stock transfer, etc.">{{ old('notes') }}</textarea>
                        </div>

                        <div class="alert alert-warning" id="lowStockWarning" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span id="warningMessage"></span>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('branch-admin.dashboard') }}" class="btn btn-outline-primary me-2">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                                <button type="submit" class="btn btn-success" id="submitBtn">
                                    <i class="bi bi-plus-circle"></i> Add Stock
                                </button>
                            </div>
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
    const displayThreshold = document.getElementById('displayThreshold');
    const quantityInput = document.getElementById('quantity');
    const newStockPreview = document.getElementById('newStockPreview');
    const lowStockWarning = document.getElementById('lowStockWarning');
    const warningMessage = document.getElementById('warningMessage');
    const purchasePriceInput = document.getElementById('purchasePrice');
    
    let currentStock = 0;
    let currentThreshold = 0;
    
    // Get pre-selected product ID from PHP
    const preSelectedProductId = {{ isset($preSelectedProductId) ? $preSelectedProductId : 'null' }};
    
    // Function to find and select option by product name (since inventory_id is different from product_id)
    function findAndSelectProduct(productId) {
        if (!productId) return false;
        
        // Loop through options to find matching product
        for (let i = 0; i < inventorySelect.options.length; i++) {
            const option = inventorySelect.options[i];
            const optionText = option.text;
            
            // Check if the product name in the option text matches
            // This is a simple approach - you may need to adjust based on your data
            if (option.value && optionText) {
                // For now, we'll just trigger the change event if there's a value
                // The actual product selection will need to be done by passing product_id to the select options
            }
        }
        return false;
    }
    
    // Show product info when selection changes
    function updateProductInfo() {
        const selected = inventorySelect.options[inventorySelect.selectedIndex];
        
        if (inventorySelect.value) {
            // Get data from selected option
            const product = selected.dataset.product;
            const flavor = selected.dataset.flavor;
            const stock = parseInt(selected.dataset.stock);
            const threshold = parseInt(selected.dataset.threshold);
            
            currentStock = stock;
            currentThreshold = threshold;
            
            // Display product info
            displayProduct.textContent = product;
            displayFlavor.textContent = flavor;
            displayStock.textContent = stock;
            displayThreshold.textContent = threshold;
            productInfo.style.display = 'block';
            
            // Update new stock preview
            updateNewStockPreview();
        } else {
            productInfo.style.display = 'none';
            newStockPreview.textContent = '';
            lowStockWarning.style.display = 'none';
        }
    }
    
    inventorySelect.addEventListener('change', updateProductInfo);
    
    // Update new stock preview when quantity changes
    function updateNewStockPreview() {
        const quantity = parseInt(quantityInput.value) || 0;
        const newStock = currentStock + quantity;
        
        if (quantity > 0 && inventorySelect.value) {
            newStockPreview.textContent = `After adding: ${newStock} units (was ${currentStock})`;
            
            // Check if new stock will still be low
            if (newStock <= currentThreshold) {
                warningMessage.textContent = `⚠️ Warning: After adding ${quantity} units, stock will still be at ${newStock} units (threshold: ${currentThreshold}). Consider adding more.`;
                lowStockWarning.style.display = 'block';
                lowStockWarning.classList.remove('alert-success');
                lowStockWarning.classList.add('alert-danger');
            } else if (currentStock <= currentThreshold) {
                warningMessage.textContent = `✅ Good! After adding ${quantity} units, stock will be at ${newStock} units, above the ${currentThreshold} threshold.`;
                lowStockWarning.style.display = 'block';
                lowStockWarning.classList.remove('alert-danger');
                lowStockWarning.classList.add('alert-success');
            } else {
                lowStockWarning.style.display = 'none';
            }
        } else {
            newStockPreview.textContent = '';
            lowStockWarning.style.display = 'none';
        }
    }
    
    quantityInput.addEventListener('input', updateNewStockPreview);
    quantityInput.addEventListener('keyup', updateNewStockPreview);
    
    // Trigger change event if there's a selected value
    if (inventorySelect.value) {
        updateProductInfo();
        updateNewStockPreview();
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
        
        if (isNaN(quantity) || quantity < 1) {
            e.preventDefault();
            alert('Quantity must be at least 1');
            return;
        }
        
        // Confirm if adding a large quantity
        if (quantity > 100) {
            if (!confirm(`You are about to add ${quantity} units. This is a large quantity. Continue?`)) {
                e.preventDefault();
                return;
            }
        }
    });
    
    // Auto-format purchase price to 2 decimal places
    purchasePriceInput.addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
});
</script>
@endpush