@extends('layouts.admin-modal')

@section('title', 'Add Stock to Branch')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-plus-circle"></i> Add Stock to Branch
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body" style="padding: 0;">
        <form id="addStockForm" method="POST" action="{{ route('admin.products.add-stock', $product) }}">
            @csrf

            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-box"></i> Product Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="info-row">
                        <div class="info-label">Product</div>
                        <div class="info-value">{{ $product->name }}</div>
                    </div>

                    @if ($product->flavors->count() > 0)
                        <div class="mb-3">
                            <label class="form-label-minimal">Variant (optional)</label>
                            <select name="flavor_id" class="form-select-minimal">
                                <option value="">-- All Variant --</option>
                                @foreach ($product->flavors as $flavor)
                                    <option value="{{ $flavor->id }}">{{ $flavor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label-minimal">Select Destination <span class="text-danger">*</span></label>
                        <select name="branch_id" id="branchSelect" class="form-select-minimal" required>
                            <option value="">-- Choose Destination --</option>
                            <option value="warehouse">🏢 Main Warehouse (Add New Stock)</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">🏪 {{ $branch->name }} (Transfer from Warehouse)
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text" id="destinationHelp">
                            <i class="bi bi-info-circle me-1"></i>
                            <span id="helpText">Select "Main Warehouse" to add new stock, or select a branch to transfer
                                from warehouse</span>
                        </div>
                    </div>

                    <!-- Warehouse Stock Info -->
                    <div id="warehouseStockInfo" class="alert-minimal alert-info-minimal" style="display: none;">
                        <i class="bi bi-building me-2"></i>
                        <strong>Main Warehouse Stock:</strong>
                        <span id="warehouseStockQty">Loading...</span> units available
                        <div id="lowStockWarning" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Low stock! Only <span
                                id="lowStockQty"></span> units available in warehouse.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-minimal">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantityInput" class="form-control-minimal"
                                min="1" required>
                            <div class="form-text" id="quantityHelp"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-minimal">Expiration Date</label>
                            <input type="date" name="expiration_date" id="expirationDate" class="form-control-minimal">
                            <div class="form-text" id="expiryHelp">Required for warehouse stock</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-minimal">Purchase Price (₱) – optional</label>
                        <input type="number" step="0.01" name="purchase_price" class="form-control-minimal"
                            placeholder="e.g., 150.00">
                        <div class="form-text">Recommended for warehouse stock</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-minimal">Notes (optional)</label>
                        <textarea name="notes" class="form-control-minimal" rows="2"
                            placeholder="Additional information about this stock addition"></textarea>
                    </div>

                    <div id="formAlert" class="alert-minimal" style="display: none;"></div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-3">
                <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-update" id="submitStockBtn" style="width: auto;">
                    <i class="bi bi-check-circle me-1"></i> Add Stock
                </button>
            </div>
        </form>
    </div>
@endsection
