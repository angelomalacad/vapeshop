@extends('layouts.branch-admin')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">{{ $inventory->product->name }}</h1>
                <p class="text-muted">{{ $inventory->flavor->name ?? 'No Flavor' }}</p>
            </div>
            <div>
                <a href="{{ route('branch-admin.inventory.add-stock', $inventory) }}" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> Add Stock
                </a>
                <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Stock Info -->
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Stock Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <th>Product:</th>
                                <td>{{ $inventory->product->name }}</td>
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
                                <td>₱{{ number_format($inventory->product->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>In Stock:</th>
                                <td class="fw-bold">{{ $inventory->quantity }}</td>
                            </tr>
                            <tr>
                                <th>Available:</th>
                                <td
                                    class="fw-bold {{ $inventory->available_quantity <= $inventory->low_stock_threshold ? 'text-warning' : 'text-success' }}">
                                    {{ $inventory->available_quantity }}
                                </td>
                            </tr>
                            <tr>
                                <th>Reserved:</th>
                                <td>{{ $inventory->reserved_quantity }}</td>
                            </tr>
                            <tr>
                                <th>Low Stock Alert:</th>
                                <td>{{ $inventory->low_stock_threshold }} units</td>
                            </tr>
                            <tr>
                                <th>Last Restocked:</th>
                                <td>{{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y') : 'Never' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Movement History -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Stock Movement History</h5>
                    </div>
                    <div class="card-body">
                        @if ($movements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
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
                                            <tr>
                                                <td>{{ $movement->created_at->format('M d, h:i A') }}</td>
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
                                                    {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                                                </td>
                                                <td>{{ $movement->previous_quantity }}</td>
                                                <td>{{ $movement->new_quantity }}</td>
                                                <td>{{ Str::limit($movement->notes, 20) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted py-3">No movement history yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
