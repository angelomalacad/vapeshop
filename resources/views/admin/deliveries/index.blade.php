@extends('layouts.admin')

@section('title', 'Delivery Management - Vape Expo')

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

    /* Delivery Badge Style */
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

    /* Filter Styles */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
        box-shadow: 0 0 0 2px rgba(59,130,246,0.1);
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
</style>

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold">
                    <i class="bi bi-truck me-2 text-primary"></i>Delivery History
                </h1>
                <p class="text-muted mb-0">Delivery status updated by drivers</p>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('admin.deliveries.export') }}" class="btn btn-success rounded-pill px-3">
                    <i class="bi bi-download me-1"></i> Export Report
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Deliveries -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #dbeafe; color: #2563eb;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Deliveries</span>
                        <h3 class="stat-value">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Active Deliveries -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Active Deliveries</span>
                        <h3 class="stat-value">{{ $stats['active_today'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Completed Deliveries -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Completed Deliveries</span>
                        <h3 class="stat-value">{{ $stats['delivered'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Today's Driver -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #ede9fe; color: #7c3aed;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Today's Driver</span>
                        <h3 class="stat-value" style="font-size: 1.2rem; font-weight: 600;">
                            {{ $todayDriverName ?? 'Not assigned' }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-container">
            <form method="GET" action="{{ route('admin.deliveries.index') }}" class="filter-form row g-3 align-items-end">
                <!-- Search by Order Number -->
                <div class="col-md-2">
                    <label class="form-label">Search Order</label>
                    <input type="text" name="search" class="form-control" placeholder="Type Order #..." value="{{ request('search') }}">
                </div>

                <!-- Filter by Active/Completed -->
                <div class="col-md-2">
                    <label class="form-label">Show</label>
                    <select name="filter_section" class="form-select">
                        <option value="all" {{ request('filter_section') == 'all' ? 'selected' : '' }}>All Deliveries</option>
                        <option value="active" {{ request('filter_section') == 'active' ? 'selected' : '' }}>Active Only</option>
                        <option value="completed" {{ request('filter_section') == 'completed' ? 'selected' : '' }}>Completed Only</option>
                    </select>
                </div>

                <!-- Filter by Delivery Type -->
                <div class="col-md-2">
                    <label class="form-label">Delivery Type</label>
                    <select name="delivery_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="lalamove" {{ request('delivery_type') == 'lalamove' ? 'selected' : '' }}>Lalamove</option>
                        <option value="staff" {{ request('delivery_type') == 'staff' ? 'selected' : '' }}>Staff</option>
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
                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-filter">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.deliveries.index') }}" class="btn-reset">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Active Deliveries Section -->
        @if ($activeToday->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-play-circle-fill text-warning me-2"></i>
                        Active Deliveries
                        <span class="badge bg-warning ms-2">{{ $activeToday->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order #</th>
                                    <th>Customer</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Driver</th>
                                    <th>Lalamove Info</th>
                                    <th class="pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activeToday as $delivery)
                                    @php
                                        $cityLower = strtolower(trim($delivery->order->city ?? ''));
                                        $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                                        $isLalamoveEligible = !$isCalambaCity;

                                        $statusColors = [
                                            'pending' => 'secondary',
                                            'lalamove_pending' => 'secondary',
                                            'assigned' => 'info',
                                            'picked_up' => 'primary',
                                            'in_transit' => 'warning',
                                        ];
                                    @endphp
                                    <tr>
                                        <td class="ps-4">{{ $delivery->order->order_number ?? 'N/A' }}</td>
                                        <td>{{ $delivery->recipient_name }}</td>
                                        <td>
                                            <strong>{{ $delivery->delivery_address }}</strong><br>
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt"></i>
                                                {{ $delivery->order->city ?? 'N/A' }},
                                                @if ($delivery->order->barangay === 'Other' && $delivery->order->other_barangay)
                                                    {{ $delivery->order->other_barangay }}
                                                @else
                                                    {{ $delivery->order->barangay ?? 'N/A' }}
                                                @endif
                                            </small><br>
                                            @if ($delivery->order->landmark)
                                                <small class="text-muted">
                                                    <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="delivery-badge">
                                                @if($isLalamoveEligible)
                                                    <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                                @else
                                                    <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                                @endif
                                            </span>
                                        </td>
                                        <td><span class="badge bg-{{ $statusColors[$delivery->status] ?? 'secondary' }}">{{ ucfirst($delivery->status) }}</span></td>
                                        <td>
                                            @if($isLalamoveEligible)
                                                —
                                            @else
                                                {{ $delivery->driver->name ?? 'Waiting...' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($isLalamoveEligible && !empty($delivery->tracking_number))
                                                <a href="{{ $delivery->tracking_number }}" target="_blank" class="btn btn-sm btn-primary" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">
                                                    <i class="bi bi-eye"></i> Link
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            <button type="button" class="btn btn-sm btn-info rounded-pill px-3"
                                                onclick="loadDeliveryModal({{ $delivery->id }})">
                                                <i class="bi bi-eye me-1"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- COMPLETED DELIVERIES SECTION (STRICTLY DELIVERED ONLY) -->
        @if ($stats['delivered'] > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Completed Deliveries
                        <span class="badge bg-success ms-2">{{ $stats['delivered'] }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order #</th>
                                    <th>Customer</th>
                                    <th>Address</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Driver</th>
                                    <th>Proofs</th>
                                    <th>Lalamove Info</th>
                                    <th class="pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveries as $delivery)
                                    @php
                                        $orderStatus = $delivery->order->order_status ?? 'unknown';
                                    @endphp

                                    {{-- 🔥 STRICT CHECK: Only show DELIVERED --}}
                                    @if ($orderStatus === 'delivered')
                                        @php
                                            $cityLower = strtolower(trim($delivery->order->city ?? ''));
                                            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                                            $isLalamoveEligible = !$isCalambaCity;
                                        @endphp
                                        <tr>
                                            <td class="ps-4">{{ $delivery->order->order_number ?? 'N/A' }}</td>
                                            <td>{{ $delivery->recipient_name }}</td>
                                            <td>
                                                <strong>{{ $delivery->delivery_address }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="bi bi-geo-alt"></i>
                                                    {{ $delivery->order->city ?? 'N/A' }},
                                                    @if ($delivery->order->barangay === 'Other' && $delivery->order->other_barangay)
                                                        {{ $delivery->order->other_barangay }}
                                                    @else
                                                        {{ $delivery->order->barangay ?? 'N/A' }}
                                                    @endif
                                                </small><br>
                                                @if ($delivery->order->landmark)
                                                    <small class="text-muted">
                                                        <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="delivery-badge">
                                                    @if($isLalamoveEligible)
                                                        <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                                    @else
                                                        <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Delivered</span>
                                            </td>
                                            <td>
                                                @if($isLalamoveEligible)
                                                    —
                                                @else
                                                    {{ $delivery->driver->name ?? 'Unassigned' }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($delivery->delivery_proof)
                                                    <button type="button" class="btn btn-sm btn-outline-success me-1 rounded-pill" title="Delivery Proof"
                                                        onclick="viewProof('{{ Storage::url($delivery->delivery_proof) }}', 'Delivery Proof')">
                                                        <i class="bi bi-camera"></i>
                                                    </button>
                                                @endif
                                                @if ($delivery->payment_proof)
                                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" title="Payment Proof"
                                                        onclick="viewProof('{{ Storage::url($delivery->payment_proof) }}', 'Payment Proof')">
                                                        <i class="bi bi-receipt"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if($isLalamoveEligible && !empty($delivery->tracking_number))
                                                    <a href="{{ $delivery->tracking_number }}" target="_blank" class="btn btn-sm btn-primary" style="font-size: 0.7rem; padding: 0.2rem 0.6rem;">
                                                        <i class="bi bi-eye"></i> Link
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="pe-4">
                                                <button type="button" class="btn btn-sm btn-info rounded-pill px-3"
                                                    onclick="loadDeliveryModal({{ $delivery->id }})">
                                                    <i class="bi bi-eye me-1"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $deliveries->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        @endif

        <!-- No Deliveries Message -->
        @if ($deliveries->count() == 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="mt-3">No deliveries found</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Container -->
    <div id="deliveryModalContainer"></div>

    <!-- Proof Modal -->
    <div class="modal fade" id="proofModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title" id="proofModalTitle">Proof</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="proofModalImage" src="" class="img-fluid"
                        style="max-height: 80vh; width: auto;">
                </div>
                <div class="modal-footer bg-dark border-0">
                    <a id="proofModalDownload" href="#" download class="btn btn-success rounded-pill">
                        <i class="bi bi-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary rounded-pill"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewProof(imageUrl, title) {
            document.getElementById('proofModalTitle').textContent = title;
            document.getElementById('proofModalImage').src = imageUrl;
            document.getElementById('proofModalDownload').href = imageUrl;
            new bootstrap.Modal(document.getElementById('proofModal')).show();
        }

        function loadDeliveryModal(deliveryId) {
            const container = document.getElementById('deliveryModalContainer');
            container.innerHTML = '';

            // Remove any existing backdrops
            const existingBackdrops = document.querySelectorAll('.modal-backdrop');
            existingBackdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');

            const modalHtml = `
            <div class="modal fade" id="deliveryModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading delivery details...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

            container.innerHTML = modalHtml;

            const modalElement = document.getElementById('deliveryModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            fetch(`/admin/deliveries/${deliveryId}/modal`)
                .then(response => response.text())
                .then(html => {
                    const modalContent = document.querySelector('#deliveryModal .modal-content');
                    if (modalContent) {
                        modalContent.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalContent = document.querySelector('#deliveryModal .modal-content');
                    if (modalContent) {
                        modalContent.innerHTML = `
                        <div class="modal-header" style="border-bottom: 1px solid #eef2f6;">
                            <h5 class="modal-title">Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                Failed to load delivery details. Please try again.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    `;
                    }
                });
        }
    </script>
@endsection