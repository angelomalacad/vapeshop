<!-- DELIVERY HISTORY TABLE - MATCHING DRIVER STYLE -->
<style>
    /* Section Headers */
    .section-header {
        margin-bottom: 1.25rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .section-title i {
        margin-right: 0.5rem;
    }

    .count-badge {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    /* Table Styles */
    .table-wrapper {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .delivery-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .delivery-table thead {
        background: #f8f9fa;
    }

    .delivery-table th {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #eef2f6;
        text-align: left;
    }

    .delivery-table td {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #eef2f6;
        vertical-align: middle;
        font-size: 0.85rem;
        color: #1a1a2e;
    }

    .delivery-table tbody tr {
        transition: all 0.2s ease;
    }

    .delivery-table tbody tr:hover {
        background: #f8f9fa;
    }

    .delivery-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.65rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        gap: 0.25rem;
    }

    .status-badge-active {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-badge-completed {
        background: #d1fae5;
        color: #059669;
    }

    .status-badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-badge-cancelled {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-badge-failed {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Delivery Type Badge */
    .delivery-badge {
        padding: 0.25rem 0.65rem;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.7rem;
        background: #f1f5f9;
        color: #475569;
    }

    .delivery-badge i {
        font-size: 0.7rem;
    }

    /* Buttons */
    .btn-view {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: auto !important;
        background: #eff6ff;
        color: #3b82f6;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        padding: 0.4rem 1rem;
        font-size: 0.7rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
        transform: translateY(-1px);
    }

    /* Filter Section */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #eef2f6;
    }

    .filter-form .form-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .filter-form .form-control,
    .filter-form .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .filter-form .form-control:focus,
    .filter-form .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .filter-form .btn-filter {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .filter-form .btn-filter:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .filter-form .btn-reset {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .filter-form .btn-reset:hover {
        background: #e2e8f0;
        color: #1a1a2e;
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0;
    }
</style>

<!-- FILTER SECTION -->
<div class="filter-container">
    <form method="GET" action="{{ route('branch-admin.pos.history') }}" class="filter-form row g-3 align-items-end">
        <input type="hidden" name="tab" value="deliveries">
        <!-- Search by Order Number -->
        <div class="col-md-2">
            <label class="form-label">Search Order</label>
            <input type="text" name="search" class="form-control" placeholder="Type Order #..."
                value="{{ request('search') }}">
        </div>

        <!-- Filter by Status -->
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="delivery_status" class="form-select">
                <option value="">All Status</option>
                <option value="assigned" {{ request('delivery_status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="picked_up" {{ request('delivery_status') == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                <option value="in_transit" {{ request('delivery_status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                <option value="delivered" {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('delivery_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="failed" {{ request('delivery_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        <!-- Date From -->
        <div class="col-md-2">
            <label class="form-label">Date From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>

        <!-- Date To -->
        <div class="col-md-2">
            <label class="form-label">Date To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        <!-- Buttons -->
        <div class="col-md-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn-filter">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('branch-admin.pos.history', ['tab' => 'deliveries']) }}" class="btn-reset">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Active Deliveries Section -->
@if ($activeDeliveries->count() > 0)
    <div class="mb-5">
        <div class="section-header">
            <h4 class="section-title">
                <i class="bi bi-play-circle-fill text-warning"></i> Active Deliveries
                <span class="count-badge">{{ $activeDeliveries->count() }} active</span>
            </h4>
        </div>
        <div class="table-wrapper">
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeDeliveries as $delivery)
                        @php
                            $firstItem = $delivery->order->items->first();
                            $product = $firstItem ? $firstItem->product : null;
                            $productName = $product ? $product->name : 'N/A';
                            $itemsCount = $delivery->order->items->count();
                            $imageUrl = null;
                            if ($product && $product->image) {
                                if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                    $imageUrl = $product->image;
                                } elseif (Storage::disk('public')->exists($product->image)) {
                                    $imageUrl = Storage::url($product->image);
                                }
                            }

                            // Lalamove Eligibility Check
                            $cityLower = strtolower(trim($delivery->order->city ?? ''));
                            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                            $isLalamoveEligible = !$isCalambaCity;

                            $statusClass = match ($delivery->status) {
                                'delivered' => 'status-badge-completed',
                                'in_transit' => 'status-badge-active',
                                'picked_up' => 'status-badge-active',
                                'assigned' => 'status-badge-pending',
                                default => 'status-badge-pending',
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold">
                                    <i class="bi bi-receipt me-1 text-muted"></i>
                                    #{{ $delivery->order->order_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $productName }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div
                                        style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $productName }}</div>
                                <small class="text-muted">{{ $itemsCount }} item(s)</small>
                            </td>
                            <td>
                                <span class="fw-bold text-success">₱{{ number_format($delivery->order->subtotal ?? 0, 2) }}</span>
                            </td>
                            <td>{{ $delivery->recipient_name }}</td>
                            <td>{{ $delivery->recipient_phone }}</td>
                            <td>
                                <div>{{ Str::limit($delivery->delivery_address, 40) }}</div>
                                @if ($delivery->order)
                                    <div class="text-muted small">
                                        {{ $delivery->order->barangay ?? '' }}, {{ $delivery->order->city ?? '' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="delivery-badge">
                                    @if ($isLalamoveEligible)
                                        <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                    @else
                                        <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="bi bi-{{ $delivery->status == 'in_transit' ? 'truck' : ($delivery->status == 'picked_up' ? 'box-seam' : 'clock') }}"></i>
                                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn-view"
                                    onclick="openBranchDeliveryModal({{ $delivery->id }})">
                                    <i class="bi bi-eye me-1"></i> Details
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination for Active Deliveries -->
        @if ($activeDeliveries->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $activeDeliveries->appends(['tab' => 'deliveries'])->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endif

<!-- Completed Deliveries Section -->
@if ($completedDeliveries->count() > 0)
    <div class="mb-4">
        <div class="section-header">
            <h4 class="section-title">
                <i class="bi bi-check-circle-fill text-success"></i> Completed Deliveries
                <span class="count-badge">{{ $completedDeliveries->count() }} completed</span>
            </h4>
        </div>
        <div class="table-wrapper">
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Delivered On</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Lalamove Info</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($completedDeliveries as $delivery)
                        @php
                            $firstItem = $delivery->order->items->first();
                            $product = $firstItem ? $firstItem->product : null;
                            $productName = $product ? $product->name : 'N/A';
                            $itemsCount = $delivery->order->items->count();
                            $imageUrl = null;
                            if ($product && $product->image) {
                                if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                    $imageUrl = $product->image;
                                } elseif (Storage::disk('public')->exists($product->image)) {
                                    $imageUrl = Storage::url($product->image);
                                }
                            }

                            // Lalamove Eligibility Check
                            $cityLower = strtolower(trim($delivery->order->city ?? ''));
                            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                            $isLalamoveEligible = !$isCalambaCity;
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold">
                                    <i class="bi bi-receipt me-1 text-muted"></i>
                                    #{{ $delivery->order->order_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $productName }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div
                                        style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $productName }}</div>
                                <small class="text-muted">{{ $itemsCount }} item(s)</small>
                            </td>
                            <td>
                                <span class="fw-bold text-success">₱{{ number_format($delivery->order->subtotal ?? 0, 2) }}</span>
                            </td>
                            <td>{{ $delivery->recipient_name }}</td>
                            <td>{{ $delivery->recipient_phone }}</td>
                            <td>
                                <div>{{ Str::limit($delivery->delivery_address, 40) }}</div>
                                @if ($delivery->order)
                                    <div class="text-muted small">
                                        {{ $delivery->order->barangay ?? '' }}, {{ $delivery->order->city ?? '' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($delivery->delivered_at)
                                    {{ \Carbon\Carbon::parse($delivery->delivered_at)->format('M d, Y h:i A') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="delivery-badge">
                                    @if ($isLalamoveEligible)
                                        <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                    @else
                                        <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-badge-completed">
                                    <i class="bi bi-check-circle-fill"></i> Delivered
                                </span>
                            </td>
                            <td>
                                @if ($isLalamoveEligible && !empty($delivery->tracking_number))
                                    <a href="{{ $delivery->tracking_number }}" target="_blank"
                                        class="btn btn-sm btn-primary"
                                        style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">
                                        <i class="bi bi-eye"></i> Link
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end" style="white-space: nowrap;">
                                <button type="button" class="btn-view"
                                    onclick="openBranchDeliveryModal({{ $delivery->id }})">
                                    <i class="bi bi-eye me-1"></i> Proof
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination for Completed Deliveries -->
        @if ($completedDeliveries->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $completedDeliveries->appends(['tab' => 'deliveries'])->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endif

<!-- Cancelled/Failed Deliveries Section -->
@if ($cancelledDeliveries->count() > 0)
    <div class="mb-4">
        <div class="section-header">
            <h4 class="section-title">
                <i class="bi bi-x-circle-fill text-danger"></i> Cancelled/Failed Deliveries
                <span class="count-badge">{{ $cancelledDeliveries->count() }} cancelled/failed</span>
            </h4>
        </div>
        <div class="table-wrapper">
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cancelledDeliveries as $delivery)
                        @php
                            $firstItem = $delivery->order->items->first();
                            $product = $firstItem ? $firstItem->product : null;
                            $productName = $product ? $product->name : 'N/A';
                            $itemsCount = $delivery->order->items->count();
                            $imageUrl = null;
                            if ($product && $product->image) {
                                if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                    $imageUrl = $product->image;
                                } elseif (Storage::disk('public')->exists($product->image)) {
                                    $imageUrl = Storage::url($product->image);
                                }
                            }

                            // Lalamove Eligibility Check
                            $cityLower = strtolower(trim($delivery->order->city ?? ''));
                            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                            $isLalamoveEligible = !$isCalambaCity;

                            $statusClass = match ($delivery->status) {
                                'cancelled' => 'status-badge-cancelled',
                                'failed' => 'status-badge-failed',
                                default => 'status-badge-pending',
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold">
                                    <i class="bi bi-receipt me-1 text-muted"></i>
                                    #{{ $delivery->order->order_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $productName }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div
                                        style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div>{{ $productName }}</div>
                                <small class="text-muted">{{ $itemsCount }} item(s)</small>
                            </td>
                            <td>
                                <span class="fw-bold text-success">₱{{ number_format($delivery->order->subtotal ?? 0, 2) }}</span>
                            </td>
                            <td>{{ $delivery->recipient_name }}</td>
                            <td>{{ $delivery->recipient_phone }}</td>
                            <td>
                                <div>{{ Str::limit($delivery->delivery_address, 40) }}</div>
                                @if ($delivery->order)
                                    <div class="text-muted small">
                                        {{ $delivery->order->barangay ?? '' }}, {{ $delivery->order->city ?? '' }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="delivery-badge">
                                    @if ($isLalamoveEligible)
                                        <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                    @else
                                        <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    <i class="bi bi-x-circle"></i>
                                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn-view"
                                    onclick="openBranchDeliveryModal({{ $delivery->id }})">
                                    <i class="bi bi-eye me-1"></i> Details
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination for Cancelled Deliveries -->
        @if ($cancelledDeliveries->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $cancelledDeliveries->appends(['tab' => 'deliveries'])->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endif

<!-- No Deliveries Message -->
@if ($activeDeliveries->count() == 0 && $completedDeliveries->count() == 0 && $cancelledDeliveries->count() == 0)
    <div class="empty-state">
        <i class="bi bi-truck"></i>
        <h5>No Deliveries Found</h5>
        <p>There are no deliveries to display at this time.</p>
    </div>
@endif

<!-- Modal Container -->
<div id="branchDeliveryModalContainer"></div>

<script>
    // ========== GLOBAL CLOSE FUNCTION (MUST BE HERE) ==========
    window.closeBranchDeliveryModal = function() {
        const modalElement = document.getElementById('branchDeliveryModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }
        const container = document.getElementById('branchDeliveryModalContainer');
        if (container) {
            setTimeout(() => {
                container.innerHTML = '';
                document.body.classList.remove('modal-open');
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
            }, 300);
        }
    };

    // ========== GLOBAL IMAGE PREVIEW FUNCTIONS ==========
    window.showImagePreview = function(imageUrl, title) {
        let previewModal = document.getElementById('imagePreviewModal');
        if (!previewModal) {
            previewModal = document.createElement('div');
            previewModal.id = 'imagePreviewModal';
            previewModal.className = 'image-preview-modal';
            previewModal.innerHTML = `
                <div class="image-preview-content">
                    <div class="image-preview-header">
                        <h6 class="mb-0" id="previewTitle">Image Preview</h6>
                        <button type="button" class="btn-close" onclick="window.closeImagePreview()"></button>
                    </div>
                    <div class="image-preview-body">
                        <img id="previewImage" src="">
                    </div>
                    <div class="image-preview-footer">
                        <button type="button" class="btn btn-sm btn-secondary me-2" onclick="window.closeImagePreview()">Close</button>
                        <a id="downloadLink" href="#" download class="btn btn-sm btn-primary">Download</a>
                    </div>
                </div>
            `;
            document.body.appendChild(previewModal);
        }

        document.getElementById('previewImage').src = imageUrl;
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('downloadLink').href = imageUrl;
        previewModal.style.display = 'flex';
    };

    window.closeImagePreview = function() {
        const previewModal = document.getElementById('imagePreviewModal');
        if (previewModal) {
            previewModal.style.display = 'none';
        }
    };

    // Close image preview when clicking outside
    document.addEventListener('click', function(e) {
        const previewModal = document.getElementById('imagePreviewModal');
        if (previewModal && e.target === previewModal) {
            window.closeImagePreview();
        }
    });

    // ========== OPEN MODAL FUNCTION ==========
    function openBranchDeliveryModal(deliveryId) {
        const container = document.getElementById('branchDeliveryModalContainer');

        // Clear any existing modals
        container.innerHTML = '';
        document.body.classList.remove('modal-open');
        const existingBackdrops = document.querySelectorAll('.modal-backdrop');
        existingBackdrops.forEach(backdrop => backdrop.remove());

        // Create the modal HTML
        container.innerHTML = `
        <div class="modal fade" id="branchDeliveryModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center p-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Loading delivery details...</p>
                    </div>
                </div>
            </div>
        </div>
        `;

        const modalElement = document.getElementById('branchDeliveryModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();

        fetch(`/branch-admin/deliveries/${deliveryId}/modal`)
            .then(response => response.text())
            .then(html => {
                const modalContent = document.querySelector('#branchDeliveryModal .modal-content');
                if (modalContent) {
                    modalContent.innerHTML = html;

                    // Rebind close button after content loads
                    const closeBtn = modalContent.querySelector('.btn-close');
                    if (closeBtn) {
                        closeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            window.closeBranchDeliveryModal();
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const modalContent = document.querySelector('#branchDeliveryModal .modal-content');
                if (modalContent) {
                    modalContent.innerHTML = `
                    <div class="modal-header" style="border-bottom: 1px solid #eef2f6;">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close" onclick="window.closeBranchDeliveryModal()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            Failed to load delivery details. Please try again.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.closeBranchDeliveryModal()">Close</button>
                    </div>
                `;
                }
            });
    }
</script>