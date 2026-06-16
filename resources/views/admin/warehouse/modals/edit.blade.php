@extends('layouts.admin-modal')

@section('title', 'Edit Warehouse Stock')

@section('content')
<div class="modal-header-minimal">
    <h5 class="modal-title">
        <i class="bi bi-pencil-square"></i> Edit Warehouse Stock
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<!-- DEBUG DISPLAY -->
<div id="editDebug" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 10px; margin-bottom: 15px; font-size: 12px; max-height: 100px; overflow-y: auto; display: none;">
    <strong>🔍 Debug:</strong>
    <div id="editDebugContent">Loading debug...</div>
</div>

<form action="{{ route('admin.warehouse.update', $item->id) }}" method="POST" id="editWarehouseForm">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Product <span class="text-danger">*</span></label>
            <select name="product_id" class="form-select-minimal product-select-edit" 
                data-edit-id="{{ $item->id }}" required>
                <option value="">Select product...</option>
                @foreach ($products as $productOption)
                    <option value="{{ $productOption->id }}"
                        {{ $item->product_id == $productOption->id ? 'selected' : '' }}>
                        {{ $productOption->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Flavor <span class="text-danger">*</span></label>
            <select name="flavor_id" class="form-select-minimal flavor-select-edit" 
                data-edit-id="{{ $item->id }}"
                data-current-flavor-id="{{ $item->flavor_id }}" required>
                <option value="">Loading flavors...</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label-minimal">Quantity <span class="text-danger">*</span></label>
            <input type="number" name="quantity" class="form-control-minimal quantity-input" 
                value="{{ $item->quantity }}" min="0" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label-minimal">Last Purchase Price (₱) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="last_purchase_price" 
                class="form-control-minimal price-input" value="{{ $item->last_purchase_price }}" 
                min="0" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label-minimal">Expiration Date</label>
            <input type="date" name="expiration_date" class="form-control-minimal" 
                value="{{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('Y-m-d') : '' }}">
            <div class="form-text" style="font-size: 0.7rem; color: #94a3b8;">Optional – leave empty if no expiry</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Low Stock Threshold <span class="text-danger">*</span></label>
            <input type="number" name="low_stock_threshold" class="form-control-minimal" 
                value="{{ $item->low_stock_threshold }}" min="1" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Reorder Point <span class="text-danger">*</span></label>
            <input type="number" name="reorder_point" class="form-control-minimal" 
                value="{{ $item->reorder_point }}" min="1" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Last Restocked</label>
            <input type="text" class="form-control-minimal bg-light" 
                value="{{ $item->last_restocked_at ? $item->last_restocked_at->format('M d, Y h:i A') : 'Never' }}" 
                readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Total Inventory Value</label>
            <input type="text" class="form-control-minimal total-value-display bg-primary text-white fw-bold" 
                value="₱{{ number_format($item->quantity * ($item->last_purchase_price ?? 0), 2) }}" 
                readonly>
        </div>
    </div>

    <hr>

    <div class="alert alert-warning alert-minimal">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Note:</strong> Changing quantity will be recorded in stock movement history.
    </div>

    <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding-top: 1rem;">
        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn-update" id="editSubmitBtn" style="width: auto; background: #3b82f6;">Update Inventory</button>
    </div>
</form>

<script>
    (function() {
        // Show debug
        var debugContainer = document.getElementById('editDebug');
        var debugContent = document.getElementById('editDebugContent');
        debugContainer.style.display = 'block';
        
        function addDebug(msg) {
            debugContent.innerHTML += '<div>' + msg + '</div>';
            console.log(msg);
        }
        
        addDebug('🔍 Edit Modal Loaded');
        addDebug('📦 Item ID: {{ $item->id }}');
        addDebug('📦 Product ID: {{ $item->product_id }}');
        addDebug('📦 Flavor ID: {{ $item->flavor_id }}');
        
        var productSelect = document.querySelector('.product-select-edit');
        var flavorSelect = document.querySelector('.flavor-select-edit');
        var currentFlavorId = flavorSelect?.dataset?.currentFlavorId || '';
        
        addDebug('✅ Product select found: ' + !!productSelect);
        addDebug('✅ Flavor select found: ' + !!flavorSelect);
        addDebug('🎯 Current Flavor ID: ' + currentFlavorId);
        
        function loadFlavors() {
            var productId = productSelect?.value;
            addDebug('🔄 Loading flavors for product ID: ' + productId);
            
            if (!productId) {
                addDebug('⚠️ No product selected');
                flavorSelect.disabled = true;
                flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                return;
            }
            
            var apiUrl = '/admin/api/products/' + productId + '/flavors';
            addDebug('📡 Fetching: ' + apiUrl);
            
            flavorSelect.disabled = true;
            flavorSelect.innerHTML = '<option value="">⏳ Loading...</option>';
            
            fetch(apiUrl)
                .then(function(response) {
                    addDebug('📥 Response status: ' + response.status);
                    if (!response.ok) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(function(data) {
                    addDebug('📦 Flavors received: ' + data.length);
                    flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
                    if (data.length > 0) {
                        data.forEach(function(flavor) {
                            addDebug('  - ' + flavor.id + ': ' + flavor.name);
                            var option = document.createElement('option');
                            option.value = flavor.id;
                            option.textContent = flavor.name;
                            flavorSelect.appendChild(option);
                        });
                        flavorSelect.disabled = false;
                        if (currentFlavorId) {
                            flavorSelect.value = currentFlavorId;
                            addDebug('✅ Set selected flavor to: ' + currentFlavorId);
                        }
                        addDebug('✅ Flavors loaded successfully!');
                    } else {
                        addDebug('⚠️ No flavors available');
                        flavorSelect.innerHTML = '<option value="">No flavors available</option>';
                        flavorSelect.disabled = true;
                    }
                })
                .catch(function(error) {
                    addDebug('❌ Error: ' + error.message);
                    flavorSelect.innerHTML = '<option value="">❌ Error loading flavors</option>';
                    flavorSelect.disabled = true;
                });
        }
        
        // Load flavors after short delay
        addDebug('⏳ Scheduling flavor load...');
        setTimeout(function() {
            addDebug('⏰ Loading flavors now...');
            loadFlavors();
        }, 500);
        
        // Reload when product changes
        if (productSelect) {
            productSelect.addEventListener('change', function() {
                addDebug('🔄 Product changed to: ' + this.value);
                loadFlavors();
            });
        }
        
        // Auto-calculate total value
        var quantityInput = document.querySelector('.quantity-input');
        var priceInput = document.querySelector('.price-input');
        var totalDisplay = document.querySelector('.total-value-display');
        
        function calculateTotal() {
            var quantity = parseFloat(quantityInput?.value) || 0;
            var price = parseFloat(priceInput?.value) || 0;
            var total = quantity * price;
            if (totalDisplay) {
                totalDisplay.value = '₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
        }
        
        if (quantityInput && priceInput) {
            quantityInput.addEventListener('input', calculateTotal);
            priceInput.addEventListener('input', calculateTotal);
        }
        
        // Form submission
        var form = document.getElementById('editWarehouseForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                addDebug('📤 Form submitted');
                
                var formData = new FormData(this);
                var submitBtn = document.getElementById('editSubmitBtn');
                var originalText = submitBtn.innerHTML;
                var actionUrl = this.action;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
                
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    addDebug('📥 Response: ' + (data.success ? '✅ Success' : '❌ Failed'));
                    if (data.success) {
                        var modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
                        if (modal) modal.hide();
                        
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Stock updated successfully!', 'success');
                        }
                        
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Error updating stock', 'error');
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(function(error) {
                    addDebug('❌ Error: ' + error.message);
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Network error. Please try again.', 'error');
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    })();
</script>
@endsection