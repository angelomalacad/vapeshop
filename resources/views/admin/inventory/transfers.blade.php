@extends('layouts.admin')

@section('title', 'Stock Transfers - Vape Expo')
<style>
.stat-card-modern {
    background: #ffffff;
    border-radius: 20px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
    border: 1px solid #eef2f6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
}

.stat-card-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
    border-color: #e0e7ed;
}

.stat-icon-wrapper {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    transition: all 0.3s ease;
}

.stat-card-modern:hover .stat-icon-wrapper {
    transform: scale(1.02);
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    font-weight: 600;
    color: #8b9cb0;
    display: block;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    color: #1e293b;
    line-height: 1.2;
}

/* Individual icon colors - Updated for transfers */
.col-md-3:nth-child(1) .stat-icon-wrapper {
    background: #fef3c7;
    color: #d97706;
}

.col-md-3:nth-child(2) .stat-icon-wrapper {
    background: #dbeafe;
    color: #2563eb;
}

.col-md-3:nth-child(3) .stat-icon-wrapper {
    background: #d1fae5;
    color: #059669;
}

.col-md-3:nth-child(4) .stat-icon-wrapper {
    background: #fee2e2;
    color: #dc2626;
}

@media (max-width: 768px) {
    .stat-card-modern {
        padding: 1rem;
        gap: 0.75rem;
    }

    .stat-icon-wrapper {
        width: 44px;
        height: 44px;
        font-size: 1.3rem;
        border-radius: 14px;
    }

    .stat-value {
        font-size: 1.4rem;
    }

    .stat-label {
        font-size: 0.65rem;
    }
}
</style>
@section('content')
<div class="container-fluid px-4">
    <!-- Header with Logo and Navigation -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Stock Transfers</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-arrow-left-right me-1"></i> Manage all transfer requests
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.inventory.create-transfer') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> New Transfer
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-box-seam me-1"></i> All Inventory
            </a>
            <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-warning rounded-pill px-3">
                <i class="bi bi-exclamation-triangle me-1"></i> Low Stock
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
<div class="row g-3 mb-4">
    @php
        $pendingCount = \App\Models\StockTransfer::where('status', 'pending')->count();
        $approvedCount = \App\Models\StockTransfer::where('status', 'approved')->count();
        $completedCount = \App\Models\StockTransfer::where('status', 'completed')->count();
        $rejectedCount = \App\Models\StockTransfer::where('status', 'cancelled')->count();
    @endphp
    
    <!-- Pending -->
    <div class="col-md-3 col-6">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="bi bi-hourglass"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Pending</span>
                <h3 class="stat-value">{{ $pendingCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Approved -->
    <div class="col-md-3 col-6">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Approved</span>
                <h3 class="stat-value">{{ $approvedCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Completed -->
    <div class="col-md-3 col-6">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Completed</span>
                <h3 class="stat-value">{{ $completedCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Rejected -->
    <div class="col-md-3 col-6">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Rejected</span>
                <h3 class="stat-value">{{ $rejectedCount }}</h3>
            </div>
        </div>
    </div>
</div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Transfers</h5>
            <span class="text-muted small">Total: {{ $transfers->total() }} transfers</span>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">From Branch</label>
                    <select name="from_branch" class="form-select">
                        <option value="">All</option>
                        <option value="warehouse">Main Warehouse</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('from_branch') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">To Branch</label>
                    <select name="to_branch" class="form-select">
                        <option value="">All</option>
                        <option value="warehouse">Main Warehouse</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('to_branch') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.inventory.transfers') }}" class="btn btn-outline-secondary ms-2 px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Transfer #</th>
                            <th>Date</th>
                            <th>From Branch</th>
                            <th>To Branch</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
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
                        <tr>
                            <td class="ps-4"><code class="fw-semibold">{{ $transfer->transfer_number }}</code></td>
                            <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                            <td>
                                @if(is_null($transfer->from_branch_id))
                                    <span class="fw-semibold text-primary">
                                        <i class="bi bi-house-door me-1"></i> Main Warehouse
                                    </span>
                                @elseif($transfer->fromBranch)
                                    {{ $transfer->fromBranch->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if(is_null($transfer->to_branch_id))
                                    <span class="fw-semibold text-primary">
                                        <i class="bi bi-house-door me-1"></i> Main Warehouse
                                    </span>
                                @elseif($transfer->toBranch)
                                    {{ $transfer->toBranch->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $transfer->product->name ?? 'N/A' }}</td>
                            <td>{{ $transfer->flavor->name ?? 'N/A' }}</td>
                            <td><span class="fw-bold">{{ number_format($transfer->quantity) }}</span></td>
                            <td>
                                <span class="badge bg-{{ $statusColors[$transfer->status] }} px-3 py-2">
                                    <i class="bi {{ $statusIcons[$transfer->status] }} me-1"></i>
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>
                            <td>{{ $transfer->requestedBy->name ?? 'System' }}</td>
                            <td class="pe-4">
                                <div class="btn-group btn-group-sm">
                                    <!-- View Details -->
                                    <button type="button" class="btn btn-outline-info" title="View Details" onclick="openTransferModal({{ $transfer->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <!-- Edit - Only show for pending transfers -->
                                    @if($transfer->status == 'pending')
                                        <button type="button" class="btn btn-outline-warning" title="Edit" onclick="openEditTransferModal({{ $transfer->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif

                                    <!-- Approve (only pending) -->
                                    @if($transfer->status == 'pending')
                                        <button type="button" class="btn btn-outline-success" title="Approve"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#approveModal"
                                            data-id="{{ $transfer->id }}"
                                            data-name="{{ $transfer->transfer_number }}">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    @endif

                                    <!-- Reject (only pending) -->
                                    @if($transfer->status == 'pending')
                                        <button type="button" class="btn btn-outline-danger" title="Reject"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal"
                                            data-id="{{ $transfer->id }}"
                                            data-name="{{ $transfer->transfer_number }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif

                                    <!-- Complete (only approved) -->
                                    @if($transfer->status == 'approved')
                                        <button type="button" class="btn btn-outline-success" title="Complete"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#completeModal"
                                            data-id="{{ $transfer->id }}"
                                            data-name="{{ $transfer->transfer_number }}">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </button>
                                    @endif


                                    <!-- Delete (only cancelled or completed) -->
                                    @if(in_array($transfer->status, ['cancelled', 'completed']))
                                        <button type="button" class="btn btn-outline-danger" title="Delete"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            data-id="{{ $transfer->id }}"
                                            data-name="{{ $transfer->transfer_number }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-arrow-left-right display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No transfer requests found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($transfers->hasPages())
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
</div>

<!-- Transfer Details Modal Container -->
<div class="modal fade" id="transferModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"></div>
    </div>
</div>

<!-- Edit Transfer Modal Container -->
<div class="modal fade" id="editTransferModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"></div>
    </div>
</div>

<!-- Approve Transfer Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle-fill me-2"></i> Approve Transfer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve this transfer?</p>
                <p class="fw-bold" id="approveItemName"></p>
                <p class="text-muted small">This will mark the transfer as approved and ready to be completed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <form id="approveForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Approve
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Transfer Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle-fill me-2"></i> Reject Transfer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this transfer?</p>
                <p class="fw-bold" id="rejectItemName"></p>
                <p class="text-muted small">This will cancel the transfer and release the reserved stock.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-lg me-1"></i> Reject
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Complete Transfer Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-check-circle-fill me-2"></i> Complete Transfer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to complete this transfer?</p>
                <p class="fw-bold" id="completeItemName"></p>
                <p class="text-muted small">This will move the stock from source to destination branch.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <form id="completeForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle-fill me-1"></i> Complete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Transfer Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-stop-circle-fill me-2"></i> Cancel Transfer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this transfer?</p>
                <p class="fw-bold" id="cancelItemName"></p>
                <p class="text-muted small">This will cancel the transfer and release the reserved stock.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <form id="cancelForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-stop-circle me-1"></i> Cancel Transfer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Transfer Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-trash-fill me-2"></i> Delete Transfer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this transfer?</p>
                <p class="fw-bold" id="deleteItemName"></p>
                <p class="text-muted small text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancel
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Transfer Details Modal
    function openTransferModal(id) {
        const modalElement = document.getElementById('transferModal');
        const modalContent = modalElement.querySelector('.modal-content');
        const url = '/admin/inventory/transfers/' + id + '/show-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div><p>Loading...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                const modal = new bootstrap.Modal(modalElement);
                
                modalElement.addEventListener('hidden.bs.modal', function() {
                    window.location.reload();
                }, { once: true });
                
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading details</div>';
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            });
    }
    
    // Edit Transfer Modal
    function openEditTransferModal(id) {
        const modalElement = document.getElementById('editTransferModal');
        const modalContent = modalElement.querySelector('.modal-content');
        const url = '/admin/inventory/transfers/' + id + '/edit-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-warning" role="status"></div><p>Loading...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Only pending transfers can be edited.');
                }
                return response.text();
            })
            .then(html => {
                modalContent.innerHTML = html;
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
            });
    }

    // Show Transfer Modal - Called after update
    function openShowTransferModal(id) {
        const modalElement = document.getElementById('transferModal');
        const modalContent = modalElement.querySelector('.modal-content');
        const url = '/admin/inventory/transfers/' + id + '/show-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div><p>Loading...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                const modal = new bootstrap.Modal(modalElement);
                
                modalElement.addEventListener('hidden.bs.modal', function() {
                    window.location.reload();
                }, { once: true });
                
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading details</div>';
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            });
    }

    // ============ ACTION MODALS ============
    document.addEventListener('DOMContentLoaded', function() {
        // Approve Modal
        const approveModal = document.getElementById('approveModal');
        if (approveModal) {
            approveModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const transferId = button.getAttribute('data-id');
                const transferName = button.getAttribute('data-name');
                document.getElementById('approveItemName').textContent = transferName || 'Unknown Transfer';
                document.getElementById('approveForm').action = '/admin/inventory/transfers/' + transferId + '/approve';
            });
        }

        // Reject Modal
        const rejectModal = document.getElementById('rejectModal');
        if (rejectModal) {
            rejectModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const transferId = button.getAttribute('data-id');
                const transferName = button.getAttribute('data-name');
                document.getElementById('rejectItemName').textContent = transferName || 'Unknown Transfer';
                document.getElementById('rejectForm').action = '/admin/inventory/transfers/' + transferId + '/reject';
            });
        }

        // Complete Modal
        const completeModal = document.getElementById('completeModal');
        if (completeModal) {
            completeModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const transferId = button.getAttribute('data-id');
                const transferName = button.getAttribute('data-name');
                document.getElementById('completeItemName').textContent = transferName || 'Unknown Transfer';
                document.getElementById('completeForm').action = '/admin/inventory/transfers/' + transferId + '/complete';
            });
        }

        // Cancel Modal
        const cancelModal = document.getElementById('cancelModal');
        if (cancelModal) {
            cancelModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const transferId = button.getAttribute('data-id');
                const transferName = button.getAttribute('data-name');
                document.getElementById('cancelItemName').textContent = transferName || 'Unknown Transfer';
                document.getElementById('cancelForm').action = '/admin/inventory/transfers/' + transferId + '/cancel';
            });
        }

        // Delete Modal
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const transferId = button.getAttribute('data-id');
                const transferName = button.getAttribute('data-name');
                document.getElementById('deleteItemName').textContent = transferName || 'Unknown Transfer';
                document.getElementById('deleteForm').action = '/admin/inventory/transfers/' + transferId;
            });
        }
    });

    // ============ HANDLE EDIT FORM SUBMISSION ============
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form && form.id === 'editTransferForm') {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitBtn = document.getElementById('submitEditTransferBtn');
                const originalText = submitBtn ? submitBtn.innerHTML : 'Update';
                const actionUrl = form.action;
                const transferId = formData.get('transfer_id');
                
                if (!transferId) {
                    alert('Error: Could not find transfer ID');
                    return;
                }
                
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';
                }
                
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const editModal = bootstrap.Modal.getInstance(document.getElementById('editTransferModal'));
                        if (editModal) editModal.hide();
                        
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Transfer updated successfully!', 'success');
                        }
                        
                        setTimeout(function() {
                            if (typeof openShowTransferModal === 'function') {
                                openShowTransferModal(transferId);
                            }
                        }, 500);
                    } else {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Error updating transfer', 'error');
                        }
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Network error. Please try again.', 'error');
                    }
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
        });
    });
</script>
@endsection