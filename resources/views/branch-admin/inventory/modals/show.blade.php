@extends('layouts.admin-modal')

@section('content')
    <!-- Product Details Header -->
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-eye"></i>Product Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <!-- Header Info -->
    <div class="mb-3">
        <h5 class="mb-0 fw-bold">{{ $inventory->product->name }}</h5>
        <span class="text-muted small">{{ $inventory->flavor->name ?? 'No Flavor' }}</span>
    </div>

    <!-- Stock Information Section -->
    <div class="info-card">
        <div class="card-header-minimal">
            <h6><i class="bi bi-box-seam"></i>Stock Information</h6>
        </div>
        <div class="card-body-minimal">
            <div class="info-row">
                <span class="info-label">Product:</span>
                <span class="info-value"><strong>{{ $inventory->product->name }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Flavor:</span>
                <span class="info-value">{{ $inventory->flavor->name ?? 'N/A' }}</span>
            </div>
            {{-- <div class="info-row">
                <span class="info-label">SKU:</span>
                <span class="info-value"><code>{{ $inventory->product->sku ?? 'N/A' }}</code></span>
            </div> --}}
            <div class="info-row">
                <span class="info-label">Price:</span>
                <span class="info-value"><strong class="text-primary">₱{{ number_format($inventory->product->price, 2) }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">In Stock:</span>
                <span class="info-value fw-bold">{{ $inventory->quantity }} units</span>
            </div>
            <div class="info-row">
                <span class="info-label">Available:</span>
                <span class="info-value fw-bold {{ $inventory->available_quantity <= $inventory->low_stock_threshold ? 'text-warning' : 'text-success' }}">
                    {{ $inventory->available_quantity }} units
                </span>
            </div>
            {{-- <div class="info-row">
                <span class="info-label">Reserved:</span>
                <span class="info-value">{{ $inventory->reserved_quantity }} units</span>
            </div> --}}
            <div class="info-row">
                <span class="info-label">Low Stock Alert:</span>
                <span class="info-value">{{ $inventory->low_stock_threshold }} units</span>
            </div>
            {{-- <div class="info-row">
                <span class="info-label">Reorder Point:</span>
                <span class="info-value">{{ $inventory->reorder_point }} units</span>
            </div> --}}
            <div class="info-row">
                <span class="info-label">Optimal Stock:</span>
                <span class="info-value">{{ $inventory->optimal_stock }} units</span>
            </div>
            <div class="info-row">
                <span class="info-label">Last Restocked:</span>
                <span class="info-value">{{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y') : 'Never' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Expiration:</span>
                <span class="info-value">
                    @if ($inventory->expiration_date)
                        {{ \Carbon\Carbon::parse($inventory->expiration_date)->format('M d, Y') }}
                        @if (\Carbon\Carbon::parse($inventory->expiration_date)->isPast())
                            <span class="badge bg-danger ms-1">Expired</span>
                        @elseif(\Carbon\Carbon::parse($inventory->expiration_date)->diffInDays(now()) <= 30)
                            <span class="badge bg-warning ms-1">Soon</span>
                        @endif
                    @else
                        <span class="text-muted">No expiry</span>
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Value:</span>
                <span class="info-value fw-bold text-primary">
                    ₱{{ number_format($inventory->quantity * ($inventory->last_purchase_price ?? $inventory->product->price), 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Stock Movement History Section -->
    <div class="info-card">
        <div class="card-header-minimal">
            <h6><i class="bi bi-clock-history"></i>Stock Movement History</h6>
        </div>
        <div class="card-body-minimal p-0">
            @if ($movements->count() > 0)
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 95px">Date</th>
                                <th style="width: 100px">Type</th>
                                <th style="width: 70px">Change</th>
                                <th style="width: 70px">Prev</th>
                                <th style="width: 70px">New</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $movement)
                                <tr>
                                    <td><small>{{ $movement->created_at->format('M d, h:i A') }}</small></td>
                                    <td>
                                        @php
                                            $colors = [
                                                'purchase' => 'success',
                                                'sale' => 'danger',
                                                'transfer_out' => 'warning',
                                                'transfer_in' => 'info',
                                                'return' => 'primary',
                                                'adjustment' => 'secondary',
                                                'damaged' => 'dark',
                                                'expired' => 'dark',
                                                'warehouse_transfer_in' => 'info',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $colors[$movement->movement_type] ?? 'secondary' }}" style="font-size: 10px;">
                                            {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                        </span>
                                    </td>
                                    <td class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                        <strong>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</strong>
                                    </td>
                                    <td><small>{{ $movement->previous_quantity }}</small></td>
                                    <td><small>{{ $movement->new_quantity }}</small></td>
                                    <td><small class="text-muted">{{ Str::limit($movement->notes, 30) }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- View Full History Link -->
                <div class="text-center py-2 border-top">
                    <a href="{{ route('branch-admin.inventory.stock-history') }}?product_id={{ $inventory->product_id }}&flavor_id={{ $inventory->flavor_id }}"
                        class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="bi bi-clock-history me-1"></i> View Full History
                    </a>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                    <p class="small">No movement history yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Footer Actions -->
    <div class="d-flex gap-2 mt-3">
        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Close</button>
    </div>
@endsection