@extends('layouts.admin-modal')

@section('title', 'Add Stock to Warehouse')

@section('content')
<div class="modal-header-minimal">
    <h5 class="modal-title">
        <i class="bi bi-plus-circle"></i> Add Stock to Warehouse
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('admin.warehouse.add-stock') }}" method="POST" id="addStockForm">
    @csrf
    <div class="alert alert-info alert-minimal">
        <i class="bi bi-info-circle me-1"></i>
        Adding stock will increase the warehouse inventory. The purchase price and expiration date will be tracked.
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Select Product <span class="text-danger">*</span></label>
            <select name="product_id" id="productSelectAdd" class="form-select-minimal" required>
                <option value="">Select product...</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-product-name="{{ $product->name }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label-minimal">Select Flavor <span class="text-danger">*</span></label>
            <select name="flavor_id" id="flavorSelectAdd" class="form-select-minimal" required disabled>
                <option value="">First select a product...</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label-minimal">Quantity <span class="text-danger">*</span></label>
            <input type="number" name="quantity" class="form-control-minimal" min="1" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label-minimal">Purchase Price (₱)</label>
            <input type="number" step="0.01" name="purchase_price" class="form-control-minimal"
                min="0" placeholder="Optional">
            <div class="form-text" style="font-size: 0.7rem; color: #94a3b8;">Cost price per unit</div>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label-minimal">Expiration Date</label>
            <input type="date" name="expiration_date" class="form-control-minimal">
            <div class="form-text" style="font-size: 0.7rem; color: #94a3b8;">Optional – leave empty if no expiry</div>
        </div>
    </div>

    <div class="alert alert-secondary alert-minimal">
        <i class="bi bi-box-seam me-1"></i>
        <strong>Note:</strong> Stock added here will be available for distribution to all branches.
    </div>

    <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding-top: 1rem;">
        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn-update" id="addStockSubmitBtn" style="width: auto; background: #3b82f6;">Add to Warehouse</button>
    </div>
</form>

<script>
    console.log('========================================');
    console.log('🔍 ADD STOCK MODAL DEBUG START');
    console.log('========================================');

    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOMContentLoaded fired');
        
        const productSelect = document.getElementById('productSelectAdd');
        const flavorSelect = document.getElementById('flavorSelectAdd');

        console.log('Product select element:', productSelect);
        console.log('Flavor select element:', flavorSelect);

        // Function to load flavors
        function loadFlavors() {
            const productId = productSelect?.value;
            console.log('========================================');
            console.log('🔄 loadFlavors() called');
            console.log('Product ID selected:', productId);
            console.log('========================================');
            
            if (!productId) {
                console.log('⚠️ No product selected, disabling flavor dropdown');
                flavorSelect.disabled = true;
                flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                return;
            }

            const apiUrl = `/admin/api/products/${productId}/flavors`;
            console.log('📡 Fetching flavors from:', apiUrl);
            
            flavorSelect.disabled = true;
            flavorSelect.innerHTML = '<option value="">⏳ Loading flavors...</option>';

            fetch(apiUrl)
                .then(response => {
                    console.log('📥 Response status:', response.status);
                    console.log('📥 Response headers:', response.headers);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📦 Flavors data received:', data);
                    console.log('📦 Number of flavors:', data.length);
                    
                    flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
                    if (data.length > 0) {
                        data.forEach((flavor, index) => {
                            console.log(`  Flavor ${index + 1}: ID=${flavor.id}, Name=${flavor.name}`);
                            const option = document.createElement('option');
                            option.value = flavor.id;
                            option.textContent = flavor.name;
                            flavorSelect.appendChild(option);
                        });
                        flavorSelect.disabled = false;
                        console.log('✅ Flavors loaded successfully, dropdown enabled');
                    } else {
                        console.log('⚠️ No flavors available for this product');
                        flavorSelect.innerHTML = '<option value="">No flavors available</option>';
                        flavorSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('❌ Error loading flavors:', error);
                    console.error('❌ Error stack:', error.stack);
                    flavorSelect.innerHTML = `<option value="">❌ Error: ${error.message}</option>`;
                    flavorSelect.disabled = true;
                });
        }

        // Initial load after DOM is ready
        console.log('⏳ Scheduling initial flavor load...');
        setTimeout(function() {
            console.log('⏰ Initial flavor load timeout fired');
            if (productSelect && productSelect.value) {
                console.log('✅ Product already selected, loading flavors');
                loadFlavors();
            } else {
                console.log('ℹ️ No product selected initially');
            }
        }, 1000);
        
        // Load when product changes
        if (productSelect) {
            console.log('✅ Attaching change event to product select');
            productSelect.addEventListener('change', function() {
                console.log('🔄 Product select changed to:', this.value);
                loadFlavors();
            });
        }
        
        // Also load when modal is fully shown
        const modalElement = document.querySelector('.modal.show');
        if (modalElement) {
            console.log('✅ Found modal element, attaching shown.bs.modal event');
            modalElement.addEventListener('shown.bs.modal', function() {
                console.log('🔄 Modal shown event fired');
                if (productSelect && productSelect.value) {
                    loadFlavors();
                }
            });
        } else {
            console.log('⚠️ No modal element found with .modal.show');
            const modalById = document.querySelector('#addStockModalContainer');
            if (modalById) {
                console.log('✅ Found modal by ID: addStockModalContainer');
                modalById.addEventListener('shown.bs.modal', function() {
                    console.log('🔄 Modal shown event fired (by ID)');
                    if (productSelect && productSelect.value) {
                        loadFlavors();
                    }
                });
            }
        }

        // Form submission
        const form = document.getElementById('addStockForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('📤 Form submitted');
                
                const formData = new FormData(this);
                const submitBtn = document.getElementById('addStockSubmitBtn');
                const originalText = submitBtn.innerHTML;
                const actionUrl = this.action;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';
                
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('📥 Response data:', data);
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
                        if (modal) modal.hide();
                        
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Stock added successfully!', 'success');
                        }
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Error adding stock', 'error');
                        }
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('❌ Fetch error:', error);
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Network error. Please try again.', 'error');
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            });
        }
    });
</script>
@endsection