<!-- Modal content only - no layout -->
<div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>Edit Inventory Item
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <!-- Product Info Summary -->
        <div class="alert alert-info bg-light border-0 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted d-block">Product</small>
                    <strong>{{ $inventory->product->name }}</strong>
                    @if ($inventory->flavor)
                        <br><span class="badge bg-secondary mt-1">{{ $inventory->flavor->name }}</span>
                    @endif
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Category</small>
                    <strong>{{ $inventory->product->category }}</strong>
                </div>
                <div class="col-md-3">
                    <small class="text-muted d-block">Price</small>
                    <strong>₱{{ number_format($inventory->product->price, 2) }}</strong>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('branch-admin.inventory.update', $inventory) }}"
            id="editForm{{ $inventory->id }}">
            @csrf
            @method('PUT')

            <!-- Stock Quantities Section -->
            <h6 class="mb-3 text-primary"><i class="bi bi-box-seam me-2"></i>Stock Quantities</h6>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ $inventory->quantity }}"
                        min="0" required>
                    <small class="text-muted">Physical stock count</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Reserved Quantity</label>
                    <input type="number" name="reserved_quantity" class="form-control"
                        value="{{ $inventory->reserved_quantity }}" min="0">
                    <small class="text-muted">Stock reserved for pending orders</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Available Quantity</label>
                    <input type="text" class="form-control bg-light" value="{{ $inventory->available_quantity }}"
                        readonly disabled>
                    <small class="text-muted">Auto-calculated (Quantity - Reserved)</small>
                </div>
            </div>

            <hr class="my-4">

            <!-- Threshold Settings Section -->
            <h6 class="mb-3 text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Threshold Settings</h6>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                    <input type="number" name="low_stock_threshold" class="form-control"
                        value="{{ $inventory->low_stock_threshold }}" min="1" required>
                    <small class="text-muted">Alert when stock reaches this level</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Reorder Point <span class="text-danger">*</span></label>
                    <input type="number" name="reorder_point" class="form-control"
                        value="{{ $inventory->reorder_point }}" min="1" required>
                    <small class="text-muted">When to reorder</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Optimal Stock Level <span class="text-danger">*</span></label>
                    <input type="number" name="optimal_stock" class="form-control"
                        value="{{ $inventory->optimal_stock }}" min="1" required>
                    <small class="text-muted">Target stock level</small>
                </div>
            </div>

            <hr class="my-4">

            <!-- Timestamp Section -->
            <h6 class="mb-3 text-info"><i class="bi bi-calendar me-2"></i>Last Updated</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Restocked Date</label>
                    <input type="datetime-local" name="last_restocked_at" class="form-control"
                        value="{{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('Y-m-d\TH:i') : '' }}">
                    <small class="text-muted">When stock was last added</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Updated</label>
                    <input type="text" class="form-control bg-light"
                        value="{{ $inventory->updated_at ? $inventory->updated_at->format('M d, Y h:i A') : 'Never' }}"
                        readonly disabled>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="row mt-2">
                <div class="col-12">
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex align-items-center">
                            <span class="me-3">Current Status:</span>
                            @if ($inventory->available_quantity <= 0)
                                <span class="badge bg-danger p-2">Out of Stock</span>
                            @elseif($inventory->available_quantity <= $inventory->low_stock_threshold)
                                <span class="badge bg-warning p-2">Low Stock</span>
                            @else
                                <span class="badge bg-success p-2">In Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Warning:</strong> Changing quantity or reserved stock directly will affect inventory levels. Use
                "Add Stock" for regular restocking.
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary"
            onclick="document.getElementById('editForm{{ $inventory->id }}').submit();">
            <i class="bi bi-save"></i> Update Inventory Settings
        </button>
    </div>
</div>
