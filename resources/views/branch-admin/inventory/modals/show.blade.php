<!-- Modal content only - no layout -->
<div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h5 class="modal-title">
            <i class="bi bi-eye me-2"></i>Product Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <!-- Header Info -->
        <div class="mb-4">
            <h3 class="mb-0">{{ $inventory->product->name }}</h3>
            <p class="text-muted">{{ $inventory->flavor->name ?? 'No Flavor' }}</p>
        </div>

        <div class="row">
            <!-- Stock Info -->
            <div class="col-md-5">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2 text-primary"></i>Stock Information</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <tr>
                                <th style="width: 40%">Product:</th>
                                <td><strong>{{ $inventory->product->name }}</strong></td>
                            </tr>
                            <tr>
                                <th>Flavor:</th>
                                <td>{{ $inventory->flavor->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>SKU:</th>
                                <td><code>{{ $inventory->product->sku }}</code></td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td><strong
                                        class="text-primary">₱{{ number_format($inventory->product->price, 2) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>In Stock:</th>
                                <td class="fw-bold">{{ $inventory->quantity }} units</td>
                            </tr>
                            <tr>
                                <th>Available:</th>
                                <td
                                    class="fw-bold {{ $inventory->available_quantity <= $inventory->low_stock_threshold ? 'text-warning' : 'text-success' }}">
                                    {{ $inventory->available_quantity }} units
                                </td>
                            </tr>
                            <tr>
                                <th>Reserved:</th>
                                <td>{{ $inventory->reserved_quantity }} units</td>
                            </tr>
                            <tr>
                                <th>Low Stock Alert:</th>
                                <td>{{ $inventory->low_stock_threshold }} units</td>
                            </tr>
                            <tr>
                                <th>Reorder Point:</th>
                                <td>{{ $inventory->reorder_point }} units</td>
                            </tr>
                            <tr>
                                <th>Optimal Stock:</th>
                                <td>{{ $inventory->optimal_stock }} units</td>
                            </tr>
                            <tr>
                                <th>Last Restocked:</th>
                                <td>{{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y') : 'Never' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Expiration Date:</th>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <th>Total Value:</th>
                                <td class="fw-bold text-primary">
                                    ₱{{ number_format($inventory->quantity * ($inventory->last_purchase_price ?? $inventory->product->price), 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                        onclick="event.preventDefault(); window.location.href='{{ route('branch-admin.inventory.add-stock', $inventory) }}'">
                        <i class="bi bi-plus-circle"></i> Add Stock
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal"
                        onclick="event.preventDefault(); window.location.href='{{ route('branch-admin.inventory.edit', $inventory) }}'">
                        <i class="bi bi-pencil"></i> Edit Inventory
                    </button>
                </div>
            </div>

            <!-- Movement History -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Stock Movement History
                        </h5>
                    </div>
                    <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                        @if ($movements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
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
                                            <tr>
                                                <td><small>{{ $movement->created_at->format('M d, h:i A') }}</small>
                                                </td>
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
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $colors[$movement->movement_type] ?? 'secondary' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                                    <strong>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</strong>
                                                </td>
                                                <td><small>{{ $movement->previous_quantity }}</small></td>
                                                <td><small>{{ $movement->new_quantity }}</small></td>
                                                <td><small
                                                        class="text-muted">{{ Str::limit($movement->notes, 25) }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                                <p>No movement history yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- View Full History Link -->
                @if ($movements->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('branch-admin.inventory.stock-history') }}?product_id={{ $inventory->product_id }}&flavor_id={{ $inventory->flavor_id }}"
                            class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="bi bi-clock-history me-1"></i> View Full History
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-primary">
            <i class="bi bi-box-arrow-right"></i> Back to Inventory
        </a>
    </div>
</div>
