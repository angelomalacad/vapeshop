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

    <!-- Main Tabs: All, Warehouse, Branch to Branch -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'all' ? 'active' : '' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['tab' => 'all', 'filter' => $filter ?? 'all']) }}">
                <i class="bi bi-grid-3x3-gap-fill me-1"></i> All Transfers
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'warehouse' ? 'active' : '' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['tab' => 'warehouse', 'filter' => 'incoming']) }}">
                <i class="bi bi-building me-1"></i> Main Warehouse
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 'branch' ? 'active' : '' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['tab' => 'branch', 'filter' => $filter ?? 'incoming']) }}">
                <i class="bi bi-shop me-1"></i> Branch to Branch
            </a>
        </li>
    </ul>

    <!-- Sub Tabs (only for Branch to Branch) -->
    @if($activeTab == 'branch')
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link {{ ($filter ?? 'incoming') == 'incoming' ? 'active bg-primary text-white' : 'bg-light text-dark' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['tab' => 'branch', 'filter' => 'incoming']) }}">
                <i class="bi bi-download me-1"></i> Incoming (To Your Branch)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($filter ?? 'incoming') == 'outgoing' ? 'active bg-primary text-white' : 'bg-light text-dark' }}" 
               href="{{ route('branch-admin.inventory.transfers', ['tab' => 'branch', 'filter' => 'outgoing']) }}">
                <i class="bi bi-upload me-1"></i> Outgoing (From Your Branch)
            </a>
        </li>
    </ul>
    @endif

    <!-- Info Alert -->
    <div class="alert alert-info mb-3">
        <i class="bi bi-info-circle me-1"></i> 
        @if($activeTab == 'warehouse')
            Showing warehouse transfers <strong>to your branch</strong> (stock sent from Main Warehouse to you)
        @elseif($activeTab == 'branch')
            Showing branch-to-branch transfers where stock is <strong>{{ ($filter ?? 'incoming') == 'incoming' ? 'coming to your branch' : 'leaving your branch' }}</strong>
        @else
            Showing all transfers related to your branch
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Transfer #</th>
                            <th>Date</th>
                            <th>From</th>
                            <th>To</th>
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
                            
                            <!-- FROM Column -->
                            <td>
                                @if(is_null($transfer->from_branch_id))
                                    <span class="fw-semibold text-primary">
                                        <i class="bi bi-building me-1"></i> Main Warehouse
                                    </span>
                                @elseif($transfer->fromBranch)
                                    <i class="bi bi-shop me-1"></i> {{ $transfer->fromBranch->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                                @if($transfer->from_branch_id == Auth::user()->branch_id)
                                    <span class="badge bg-info ms-1">Your Branch</span>
                                @endif
                            </td>
                            
                            <!-- TO Column -->
                            <td>
                                @if($transfer->toBranch)
                                    <i class="bi bi-shop me-1"></i> {{ $transfer->toBranch->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                                @if($transfer->to_branch_id == Auth::user()->branch_id)
                                    <span class="badge bg-info ms-1">Your Branch</span>
                                @endif
                            </td>
                            
                            <td>{{ $transfer->product->name ?? 'N/A' }}</td>
                            <td>{{ $transfer->flavor->name ?? 'N/A' }}</td>
                            <td><strong>{{ number_format($transfer->quantity) }}</strong></td>
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
                                    <small class="text-muted">System</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Warehouse Transfer Actions (only incoming) -->
                                    @if(is_null($transfer->from_branch_id))
                                        @if($transfer->to_branch_id == Auth::user()->branch_id)
                                            @if($transfer->status == 'approved')
                                                <form action="{{ route('branch-admin.warehouse.receive', $transfer) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Receive Stock" onclick="return confirm('Receive this stock? It will be added to your inventory.')">
                                                        <i class="bi bi-download"></i> Receive
                                                    </button>
                                                </form>
                                            @elseif($transfer->status == 'pending')
                                                <span class="badge bg-warning">Waiting for owner</span>
                                            @endif
                                        @endif
                                    @else
                                        <!-- Branch to Branch Transfer Actions -->
                                        @if($transfer->from_branch_id == Auth::user()->branch_id)
                                            @if($transfer->status == 'pending')
                                                <form action="{{ route('branch-admin.inventory.transfers.approve', $transfer) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Approve Transfer" onclick="return confirm('Approve this transfer request?')">
                                                        <i class="bi bi-check-lg"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('branch-admin.inventory.transfers.reject', $transfer) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Reject Transfer" onclick="return confirm('Reject this transfer request?')">
                                                        <i class="bi bi-x-lg"></i> Reject
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        
                                        @if($transfer->to_branch_id == Auth::user()->branch_id)
                                            @if($transfer->status == 'approved')
                                                <form action="{{ route('branch-admin.inventory.transfers.complete', $transfer) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Receive Stock" onclick="return confirm('Mark this transfer as received?')">
                                                        <i class="bi bi-check-lg"></i> Receive
                                                    </button>
                                                </form>
                                            @elseif($transfer->status == 'pending')
                                                <span class="badge bg-warning">Waiting for source</span>
                                            @endif
                                        @endif
                                    @endif
                                    
                                    <!-- Cancel for own requests -->
                                    @if($transfer->requested_by == Auth::user()->id && $transfer->status == 'pending')
                                        <form action="{{ route('branch-admin.inventory.transfers.cancel', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Cancel My Request" onclick="return confirm('Cancel this transfer request you made?')">
                                                <i class="bi bi-x-circle"></i> Cancel
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
                                <p class="text-muted mb-3">
                                    @if($activeTab == 'warehouse')
                                        No warehouse transfers found.
                                    @elseif($activeTab == 'branch')
                                        No branch-to-branch transfers {{ ($filter ?? 'incoming') == 'incoming' ? 'to' : 'from' }} your branch.
                                    @else
                                        No transfers related to your branch.
                                    @endif
                                </p>
                                <a href="{{ route('branch-admin.inventory.transfer.form') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Create New Transfer Request
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Simple Previous/Next Pagination -->
            @if(method_exists($transfers, 'hasPages') && $transfers->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                    <div class="text-muted small">
                        Showing {{ $transfers->firstItem() }} to {{ $transfers->lastItem() }} of {{ $transfers->total() }} results
                    </div>
                    <div class="d-flex gap-2">
                        @if ($transfers->onFirstPage())
                            <span class="btn btn-secondary disabled">Previous</span>
                        @else
                            <a href="{{ $transfers->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                        @endif
                        
                        @if ($transfers->hasMorePages())
                            <a href="{{ $transfers->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
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