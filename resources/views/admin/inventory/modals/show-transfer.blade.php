@extends('layouts.admin-modal')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-arrow-left-right"></i> Transfer Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeAdminModal()"></button>
    </div>

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

    <!-- Status Banner -->
    <div class="alert alert-{{ $statusColors[$transfer->status] ?? 'secondary' }} alert-minimal">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi {{ $statusIcons[$transfer->status] ?? 'bi-info-circle' }} fs-4 me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold">{{ ucfirst($transfer->status) }}</h6>
                    <small>
                        @if ($transfer->status == 'pending')
                            This transfer is waiting for approval
                        @elseif($transfer->status == 'approved')
                            This transfer has been approved and is ready to be completed
                        @elseif($transfer->status == 'completed')
                            This transfer has been completed successfully
                        @elseif($transfer->status == 'cancelled')
                            @if ($transfer->rejection_reason)
                                This transfer was rejected
                            @else
                                This transfer has been cancelled
                            @endif
                        @endif
                    </small>
                </div>
            </div>
            <div class="d-flex gap-2">
                @if ($transfer->status == 'pending')
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#approveModal" data-id="{{ $transfer->id }}"
                        data-name="{{ $transfer->transfer_number }}">
                        <i class="bi bi-check-lg me-1"></i> Approve
                    </button>
                @endif
                @if ($transfer->status == 'pending')
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#rejectModal" data-id="{{ $transfer->id }}"
                        data-name="{{ $transfer->transfer_number }}">
                        <i class="bi bi-x-lg me-1"></i> Reject
                    </button>
                @endif
                @if ($transfer->status == 'approved')
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#completeModal" data-id="{{ $transfer->id }}"
                        data-name="{{ $transfer->transfer_number }}">
                        <i class="bi bi-check-circle-fill me-1"></i> Complete
                    </button>
                @endif
                @if (in_array($transfer->status, ['pending', 'approved']))
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#cancelModal" data-id="{{ $transfer->id }}"
                        data-name="{{ $transfer->transfer_number }}">
                        <i class="bi bi-stop-circle me-1"></i> Cancel
                    </button>
                @endif
                @if (in_array($transfer->status, ['cancelled', 'completed']))
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal" data-id="{{ $transfer->id }}"
                        data-name="{{ $transfer->transfer_number }}">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Transfer Information -->
        <div class="col-md-6 mb-3">
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-info-circle"></i> Transfer Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="info-row">
                        <span class="info-label">Transfer #</span>
                        <span class="info-value"><code>{{ $transfer->transfer_number }}</code></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ $transfer->created_at->format('F d, Y h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Requested By</span>
                        <span class="info-value">{{ $transfer->requestedBy->name ?? 'N/A' }}</span>
                    </div>
                    @if ($transfer->approved_at)
                        <div class="info-row">
                            <span class="info-label">Approved Date</span>
                            <span class="info-value">
                                @if ($transfer->approved_at instanceof \Carbon\Carbon)
                                    {{ $transfer->approved_at->format('F d, Y h:i A') }}
                                @else
                                    {{ \Carbon\Carbon::parse($transfer->approved_at)->format('F d, Y h:i A') }}
                                @endif
                            </span>
                        </div>
                    @endif
                    @if ($transfer->approvedBy)
                        <div class="info-row">
                            <span class="info-label">Approved By</span>
                            <span class="info-value">{{ $transfer->approvedBy->name ?? 'N/A' }}</span>
                        </div>
                    @endif
                    @if ($transfer->completed_at)
                        <div class="info-row">
                            <span class="info-label">Completed Date</span>
                            <span class="info-value">
                                @if ($transfer->completed_at instanceof \Carbon\Carbon)
                                    {{ $transfer->completed_at->format('F d, Y h:i A') }}
                                @else
                                    {{ \Carbon\Carbon::parse($transfer->completed_at)->format('F d, Y h:i A') }}
                                @endif
                            </span>
                        </div>
                    @endif
                    @if ($transfer->received_at)
                        <div class="info-row">
                            <span class="info-label">Received Date</span>
                            <span class="info-value">
                                @if ($transfer->received_at instanceof \Carbon\Carbon)
                                    {{ $transfer->received_at->format('F d, Y h:i A') }}
                                @else
                                    {{ \Carbon\Carbon::parse($transfer->received_at)->format('F d, Y h:i A') }}
                                @endif
                            </span>
                        </div>
                    @endif
                    @if ($transfer->receivedBy)
                        <div class="info-row">
                            <span class="info-label">Received By</span>
                            <span class="info-value">{{ $transfer->receivedBy->name ?? 'N/A' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Branch Information -->
        <div class="col-md-6 mb-3">
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-shop"></i> Branch Details</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-1" style="font-size: 0.75rem; font-weight: 600;">From Branch</h6>
                            <p class="mb-0" style="font-size: 0.8rem; font-weight: 500;">
                                {{ $transfer->fromBranch->name ?? 'N/A' }}</p>
                            <small class="text-muted">{{ $transfer->fromBranch->address ?? 'N/A' }}</small>
                            <br><small class="text-muted">Manager:
                                {{ $transfer->fromBranch->manager_name ?? 'N/A' }}</small>
                            <br><small class="text-muted">Phone: {{ $transfer->fromBranch->phone ?? 'N/A' }}</small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-1" style="font-size: 0.75rem; font-weight: 600;">To Branch</h6>
                            <p class="mb-0" style="font-size: 0.8rem; font-weight: 500;">
                                {{ $transfer->toBranch->name ?? 'N/A' }}</p>
                            <small class="text-muted">{{ $transfer->toBranch->address ?? 'N/A' }}</small>
                            <br><small class="text-muted">Manager: {{ $transfer->toBranch->manager_name ?? 'N/A' }}</small>
                            <br><small class="text-muted">Phone: {{ $transfer->toBranch->phone ?? 'N/A' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Information -->
    <div class="info-card">
        <div class="card-header-minimal">
            <h6><i class="bi bi-box"></i> Product Details</h6>
        </div>
        <div class="card-body-minimal">
            <div class="row">
                <div class="col-md-2 text-center">
                    @if (isset($transfer->product) && $transfer->product)
                        @if ($transfer->product->image_url)
                            <img src="{{ \App\Helpers\GoogleDriveHelper::getThumbnailUrl($transfer->product->image_url, 80) }}"
                                alt="{{ $transfer->product->name }}" style="max-height: 60px; object-fit: contain;">
                        @elseif($transfer->product->image)
                            <img src="{{ Storage::url($transfer->product->image) }}"
                                alt="{{ $transfer->product->name }}" style="max-height: 60px; object-fit: contain;">
                        @else
                            <i class="bi bi-image text-muted" style="font-size: 2.5rem;"></i>
                        @endif
                    @else
                        <i class="bi bi-box text-muted" style="font-size: 2.5rem;"></i>
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Product</span>
                                <span class="info-value">{{ $transfer->product->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Brand</span>
                                <span class="info-value">{{ $transfer->product->brand ?? 'N/A' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Flavor</span>
                                <span class="info-value">{{ $transfer->flavor->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-row">
                                <span class="info-label">Quantity</span>
                                <span class="info-value"><strong>{{ $transfer->quantity }}</strong> units</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Price</span>
                                <span class="info-value">₱{{ number_format($transfer->product->price ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($transfer->notes && $transfer->status != 'cancelled')
                <div class="mt-3 p-3 bg-light rounded" style="border-radius: 12px;">
                    <strong style="font-size: 0.7rem; text-transform: uppercase; color: #64748b;">Notes:</strong>
                    <p class="mb-0" style="font-size: 0.8rem;">{{ $transfer->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- ✅ Cancellation/Rejection Details Card (only for cancelled transfers) -->
    @if ($transfer->status == 'cancelled')
        <div class="info-card mt-3 border border-danger">
            <div class="card-header-minimal bg-danger bg-opacity-10">
                <h6 class="text-danger"><i class="bi bi-exclamation-triangle"></i> Cancellation/Rejection Details</h6>
            </div>
            <div class="card-body-minimal">
                @if ($transfer->rejection_reason)
                    <div class="info-row">
                        <span class="info-label">Rejection Reason</span>
                        <span class="info-value text-danger fw-bold">{{ $transfer->rejection_reason }}</span>
                    </div>
                @endif
                @if ($transfer->rejected_at)
                    <div class="info-row">
                        <span class="info-label">Rejected Date</span>
                        <span class="info-value">
                            @if ($transfer->rejected_at instanceof \Carbon\Carbon)
                                {{ $transfer->rejected_at->format('F d, Y h:i A') }}
                            @else
                                {{ \Carbon\Carbon::parse($transfer->rejected_at)->format('F d, Y h:i A') }}
                            @endif
                        </span>
                    </div>
                @endif
                @if ($transfer->rejectedBy)
                    <div class="info-row">
                        <span class="info-label">Rejected By</span>
                        <span class="info-value text-danger fw-bold">{{ $transfer->rejectedBy->name ?? 'N/A' }}</span>
                    </div>
                @endif
                @if ($transfer->cancelledBy)
                    <div class="info-row">
                        <span class="info-label">Cancelled By</span>
                        <span class="info-value text-warning fw-bold">{{ $transfer->cancelledBy->name ?? 'N/A' }}</span>
                    </div>
                @endif
                @if ($transfer->notes)
                    <div class="info-row">
                        <span class="info-label">Additional Notes</span>
                        <span class="info-value">{{ $transfer->notes }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <script>
        function openEditTransferModal(id) {
            const currentModal = bootstrap.Modal.getInstance(document.getElementById('transferModal'));
            if (currentModal) currentModal.hide();

            const modalElement = document.getElementById('editTransferModal');
            const modalContent = modalElement.querySelector('.modal-content');
            const url = '/admin/inventory/transfers/' + id + '/edit-modal';

            modalContent.innerHTML =
                '<div class="text-center p-5"><div class="spinner-border text-warning" role="status"></div><p>Loading...</p></div>';

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading edit form</div>';
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                });
        }
    </script>
@endsection
