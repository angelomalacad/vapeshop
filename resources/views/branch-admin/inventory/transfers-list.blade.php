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
                <i class="bi bi-house-door me-1"></i> Main Warehouse
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

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('branch-admin.inventory.transfers') }}" class="row g-3">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                @if($activeTab == 'branch')
                    <input type="hidden" name="filter" value="{{ $filter ?? 'incoming' }}">
                @endif

                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <a href="{{ route('branch-admin.inventory.transfers', ['tab' => $activeTab, 'filter' => $filter ?? 'all']) }}" 
                           class="btn btn-secondary w-100">
                            <i class="bi bi-arrow-repeat"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
        @if(request('status'))
            — Filtered by: <strong>{{ ucfirst(request('status')) }}</strong>
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
                            <th>Variant</th>
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
                                @if(is_null($transfer->from_branch_id))
                                    <span class="fw-semibold text-primary">
                                        <i class="bi bi-house-door me-1"></i> Main Warehouse
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
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $statusIcons = [
                                        'pending' => 'bi-hourglass',
                                        'approved' => 'bi-check-circle',
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
                                    @if(is_null($transfer->from_branch_id))
                                        @if($transfer->to_branch_id == Auth::user()->branch_id)
                                            @if($transfer->status == 'approved')
                                                <!-- ✅ FIXED: Points to InventoryController via correct route -->
                                                <form action="{{ route('branch-admin.inventory.transfers.complete', $transfer) }}" method="POST" class="d-inline transfer-action-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Receive Stock">
                                                        <i class="bi bi-download"></i> Receive
                                                    </button>
                                                </form>
                                            @elseif($transfer->status == 'pending')
                                                <span class="badge bg-warning">Waiting for owner</span>
                                            @endif
                                        @endif
                                    @else
                                        @if($transfer->from_branch_id == Auth::user()->branch_id)
                                            @if($transfer->status == 'pending')
                                                <form action="{{ route('branch-admin.inventory.transfers.approve', $transfer) }}" method="POST" class="d-inline transfer-action-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Approve Transfer">
                                                        <i class="bi bi-check-lg"></i> Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('branch-admin.inventory.transfers.reject', $transfer) }}" method="POST" class="d-inline transfer-action-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Reject Transfer">
                                                        <i class="bi bi-x-lg"></i> Reject
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                        
                                        @if($transfer->to_branch_id == Auth::user()->branch_id)
                                            @if($transfer->status == 'approved')
                                                <form action="{{ route('branch-admin.inventory.transfers.complete', $transfer) }}" method="POST" class="d-inline transfer-action-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Receive Stock">
                                                        <i class="bi bi-check-lg"></i> Receive
                                                    </button>
                                                </form>
                                            @elseif($transfer->status == 'pending')
                                                <span class="badge bg-warning">Waiting for source</span>
                                            @endif
                                        @endif
                                    @endif
                                    
                                    @if($transfer->requested_by == Auth::user()->id && $transfer->status == 'pending')
                                        <form action="{{ route('branch-admin.inventory.transfers.cancel', $transfer) }}" method="POST" class="d-inline transfer-action-form">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Cancel My Request">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.transfer-action-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Show processing notification
            if (typeof window.showNotification === 'function') {
                window.showNotification('Processing request...', 'info');
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

            const formData = new FormData(this);

            // ✅ Force X-Requested-With to ensure Laravel knows it's AJAX
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // If server returned HTML, reload the current page
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    window.location.reload();
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;

                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                if (data.success) {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.message || 'Action completed successfully!', 'success');
                    }
                    
                    // ✅ FORCE REDIRECT TO TRANSFERS LIST PAGE
                    setTimeout(() => {
                        window.location.href = "{{ route('branch-admin.inventory.transfers') }}";
                    }, 1500);
                } else {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.message || 'Action failed. Please try again.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (typeof window.showNotification === 'function') {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (error.message && error.message.includes('<!DOCTYPE')) {
                        errorMsg = 'Server returned an error. Please refresh and try again.';
                    }
                    window.showNotification(errorMsg, 'error');
                }
            });
        });
    });
});
</script>
@endpush