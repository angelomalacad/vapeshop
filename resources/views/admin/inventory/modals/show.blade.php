<div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h5 class="modal-title">
            <i class="bi bi-eye me-2"></i> Inventory Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
        <!-- Stock Info Cards -->
        <div class="row g-3 mb-4">
            @php
                $available = $inventory->available_quantity;
                $statusClass = $available <= 0 ? 'danger' : ($available <= $inventory->low_stock_threshold ? 'warning' : 'success');
                $statusText = $available <= 0 ? 'Out of Stock' : ($available <= $inventory->low_stock_threshold ? 'Low Stock' : 'In Stock');
            @endphp
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body text-center">
                        <h6 class="text-white-50 mb-1">Current Stock</h6>
                        <h2 class="mb-0 fw-bold">{{ $inventory->quantity }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <h6 class="text-white-50 mb-1">Reserved</h6>
                        <h2 class="mb-0 fw-bold">{{ $inventory->reserved_quantity }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h6 class="text-white-50 mb-1">Available</h6>
                        <h2 class="mb-0 fw-bold">{{ $available }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-{{ $statusClass }} text-white">
                    <div class="card-body text-center">
                        <h6 class="text-white-50 mb-1">Status</h6>
                        <h2 class="mb-0 fw-bold">{{ $statusText }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Details -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Inventory Details</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td class="text-muted">Branch:</td><td class="fw-semibold">{{ $inventory->branch->name }} ({{ $inventory->branch->code }})</td></tr>
                            <tr><td class="text-muted">Product:</td><td class="fw-semibold">{{ $inventory->product->name }}</td></tr>
                            <tr><td class="text-muted">Brand:</td><td class="fw-semibold">{{ $inventory->product->brand }}</td></tr>
                            <tr><td class="text-muted">Flavor:</td><td class="fw-semibold">{{ $inventory->flavor->name ?? 'N/A' }}</td></tr>
                            <tr><td class="text-muted">Category:</td><td class="fw-semibold">{{ $inventory->product->category }}</td></tr>
                            <tr><td class="text-muted">Price:</td><td class="fw-semibold">₱{{ number_format($inventory->product->price, 2) }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-gear me-2 text-primary"></i>Threshold Settings</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td class="text-muted">Low Stock Threshold:</td><td class="fw-semibold">{{ $inventory->low_stock_threshold }} units</td></tr>
                            <tr><td class="text-muted">Reorder Point:</td><td class="fw-semibold">{{ $inventory->reorder_point }} units</td></tr>
                            <tr><td class="text-muted">Optimal Stock Level:</td><td class="fw-semibold">{{ $inventory->optimal_stock }} units</td></tr>
                            <tr><td class="text-muted">Last Purchase Price:</td><td class="fw-semibold">₱{{ number_format($inventory->last_purchase_price ?? 0, 2) }}</td></tr>
                            <tr><td class="text-muted">Last Restocked:</td><td class="fw-semibold">{{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y h:i A') : 'Never' }}</td></tr>
                            <tr><td class="text-muted">Created:</td><td class="fw-semibold">{{ $inventory->created_at->format('M d, Y') }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movement History -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Stock Movements</h6>
            </div>
            <div class="card-body p-0">
                @if($movements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr><th>Date</th><th>Type</th><th>Change</th><th>Previous</th><th>New</th><th>Notes</th></tr>
                            </thead>
                            <tbody>
                                @foreach($movements as $movement)
                                @php
                                    $typeColors = ['purchase' => 'success', 'sale' => 'danger', 'transfer_out' => 'warning', 'transfer_in' => 'info', 'return' => 'primary', 'adjustment' => 'secondary', 'initial' => 'primary', 'damaged' => 'dark', 'expired' => 'dark'];
                                    $color = $typeColors[$movement->movement_type] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td>{{ $movement->created_at->format('M d, Y - h:i A') }}</td>
                                    <td><span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}</span></td>
                                    <td class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</td>
                                    <td>{{ $movement->previous_quantity }}</td>
                                    <td>{{ $movement->new_quantity }}</td>
                                    <td>{{ Str::limit($movement->notes, 20) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-clock-history display-4 text-muted"></i>
                        <p class="mt-2 text-muted">No stock movements found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
        </button>
    </div>
</div>