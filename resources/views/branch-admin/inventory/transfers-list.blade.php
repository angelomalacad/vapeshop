@extends('layouts.branch-admin')

@section('title', 'Stock Transfers - Vape Expo')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Stock Transfers</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-arrow-left-right me-1"></i> Manage incoming and outgoing transfer requests
            </p>
        </div>
        <a href="{{ route('branch-admin.inventory.transfer.form') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Transfer Request
        </a>
    </div>

    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['filter' => 'all']) }}">
                All
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('filter') == 'incoming' ? 'active' : '' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['filter' => 'incoming']) }}">
                Incoming (To Your Branch)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('filter') == 'outgoing' ? 'active' : '' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['filter' => 'outgoing']) }}">
                Outgoing (From Your Branch)
            </a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Transfer #</th>
                            <th>Date</th>
                            <th>From Branch</th>
                            <th>To Branch</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        <tr>
                            <td><code>{{ $transfer->transfer_number }}</code></td>
                            <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                            <td>
                                {{ $transfer->fromBranch->name }}
                                @if($transfer->from_branch_id == Auth::user()->branch_id)
                                    <span class="badge bg-info ms-1">Your Branch</span>
                                @endif
                            </td>
                            <td>
                                {{ $transfer->toBranch->name }}
                                @if($transfer->to_branch_id == Auth::user()->branch_id)
                                    <span class="badge bg-info ms-1">Your Branch</span>
                                @endif
                            </td>
                            <td>{{ $transfer->product->name }}</td>
                            <td>{{ $transfer->flavor->name ?? 'N/A' }}</td>
                            <td><strong>{{ $transfer->quantity }}</strong></td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'approved' => 'info',
                                        'in_transit' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusIcons = [
                                        'pending' => 'bi-hourglass',
                                        'approved' => 'bi-check-circle',
                                        'in_transit' => 'bi-truck',
                                        'completed' => 'bi-check-circle-fill',
                                        'cancelled' => 'bi-x-circle'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$transfer->status] }}">
                                    <i class="bi {{ $statusIcons[$transfer->status] }} me-1"></i>
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>
                            <td>
                                @if($transfer->requestedBy)
                                    <small>{{ $transfer->requestedBy->name }}</small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- FOR OUTGOING TRANSFERS (from your branch - you are the SOURCE) -->
                                    @if($transfer->from_branch_id == Auth::user()->branch_id)
                                        @if($transfer->status == 'pending')
                                            <!-- Outgoing pending - Source branch can approve or reject -->
                                            <form action="{{ route('branch-admin.inventory.transfers.approve', $transfer) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Approve Transfer" onclick="return confirm('Approve this transfer request?')">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('branch-admin.inventory.transfers.reject', $transfer) }}" method="POST" class="d-inline ms-1">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" title="Reject Transfer" onclick="return confirm('Reject this transfer request?')">
                                                    <i class="bi bi-x-lg"></i> Reject
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                    
                                    <!-- FOR INCOMING TRANSFERS (to your branch - you are the DESTINATION) -->
                                    @if($transfer->to_branch_id == Auth::user()->branch_id)
                                        @if($transfer->status == 'approved')
                                            <!-- Incoming approved - Destination branch can receive -->
                                            <form action="{{ route('branch-admin.inventory.transfers.complete', $transfer) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary" title="Receive Stock" onclick="return confirm('Mark this transfer as received?')">
                                                    <i class="bi bi-check-lg"></i> Receive Stock
                                                </button>
                                            </form>
                                        @elseif($transfer->status == 'pending')
                                            <span class="badge bg-warning">Waiting for source approval</span>
                                        @endif
                                    @endif
                                    
                                    <!-- REQUESTER CANCEL - Anyone can cancel their own pending request -->
                                    @if($transfer->requested_by == Auth::user()->id && $transfer->status == 'pending')
                                        <form action="{{ route('branch-admin.inventory.transfers.cancel', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Cancel My Request" onclick="return confirm('Cancel this transfer request you made?')">
                                                <i class="bi bi-x-circle"></i> Cancel My Request
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-arrow-left-right display-1 text-muted"></i>
                                <h5 class="mt-3">No Transfer Requests Found</h5>
                                <p class="text-muted mb-3">There are no stock transfer requests to display.</p>
                                <a href="{{ route('branch-admin.inventory.transfer.form') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create New Transfer Request
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(method_exists($transfers, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $transfers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection