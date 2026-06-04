<div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i> Add Stock from Warehouse
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="{{ route('admin.inventory.add-stock.post', $inventory) }}" id="addStockForm">
        @csrf
        <div class="modal-body">
            @php
                $warehouseStock = \App\Models\WarehouseInventory::where('product_id', $inventory->product_id)
                    ->when($inventory->flavor_id, function($query) use ($inventory) {
                        return $query->where('flavor_id', $inventory->flavor_id);
                    })
                    ->first();
                $availableWarehouseStock = $warehouseStock ? $warehouseStock->quantity : 0;
            @endphp

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center py-2">
                            <small>Current Branch Stock</small>
                            <h4 class="mb-0">{{ $inventory->quantity }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center py-2">
                            <small>Branch Available</small>
                            <h4 class="mb-0">{{ $inventory->available_quantity }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card {{ $availableWarehouseStock > 0 ? 'bg-primary' : 'bg-secondary' }} text-white">
                        <div class="card-body text-center py-2">
                            <small>Warehouse Stock</small>
                            <h4 class="mb-0">{{ $availableWarehouseStock }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Product</label>
                <input type="text" class="form-control bg-light" value="{{ $inventory->product->name }} @if($inventory->flavor)- {{ $inventory->flavor->name }}@endif" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Branch</label>
                <input type="text" class="form-control bg-light" value="{{ $inventory->branch->name }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Quantity to Add *</label>
                <input type="number" name="quantity" id="quantityInput" class="form-control" value="1" min="1" max="{{ $availableWarehouseStock }}" {{ $availableWarehouseStock <= 0 ? 'disabled' : '' }} required>
                @if($availableWarehouseStock > 0)
                    <small class="text-muted">Max available from warehouse: {{ $availableWarehouseStock }} units</small>
                @else
                    <small class="text-danger">No stock available in warehouse. Please restock warehouse first.</small>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Purchase Price (Optional)</label>
                <div class="input-group">
                    <span class="input-group-text">₱</span>
                    <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" min="0">
                </div>
                <small class="text-muted">Last purchase price for cost tracking</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Notes (Optional)</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Restock from warehouse, transfer received, etc.">{{ old('notes') }}</textarea>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                Adding stock will deduct from warehouse inventory and add to branch inventory.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-success" id="submitAddStockBtn" {{ $availableWarehouseStock <= 0 ? 'disabled' : '' }}>
                <i class="bi bi-plus-circle me-1"></i> Add Stock
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('addStockForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submitAddStockBtn');
        const originalText = submitBtn.innerHTML;
        const formData = new FormData(form);
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('addStockModal'));
                modal.hide();
                window.location.href = data.redirect + '?success=' + encodeURIComponent(data.message);
            } else {
                alert('❌ ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>