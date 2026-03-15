@extends('layouts.admin')

@section('title', 'Inventory Details - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">{{ $inventory->product->name }}</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-shop me-1"></i> {{ $inventory->branch->name }} 
                    @if($inventory->flavor) • {{ $inventory->flavor->name }} @endif
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.inventory.edit', $inventory) }}" class="btn btn-warning rounded-pill px-3">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.inventory.add-stock', $inventory) }}" class="btn btn-success rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> Add Stock
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <!-- Stock Info Cards -->
    <div class="row g-3 mb-4">
        @php
            $available = $inventory->available_quantity;
            $statusClass = $available <= 0 ? 'danger' : ($available <= $inventory->low_stock_threshold ? 'warning' : 'success');
            $statusText = $available <= 0 ? 'Out of Stock' : ($available <= $inventory->low_stock_threshold ? 'Low Stock' : 'In Stock');
        @endphp
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Current Stock</h6>
                    <h2 class="mb-0 fw-bold">{{ $inventory->quantity }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Reserved</h6>
                    <h2 class="mb-0 fw-bold">{{ $inventory->reserved_quantity }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Available</h6>
                    <h2 class="mb-0 fw-bold">{{ $available }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-{{ $statusClass }} text-white">
                <div class="card-body">
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
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Inventory Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Branch:</td>
                            <td class="fw-semibold">{{ $inventory->branch->name }} ({{ $inventory->branch->code }})</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Product:</td>
                            <td>{{ $inventory->product->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Brand:</td>
                            <td>{{ $inventory->product->brand }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Flavor:</td>
                            <td>{{ $inventory->flavor->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Category:</td>
                            <td>{{ $inventory->product->category }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Price:</td>
                            <td>₱{{ number_format($inventory->product->price, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-gear me-2 text-primary"></i>Threshold Settings</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Low Stock Threshold:</td>
                            <td class="fw-semibold">{{ $inventory->low_stock_threshold }} units</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Reorder Point:</td>
                            <td>{{ $inventory->reorder_point }} units</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Optimal Stock Level:</td>
                            <td>{{ $inventory->optimal_stock }} units</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Last Purchase Price:</td>
                            <td>₱{{ number_format($inventory->last_purchase_price ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Last Restocked:</td>
                            <td>{{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y h:i A') : 'Never' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created:</td>
                            <td>{{ $inventory->created_at->format('M d, Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movement History -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Stock Movement History</h5>
            <a href="{{ route('admin.inventory.stock-history') }}?product_id={{ $inventory->product_id }}&flavor_id={{ $inventory->flavor_id }}" class="btn btn-sm btn-outline-primary rounded-pill">
                View Full History
            </a>
        </div>
        <div class="card-body p-0">
            @if($movements->count() > 0)
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Type</th>
                                <th>Change</th>
                                <th>Previous</th>
                                <th>New</th>
                                <th>Notes</th>
                                <th class="pe-4">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
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
                                    'expired' => 'dark'
                                ];
                                $color = $typeColors[$movement->movement_type] ?? 'secondary';
                            @endphp
                            <tr>
                                <td class="ps-4">{{ $movement->created_at->format('M d, Y - h:i A') }}</td>
                                <td>
                                    <span class="badge bg-{{ $color }} px-3 py-2">
                                        {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                    </span>
                                </td>
                                <td class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                                </td>
                                <td>{{ $movement->previous_quantity }}</td>
                                <td>{{ $movement->new_quantity }}</td>
                                <td>{{ Str::limit($movement->notes, 30) }}</td>
                                <td class="pe-4">{{ $movement->creator->name ?? 'System' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($movements instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $movements->links() }}
                    </div>
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-clock-history display-1 text-muted"></i>
                    <p class="mt-3 text-muted">No stock movements found</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection