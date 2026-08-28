@extends('layouts.branch-admin')

@section('title', 'Online Orders - Branch Staff')

@section('page-title', 'Online Orders Management')

@section('content')
    <style>
        /* Modern Minimalist Styles */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0;
        }

        /* Status Cards */
        .status-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
            padding: 0.4rem 0.8rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .status-card-body {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0;
        }

        .status-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .status-icon i {
            font-size: 1rem;
        }

        .status-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .status-number {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }

        .status-label {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #64748b;
            margin: 0;
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
            padding: 0.75rem;
            border-bottom: 1px solid #eef2f6;
            white-space: nowrap;
        }

        .order-table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #eef2f6;
            color: #334155;
            font-size: 0.85rem;
        }

        .order-table tbody tr:hover {
            background: #f8f9fa;
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
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
            font-weight: 500;
            font-size: 0.7rem;
        }

        /* Buttons */
        .btn-manage {
            background: #1a1a2e;
            border: none;
            border-radius: 30px;
            padding: 0.35rem 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-manage:hover {
            background: #16213e;
            transform: translateY(-1px);
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

        /* Simple Pagination */
        .simple-pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 0;
        }

        .simple-pagination .btn {
            border-radius: 30px;
            padding: 0.3rem 1.2rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {

            .order-table th,
            .order-table td {
                padding: 0.5rem;
                font-size: 0.75rem;
                white-space: nowrap;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title mb-1"><i class="bi bi-cart me-2 text-primary"></i> Online Orders</h1>
                    <p class="text-muted mb-0">Manage customer orders from confirmation to ready for delivery</p>
                </div>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="row g-2 mb-4">
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-warning bg-opacity-10">
                            <i class="bi bi-hourglass-split text-warning"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['pending'] }}</h2>
                            <p class="status-label">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-info bg-opacity-10">
                            <i class="bi bi-check-circle text-info"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['confirmed'] }}</h2>
                            <p class="status-label">Confirmed</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-primary bg-opacity-10">
                            <i class="bi bi-box-seam text-primary"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['processing'] }}</h2>
                            <p class="status-label">Packing</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-success bg-opacity-10">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['ready'] }}</h2>
                            <p class="status-label">Ready</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-secondary bg-opacity-10">
                            <i class="bi bi-truck text-secondary"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['out_for_delivery'] }}</h2>
                            <p class="status-label">Out for Delivery</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-dark bg-opacity-10">
                            <i class="bi bi-flag-fill text-dark"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['delivered'] }}</h2>
                            <p class="status-label">Delivered</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('branch-admin.online-orders.index') }}"
                    class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Filter by Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Packing
                            </option>
                            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="out_for_delivery"
                                {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Order #..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        <a href="{{ route('branch-admin.online-orders.index') }}"
                            class="btn btn-outline-secondary w-100 mt-1">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table order-table">
                        <thead>
                            <tr>
                                <th class="ps-3">Order #</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
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
                                            : ucfirst(str_replace('_', ' ', $order->order_status));

                                    $firstItem = $order->items->first();
                                    $product = $firstItem ? $firstItem->product : null;
                                    $productName = $product ? $product->name : 'N/A';
                                    $itemsCount = $order->items->count();

                                    $imageUrl = null;
                                    if ($product && $product->image) {
                                        if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                            $imageUrl = $product->image;
                                        } elseif (Storage::disk('public')->exists($product->image)) {
                                            $imageUrl = Storage::url($product->image);
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-3"><code class="fw-semibold">{{ $order->order_number }}</code></td>
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
                                        <div class="fw-semibold">{{ $productName }}</div>
                                        <small class="text-muted">{{ $itemsCount }} item(s)</small>
                                    </td>
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
                                            @if ($order->is_lalamove)
                                                <i class="bi bi-truck me-1 text-primary"></i> Lalamove
                                            @else
                                                <i class="bi bi-bicycle me-1 text-success"></i> Staff
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusClass }}">{{ $displayStatus }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <button onclick="openOrderModal({{ $order->id }})"
                                            class="btn btn-manage btn-sm text-white">
                                            <i class="bi bi-eye me-1"></i> Manage
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <h5 class="mt-3">No Online Orders</h5>
                                        <p class="text-muted">There are no online orders to process at this time.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="simple-pagination">
                    @if ($orders->onFirstPage())
                        <button class="btn btn-outline-secondary" disabled>Previous</button>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                    @endif

                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
                    @else
                        <button class="btn btn-outline-secondary" disabled>Next</button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Container -->
    <div id="modalContainer"></div>

    <script>
        function openOrderModal(orderId) {
            const container = document.getElementById('modalContainer');

            // Create a simple, unstoppable modal
            container.innerHTML = `
            <div id="orderModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; justify-content: center; align-items: center;">
                <div style="background: white; width: 90%; max-width: 900px; max-height: 90vh; overflow-y: auto; border-radius: 12px; padding: 20px; position: relative;">
                    <div style="text-align: center; padding: 40px;">
                        <div class="spinner-border"></div>
                        <p>Loading order details...</p>
                    </div>
                </div>
            </div>
        `;

            // Fetch the content
            fetch(`/branch-admin/online-orders/${orderId}`)
                .then(response => response.text())
                .then(html => {
                    // Replace the loading spinner with the actual content
                    container.querySelector('#orderModal > div').innerHTML = html;

                    // ✅ CRITICAL: Execute the scripts in the returned HTML
                    const scripts = container.querySelectorAll('#orderModal script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        script.remove();
                    });
                })
                .catch(error => {
                    container.querySelector('#orderModal > div').innerHTML = '<p>Error loading order.</p>';
                });
        }
    </script>
@endsection
