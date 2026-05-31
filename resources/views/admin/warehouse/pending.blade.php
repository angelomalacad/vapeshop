@extends('layouts.admin')

@section('title', 'Pending Distributions - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">
                <i class="bi bi-clock-history me-2 text-warning"></i>Branch Stock Requests
            </h1>
            <p class="text-muted small mb-0">Manage branch requests and view distribution history</p>
        </div>
        <a href="{{ route('admin.warehouse.index') }}" class="btn btn-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Warehouse
        </a>
    </div>

    <!-- Tabs for Pending and History -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ !request('tab') || request('tab') == 'pending' ? 'active' : '' }}" 
               href="{{ route('admin.warehouse.pending', ['tab' => 'pending']) }}">
                <i class="bi bi-hourglass-split me-1"></i> Pending Requests 
                @if($pendingCount > 0)
                    <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('tab') == 'history' ? 'active' : '' }}" 
               href="{{ route('admin.warehouse.pending', ['tab' => 'history']) }}">
                <i class="bi bi-clock-history me-1"></i> Distribution History
            </a>
        </li>
    </ul>

    <!-- PENDING REQUESTS TAB -->
    @if(!request('tab') || request('tab') == 'pending')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Pending Requests from Branches</h5>
            <p class="text-muted small mb-0 mt-1">Requests waiting for your approval</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Request #</th>
                            <th>Date</th>
                            <th>Requesting Branch</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Quantity</th>
                            <th>Expiration Date</th>
                            <th>Notes</th>
                            <th class="pe-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                            <tr>
                                <td class="ps-4"><code>{{ $transfer->transfer_number }}</code></td>
                                <td>{{ $transfer->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $transfer->toBranch->name ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $transfer->product->name ?? 'N/A' }}</td>
                                <td>{{ $transfer->flavor->name ?? 'N/A' }}</td>
                                <td><span class="fw-bold">{{ number_format($transfer->quantity) }}</span></td>
                                <td>
                                    @if($transfer->expiration_date)
                                        {{ \Carbon\Carbon::parse($transfer->expiration_date)->format('M d, Y') }}
                                        @if(\Carbon\Carbon::parse($transfer->expiration_date)->isPast())
                                            <span class="badge bg-danger ms-1">Expired</span>
                                        @elseif(\Carbon\Carbon::parse($transfer->expiration_date)->diffInDays(now()) <= 30)
                                            <span class="badge bg-warning ms-1">Soon</span>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($transfer->notes, 30) ?? 'No notes' }}</td>
                                <td class="pe-4 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-success rounded-pill me-1" 
                                                data-bs-toggle="modal" data-bs-target="#approveModal{{ $transfer->id }}">
                                            <i class="bi bi-check-lg me-1"></i>Approve
                                        </button>
                                        <button type="button" class="btn btn-danger rounded-pill" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $transfer->id }}">
                                            <i class="bi bi-x-lg me-1"></i>Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $transfer->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title"><i class="bi bi-check-circle me-2"></i>Approve Distribution</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to approve this distribution?</p>
                                            <div class="alert alert-info">
                                                <strong>Details:</strong><br>
                                                Branch: {{ $transfer->toBranch->name }}<br>
                                                Product: {{ $transfer->product->name }}<br>
                                                Flavor: {{ $transfer->flavor->name ?? 'N/A' }}<br>
                                                Quantity: {{ number_format($transfer->quantity) }} units<br>
                                                Expiration Date: @if($transfer->expiration_date) {{ \Carbon\Carbon::parse($transfer->expiration_date)->format('M d, Y') }} @else N/A @endif
                                            </div>
                                            <p class="text-warning mb-0">
                                                <i class="bi bi-exclamation-triangle me-1"></i>
                                                This will deduct stock from the warehouse.
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('admin.warehouse.approve', $transfer) }}" method="POST">
                                                @csrf
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Approve Distribution</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $transfer->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="bi bi-x-circle me-2"></i>Reject Distribution</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.warehouse.reject', $transfer) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Are you sure you want to reject this distribution request?</p>
                                                <div class="alert alert-warning">
                                                    <strong>Request Details:</strong><br>
                                                    Branch: {{ $transfer->toBranch->name }}<br>
                                                    Product: {{ $transfer->product->name }}<br>
                                                    Flavor: {{ $transfer->flavor->name ?? 'N/A' }}<br>
                                                    Quantity: {{ number_format($transfer->quantity) }} units<br>
                                                    Expiration Date: @if($transfer->expiration_date) {{ \Carbon\Carbon::parse($transfer->expiration_date)->format('M d, Y') }} @else N/A @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for rejection (Optional)</label>
                                                    <textarea name="rejection_reason" class="form-control" rows="2" placeholder="e.g., Insufficient stock, etc."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Reject Request</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted">No pending distribution requests</p>
                                    <a href="{{ route('admin.warehouse.index') }}" class="btn btn-primary">
                                        Go to Warehouse
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transfers->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
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
        </div>
        @endif
    </div>
    @endif

    <!-- DISTRIBUTION HISTORY TAB -->
    @if(request('tab') == 'history')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-check-circle me-2 text-success"></i>Distribution History</h5>
            <p class="text-muted small mb-0 mt-1">Log of all completed and rejected distributions</p>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Transfer #</th>
                            <th>Date Requested</th>
                            <th>Branch</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Quantity</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th>Processed Date</th>
                            <th class="pe-4">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyTransfers as $history)
                            <tr>
                                <td class="ps-4"><code>{{ $history->transfer_number }}</code></td>
                                <td>{{ $history->created_at->format('M d, Y h:i A') }}</td>
                                <td><span class="badge bg-info">{{ $history->toBranch->name ?? 'N/A' }}</span></td>
                                <td>{{ $history->product->name ?? 'N/A' }}</td>
                                <td>{{ $history->flavor->name ?? 'N/A' }}</td>
                                <td><span class="fw-bold">{{ number_format($history->quantity) }}</span></td>
                                <td>
                                    @if($history->expiration_date)
                                        {{ \Carbon\Carbon::parse($history->expiration_date)->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($history->status == 'completed')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>Completed
                                        </span>
                                    @elseif($history->status == 'cancelled')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle-fill me-1"></i>Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($history->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($history->approved_at)
                                        {{ \Carbon\Carbon::parse($history->approved_at)->format('M d, Y') }}
                                    @elseif($history->completed_at)
                                        {{ \Carbon\Carbon::parse($history->completed_at)->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <small class="text-muted">{{ Str::limit($history->notes, 40) ?? '—' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bi bi-clock-history fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted">No distribution history found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($historyTransfers->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $historyTransfers->firstItem() }} to {{ $historyTransfers->lastItem() }} of {{ $historyTransfers->total() }} results
                </div>
                <div class="d-flex gap-2">
                    @if ($historyTransfers->onFirstPage())
                        <span class="btn btn-secondary disabled">Previous</span>
                    @else
                        <a href="{{ $historyTransfers->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                    @endif
                    
                    @if ($historyTransfers->hasMorePages())
                        <a href="{{ $historyTransfers->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
                    @else
                        <span class="btn btn-secondary disabled">Next</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection