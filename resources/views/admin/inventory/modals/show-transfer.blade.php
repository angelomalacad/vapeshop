<div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h5 class="modal-title">
            <i class="bi bi-arrow-left-right me-2"></i> Transfer Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
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

        <!-- Status Banner -->
        <div class="alert alert-{{ $statusColors[$transfer->status] ?? 'secondary' }} border-0 shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi {{ $statusIcons[$transfer->status] ?? 'bi-info-circle' }} fs-2 me-3"></i>
                <div>
                    <h5 class="mb-1 fw-bold">{{ ucfirst($transfer->status) }}</h5>
                    <p class="mb-0">
                        @if($transfer->status == 'pending')
                            This transfer is waiting for approval
                        @elseif($transfer->status == 'approved')
                            This transfer has been approved and is ready to be completed
                        @elseif($transfer->status == 'completed')
                            This transfer has been completed successfully
                        @elseif($transfer->status == 'cancelled')
                            This transfer has been cancelled
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Transfer Information -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Transfer Information</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><td class="text-muted">Transfer Number:</td><td class="fw-semibold"><code>{{ $transfer->transfer_number }}</code></td></tr>
                            <tr><td class="text-muted">Date Requested:</td><td>{{ $transfer->created_at->format('F d, Y h:i A') }}</td></tr>
                            <tr><td class="text-muted">Requested By:</td><td>{{ $transfer->requestedBy->name ?? 'N/A' }}</td></tr>
                            @if($transfer->approved_at)<tr><td class="text-muted">Approved Date:</td><td>{{ $transfer->approved_at->format('F d, Y h:i A') }}</td></tr>@endif
                            @if($transfer->approvedBy)<tr><td class="text-muted">Approved By:</td><td>{{ $transfer->approvedBy->name ?? 'N/A' }}</td></tr>@endif
                            @if($transfer->completed_at)<tr><td class="text-muted">Completed Date:</td><td>{{ $transfer->completed_at->format('F d, Y h:i A') }}</td></tr>@endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Branch Information -->
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-semibold"><i class="bi bi-shop me-2 text-primary"></i>Branch Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-primary">From Branch</h6>
                                <p class="mb-1"><strong>{{ $transfer->fromBranch->name ?? 'N/A' }}</strong></p>
                                <p class="mb-1 small">{{ $transfer->fromBranch->address ?? 'N/A' }}</p>
                                <p class="mb-1 small">Manager: {{ $transfer->fromBranch->manager_name ?? 'N/A' }}</p>
                                <p class="mb-0 small">Phone: {{ $transfer->fromBranch->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-success">To Branch</h6>
                                <p class="mb-1"><strong>{{ $transfer->toBranch->name ?? 'N/A' }}</strong></p>
                                <p class="mb-1 small">{{ $transfer->toBranch->address ?? 'N/A' }}</p>
                                <p class="mb-1 small">Manager: {{ $transfer->toBranch->manager_name ?? 'N/A' }}</p>
                                <p class="mb-0 small">Phone: {{ $transfer->toBranch->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-box me-2 text-primary"></i>Product Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        @if(isset($transfer->product) && $transfer->product)
                            @if($transfer->product->image_url)
                                <img src="{{ \App\Helpers\GoogleDriveHelper::getThumbnailUrl($transfer->product->image_url, 100) }}" 
                                     alt="{{ $transfer->product->name }}"
                                     style="max-height: 80px; object-fit: contain;">
                            @elseif($transfer->product->image)
                                <img src="{{ Storage::url($transfer->product->image) }}" 
                                     alt="{{ $transfer->product->name }}"
                                     style="max-height: 80px; object-fit: contain;">
                            @else
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            @endif
                        @else
                            <i class="bi bi-box text-muted" style="font-size: 3rem;"></i>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <table class="table table-sm mb-0">
                            <tr><td class="text-muted" style="width: 120px;">Product:</td><td class="fw-semibold">{{ $transfer->product->name ?? 'N/A' }}</td></tr>
                            <tr><td class="text-muted">Brand:</td><td>{{ $transfer->product->brand ?? 'N/A' }}</td></tr>
                            <tr><td class="text-muted">Flavor:</td><td>{{ $transfer->flavor->name ?? 'N/A' }}</td></tr>
                            <tr><td class="text-muted">Quantity:</td><td><span class="fw-bold fs-5">{{ $transfer->quantity }}</span> units</td></tr>
                            <tr><td class="text-muted">Price:</td><td>₱{{ number_format($transfer->product->price ?? 0, 2) }}</td></tr>
                        </table>
                    </div>
                </div>
                @if($transfer->notes)
                <div class="mt-3 p-3 bg-light rounded">
                    <strong>Notes:</strong>
                    <p class="mb-0">{{ $transfer->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
        </button>
    </div>
</div>