@extends('layouts.admin')

@section('title', 'Online Orders Management - Vape Expo')

@section('content')
    <style>
        /* Modern Minimalist Styles */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.875rem;
        }

        /* Status Cards - Modern Minimalist */
        .status-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .status-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .status-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            margin-bottom: 0.75rem;
        }

        .status-number {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0;
            color: #1a1a2e;
        }

        .status-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        /* Modern Card */
        .modern-card {
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header-modern {
            background: white;
            border-bottom: 1px solid #eef2f6;
            padding: 1rem 1.25rem;
        }

        .card-header-modern h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* Table Styles */
        .order-table {
            margin-bottom: 0;
        }

        .order-table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 1rem;
            border-bottom: 1px solid #eef2f6;
        }

        .order-table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 0.875rem;
        }

        .order-table tbody tr {
            transition: all 0.2s;
        }

        .order-table tbody tr:hover {
            background: #f8f9fa;
            cursor: pointer;
        }

        /* Badge Styles */
        .badge-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-confirmed {
            background: #dbeafe;
            color: #2563eb;
        }

        .badge-packing {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .badge-ready {
            background: #d1fae5;
            color: #059669;
        }

        .badge-out_for_delivery {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-delivered {
            background: #d1fae5;
            color: #059669;
        }

        .badge-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.7rem;
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
            background: #3b82f6;
            border: none;
            border-radius: 30px;
            padding: 0.35rem 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-view:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        /* Pagination */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border: none;
            color: #1a1a2e;
            border-radius: 30px;
            margin: 0 2px;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .pagination .page-link:hover {
            background: #f1f5f9;
            color: #1a1a2e;
        }

        .pagination .active .page-link {
            background: #3b82f6;
            color: white;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #eef2f6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .status-number {
                font-size: 1.25rem;
            }

            .status-icon {
                width: 40px;
                height: 40px;
            }

            .order-table th,
            .order-table td {
                padding: 0.75rem;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 fw-bold">
                    <i class="bi bi-cart me-2 text-primary"></i>Online Orders
                </h1>
                <p class="text-muted small mb-0">Monitor customer orders from confirmation to delivery (Read-only)</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-primary bg-opacity-10 text-dark px-3 py-2 rounded-pill">
                    <i class="bi bi-shop me-1"></i> All Branches
                </span>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.online-orders.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed
                        </option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Packing
                        </option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out
                            for Delivery</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Date From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Date To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm"
                        value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 flex-grow-1">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.online-orders.index') }}"
                            class="btn btn-secondary btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-repeat me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Status Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-2 col-6">
                <div class="card status-card">
                    <div class="card-body text-center">
                        <div class="status-icon bg-warning bg-opacity-10 mx-auto">
                            <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                        </div>
                        <h2 class="status-number">{{ $counts['pending'] ?? 0 }}</h2>
                        <p class="status-label mb-0">Pending</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card status-card">
                    <div class="card-body text-center">
                        <div class="status-icon bg-info bg-opacity-10 mx-auto">
                            <i class="bi bi-check-circle fs-4 text-info"></i>
                        </div>
                        <h2 class="status-number">{{ $counts['confirmed'] ?? 0 }}</h2>
                        <p class="status-label mb-0">Confirmed</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card status-card">
                    <div class="card-body text-center">
                        <div class="status-icon bg-primary bg-opacity-10 mx-auto">
                            <i class="bi bi-box-seam fs-4 text-primary"></i>
                        </div>
                        <h2 class="status-number">{{ $counts['processing'] ?? 0 }}</h2>
                        <p class="status-label mb-0">Packing</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card status-card">
                    <div class="card-body text-center">
                        <div class="status-icon bg-success bg-opacity-10 mx-auto">
                            <i class="bi bi-check-circle-fill fs-4 text-success"></i>
                        </div>
                        <h2 class="status-number">{{ $counts['ready'] ?? 0 }}</h2>
                        <p class="status-label mb-0">Ready</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card status-card">
                    <div class="card-body text-center">
                        <div class="status-icon bg-secondary bg-opacity-10 mx-auto">
                            <i class="bi bi-truck fs-4 text-secondary"></i>
                        </div>
                        <h2 class="status-number">{{ $counts['out_for_delivery'] ?? 0 }}</h2>
                        <p class="status-label mb-0">Out for Delivery</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-6">
                <div class="card status-card">
                    <div class="card-body text-center">
                        <div class="status-icon bg-dark bg-opacity-10 mx-auto">
                            <i class="bi bi-flag-fill fs-4 text-dark"></i>
                        </div>
                        <h2 class="status-number">{{ $counts['delivered'] ?? 0 }}</h2>
                        <p class="status-label mb-0">Delivered</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card modern-card">
            <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i> Order List</h5>
                <span class="badge bg-secondary">Total: {{ $orders->total() }} orders</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table order-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Delivery Type</th>
                                <th>Status</th>
                                {{-- <th>Driver</th> --}}
                                <th class="pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                @php
                                    $statusClass = match ($order->order_status) {
                                        'pending' => 'badge-pending',
                                        'confirmed' => 'badge-confirmed',
                                        'processing' => 'badge-packing',
                                        'ready' => 'badge-ready',
                                        'out_for_delivery' => 'badge-out_for_delivery',
                                        'delivered' => 'badge-delivered',
                                        'cancelled' => 'badge-cancelled',
                                        default => 'badge-secondary',
                                    };

                                    $displayStatus =
                                        $order->order_status == 'processing'
                                            ? 'Packing'
                                            : ucfirst($order->order_status);
                                    $driverName = $order->delivery
                                        ? $order->delivery->driver->name ?? 'Not Assigned'
                                        : 'Not Assigned';
                                @endphp
                                <tr>
                                    <td class="ps-4"><code class="fw-semibold">{{ $order->order_number }}</code></td>
                                    <td class="text-nowrap">
                                        {{ $order->created_at->format('M d, Y') }}<br>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>
                                    <td><strong
                                            class="text-success">₱{{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="delivery-badge">
                                            <i class="bi bi-truck me-1"></i>
                                            Delivery
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusClass }}">
                                            {{ $displayStatus }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <small class="text-muted">{{ $driverName }}</small>
                                    </td> --}}
                                    <td class="pe-4">
                                        <button onclick="openOrderModal({{ $order->id }})" class="btn-view btn-sm">
                                            <i class="bi bi-eye me-1"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <h5 class="mt-3">No Online Orders</h5>
                                        <p class="text-muted">There are no online orders to display at this time.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($orders->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Container -->
    <div id="orderModalContainer"></div>

    <script>
        function openOrderModal(orderId) {
            const container = document.getElementById('orderModalContainer');
            container.innerHTML = '';

            // Remove any existing backdrops
            const existingBackdrops = document.querySelectorAll('.modal-backdrop');
            existingBackdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');

            const modalHtml = `
            <div class="modal fade" id="orderModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading order details...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

            container.innerHTML = modalHtml;

            const modalElement = document.getElementById('orderModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

            fetch(`/admin/online-orders/${orderId}/modal`)
                .then(response => response.text())
                .then(html => {
                    const modalContent = document.querySelector('#orderModal .modal-content');
                    if (modalContent) {
                        modalContent.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalContent = document.querySelector('#orderModal .modal-content');
                    if (modalContent) {
                        modalContent.innerHTML = `
                        <div class="modal-header" style="border-bottom: 1px solid #eef2f6;">
                            <h5 class="modal-title">Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                Failed to load order details. Please try again.
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
