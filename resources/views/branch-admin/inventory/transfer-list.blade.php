@extends('layouts.branch-admin')

@section('title', 'Stock Transfers - Vape Expo')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Stock Transfers</h1>
        <a href="{{ route('branch-admin.inventory.transfer.form') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Transfer Request
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Transfer #</th>
                            <th>Date</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        <tr>
                            <td><code>{{ $transfer->transfer_number }}</code></td>
                            <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                            <td>{{ $transfer->fromBranch->name }}</td>
                            <td>{{ $transfer->toBranch->name }}</td>
                            <td>{{ $transfer->product->name }}</td>
                            <td>{{ $transfer->flavor->name ?? 'N/A' }}</td>
                            <td>{{ $transfer->quantity }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'info',
                                        'in_transit' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$transfer->status] }}">
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>
                            <td>
                                @if($transfer->to_branch_id == Auth::user()->branch_id && $transfer->status == 'approved')
                                    <form action="{{ route('branch-admin.inventory.transfers.complete', $transfer) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i> Receive
                                        </button>
                                    </form>
                                @endif
                                
                                @if($transfer->from_branch_id == Auth::user()->branch_id && $transfer->status == 'pending')
                                    <form action="{{ route('branch-admin.inventory.transfers.cancel', $transfer) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this transfer?')">
                                            <i class="bi bi-x-lg"></i> Cancel
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-arrow-left-right display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No transfer requests found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection