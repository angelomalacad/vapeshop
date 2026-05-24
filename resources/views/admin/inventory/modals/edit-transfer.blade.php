<div class="modal-content">
    <div class="modal-header bg-warning text-white">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Transfer
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="{{ route('admin.inventory.transfers.update', $transfer) }}" id="editTransferForm">
        @csrf
        @method('PUT')
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <!-- Warning for non-pending transfers -->
            @if($transfer->status != 'pending')
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                This transfer is {{ $transfer->status }} and cannot be edited.
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">From Branch *</label>
                    <select name="from_branch_id" class="form-select @error('from_branch_id') is-invalid @enderror" id="from_branch_id" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                        <option value="">Select Source Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('from_branch_id', $transfer->from_branch_id) == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }} ({{ $branch->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('from_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">To Branch *</label>
                    <select name="to_branch_id" class="form-select @error('to_branch_id') is-invalid @enderror" id="to_branch_id" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                        <option value="">Select Destination Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('to_branch_id', $transfer->to_branch_id) == $branch->id ? 'selected' : '' }}>
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
                    <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" id="product_id" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-has-flavors="{{ $product->flavors->count() > 0 ? 'true' : 'false' }}" {{ old('product_id', $transfer->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->brand }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6 mb-3" id="flavorRow" style="display: none;">
                    <label class="form-label fw-semibold">Flavor</label>
                    <select name="flavor_id" class="form-select @error('flavor_id') is-invalid @enderror" id="flavor_id" {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                        <option value="">No Flavor</option>
                    </select>
                    @error('flavor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Quantity *</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $transfer->quantity) }}" min="1" id="quantity" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <small class="text-muted" id="availableStock"></small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="2" {{ $transfer->status != 'pending' ? 'disabled' : '' }}>{{ old('notes', $transfer->notes) }}</textarea>
                </div>
            </div>

            <div class="alert alert-info" id="stockCheck" style="display: none;">
                <i class="bi bi-info-circle-fill me-2"></i>
                <span id="stockMessage"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            @if($transfer->status == 'pending')
            <button type="submit" class="btn btn-warning" id="submitEditTransferBtn">
                <i class="bi bi-check-circle me-1"></i> Update Transfer
            </button>
            @endif
        </div>
    </form>
</div>

<script>
    // Products data
    const products = @json($products);
    const currentFlavorId = {{ $transfer->flavor_id ?? 'null' }};

    // Check available stock when from branch and product are selected
    function checkAvailableStock() {
        const fromBranchId = document.getElementById('from_branch_id').value;
        const productId = document.getElementById('product_id').value;
        const flavorId = document.getElementById('flavor_id').value;
        const quantity = document.getElementById('quantity').value;
        const stockCheck = document.getElementById('stockCheck');
        const stockMessage = document.getElementById('stockMessage');
        const availableStock = document.getElementById('availableStock');

        if (!fromBranchId || !productId) {
            stockCheck.style.display = 'none';
            availableStock.innerHTML = '';
            return;
        }

        // Make AJAX call to check available stock
        fetch(`/api/branches/${fromBranchId}/products/${productId}/stock${flavorId ? '?flavor_id=' + flavorId : ''}`)
            .then(response => response.json())
            .then(data => {
                if (data.available !== undefined) {
                    availableStock.innerHTML = `Available: ${data.available} units`;
                    
                    if (quantity > data.available) {
                        stockCheck.style.display = 'block';
                        stockMessage.innerHTML = `Warning: Requested quantity (${quantity}) exceeds available stock (${data.available})`;
                        stockCheck.classList.remove('alert-info');
                        stockCheck.classList.add('alert-danger');
                    } else {
                        stockCheck.style.display = 'block';
                        stockMessage.innerHTML = `Available stock: ${data.available} units. You can transfer up to ${data.available} units.`;
                        stockCheck.classList.remove('alert-danger');
                        stockCheck.classList.add('alert-info');
                    }
                } else {
                    availableStock.innerHTML = 'No stock available';
                    stockCheck.style.display = 'block';
                    stockMessage.innerHTML = 'No stock available in source branch';
                    stockCheck.classList.remove('alert-info');
                    stockCheck.classList.add('alert-warning');
                }
            })
            .catch(error => {
                console.error('Error checking stock:', error);
            });
    }

    // Product change handler
    const productSelect = document.getElementById('product_id');
    if (productSelect) {
        productSelect.addEventListener('change', function() {
            const productId = this.value;
            const flavorRow = document.getElementById('flavorRow');
            const flavorSelect = document.getElementById('flavor_id');
            
            if (!productId) {
                flavorRow.style.display = 'none';
                checkAvailableStock();
                return;
            }
            
            const selectedProduct = products.find(p => p.id == productId);
            
            if (selectedProduct && selectedProduct.flavors && selectedProduct.flavors.length > 0) {
                // Clear and populate flavors
                flavorSelect.innerHTML = '<option value="">Select Flavor</option>';
                selectedProduct.flavors.forEach(flavor => {
                    const selected = flavor.id == currentFlavorId ? 'selected' : '';
                    flavorSelect.innerHTML += `<option value="${flavor.id}" ${selected}>${flavor.name}</option>`;
                });
                flavorRow.style.display = 'block';
            } else {
                flavorSelect.innerHTML = '<option value="">No Flavor</option>';
                flavorRow.style.display = 'block';
            }
            
            checkAvailableStock();
        });
        
        // Trigger change on page load
        productSelect.dispatchEvent(new Event('change'));
    }

    // From branch change handler
    const fromBranchSelect = document.getElementById('from_branch_id');
    if (fromBranchSelect) {
        fromBranchSelect.addEventListener('change', checkAvailableStock);
    }

    // Flavor change handler
    const flavorSelect = document.getElementById('flavor_id');
    if (flavorSelect) {
        flavorSelect.addEventListener('change', checkAvailableStock);
    }

    // Quantity input handler
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', checkAvailableStock);
    }

    // Handle form submission via AJAX
    const editForm = document.getElementById('editTransferForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitEditTransferBtn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editTransferModal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert(data.message || 'Error updating transfer');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
</script>