@extends('layouts.admin-modal')

@section('title', 'Edit Inventory Item')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Inventory Item
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <form method="POST" action="{{ route('admin.inventory.update', $inventory) }}" id="editInventoryForm">
        @csrf
        @method('PUT')

        <div class="modal-body" style="padding: 0;">
            <!-- Hidden fields for branch, product, flavor -->
            <input type="hidden" name="branch_id" value="{{ $inventory->branch_id }}">
            <input type="hidden" name="product_id" value="{{ $inventory->product_id }}">
            <input type="hidden" name="flavor_id" value="{{ $inventory->flavor_id }}">

            <!-- Read-only Information Display -->
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-info-circle"></i> Product Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Branch</div>
                                <div class="info-value"><strong>{{ $inventory->branch->name }}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Product</div>
                                <div class="info-value"><strong>{{ $inventory->product->name }}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-row">
                                <div class="info-label">Flavor</div>
                                <div class="info-value"><strong>{{ $inventory->flavor->name ?? 'No Flavor' }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Settings Card -->
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-box-seam"></i> Stock Settings</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Quantity *</label>
                            <input type="number" name="quantity" class="form-control-minimal"
                                value="{{ $inventory->quantity }}" min="0" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Reserved Quantity *</label>
                            <input type="number" name="reserved_quantity" class="form-control-minimal"
                                value="{{ $inventory->reserved_quantity }}" min="0" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Low Stock Threshold *</label>
                            <input type="number" name="low_stock_threshold" class="form-control-minimal"
                                value="{{ $inventory->low_stock_threshold }}" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Last Purchase Price (₱)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="last_purchase_price" class="form-control-minimal"
                                    value="{{ $inventory->last_purchase_price }}" min="0">
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Last Restocked Date</label>
                            <input type="datetime-local" name="last_restocked_at" class="form-control-minimal"
                                value="{{ $inventory->last_restocked_at ? \Carbon\Carbon::parse($inventory->last_restocked_at)->format('Y-m-d\TH:i') : '' }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Expiration Date</label>
                            <input type="date" name="expiration_date" class="form-control-minimal"
                                value="{{ $inventory->expiration_date ? \Carbon\Carbon::parse($inventory->expiration_date)->format('Y-m-d') : '' }}">
                            <small class="text-muted">Leave empty if no expiration date</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-minimal">Status</label>
                            <div class="form-control-minimal bg-light">
                                @php
                                    $available = $inventory->quantity - ($inventory->reserved_quantity ?? 0);
                                    $isExpired =
                                        $inventory->expiration_date &&
                                        \Carbon\Carbon::parse($inventory->expiration_date)->isPast();
                                @endphp
                                @if ($isExpired)
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
                </div>
            </div>

            @if ($inventory->expiration_date)
                @php
                    $expiryDate = \Carbon\Carbon::parse($inventory->expiration_date);
                    $daysLeft = \Carbon\Carbon::now()->diffInDays($expiryDate, false);
                @endphp
                @if ($expiryDate->isPast())
                    <div class="alert-minimal alert-danger-minimal mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Expired!</strong> This product expired on {{ $expiryDate->format('M d, Y') }}.
                    </div>
                @elseif($daysLeft <= 30)
                    <div class="alert-minimal alert-warning-minimal mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Expiring Soon!</strong> This product expires in {{ $daysLeft }} days
                        ({{ $expiryDate->format('M d, Y') }}).
                    </div>
                @endif
            @endif
        </div>

        <div class="d-flex gap-2 justify-content-end mt-3">
            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">
                <i class="bi bi-x-circle me-1"></i> Cancel
            </button>
            <button type="submit" class="btn-update" style="width: auto;" id="submitBtn">
                <i class="bi bi-check-circle me-1"></i> Update Inventory
            </button>
        </div>
    </form>

    <script>
        (function() {
            const editForm = document.getElementById('editInventoryForm');
            if (!editForm) return;

            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                const formData = new FormData(editForm);

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';

                fetch(editForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close modal
                            const modalElement = document.querySelector('.modal');
                            if (modalElement) {
                                const modal = bootstrap.Modal.getInstance(modalElement);
                                if (modal) modal.hide();
                            }

                            // === USE THE GLOBAL NOTIFICATION FROM admin-notifications.js ===
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Inventory updated successfully!',
                                    'success');
                            } else {
                                // Fallback alert if global function missing
                                alert(data.message || 'Inventory updated successfully!');
                            }

                            // Reload page after notification
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Update failed', 'error');
                            } else {
                                alert('Error: ' + (data.message || 'Unknown error'));
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Network error. Please try again.', 'error');
                        } else {
                            alert('Network error. Please try again.');
                        }
                    });
            });
        })();
    </script>
@endsection

<style>
    .info-row {
        display: flex;
        margin-bottom: 0.5rem;
    }

    .info-label {
        width: 70px;
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
    }

    .alert-minimal {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .alert-danger-minimal {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #dc2626;
    }

    .alert-warning-minimal {
        background: #fefce8;
        border: 1px solid #fef3c7;
        color: #d97706;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
