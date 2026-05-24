<div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i> Add Stock
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <form method="POST" action="{{ route('admin.inventory.add-stock.post', $inventory) }}" id="addStockForm">
        @csrf
        <div class="modal-body">
            <!-- Current Stock Info -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center py-2">
                            <small>Current Stock</small>
                            <h4 class="mb-0">{{ $inventory->quantity }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center py-2">
                            <small>Available</small>
                            <h4 class="mb-0">{{ $inventory->available_quantity }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Product</label>
                <input type="text" class="form-control bg-light" value="{{ $inventory->product->name }} @if($inventory->flavor)- {{ $inventory->flavor->name }}@endif" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Quantity to Add *</label>
                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <small class="text-muted">Current: {{ $inventory->quantity }} → New: {{ $inventory->quantity }} + [quantity]</small>
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
                <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Restock from supplier, transfer received, etc.">{{ old('notes') }}</textarea>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                Adding stock will update the inventory quantity and create a stock movement record.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-success" id="submitAddStockBtn">
                <i class="bi bi-plus-circle me-1"></i> Add Stock
            </button>
        </div>
    </form>
</div>

<script>
    const addStockForm = document.getElementById('addStockForm');
    if (addStockForm) {
        addStockForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitAddStockBtn');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addStockModal'));
                    modal.hide();
                    location.reload();
                } else {
                    alert(data.message || 'Error adding stock');
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