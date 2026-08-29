@extends('layouts.driver')

@section('page-class', 'has-sidebar')

@section('title', 'Online Orders - Driver')

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

        /* Modern Card */
        .modern-card {
            border: none;
            border-radius: 16px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
        .badge-ready {
            background: #d1fae5;
            color: #059669;
        }

        .badge-out_for_delivery {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-picked_up {
            background: #dbeafe;
            color: #2563eb;
        }

        .badge-in_transit {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .badge-delivered {
            background: #d1fae5;
            color: #059669;
        }

        .badge-delivery_failed {
            background: #fee2e2;
            color: #dc2626;
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

        /* Pagination */
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

        /* Responsive */
        @media (max-width: 768px) {

            .order-table th,
            .order-table td {
                padding: 0.5rem;
                font-size: 0.75rem;
                white-space: nowrap;
            }
        }

        /* Sidebar Styles - OVERRIDE to match layout */
        /* .app-sidebar {
                    position: fixed !important;
                    top: 60px !important;
                    left: 0 !important;
                    width: 260px !important;
                    height: auto !important;
                    max-height: calc(100vh - 80px) !important;
                    background: #ffffff;
                    border-radius: 0 0 16px 0;
                    box-shadow: 2px 0 20px rgba(0, 0, 0, 0.05);
                    z-index: 1040 !important;
                    overflow-y: auto !important;
                    padding-bottom: 10px !important;
                    margin-top: 0 !important;
                    transform: none !important;
                    transition: none !important;
                }


                .sidebar-header {
                    background: #1e293b;
                    padding: 18px 20px;
                    text-align: center;
                    color: #fff;
                }

                .sidebar-header h6 {
                    font-weight: 600;
                    margin: 0;
                    letter-spacing: 0.3px;
                }

                .sidebar-menu {
                    padding: 12px;
                }

                .sidebar-menu .menu-item {
                    display: flex;
                    align-items: center;
                    padding: 12px 16px;
                    border-radius: 12px;
                    color: #64748b;
                    text-decoration: none;
                    transition: all 0.2s ease;
                    margin-bottom: 4px;
                    font-weight: 500;
                    font-size: 0.9rem;
                }

                .sidebar-menu .menu-item i {
                    font-size: 1.1rem;
                    width: 24px;
                    text-align: center;
                    margin-right: 14px;
                }

                .sidebar-menu .menu-item:hover {
                    background: #f1f5f9;
                    color: #1e293b;
                }

                .sidebar-menu .menu-item.active {
                    background: #eff6ff;
                    color: #2563eb;
                    border-left: 3px solid #2563eb;
                } */

        /* MODAL STYLES - CRITICAL */
        #customModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 99999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        #customModalContent {
            background: white;
            width: 95%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
    </style>

    <!-- SIDEBAR -->
    <div class="app-sidebar">
        <div class="sidebar-header">
            <h6><i class="bi bi-grid-3x3-gap-fill"></i> Driver Menu</h6>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('driver.dashboard') }}"
                class="menu-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('driver.online-orders.index') }}"
                class="menu-item {{ request()->routeIs('driver.online-orders*') ? 'active' : '' }}">
                <i class="bi bi-cart"></i> Online Orders
            </a>
            <a href="{{ route('driver.delivery-history', ['sidebar' => 1]) }}"
                class="menu-item {{ request()->routeIs('driver.delivery-history') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Delivery History
            </a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title mb-1"><i class="bi bi-cart me-2 text-primary"></i> Online Orders</h1>
                    <p class="page-subtitle mb-0">Manage your assigned deliveries</p>
                </div>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="row g-2 mb-4">
            <div class="col-md-3 col-6">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-success bg-opacity-10">
                            <i class="bi bi-box-seam text-success"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['ready'] ?? 0 }}</h2>
                            <p class="status-label">Ready</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-secondary bg-opacity-10">
                            <i class="bi bi-truck text-secondary"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['out_for_delivery'] ?? 0 }}</h2>
                            <p class="status-label">Out for Delivery</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-dark bg-opacity-10">
                            <i class="bi bi-flag-fill text-dark"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['delivered'] ?? 0 }}</h2>
                            <p class="status-label">Delivered</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card status-card">
                    <div class="card-body status-card-body">
                        <div class="status-icon bg-danger bg-opacity-10">
                            <i class="bi bi-x-circle text-danger"></i>
                        </div>
                        <div class="status-info">
                            <h2 class="status-number">{{ $counts['delivery_failed'] ?? 0 }}</h2>
                            <p class="status-label">Failed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('driver.online-orders.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Filter by Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="out_for_delivery"
                                {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="delivery_failed" {{ request('status') == 'delivery_failed' ? 'selected' : '' }}>
                                Delivery Failed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('driver.online-orders.index') }}" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card modern-card">
            <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i> Delivery Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table order-table">
                        <thead>
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Lalamove Info</th>
                                <th class="pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                @php
                                    $statusClass = match ($order->order_status) {
                                        'ready' => 'badge-ready',
                                        'out_for_delivery' => 'badge-out_for_delivery',
                                        'picked_up' => 'badge-picked_up',
                                        'in_transit' => 'badge-in_transit',
                                        'delivered' => 'badge-delivered',
                                        'delivery_failed' => 'badge-delivery_failed',
                                        'cancelled' => 'badge-cancelled',
                                        default => 'badge-secondary',
                                    };
                                    $displayStatus = ucfirst(str_replace('_', ' ', $order->order_status));
                                    if ($order->order_status == 'delivery_failed') {
                                        $displayStatus = 'Delivery Failed';
                                    }

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

                                    $cityLower = strtolower(trim($order->city ?? ''));
                                    $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                                    $isLalamoveEligible = !$isCalambaCity;
                                @endphp
                                <tr>
                                    <td class="ps-4"><code class="fw-semibold">{{ $order->order_number }}</code></td>
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
                                    <td>
                                        @if ($isLalamoveEligible && $order->delivery && !empty($order->delivery->tracking_number))
                                            <a href="{{ $order->delivery->tracking_number }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View Link
                                            </a>
                                        @elseif($isLalamoveEligible && in_array($order->order_status, ['out_for_delivery', 'picked_up', 'in_transit']))
                                            <span class="text-muted">Awaiting link</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="pe-4">
                                        <button onclick="window.openOrderModal({{ $order->id }})"
                                            class="btn btn-manage btn-sm text-white">
                                            <i class="bi bi-eye me-1"></i> Manage
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="bi bi-inbox display-1 text-muted"></i>
                                        <h5 class="mt-3">No Online Orders</h5>
                                        <p class="text-muted">There are no online orders assigned to you at this time.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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

    <!-- MODAL CONTAINER - CRITICAL FIX -->
    <div id="customModal">
        <div id="customModalContent">
            <!-- Content will be injected here via AJAX -->
        </div>
    </div>

    <script>
        // ✅ GLOBAL FUNCTIONS
        window.closeModal = function() {
            const modal = document.getElementById('customModal');
            if (modal) {
                modal.style.display = 'none';
            }
            const content = document.getElementById('customModalContent');
            if (content) {
                content.innerHTML = '';
            }
        };

        window.openOrderModal = function(orderId) {
            const modal = document.getElementById('customModal');
            const modalContent = document.getElementById('customModalContent');

            modalContent.innerHTML = `
                <div style="padding: 40px; text-align: center;">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading order details...</p>
                </div>
            `;
            modal.style.display = 'flex';

            fetch(`/driver/online-orders/${orderId}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;

                    // ✅ Execute any scripts in the returned HTML
                    const scripts = modalContent.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        script.remove();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = `
                        <div style="padding: 20px;">
                            <div class="alert alert-danger mb-3">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Failed to load order details. Please try again.
                            </div>
                            <div class="text-center">
                                <button onclick="window.closeModal()" class="btn btn-secondary rounded-pill px-4">Close</button>
                            </div>
                        </div>
                    `;
                });
        };

        // ✅ ADD THIS: Open Delivery Modal (for "Manage Delivery" button)
        // ✅ ADD THIS FUNCTION
        window.openDeliveryModal = function(deliveryId) {
            const modal = document.getElementById('customModal');
            const modalContent = document.getElementById('customModalContent');

            modalContent.innerHTML = `
        <div style="padding: 40px; text-align: center;">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading delivery details...</p>
        </div>
    `;
            modal.style.display = 'flex';

            fetch(`/driver/deliveries/${deliveryId}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;

                    // ✅ Execute any scripts in the returned HTML
                    const scripts = modalContent.querySelectorAll('script');
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        script.remove();
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = `
                <div style="padding: 20px;">
                    <div class="alert alert-danger mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Failed to load delivery details. Please try again.
                    </div>
                    <div class="text-center">
                        <button onclick="window.closeModal()" class="btn btn-secondary rounded-pill px-4">Close</button>
                    </div>
                </div>
            `;
                });
        };
    </script>
@endsection
