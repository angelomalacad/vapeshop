@extends('layouts.admin-modal')

@section('title', 'Add Stock from Warehouse')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i> Add Stock from Warehouse
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <form method="POST" action="{{ route('admin.inventory.add-stock.post', $inventory) }}" id="addStockForm">
        @csrf

        <div class="modal-body" style="padding: 0;">
            @php
                $warehouseStock = \App\Models\WarehouseInventory::where('product_id', $inventory->product_id)
                    ->when($inventory->flavor_id, function ($query) use ($inventory) {
                        return $query->where('flavor_id', $inventory->flavor_id);
                    })
                    ->first();
                $availableWarehouseStock = $warehouseStock ? $warehouseStock->quantity : 0;
            @endphp

            <!-- Stock Stats Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-6">
                    <div class="stat-card-minimal">
                        <div class="stat-icon-minimal" style="background: #eef4ff; color: #3b82f6;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="stat-content-minimal">
                            <span class="stat-label-minimal">Branch Stock</span>
                            <h3 class="stat-value-minimal">{{ $inventory->quantity }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="stat-card-minimal">
                        <div class="stat-icon-minimal" style="background: #e6f7e6; color: #10b981;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-content-minimal">
                            <span class="stat-label-minimal">Available Stock</span>
                            <h3 class="stat-value-minimal">{{ $inventory->available_quantity }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="stat-card-minimal">
                        <div class="stat-icon-minimal"
                            style="background: {{ $availableWarehouseStock > 0 ? '#eef4ff' : '#fef2f2' }}; color: {{ $availableWarehouseStock > 0 ? '#3b82f6' : '#ef4444' }};">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stat-content-minimal">
                            <span class="stat-label-minimal">Warehouse Stock</span>
                            <h3 class="stat-value-minimal">{{ $availableWarehouseStock }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information Card -->
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-box"></i> Product Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="info-row">
                        <div class="info-label">Product</div>
                        <div class="info-value">{{ $inventory->product->name }} @if ($inventory->flavor)
                                - {{ $inventory->flavor->name }}
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Branch</div>
                        <div class="info-value">{{ $inventory->branch->name }}</div>
                    </div>
                </div>
            </div>

            <!-- Add Stock Form Card -->
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-plus-circle"></i> Add Stock Details</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="mb-3">
                        <label class="form-label-minimal">Quantity to Add <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantityInput" class="form-control-minimal" value="1"
                            min="1" max="{{ $availableWarehouseStock }}"
                            {{ $availableWarehouseStock <= 0 ? 'disabled' : '' }} required>
                        @if ($availableWarehouseStock > 0)
                            <small class="text-muted">Max: {{ $availableWarehouseStock }} units</small>
                        @else
                            <small class="text-danger">No stock available in warehouse</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label-minimal">Purchase Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="purchase_price" class="form-control-minimal"
                                value="{{ old('purchase_price') }}" min="0" required>
                        </div>
                        <small class="text-muted">Required for cost tracking</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-minimal">Notes (Optional)</label>
                        <textarea name="notes" class="form-control-minimal" rows="2" placeholder="e.g., Restock from warehouse">{{ old('notes') }}</textarea>
                    </div>

                    <div class="alert-minimal alert-info-minimal">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Stock will be transferred from warehouse to branch.
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 justify-content-end mt-3">
            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn-update" id="submitAddStockBtn" style="width: auto;"
                {{ $availableWarehouseStock <= 0 ? 'disabled' : '' }}>
                <i class="bi bi-plus-circle me-1"></i> Add Stock
            </button>
        </div>
    </form>

    <script>
        document.getElementById('addStockForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = document.getElementById('submitAddStockBtn');
            const originalText = submitBtn.innerHTML;
            const formData = new FormData(form);

            // Validate purchase price
            const purchasePrice = form.querySelector('input[name="purchase_price"]').value;
            if (!purchasePrice || parseFloat(purchasePrice) <= 0) {
                alert('Please enter a valid purchase price.');
                form.querySelector('input[name="purchase_price"]').focus();
                return;
            }

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
@endsection

<style>
    /* Minimalist Stats Cards */
    .stat-card-minimal {
        background: #ffffff;
        border-radius: 16px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 1px solid #eef2f6;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .stat-icon-minimal {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .stat-content-minimal {
        flex: 1;
    }

    .stat-label-minimal {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        color: #8b9cb0;
        display: block;
        margin-bottom: 0.15rem;
    }

    .stat-value-minimal {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        line-height: 1.2;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
        align-items: baseline;
    }

    .info-label {
        width: 100px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        flex-shrink: 0;
    }

    .info-value {
        flex: 1;
        font-size: 0.85rem;
        color: #1e293b;
        font-weight: 500;
        word-break: break-word;
        padding-left: 0.5rem;
    }

    /* Alert Styles */
    .alert-minimal {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .alert-info-minimal {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
    }
</style>
