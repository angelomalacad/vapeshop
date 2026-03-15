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
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
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
                            <td>{{ $movement->product->name ?? 'N/A' }}</td>
                            <td>{{ $movement->flavor->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $typeColors = [
                                        'purchase' => 'success',
                                        'sale' => 'danger',
                                        'transfer_out' => 'warning',
                                        'transfer_in' => 'info',
                                        'return' => 'primary',
                                        'adjustment' => 'secondary',
                                        'damaged' => 'dark',
                                        'expired' => 'dark'
                                    ];
                                    $color = $typeColors[$movement->movement_type] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                </span>
                            </td>
                            <td class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                <strong>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</strong>
                            </td>
                            <td>{{ $movement->previous_quantity }}</td>
                            <td>{{ $movement->new_quantity }}</td>
                            <td>{{ Str::limit($movement->notes, 30) }}</td>
                            <td>{{ $movement->creator->name ?? 'System' }}</td>
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

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $movements->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection