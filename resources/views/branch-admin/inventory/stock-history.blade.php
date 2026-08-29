@extends('layouts.branch-admin')

@section('title', 'Stock Movement History - Vape Expo')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Stock Movement History</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-clock-history me-1"></i> Complete log of all stock changes for your branch
            </p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('branch-admin.inventory.stock-history') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Movement Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>POS Sale</option>
                        <option value="online_sale" {{ request('type') == 'online_sale' ? 'selected' : '' }}>Online Order Sale</option>
                        <option value="reserve" {{ request('type') == 'reserve' ? 'selected' : '' }}>Online Order Reserve</option>
                        <option value="reserve_cancel" {{ request('type') == 'reserve_cancel' ? 'selected' : '' }}>Reservation Cancelled</option>
                        <option value="warehouse_receive" {{ request('type') == 'warehouse_receive' ? 'selected' : '' }}>Warehouse Receive</option>
                        <option value="transfer_out" {{ request('type') == 'transfer_out' ? 'selected' : '' }}>Transfer Out</option>
                        <option value="transfer_in" {{ request('type') == 'transfer_in' ? 'selected' : '' }}>Transfer In</option>
                        <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                        <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Return</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select">
                        <option value="">All Products</option>
                        @foreach($products ?? [] as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Flavor</label>
                    <select name="flavor_id" class="form-select">
                        <option value="">All Flavors</option>
                        @foreach($flavors ?? [] as $flavor)
                            <option value="{{ $flavor->id }}" {{ request('flavor_id') == $flavor->id ? 'selected' : '' }}>
                                {{ $flavor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Apply Filters
                        </button>
                        <a href="{{ route('branch-admin.inventory.stock-history') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-repeat"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 15%">Date & Time</th>
                            <th style="width: 20%">Product</th>
                            <th style="width: 15%">Flavor</th>
                            <th style="width: 15%">Type</th>
                            <th style="width: 10%">Change</th>
                            <th style="width: 10%">Previous Qty</th>
                            <th style="width: 10%">New Qty</th>
                            <th style="width: 30%">Notes</th>
                            <th style="width: 15%">By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td class="text-nowrap">{{ $movement->created_at->format('M d, Y - h:i A') }}</td>
                            <td>
                                <strong>{{ $movement->product->name ?? 'N/A' }}</strong>
                                @if($movement->product && $movement->product->brand)
                                    <br><small class="text-muted">{{ $movement->product->brand }}</small>
                                @endif
                            </td>
                            <td>{{ $movement->flavor->name ?? 'No Flavor' }}</td>
                            <td>
                                @php
                                    $movementType = $movement->movement_type;
                                    $notes = $movement->notes ?? '';
                                    $lowerNotes = strtolower($notes);
                                    
                                    // ✅ Check if notes contain "pos" (POS Sale)
                                    $isPOS = str_contains($lowerNotes, 'pos');
                                    
                                    // ✅ Check if notes contain "order #ord-" or "ord-" (Online Order)
                                    $isOnlineOrder = str_contains($lowerNotes, 'order #ord-') || str_contains($lowerNotes, 'ord-');
                                    
                                    // Map movement types to display names
                                    $typeDisplay = [
                                        'purchase' => 'Purchase',
                                        'sale' => 'Sale',
                                        'online_sale' => 'Online Order Sale',
                                        'reserve' => 'Online Order Reserve',
                                        'reserve_cancel' => 'Reservation Cancelled',
                                        'transfer_out' => 'Transfer Out',
                                        'transfer_in' => 'Transfer In',
                                        'receive' => 'Warehouse Receive',
                                        'warehouse_receive' => 'Warehouse Receive',
                                        'return' => 'Return',
                                        'adjustment' => 'Adjustment',
                                    ];
                                    
                                    // ✅ Override display name based on notes
                                    if ($movementType === 'sale' && $isOnlineOrder) {
                                        $displayName = 'Online Order Sale';
                                    } elseif ($movementType === 'sale' && $isPOS) {
                                        $displayName = 'POS Sale';
                                    } elseif ($movementType === 'sale') {
                                        $displayName = 'POS Sale'; // Default for sale
                                    } elseif (isset($typeDisplay[$movementType])) {
                                        $displayName = $typeDisplay[$movementType];
                                    } else {
                                        $displayName = ucfirst(str_replace('_', ' ', $movementType));
                                    }
                                    
                                    $typeColors = [
                                        'purchase' => 'success',
                                        'sale' => 'danger',
                                        'online_sale' => 'danger',
                                        'reserve' => 'warning',
                                        'reserve_cancel' => 'info',
                                        'transfer_out' => 'warning',
                                        'transfer_in' => 'info',
                                        'receive' => 'primary',
                                        'warehouse_receive' => 'primary',
                                        'return' => 'primary',
                                        'adjustment' => 'secondary',
                                    ];
                                    
                                    $color = $typeColors[$movementType] ?? 'secondary';
                                    
                                    $typeIcons = [
                                        'purchase' => 'bi-box-arrow-in-down',
                                        'sale' => 'bi-cart-x',
                                        'online_sale' => 'bi-cart-check',
                                        'reserve' => 'bi-hourglass-split',
                                        'reserve_cancel' => 'bi-x-circle',
                                        'transfer_out' => 'bi-send',
                                        'transfer_in' => 'bi-download',
                                        'receive' => 'bi-building',
                                        'warehouse_receive' => 'bi-building',
                                        'adjustment' => 'bi-sliders'
                                    ];
                                    
                                    $icon = $typeIcons[$movementType] ?? 'bi-clock';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="{{ $icon }} me-1"></i>
                                    {{ $displayName }}
                                </span>
                            </td>
                            <td class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                <strong>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ number_format($movement->quantity_change) }}</strong>
                            </td>
                            <td>{{ number_format($movement->previous_quantity) }}</td>
                            <td>{{ number_format($movement->new_quantity) }}</td>
                            <td>
                                <small class="text-muted">{{ Str::limit($movement->notes, 60) }}</small>
                            </td>
                            <td>
                                <small>{{ $movement->creator->name ?? 'System' }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-clock-history display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No stock movements found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Simple Previous/Next Pagination -->
            @if ($movements->hasPages())
                <div class="d-flex justify-content-between align-items-center p-3 bg-white border-top">
                    <div class="text-muted small">
                        Showing {{ $movements->firstItem() }} to {{ $movements->lastItem() }} of {{ $movements->total() }} results
                    </div>
                    <div class="d-flex gap-2">
                        @if ($movements->onFirstPage())
                            <span class="btn btn-secondary disabled">Previous</span>
                        @else
                            <a href="{{ $movements->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                        @endif
                        
                        @if ($movements->hasMorePages())
                            <a href="{{ $movements->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
                        @else
                            <span class="btn btn-secondary disabled">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection