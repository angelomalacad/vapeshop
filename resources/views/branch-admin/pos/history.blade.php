@extends('layouts.branch-admin')

@section('title', 'POS Purchase History - Vape Expo')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">POS Purchase History</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-clock-history me-1"></i> All walk-in customer transactions
            </p>
        </div>
        <div>
            <a href="{{ route('branch-admin.pos.index') }}" class="btn btn-primary">
                <i class="bi bi-cash-coin"></i> New Sale
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Sales</h6>
                            <h2 class="mb-0 fw-bold">₱{{ number_format($todaySales, 2) }}</h2>
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
                            <h6 class="text-white-50 mb-1">Total Sales</h6>
                            <h2 class="mb-0 fw-bold">₱{{ number_format($totalSales, 2) }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-graph-up fs-4"></i>
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
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Avg. Order Value</h6>
                            <h2 class="mb-0 fw-bold">₱{{ $totalOrders > 0 ? number_format($totalSales / $totalOrders, 2) : '0.00' }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-calculator fs-4"></i>
                        </div>
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
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer" class="form-control" placeholder="Search customer..." value="{{ request('customer') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
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
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Cashier</th>
                            <th class="pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4"><code>{{ $order->order_number }}</code></td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->items->count() }} items</td>
                            <td class="fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-success text-uppercase">{{ $order->payment_method }}</span>
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td class="pe-4">
                                <a href="{{ route('branch-admin.pos.order.show', $order) }}" class="btn btn-sm btn-outline-info" title="View Receipt">
                                    <i class="bi bi-receipt"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-receipt display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No purchase history found</p>
                                <a href="{{ route('branch-admin.pos.index') }}" class="btn btn-primary">
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
@endsection