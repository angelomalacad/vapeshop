@extends('layouts.admin-modal')

@section('content')
    <!-- Modal Header -->
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square"></i>Edit Inventory Item
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <!-- Product Info Summary -->
    <div class="alert alert-info bg-light border-0 mb-4" style="border-radius: 12px;">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Product</small>
                <strong>{{ $inventory->product->name }}</strong>
                @if ($inventory->flavor)
                    <br><span class="badge bg-secondary mt-1">{{ $inventory->flavor->name }}</span>
                @endif
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Category</small>
                <strong>{{ $inventory->product->category }}</strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Price</small>
                <strong>₱{{ number_format($inventory->product->price, 2) }}</strong>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('branch-admin.inventory.update', $inventory) }}" 
          id="editForm{{ $inventory->id }}">
        @csrf
        @method('PUT')

        <!-- Stock Quantities Section -->
        <div class="info-card">
            <div class="card-header-minimal">
                <h6><i class="bi bi-box-seam"></i>Stock Quantities</h6>
            </div>
            <div class="card-body-minimal">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-minimal">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control-minimal" value="{{ $inventory->quantity }}" min="0" required>
                        <small class="text-muted" style="font-size: 0.7rem;">Physical stock count</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-minimal">Available Quantity</label>
                        <input type="text" class="form-control-minimal bg-light" value="{{ $inventory->available_quantity }}" readonly disabled>
                        <small class="text-muted" style="font-size: 0.7rem;">Auto-calculated (Quantity - Reserved)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Threshold Settings Section -->
        <div class="info-card">
            <div class="card-header-minimal">
                <h6><i class="bi bi-exclamation-triangle"></i>Threshold Settings</h6>
            </div>
            <div class="card-body-minimal">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-minimal">Low Stock Threshold <span class="text-danger">*</span></label>
                        <input type="number" name="low_stock_threshold" class="form-control-minimal" value="{{ $inventory->low_stock_threshold }}" min="1" required>
                        <small class="text-muted" style="font-size: 0.7rem;">Alert when stock reaches this level</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-minimal">Optimal Stock Level <span class="text-danger">*</span></label>
                        <input type="number" name="optimal_stock" class="form-control-minimal" value="{{ $inventory->optimal_stock }}" min="1" required>
                        <small class="text-muted" style="font-size: 0.7rem;">Target stock level</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timestamp Section -->
        <div class="info-card">
            <div class="card-header-minimal">
                <h6><i class="bi bi-calendar"></i>Last Updated</h6>
            </div>
            <div class="card-body-minimal">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label-minimal">Last Restocked Date</label>
                        <input type="datetime-local" name="last_restocked_at" class="form-control-minimal" value="{{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('Y-m-d\TH:i') : '' }}">
                        <small class="text-muted" style="font-size: 0.7rem;">When stock was last added</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label-minimal">Last Updated</label>
                        <input type="text" class="form-control-minimal bg-light" value="{{ $inventory->updated_at ? $inventory->updated_at->format('M d, Y h:i A') : 'Never' }}" readonly disabled>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="info-card">
            <div class="card-body-minimal">
                <div class="d-flex align-items-center">
                    <span class="me-3" style="font-size: 0.8rem; font-weight: 600; color: #64748b;">Current Status:</span>
                    @if ($inventory->available_quantity <= 0)
                        <span class="badge bg-danger" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Out of Stock</span>
                    @elseif($inventory->available_quantity <= $inventory->low_stock_threshold)
                        <span class="badge bg-warning" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">Low Stock</span>
                    @else
                        <span class="badge bg-success" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">In Stock</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Warning Alert -->
        <div class="alert alert-warning alert-minimal mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Warning:</strong> Changing quantity directly will affect inventory levels. Use "Add Stock" for regular restocking.
        </div>

        <!-- Footer Actions -->
        <div class="d-flex gap-2 mt-3">
            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn-update" data-inventory-id="{{ $inventory->id }}" style="width: auto; padding: 0.5rem 1.25rem;">
                <i class="bi bi-save"></i> Update Inventory Settings
            </button>
        </div>
    </form>
@endsection