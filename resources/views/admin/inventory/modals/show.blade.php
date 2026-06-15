@extends('layouts.admin-modal')

@section('title', 'Inventory Details')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-eye me-2"></i> Inventory Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body" style="padding: 0;">
        <!-- Stock Info Cards - Modern Minimalist (No Animation) -->
        <div class="row g-3 mb-4">
            @php
                $available = $inventory->available_quantity;
                $statusClass =
                    $available <= 0
                        ? 'danger'
                        : ($available <= $inventory->low_stock_threshold
                            ? 'warning'
                            : 'success');
                $statusText =
                    $available <= 0
                        ? 'Out of Stock'
                        : ($available <= $inventory->low_stock_threshold
                            ? 'Low Stock'
                            : 'In Stock');
            @endphp
            <div class="col-md-3 col-6">
                <div class="stat-card-minimal">
                    <div class="stat-icon-minimal" style="background: #eef4ff; color: #3b82f6;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content-minimal">
                        <span class="stat-label-minimal">Current Stock</span>
                        <h3 class="stat-value-minimal">{{ $inventory->quantity }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-minimal">
                    <div class="stat-icon-minimal" style="background: #fefce8; color: #f59e0b;">
                        <i class="bi bi-lock"></i>
                    </div>
                    <div class="stat-content-minimal">
                        <span class="stat-label-minimal">Reserved</span>
                        <h3 class="stat-value-minimal">{{ $inventory->reserved_quantity }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-minimal">
                    <div class="stat-icon-minimal" style="background: #e6f7e6; color: #10b981;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content-minimal">
                        <span class="stat-label-minimal">Available</span>
                        <h3 class="stat-value-minimal">{{ $available }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-minimal">
                    <div class="stat-icon-minimal"
                        style="background: {{ $statusClass == 'danger' ? '#fef2f2' : ($statusClass == 'warning' ? '#fefce8' : '#e6f7e6') }}; color: {{ $statusClass == 'danger' ? '#ef4444' : ($statusClass == 'warning' ? '#f59e0b' : '#10b981') }};">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="stat-content-minimal">
                        <span class="stat-label-minimal">Status</span>
                        <span class="stat-value-minimal"
                            style="font-size: 0.85rem; display: block;">{{ $statusText }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Details Card -->
        <div class="info-card mt-3">
            <div class="card-header-minimal">
                <h6><i class="bi bi-info-circle"></i> Inventory Details</h6>
            </div>
            <div class="card-body-minimal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Branch</div>
                            <div class="info-value">{{ $inventory->branch->name }} ({{ $inventory->branch->code }})</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Product</div>
                            <div class="info-value">{{ $inventory->product->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Brand</div>
                            <div class="info-value">{{ $inventory->product->brand }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Flavor</div>
                            <div class="info-value">{{ $inventory->flavor->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Category</div>
                            <div class="info-value">{{ $inventory->product->category }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Price</div>
                            <div class="info-value">₱{{ number_format($inventory->product->price, 2) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Expiration Date</div>
                            <div class="info-value">
                                @if ($inventory->expiration_date)
                                    {{ \Carbon\Carbon::parse($inventory->expiration_date)->format('F d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Threshold Settings Card -->
        <div class="info-card mt-3">
            <div class="card-header-minimal">
                <h6><i class="bi bi-gear"></i> Threshold Settings</h6>
            </div>
            <div class="card-body-minimal">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Low Stock Threshold</div>
                            <div class="info-value">{{ $inventory->low_stock_threshold }} units</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Reorder Point</div>
                            <div class="info-value">{{ $inventory->reorder_point }} units</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Optimal Stock Level</div>
                            <div class="info-value">{{ $inventory->optimal_stock }} units</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-row">
                            <div class="info-label">Last Purchase Price</div>
                            <div class="info-value">₱{{ number_format($inventory->last_purchase_price ?? 0, 2) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Last Restocked</div>
                            <div class="info-value">
                                {{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y h:i A') : 'Never' }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Created</div>
                            <div class="info-value">{{ $inventory->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movement History Card -->
        <div class="info-card mt-3">
            <div class="card-header-minimal">
                <h6><i class="bi bi-clock-history"></i> Recent Stock Movements</h6>
            </div>
            <div class="card-body-minimal p-0">
                @if ($movements->count() > 0)
                    <div class="table-responsive">
                        <table class="table admin-table mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Change</th>
                                    <th>Previous</th>
                                    <th>New</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movements as $movement)
                                    @php
                                        $typeColors = [
                                            'purchase' => 'success',
                                            'sale' => 'danger',
                                            'transfer_out' => 'warning',
                                            'transfer_in' => 'info',
                                            'return' => 'primary',
                                            'adjustment' => 'secondary',
                                            'initial' => 'primary',
                                            'damaged' => 'dark',
                                            'expired' => 'dark',
                                        ];
                                        $color = $typeColors[$movement->movement_type] ?? 'secondary';
                                    @endphp
                                    <tr>
                                        <td class="small">{{ $movement->created_at->format('M d, Y - h:i A') }}</td>
                                        <td><span
                                                class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}</span>
                                        </td>
                                        <td
                                            class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                            {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                                        </td>
                                        <td>{{ $movement->previous_quantity }}</td>
                                        <td>{{ $movement->new_quantity }}</td>
                                        <td class="small text-muted">{{ Str::limit($movement->notes, 25) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clock-history fs-1 text-muted"></i>
                        <p class="mt-2 text-muted">No stock movements found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
        </button>
    </div>
@endsection

<style>
    /* Minimalist Stats Cards - No Animation */
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
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        line-height: 1.2;
    }

    .admin-table {
        margin-bottom: 0;
    }

    .admin-table th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #eef2f6;
    }

    .admin-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        font-size: 0.8rem;
        border-bottom: 1px solid #eef2f6;
    }

    /* Info Rows - Proper Spacing */
    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
        align-items: baseline;
    }

    .info-label {
        width: 140px;
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

    .info-value.text-muted {
        color: #64748b;
        font-style: italic;
    }
</style>
