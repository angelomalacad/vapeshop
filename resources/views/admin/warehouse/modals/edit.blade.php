@extends('layouts.admin-modal')

@section('title', 'Edit Warehouse Stock')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square"></i> Edit Warehouse Stock
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <form action="{{ route('admin.warehouse.update', $item->id) }}" method="POST" id="editWarehouseForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label-minimal">Product <span class="text-danger">*</span></label>
                <select name="product_id" class="form-select-minimal product-select-edit" data-edit-id="{{ $item->id }}"
                    required>
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
                <select name="flavor_id" class="form-select-minimal flavor-select-edit" data-edit-id="{{ $item->id }}"
                    data-current-flavor-id="{{ $item->flavor_id }}" required>
                    <option value="">-- Select flavor --</option>
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
                <input type="number" step="0.01" name="last_purchase_price" class="form-control-minimal price-input"
                    value="{{ $item->last_purchase_price }}" min="0" required>
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
                <input type="number" name="reorder_point" class="form-control-minimal" value="{{ $item->reorder_point }}"
                    min="1" required>
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
                    value="₱{{ number_format($item->quantity * ($item->last_purchase_price ?? 0), 2) }}" readonly>
            </div>
        </div>

        <hr>

        <div class="alert alert-warning alert-minimal">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Note:</strong> Changing quantity will be recorded in stock movement history.
        </div>

        <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding-top: 1rem;">
            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-update" id="editSubmitBtn" style="width: auto; background: #3b82f6;">Update
                Inventory</button>
        </div>
    </form>
@endsection
