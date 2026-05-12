@extends('layouts.admin')

@section('title', 'Pending Distributions - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">
                <i class="bi bi-clock-history me-2 text-warning"></i>Pending Distributions
            </h1>
            <p class="text-muted small mb-0">Branch requests waiting for approval</p>
        </div>
        <a href="{{ route('admin.warehouse.index') }}" class="btn btn-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Warehouse
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Pending Requests</h5>
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
                            <th>Quantity</th>
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
                                <td><span class="fw-bold">{{ number_format($transfer->quantity) }}</span></td>
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
                                                Quantity: {{ number_format($transfer->quantity) }} units
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
                                        <div class="modal-body">
                                            <p>Are you sure you want to reject this distribution request?</p>
                                            <div class="alert alert-warning">
                                                <strong>Request Details:</strong><br>
                                                Branch: {{ $transfer->toBranch->name }}<br>
                                                Product: {{ $transfer->product->name }}<br>
                                                Quantity: {{ number_format($transfer->quantity) }} units
                                            </div>
                                            <form action="{{ route('admin.warehouse.reject', $transfer) }}" method="POST">
                                                @csrf
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
                                <td colspan="7" class="text-center py-5">
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
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $transfers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection