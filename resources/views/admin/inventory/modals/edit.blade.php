<div class="modal-content">
    <div class="modal-header bg-warning text-white">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Inventory Item
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="{{ route('admin.inventory.update', $inventory) }}" id="editInventoryForm">
        @csrf
        @method('PUT')
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Branch *</label>
                    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" id="branch_id" required>
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('branch_id', $inventory->branch_id) == $branch->id ? 'selected' : '' }}>
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
                            <option value="{{ $product->id }}" data-has-flavors="{{ $product->flavors->count() > 0 ? 'true' : 'false' }}" {{ old('product_id', $inventory->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->brand }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row" id="flavorRow">
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
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $inventory->quantity) }}" min="0" required>
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Reserved Quantity *</label>
                    <input type="number" name="reserved_quantity" class="form-control @error('reserved_quantity') is-invalid @enderror" value="{{ old('reserved_quantity', $inventory->reserved_quantity) }}" min="0" required>
                    @error('reserved_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Low Stock Threshold *</label>
                    <input type="number" name="low_stock_threshold" class="form-control @error('low_stock_threshold') is-invalid @enderror" value="{{ old('low_stock_threshold', $inventory->low_stock_threshold) }}" min="1" required>
                    @error('low_stock_threshold') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Reorder Point *</label>
                    <input type="number" name="reorder_point" class="form-control @error('reorder_point') is-invalid @enderror" value="{{ old('reorder_point', $inventory->reorder_point) }}" min="1" required>
                    @error('reorder_point') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Optimal Stock *</label>
                    <input type="number" name="optimal_stock" class="form-control @error('optimal_stock') is-invalid @enderror" value="{{ old('optimal_stock', $inventory->optimal_stock) }}" min="1" required>
                    @error('optimal_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Last Purchase Price (₱)</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" name="last_purchase_price" class="form-control" value="{{ old('last_purchase_price', $inventory->last_purchase_price) }}" min="0">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Last Restocked Date</label>
                    <input type="datetime-local" name="last_restocked_at" class="form-control" value="{{ old('last_restocked_at', $inventory->last_restocked_at ? \Carbon\Carbon::parse($inventory->last_restocked_at)->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i> Update Inventory
            </button>
        </div>
    </form>
</div>

<script>
    const products = @json($products);
    const currentFlavorId = {{ $inventory->flavor_id ?? 'null' }};
    
    const productSelect = document.getElementById('product_id');
    if (productSelect) {
        productSelect.addEventListener('change', function() {
            const productId = this.value;
            const flavorSelect = document.getElementById('flavor_id');
            
            if (!productId) {
                flavorSelect.innerHTML = '<option value="">No Flavor</option>';
                return;
            }
            
            const selectedProduct = products.find(p => p.id == productId);
            
            if (selectedProduct && selectedProduct.flavors && selectedProduct.flavors.length > 0) {
                flavorSelect.innerHTML = '<option value="">Select Flavor</option>';
                selectedProduct.flavors.forEach(flavor => {
                    const selected = flavor.id == currentFlavorId ? 'selected' : '';
                    flavorSelect.innerHTML += `<option value="${flavor.id}" ${selected}>${flavor.name}</option>`;
                });
            } else {
                flavorSelect.innerHTML = '<option value="">No Flavor</option>';
            }
        });
        
        // Trigger change on page load
        productSelect.dispatchEvent(new Event('change'));
    }
    
    // Handle form submission via AJAX
    const editForm = document.getElementById('editInventoryForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert(data.message || 'Error updating inventory');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
</script>