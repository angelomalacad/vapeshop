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
        <div class="d-flex gap-2">
            <a href="{{ route('branch-admin.warehouse.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-building"></i> Warehouse Requests
            </a>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Filter Section Only (No Statistics Cards) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('branch-admin.inventory.stock-history') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Movement Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Sale</option>
                        <option value="receive" {{ request('type') == 'receive' ? 'selected' : '' }}>Warehouse Receive</option>
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
                            <th>Date & Time</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Type</th>
                            <th>Change</th>
                            <th>Previous Qty</th>
                            <th>New Qty</th>
                            <th>Notes</th>
                            <th>By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M d, Y - h:i A') }}</td>
                            <td>
                                <strong>{{ $movement->product->name ?? 'N/A' }}</strong>
                                @if($movement->product && $movement->product->brand)
                                    <br><small class="text-muted">{{ $movement->product->brand }}</small>
                                @endif
                            </td>
                            <td>{{ $movement->flavor->name ?? 'No Flavor' }}</td>
                            <td>
                                @php
                                    $typeColors = [
                                        'purchase' => 'success',
                                        'sale' => 'danger',
                                        'transfer_out' => 'warning',
                                        'transfer_in' => 'info',
                                        'receive' => 'primary',
                                        'return' => 'primary',
                                        'adjustment' => 'secondary',
                                    ];
                                    $typeIcons = [
                                        'purchase' => 'bi-box-arrow-in-down',
                                        'sale' => 'bi-cart-x',
                                        'receive' => 'bi-building',
                                        'transfer_out' => 'bi-send',
                                        'transfer_in' => 'bi-download',
                                        'adjustment' => 'bi-sliders'
                                    ];
                                    $color = $typeColors[$movement->movement_type] ?? 'secondary';
                                    $icon = $typeIcons[$movement->movement_type] ?? 'bi-clock';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    <i class="{{ $icon }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                </span>
                            <td>
                            <td class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                <strong>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ number_format($movement->quantity_change) }}</strong>
                            </td>
                            <td>{{ number_format($movement->previous_quantity) }}</td>
                            <td>{{ number_format($movement->new_quantity) }}</td>
                            <td>
                                <small class="text-muted">{{ Str::limit($movement->notes, 50) }}</small>
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
                <div class="d-flex justify-content-between align-items-center p-3 bg-white">
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