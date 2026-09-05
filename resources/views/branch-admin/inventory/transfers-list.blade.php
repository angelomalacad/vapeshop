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
        @if ($activeTab == 'branch')
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
                    @if ($activeTab == 'branch')
                        <input type="hidden" name="filter" value="{{ $filter ?? 'incoming' }}">
                    @endif

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
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
            @if ($activeTab == 'warehouse')
                Showing warehouse transfers <strong>to your branch</strong> (stock sent from Main Warehouse to you)
            @elseif($activeTab == 'branch')
                Showing branch-to-branch transfers where stock is
                <strong>{{ ($filter ?? 'incoming') == 'incoming' ? 'coming to your branch' : 'leaving your branch' }}</strong>
            @else
                Showing all transfers related to your branch
            @endif
            @if (request('status'))
                — Filtered by: <strong>{{ ucfirst(request('status')) }}</strong>
            @endif
        </div>

        <!-- Queue/Pending Transfers Notice -->
        @if ($activeTab == 'branch' && ($filter ?? 'incoming') == 'incoming')
            @php
                $queuedTransfers = \App\Models\StockTransfer::where('to_branch_id', Auth::user()->branch_id)
                    ->where('status', 'pending')
                    ->where('transfer_type', 'branch_to_branch')
                    ->count();
            @endphp
            @if ($queuedTransfers > 0)
                <div class="alert alert-warning mb-3">
                    <i class="bi bi-hourglass-split me-1"></i>
                    You have <strong>{{ $queuedTransfers }}</strong> pending transfer request(s) waiting for source branch
                    approval.
                </div>
            @endif
        @endif

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
                                @php
                                    // Determine if this transfer has a Receive button
                                    $hasReceiveButton = false;

                                    // Check for Warehouse Transfer Receive button
                                    if (is_null($transfer->from_branch_id)) {
                                        if (
                                            $transfer->to_branch_id == Auth::user()->branch_id &&
                                            $transfer->status == 'approved'
                                        ) {
                                            $hasReceiveButton = true;
                                        }
                                    } else {
                                        // Check for Branch to Branch Receive button
                                        if (
                                            $transfer->to_branch_id == Auth::user()->branch_id &&
                                            $transfer->status == 'approved'
                                        ) {
                                            $hasReceiveButton = true;
                                        }
                                    }
                                @endphp
                                <tr data-transfer-id="{{ $transfer->id }}"
                                    data-rejection-reason="{{ $transfer->rejection_reason ?? '' }}"
                                    data-notes="{{ $transfer->notes ?? '' }}"
                                    data-approved-by="{{ $transfer->approvedBy->name ?? 'N/A' }}"
                                    data-approved-at="{{ $transfer->approved_at ?? '' }}"
                                    data-received-by="{{ $transfer->receivedBy->name ?? 'N/A' }}"
                                    data-received-at="{{ $transfer->received_at ?? '' }}"
                                    data-rejected-by="{{ $transfer->rejectedBy->name ?? 'N/A' }}"
                                    data-cancelled-by="{{ $transfer->cancelledBy->name ?? 'N/A' }}">
                                    <td><code>{{ $transfer->transfer_number }}</code></td>
                                    <td>{{ $transfer->created_at->format('M d, Y') }}</td>

                                    <!-- FROM Column -->
                                    <td>
                                        @if (is_null($transfer->from_branch_id))
                                            <span class="fw-semibold text-primary">
                                                <i class="bi bi-house-door me-1"></i> Main Warehouse
                                            </span>
                                        @elseif($transfer->fromBranch)
                                            <i class="bi bi-shop me-1"></i> {{ $transfer->fromBranch->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                        @if ($transfer->from_branch_id == Auth::user()->branch_id)
                                            <span class="badge bg-info ms-1">Your Branch</span>
                                        @endif
                                    </td>

                                    <!-- TO Column -->
                                    <td>
                                        @if ($transfer->toBranch)
                                            <i class="bi bi-shop me-1"></i> {{ $transfer->toBranch->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                        @if ($transfer->to_branch_id == Auth::user()->branch_id)
                                            <span class="badge bg-info ms-1">Your Branch</span>
                                        @endif
                                    </td>

                                    <td>{{ $transfer->product->name ?? 'N/A' }}</td>
                                    <td>{{ $transfer->flavor->name ?? 'N/A' }}</td>
                                    <td><strong>{{ number_format($transfer->quantity) }}</strong></td>

                                    <!-- Status Badge -->
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $statusIcons = [
                                                'pending' => 'bi-hourglass',
                                                'approved' => 'bi-check-circle',
                                                'completed' => 'bi-check-circle-fill',
                                                'cancelled' => 'bi-x-circle',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$transfer->status] }}">
                                            <i class="bi {{ $statusIcons[$transfer->status] }} me-1"></i>
                                            {{ ucfirst($transfer->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($transfer->requestedBy)
                                            <small>{{ $transfer->requestedBy->name }}</small>
                                        @else
                                            <small class="text-muted">System</small>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <!-- Warehouse Transfer Actions (only incoming) -->
                                            @if (is_null($transfer->from_branch_id))
                                                @if ($transfer->to_branch_id == Auth::user()->branch_id)
                                                    @if ($transfer->status == 'approved')
                                                        <form
                                                            action="{{ route('branch-admin.inventory.transfers.complete', $transfer) }}"
                                                            method="POST" class="d-inline transfer-action-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm"
                                                                title="Receive Stock">
                                                                <i class="bi bi-download"></i> Receive
                                                            </button>
                                                        </form>
                                                    @elseif($transfer->status == 'pending')
                                                        <span class="badge bg-warning">Waiting for owner</span>
                                                    @endif
                                                @endif
                                            @else
                                                <!-- Branch to Branch Transfer Actions -->
                                                @if ($transfer->from_branch_id == Auth::user()->branch_id)
                                                    @if ($transfer->status == 'pending')
                                                        <form
                                                            action="{{ route('branch-admin.inventory.transfers.approve', $transfer) }}"
                                                            method="POST" class="d-inline transfer-action-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm"
                                                                title="Approve Transfer">
                                                                <i class="bi bi-check-lg"></i> Approve
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            title="Reject Transfer"
                                                            onclick="openRejectModal('{{ $transfer->id }}', '{{ $transfer->transfer_number }}')">
                                                            <i class="bi bi-x-lg"></i> Decline
                                                        </button>
                                                    @endif
                                                @endif

                                                @if ($transfer->to_branch_id == Auth::user()->branch_id)
                                                    @if ($transfer->status == 'approved')
                                                        <form
                                                            action="{{ route('branch-admin.inventory.transfers.complete', $transfer) }}"
                                                            method="POST" class="d-inline transfer-action-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-sm"
                                                                title="Receive Stock">
                                                                <i class="bi bi-check-lg"></i> Receive
                                                            </button>
                                                        </form>
                                                    @elseif($transfer->status == 'pending')
                                                        <span class="badge bg-warning">Waiting for source</span>
                                                    @endif
                                                @endif
                                            @endif

                                            <!-- Cancel for own requests -->
                                            @if ($transfer->requested_by == Auth::user()->id && $transfer->status == 'pending')
                                                <form
                                                    action="{{ route('branch-admin.inventory.transfers.cancel', $transfer) }}"
                                                    method="POST" class="d-inline transfer-action-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Cancel My Request">
                                                        <i class="bi bi-x-circle"></i> Cancel
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Show More button for ALL cancelled transfers -->
                                            @if ($transfer->status == 'cancelled')
                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                    title="View Cancellation Details"
                                                    onclick="viewTransferDetails('{{ $transfer->id }}', '{{ $transfer->transfer_number }}')">
                                                    <i class="bi bi-info-circle"></i> More
                                                </button>
                                            @endif

                                            <!-- Show More button for approved/completed transfers (only when NO Receive button) -->
                                            @if (in_array($transfer->status, ['approved', 'completed']) && !$hasReceiveButton)
                                                <button type="button" class="btn btn-outline-info btn-sm"
                                                    title="View Details"
                                                    onclick="viewTransferDetails('{{ $transfer->id }}', '{{ $transfer->transfer_number }}')">
                                                    <i class="bi bi-info-circle"></i> More
                                                </button>
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
                                            @if ($activeTab == 'warehouse')
                                                No warehouse transfers found.
                                            @elseif($activeTab == 'branch')
                                                No branch-to-branch transfers
                                                {{ ($filter ?? 'incoming') == 'incoming' ? 'to' : 'from' }} your branch.
                                            @else
                                                No transfers related to your branch.
                                            @endif
                                        </p>
                                        <a href="{{ route('branch-admin.inventory.transfer.form') }}"
                                            class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Create New Transfer Request
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if (method_exists($transfers, 'hasPages') && $transfers->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-2 border-top">
                        <div class="text-muted small">
                            Showing {{ $transfers->firstItem() }} to {{ $transfers->lastItem() }} of
                            {{ $transfers->total() }} results
                        </div>
                        <div class="d-flex gap-2">
                            @if ($transfers->onFirstPage())
                                <span class="btn btn-secondary disabled">Previous</span>
                            @else
                                <a href="{{ $transfers->previousPageUrl() }}"
                                    class="btn btn-outline-primary">Previous</a>
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

    <!-- Rejection Reason Modal -->
    <div class="modal fade" id="rejectTransferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="border-bottom: 1px solid #eef2f6;">
                    <h5 class="modal-title">
                        <i class="bi bi-x-circle text-danger me-2"></i> Reject Transfer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectTransferForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to reject this transfer?</p>
                        <p class="fw-bold" id="rejectTransferNumber"></p>
                        <div class="mb-3">
                            <label class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="3" required
                                placeholder="Please provide a reason for rejecting this transfer..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #eef2f6;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-lg me-1"></i> Reject Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Transfer Details Modal -->
    <div class="modal fade" id="viewTransferDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="border-bottom: 1px solid #eef2f6;">
                    <h5 class="modal-title">
                        <i class="bi bi-info-circle text-primary me-2"></i> Transfer Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="transferDetailsBody">
                    <!-- Content will be loaded via AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading details...</p>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eef2f6;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                // Server returned HTML — show a friendly error notification
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                                if (typeof window.showNotification === 'function') {
                                    window.showNotification(
                                        'The server encountered an error. Please refresh and try again.',
                                        'error');
                                }
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
                                    window.showNotification(data.message ||
                                        'Action completed successfully!',
                                        'success');
                                }
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                if (typeof window.showNotification === 'function') {
                                    window.showNotification(data.message ||
                                        'Action failed. Please try again.',
                                        'error');
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
                                    errorMsg =
                                        'Server returned an error. Please refresh and try again.';
                                }
                                window.showNotification(errorMsg, 'error');
                            }
                        });
                });
            });
        });

        // Rejection Modal Functions
        let rejectTransferId = null;

        function openRejectModal(transferId, transferNumber) {
            rejectTransferId = transferId;
            document.getElementById('rejectTransferNumber').textContent = 'Transfer #' + transferNumber;

            // Clear previous reason
            document.querySelector('#rejectTransferForm textarea[name="rejection_reason"]').value = '';

            const rejectModal = new bootstrap.Modal(document.getElementById('rejectTransferModal'));
            rejectModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rejectForm = document.getElementById('rejectTransferForm');
            if (rejectForm) {
                rejectForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const reasonInput = this.querySelector('textarea[name="rejection_reason"]');
                    const reason = reasonInput.value.trim();

                    if (!reason) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Please provide a reason for rejection.', 'error');
                        }
                        return;
                    }

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

                    fetch('/branch-admin/inventory/transfers/' + rejectTransferId + '/reject', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                rejection_reason: reason
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;

                            if (data.success) {
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'rejectTransferModal'));
                                if (modal) modal.hide();

                                if (typeof window.showNotification === 'function') {
                                    window.showNotification(data.message, 'success');
                                }

                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500);
                            } else {
                                if (typeof window.showNotification === 'function') {
                                    window.showNotification(data.message ||
                                        'Failed to reject transfer.',
                                        'error');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;

                            if (typeof window.showNotification === 'function') {
                                window.showNotification('Network error. Please try again.', 'error');
                            }
                        });
                });
            }
        });

        // View Transfer Details Function (For all statuses: approved, completed, cancelled)
        function viewTransferDetails(transferId, transferNumber) {
            const modal = document.getElementById('viewTransferDetailsModal');
            const modalBody = document.getElementById('transferDetailsBody');

            // Show loading state
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading details...</p>
                </div>
            `;

            // Fetch transfer details from server
            fetch('/branch-admin/inventory/transfers/' + transferId + '/details', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const t = data.transfer;
                        const status = t.status.toLowerCase();

                        let html = `
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Transfer #:</strong> <code>${t.transfer_number}</code></p>
                                    <p><strong>Status:</strong> <span class="badge bg-${status === 'cancelled' ? 'danger' : status === 'completed' ? 'success' : 'info'}">${status.charAt(0).toUpperCase() + status.slice(1)}</span></p>
                                    <p><strong>Product:</strong> ${t.product_name}</p>
                                    <p><strong>Variant:</strong> ${t.flavor_name}</p>
                                    <p><strong>Quantity:</strong> <strong>${t.quantity}</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>From:</strong> ${t.from_branch}</p>
                                    <p><strong>To:</strong> ${t.to_branch}</p>
                                    <p><strong>Requested By:</strong> ${t.requested_by}</p>
                                    <p><strong>Requested At:</strong> ${t.created_at}</p>
                                </div>
                            </div>
                        `;

                        // Show cancellation details if cancelled
                        if (status === 'cancelled') {
                            html += `
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-danger"><i class="bi bi-x-circle"></i> Cancellation Details</h6>
                                        <p><strong>Cancelled/Rejected By:</strong> <span class="text-danger">${t.cancelled_by || t.rejected_by || 'N/A'}</span></p>
                                        ${t.rejection_reason ? `<p><strong>Reason:</strong> ${t.rejection_reason}</p>` : ''}
                                        ${t.notes ? `<p><strong>Additional Notes:</strong> ${t.notes}</p>` : ''}
                                    </div>
                                </div>
                            `;
                        } else {
                            // Show approval and receiving details for non-cancelled
                            html += `
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-success"><i class="bi bi-check-circle"></i> Approval Details</h6>
                                        <p><strong>Approved By:</strong> <span class="text-success">${t.approved_by}</span></p>
                                        <p><strong>Approved At:</strong> ${t.approved_at}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary"><i class="bi bi-check-circle-fill"></i> Receiving Details</h6>
                                        <p><strong>Received By:</strong> <span class="text-primary">${t.received_by}</span></p>
                                        <p><strong>Received At:</strong> ${t.received_at}</p>
                                    </div>
                                </div>
                                ${t.notes ? `<hr><p><strong>Notes:</strong> ${t.notes}</p>` : ''}
                            `;
                        }

                        modalBody.innerHTML = html;
                    } else {
                        modalBody.innerHTML =
                            `<p class="text-danger">${data.message || 'Unable to load transfer details.'}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = `
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                            <p class="text-danger mt-3">Unable to load transfer details. Please try again.</p>
                            <p class="text-muted small">Error: ${error.message}</p>
                        </div>
                    `;
                });

            // Show modal
            const viewDetailsModal = new bootstrap.Modal(modal);
            viewDetailsModal.show();
        }
    </script>
@endpush
