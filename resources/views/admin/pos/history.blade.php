@extends('layouts.admin')

@section('title', 'Sales History - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Sales History</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-receipt me-1"></i> All POS sales across all branches
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.pos.index') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-cash-coin"></i> New Sale
            </a>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Sales</h6>
                            <h2 class="mb-0 fw-bold">₱{{ number_format($totalSales, 2) }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-currency-dollar fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Orders</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalOrders }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-receipt fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Sales</h6>
                            <h2 class="mb-0 fw-bold">₱{{ number_format($todaySales, 2) }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-calendar-day fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Orders</h6>
                            <h2 class="mb-0 fw-bold">{{ $todayOrders }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-cart-check fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales by Branch -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-shop me-2 text-primary"></i>Sales by Branch</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesByBranch as $branch)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $branch->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $branch->orders_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-end">
                                        ₱{{ number_format($branch->orders_sum_total_amount ?? 0, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th class="fw-bold">Total</th>
                                    <th class="text-center fw-bold">{{ $totalOrders }}</th>
                                    <th class="text-end fw-bold">₱{{ number_format($totalSales, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sales by Payment Method -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-credit-card me-2 text-primary"></i>Sales by Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Payment Method</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesByPayment as $payment)
                                <tr>
                                    <td>
                                        <span class="badge bg-info text-uppercase">{{ $payment->payment_method }}</span>
                                    </td>
                                    <td class="text-center">{{ $payment->total_orders }}</td>
                                    <td class="text-end">₱{{ number_format($payment->total_amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Orders</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
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
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Customer Name</label>
                    <input type="text" name="customer" class="form-control" placeholder="Search customer..." value="{{ request('customer') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                        <option value="paymaya" {{ request('payment_method') == 'paymaya' ? 'selected' : '' }}>PayMaya</option>
                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.pos.history') }}" class="btn btn-outline-secondary ms-2 px-4">
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
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Date & Time</th>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Subtotal</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Cashier</th>
                            <th class="pe-4">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4"><code class="fw-semibold">{{ $order->order_number }}</code></td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $order->branch->name }}</span>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->items->count() }} items</td>
                            <td>₱{{ number_format($order->subtotal, 2) }}</td>
                            <td>₱{{ number_format($order->tax, 2) }}</td>
                            <td class="fw-bold text-primary">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->payment_method == 'cash' ? 'success' : 'info' }} text-uppercase">
                                    {{ $order->payment_method }}
                                </span>
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td class="pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" data-bs-target="#receiptModal{{ $order->id }}">
                                    <i class="bi bi-receipt"></i> View
                                </button>
                                
                                <!-- Receipt Modal -->
                                <div class="modal fade" id="receiptModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="bi bi-receipt me-2"></i>Order Receipt</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <!-- Receipt Content -->
                                                <div class="text-center mb-3">
                                                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="50">
                                                    <h5 class="mt-2 mb-0">Vape Expo</h5>
                                                    <p class="text-muted small mb-0">{{ $order->branch->name }}</p>
                                                    <p class="text-muted small">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                                </div>
                                                
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Order #:</span>
                                                        <span class="fw-semibold">{{ $order->order_number }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Cashier:</span>
                                                        <span>{{ $order->user->name }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted">Customer:</span>
                                                        <span>{{ $order->customer_name }}</span>
                                                    </div>
                                                </div>
                                                
                                                <hr>
                                                
                                                @foreach($order->items as $item)
                                                <div class="d-flex justify-content-between mb-1">
                                                    <div>
                                                        {{ $item->product->name }}
                                                        <br><small class="text-muted">{{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}</small>
                                                    </div>
                                                    <span>₱{{ number_format($item->subtotal, 2) }}</span>
                                                </div>
                                                @endforeach
                                                
                                                <hr>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <span>Subtotal:</span>
                                                    <span>₱{{ number_format($order->subtotal, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Tax (12%):</span>
                                                    <span>₱{{ number_format($order->tax, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between fw-bold fs-5">
                                                    <span>TOTAL:</span>
                                                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                                                </div>
                                                
                                                <hr>
                                                
                                                <div class="d-flex justify-content-between">
                                                    <span>Payment:</span>
                                                    <span class="text-uppercase">{{ $order->payment_method }}</span>
                                                </div>
                                                
                                                <div class="text-center mt-3">
                                                    <small class="text-muted">Thank you for shopping at Vape Expo!</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button onclick="printReceipt({{ $order->id }})" class="btn btn-primary">
                                                    <i class="bi bi-printer"></i> Print
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="bi bi-receipt display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No sales found</p>
                                <a href="{{ route('admin.pos.index') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-cash-coin"></i> Make First Sale
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
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, .top-navbar, .btn, .modal-footer, .card-header .btn, .pagination, .filter-section {
            display: none !important;
        }
        .modal-content {
            box-shadow: none !important;
            border: none !important;
        }
        .modal-dialog {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

<script>
    function printReceipt(orderId) {
        const modalContent = document.querySelector(`#receiptModal${orderId} .modal-body`).cloneNode(true);
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Receipt - Vape Expo</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { padding: 20px; }
                        @media print {
                            body { padding: 0; }
                        }
                    </style>
                </head>
                <body>
                    ${modalContent.outerHTML}
                    <script>window.print(); window.close();<\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endsection