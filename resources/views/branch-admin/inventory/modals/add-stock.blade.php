<!-- Modal content only - no layout -->
<div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i>Add Stock to {{ $inventory->product->name }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <form method="POST" action="{{ route('branch-admin.inventory.add-stock.post', $inventory) }}"
            id="addStockForm{{ $inventory->id }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control bg-light"
                    value="{{ $inventory->product->name }} @if ($inventory->flavor) ({{ $inventory->flavor->name }}) @endif"
                    readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Stock</label>
                <input type="text" class="form-control bg-light" value="{{ $inventory->quantity }} units" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Quantity to Add <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (Optional)</label>
                <textarea name="notes" class="form-control" rows="2"
                    placeholder="e.g., Received from supplier, stock transfer, etc."></textarea>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success"
            onclick="document.getElementById('addStockForm{{ $inventory->id }}').submit();">
            <i class="bi bi-plus-circle"></i> Add Stock
        </button>
    </div>
</div>
