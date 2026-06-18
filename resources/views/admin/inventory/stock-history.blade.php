@extends('layouts.admin')

@section('title', 'Stock Movement History - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Stock Movement History</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-clock-history me-1"></i> Complete log of all inventory changes
                    </p>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex gap-2">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary rounded-pill px-3">
                    <i class="bi bi-box-seam me-1"></i> Back to Inventory
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Movements</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">All Branches</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Product</label>
                        <select name="product_id" class="form-select">
                            <option value="">All Products</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Movement Type</label>
                        <select name="movement_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="purchase" {{ request('movement_type') == 'purchase' ? 'selected' : '' }}>Purchase
                            </option>
                            <option value="sale" {{ request('movement_type') == 'sale' ? 'selected' : '' }}>Sale</option>
                            <option value="transfer_out"
                                {{ request('movement_type') == 'transfer_out' ? 'selected' : '' }}>Transfer Out</option>
                            <option value="transfer_in" {{ request('movement_type') == 'transfer_in' ? 'selected' : '' }}>
                                Transfer In</option>
                            <option value="adjustment" {{ request('movement_type') == 'adjustment' ? 'selected' : '' }}>
                                Adjustment</option>
                            <option value="initial" {{ request('movement_type') == 'initial' ? 'selected' : '' }}>Initial
                                Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"
                            placeholder="From">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.inventory.stock-history') }}" class="btn btn-outline-secondary ms-2 px-4">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Movements Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Date & Time</th>
                                <th>Branch</th>
                                <th>Product</th>
                                <th>Flavor</th>
                                <th>Type</th>
                                <th>Change</th>
                                <th>Previous</th>
                                <th>New</th>
                                <th>Notes</th>
                                <th class="pe-4">By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $movement)
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
                                    <td class="ps-4">{{ $movement->created_at->format('M d, Y - h:i A') }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $movement->branch->name ?? 'Unknown Branch' }}</span>
                                    </td>
                                    <td>{{ $movement->product->name ?? 'N/A' }}</td>
                                    <td>{{ $movement->flavor->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $color }} px-3 py-2">
                                            {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                        </span>
                                    </td>
                                    <td
                                        class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                        {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                                    </td>
                                    <td>{{ $movement->previous_quantity }}</td>
                                    <td>{{ $movement->new_quantity }}</td>
                                    <td>{{ Str::limit($movement->notes, 30) }}</td>
                                    <td class="pe-4">{{ $movement->creator->name ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="bi bi-clock-history display-1 text-muted"></i>
                                        <p class="mt-3 text-muted">No stock movements found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($movements->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-end">
                        @if ($movements->onFirstPage())
                            <span class="btn btn-outline-secondary disabled rounded-pill px-3 me-2">
                                Previous
                            </span>
                        @else
                            <a href="{{ $movements->previousPageUrl() }}"
                                class="btn btn-outline-primary rounded-pill px-3 me-2">
                                Previous
                            </a>
                        @endif

                        @if ($movements->hasMorePages())
                            <a href="{{ $movements->nextPageUrl() }}" class="btn btn-outline-primary rounded-pill px-3">
                                Next
                            </a>
                        @else
                            <span class="btn btn-outline-secondary disabled rounded-pill px-3">
                                Next
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
