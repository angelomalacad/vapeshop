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
                <label class="form-label-minimal">Select Variant <span class="text-danger">*</span></label>
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
                <input type="number" step="0.01" name="purchase_price" class="form-control-minimal" min="0"
                    placeholder="Optional">
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
            <button type="submit" class="btn-update" id="addStockSubmitBtn" style="width: auto; background: #3b82f6;">Add
                to Warehouse</button>
        </div>
    </form>
@endsection
