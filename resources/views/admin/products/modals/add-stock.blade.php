<div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Stock to Branch</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <form id="addStockForm" method="POST" action="{{ route('admin.products.add-stock', $product) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control" value="{{ $product->name }}" readonly>
            </div>

            @if($product->flavors->count() > 0)
            <div class="mb-3">
                <label class="form-label">Flavor (optional)</label>
                <select name="flavor_id" class="form-select">
                    <option value="">-- All flavors --</option>
                    @foreach($product->flavors as $flavor)
                        <option value="{{ $flavor->id }}">{{ $flavor->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Select Destination <span class="text-danger">*</span></label>
                <select name="branch_id" id="branchSelect" class="form-select" required>
                    <option value="">-- Choose Destination --</option>
                    <option value="warehouse">🏢 Main Warehouse (Add New Stock)</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">🏪 {{ $branch->name }} (Transfer from Warehouse)</option>
                    @endforeach
                </select>
                <div class="form-text" id="destinationHelp">
                    <i class="bi bi-info-circle me-1"></i> 
                    <span id="helpText">Select "Main Warehouse" to add new stock, or select a branch to transfer from warehouse</span>
                </div>
            </div>

            <!-- Warehouse Stock Info (shown when branch is selected) -->
            <div id="warehouseStockInfo" class="alert alert-info mb-3" style="display: none;">
                <i class="bi bi-building me-2"></i>
                <strong>Main Warehouse Stock:</strong> 
                <span id="warehouseStockQty">Loading...</span> units available
                <div id="lowStockWarning" class="small text-warning mt-1" style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Low stock! Only <span id="lowStockQty"></span> units available in warehouse.
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" id="quantityInput" class="form-control" min="1" required>
                    <div class="form-text" id="quantityHelp"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expiration Date</label>
                    <input type="date" name="expiration_date" id="expirationDate" class="form-control">
                    <div class="form-text" id="expiryHelp">Required for warehouse stock</div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Purchase Price (₱) – optional</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control" placeholder="e.g., 150.00">
                <div class="form-text">Recommended for warehouse stock</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Additional information about this stock addition"></textarea>
            </div>

            <div id="formAlert" class="alert" style="display: none;"></div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="submitStockBtn">
            <i class="bi bi-check-circle me-1"></i> Add Stock
        </button>
    </div>
</div>

<script>
    const branchSelect = document.querySelector('#branchSelect');
    const quantityInput = document.querySelector('#quantityInput');
    const expirationDate = document.querySelector('#expirationDate');
    const warehouseStockInfo = document.querySelector('#warehouseStockInfo');
    const warehouseStockQty = document.querySelector('#warehouseStockQty');
    const lowStockWarning = document.querySelector('#lowStockWarning');
    const lowStockQty = document.querySelector('#lowStockQty');
    const quantityHelp = document.querySelector('#quantityHelp');
    const expiryHelp = document.querySelector('#expiryHelp');
    const helpTextSpan = document.querySelector('#helpText');
    const productId = {{ $product->id }};
    
    let currentWarehouseStock = 0;
    
    // Check warehouse stock when branch or flavor changes
    async function checkWarehouseStock() {
        const branchId = branchSelect.value;
        const flavorId = document.querySelector('select[name="flavor_id"]')?.value || '';
        
        if (branchId && branchId !== 'warehouse') {
            // Show loading
            warehouseStockInfo.style.display = 'block';
            warehouseStockQty.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
            lowStockWarning.style.display = 'none';
            
            try {
                const response = await fetch(`/admin/api/warehouse-stock/${productId}?flavor_id=${flavorId}`);
                const data = await response.json();
                
                if (data.success) {
                    currentWarehouseStock = data.quantity;
                    warehouseStockQty.innerHTML = `<strong class="text-primary">${currentWarehouseStock}</strong>`;
                    
                    if (currentWarehouseStock === 0) {
                        warehouseStockQty.innerHTML = `<strong class="text-danger">0</strong>`;
                        quantityHelp.innerHTML = '<span class="text-danger">⚠️ No stock available in warehouse. Please add stock to warehouse first.</span>';
                        quantityInput.max = 0;
                    } else if (currentWarehouseStock < 10) {
                        lowStockWarning.style.display = 'block';
                        lowStockQty.innerHTML = currentWarehouseStock;
                        quantityHelp.innerHTML = `<span class="text-warning">⚠️ Only ${currentWarehouseStock} units available in warehouse.</span>`;
                        quantityInput.max = currentWarehouseStock;
                    } else {
                        quantityHelp.innerHTML = `<span class="text-success">✓ ${currentWarehouseStock} units available in warehouse</span>`;
                        quantityInput.max = currentWarehouseStock;
                    }
                    
                    // Validate current quantity
                    if (parseInt(quantityInput.value) > currentWarehouseStock) {
                        quantityInput.value = currentWarehouseStock;
                    }
                } else {
                    warehouseStockQty.innerHTML = '<span class="text-danger">Error loading</span>';
                }
            } catch (error) {
                console.error('Error checking warehouse stock:', error);
                warehouseStockQty.innerHTML = '<span class="text-danger">Unavailable</span>';
            }
        } else if (branchId === 'warehouse') {
            warehouseStockInfo.style.display = 'none';
            quantityHelp.innerHTML = '';
            quantityInput.max = '';
        } else {
            warehouseStockInfo.style.display = 'none';
            quantityHelp.innerHTML = '';
            quantityInput.max = '';
        }
    }
    
    // Update UI based on selected destination
    function updateUI() {
        const branchId = branchSelect.value;
        
        if (branchId === 'warehouse') {
            // Adding to warehouse directly
            helpTextSpan.innerHTML = 'Adding new stock to Main Warehouse. This will increase warehouse inventory.';
            expiryHelp.innerHTML = '<span class="text-warning">⚠️ Required for warehouse stock tracking</span>';
            if (!expirationDate.value) {
                expirationDate.style.borderColor = '#ffc107';
            }
            quantityHelp.innerHTML = 'Enter the quantity to add to warehouse';
            quantityInput.max = '';
            warehouseStockInfo.style.display = 'none';
        } else if (branchId && branchId !== '') {
            // Transferring to branch
            helpTextSpan.innerHTML = 'Transferring stock from Main Warehouse to selected branch. Stock will be deducted from warehouse.';
            expiryHelp.innerHTML = 'Optional - will be copied from warehouse if available';
            checkWarehouseStock();
        } else {
            // No selection
            helpTextSpan.innerHTML = 'Select "Main Warehouse" to add new stock, or select a branch to transfer from warehouse';
            expiryHelp.innerHTML = 'Required for warehouse stock';
            warehouseStockInfo.style.display = 'none';
            quantityHelp.innerHTML = '';
        }
    }
    
    // Validate quantity before submit
    function validateQuantity() {
        const branchId = branchSelect.value;
        const quantity = parseInt(quantityInput.value);
        
        if (branchId && branchId !== 'warehouse') {
            if (quantity > currentWarehouseStock) {
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill me-2"></i> Insufficient warehouse stock. Available: ${currentWarehouseStock} units.`;
                alertDiv.style.display = 'block';
                return false;
            }
            if (quantity <= 0) {
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> Please enter a valid quantity.';
                alertDiv.style.display = 'block';
                return false;
            }
        }
        
        if (branchId === 'warehouse' && !expirationDate.value) {
            if (confirm('No expiration date set for warehouse stock. This is recommended for inventory tracking. Continue anyway?')) {
                return true;
            }
            return false;
        }
        
        return true;
    }
    
    // Event listeners
    branchSelect.addEventListener('change', updateUI);
    
    const flavorSelect = document.querySelector('select[name="flavor_id"]');
    if (flavorSelect) {
        flavorSelect.addEventListener('change', function() {
            if (branchSelect.value && branchSelect.value !== 'warehouse') {
                checkWarehouseStock();
            }
        });
    }
    
    quantityInput.addEventListener('input', function() {
        const branchId = branchSelect.value;
        const quantity = parseInt(this.value);
        
        if (branchId && branchId !== 'warehouse') {
            if (quantity > currentWarehouseStock) {
                this.value = currentWarehouseStock;
                quantityHelp.innerHTML = `<span class="text-danger">⚠️ Max available: ${currentWarehouseStock} units</span>`;
            } else if (quantity <= 0) {
                this.value = 1;
            } else {
                quantityHelp.innerHTML = `<span class="text-success">✓ Transferring ${quantity} units from warehouse</span>`;
            }
        }
    });
    
    expirationDate.addEventListener('change', function() {
        if (branchSelect.value === 'warehouse' && this.value) {
            this.style.borderColor = '#198754';
        }
    });
    
    // Submit handler
    document.getElementById('submitStockBtn').addEventListener('click', function() {
        const form = document.getElementById('addStockForm');
        const formData = new FormData(form);
        const alertDiv = document.getElementById('formAlert');
        const submitBtn = this;
        const originalText = submitBtn.innerHTML;
        
        // Clear previous alerts
        alertDiv.style.display = 'none';
        
        // Validate quantity
        if (!validateQuantity()) {
            submitBtn.disabled = false;
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertDiv.className = 'alert alert-success';
                alertDiv.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> ' + data.message;
                alertDiv.style.display = 'block';
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addStockModal'));
                    modal.hide();
                    location.reload();
                }, 1500);
            } else {
                alertDiv.className = 'alert alert-danger';
                let errorHtml = '<i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong><ul>';
                if (data.errors) {
                    for (const [field, errors] of Object.entries(data.errors)) {
                        errors.forEach(error => { errorHtml += `<li>${error}</li>`; });
                    }
                } else if (data.message) {
                    errorHtml += `<li>${data.message}</li>`;
                } else {
                    errorHtml += `<li>An unknown error occurred.</li>`;
                }
                errorHtml += '</ul>';
                alertDiv.innerHTML = errorHtml;
                alertDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> An error occurred. Please try again.';
            alertDiv.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // Initialize UI
    updateUI();
</script>