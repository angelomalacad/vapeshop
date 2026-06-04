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
            <!-- Hidden fields for branch, product, flavor (can't be changed) -->
            <input type="hidden" name="branch_id" value="{{ $inventory->branch_id }}">
            <input type="hidden" name="product_id" value="{{ $inventory->product_id }}">
            <input type="hidden" name="flavor_id" value="{{ $inventory->flavor_id }}">

            <!-- Read-only Information Display -->
            <div class="alert alert-info bg-light border-0 mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Branch</small>
                        <strong>{{ $inventory->branch->name }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Product</small>
                        <strong>{{ $inventory->product->name }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Flavor</small>
                        <strong>{{ $inventory->flavor->name ?? 'No Flavor' }}</strong>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Quantity *</label>
                    <input type="number" name="quantity" class="form-control" value="{{ $inventory->quantity }}" min="0" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Reserved Quantity *</label>
                    <input type="number" name="reserved_quantity" class="form-control" value="{{ $inventory->reserved_quantity }}" min="0" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Low Stock Threshold *</label>
                    <input type="number" name="low_stock_threshold" class="form-control" value="{{ $inventory->low_stock_threshold }}" min="1" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Reorder Point *</label>
                    <input type="number" name="reorder_point" class="form-control" value="{{ $inventory->reorder_point }}" min="1" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Optimal Stock *</label>
                    <input type="number" name="optimal_stock" class="form-control" value="{{ $inventory->optimal_stock }}" min="1" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Last Purchase Price (₱)</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" name="last_purchase_price" class="form-control" value="{{ $inventory->last_purchase_price }}" min="0">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Last Restocked Date</label>
                    <input type="datetime-local" name="last_restocked_at" class="form-control" value="{{ $inventory->last_restocked_at ? \Carbon\Carbon::parse($inventory->last_restocked_at)->format('Y-m-d\TH:i') : '' }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Expiration Date</label>
                    <input type="date" name="expiration_date" class="form-control" value="{{ $inventory->expiration_date ? \Carbon\Carbon::parse($inventory->expiration_date)->format('Y-m-d') : '' }}">
                    <small class="text-muted">Leave empty if no expiration date</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <div class="form-control bg-light">
                        @php
                            $available = $inventory->quantity - ($inventory->reserved_quantity ?? 0);
                            $isExpired = $inventory->expiration_date && \Carbon\Carbon::parse($inventory->expiration_date)->isPast();
                        @endphp
                        @if($isExpired)
                            <span class="badge bg-danger">Expired</span>
                        @elseif($available <= 0)
                            <span class="badge bg-danger">Out of Stock</span>
                        @elseif($available <= $inventory->low_stock_threshold)
                            <span class="badge bg-warning">Low Stock</span>
                        @else
                            <span class="badge bg-success">In Stock</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($inventory->expiration_date)
                @php
                    $expiryDate = \Carbon\Carbon::parse($inventory->expiration_date);
                    $daysLeft = \Carbon\Carbon::now()->diffInDays($expiryDate, false);
                @endphp
                @if($expiryDate->isPast())
                    <div class="alert alert-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Expired!</strong> This product expired on {{ $expiryDate->format('M d, Y') }}.
                    </div>
                @elseif($daysLeft <= 30)
                    <div class="alert alert-warning mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Expiring Soon!</strong> This product expires in {{ $daysLeft }} days ({{ $expiryDate->format('M d, Y') }}).
                    </div>
                @endif
            @endif
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editInventoryModal'));
                    if (modal) modal.hide();
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
</script>