@extends('layouts.driver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><i class="bi bi-speedometer2"></i> Driver Dashboard</h1>
            <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div>
            <span class="badge bg-primary">{{ Auth::user()->branch->name ?? 'Branch Assigned' }}</span>
        </div>
    </div>

    <!-- Quick Stats Cards - Shows ALL deliveries for this driver -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Deliveries</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalDeliveries ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-truck fs-1 opacity-50"></i>
                    </div>
                    <small>All deliveries assigned to you</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">In Transit</h6>
                            <h2 class="mb-0 fw-bold">{{ $inTransitCount ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-arrow-right-circle fs-1 opacity-50"></i>
                    </div>
                    <small>Currently on the road</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Completed</h6>
                            <h2 class="mb-0 fw-bold">{{ $deliveredCount ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                    <small>Successfully delivered</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending Orders</h6>
                            <h2 class="mb-0 fw-bold">{{ $pendingOrders ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-cart fs-1 opacity-50"></i>
                    </div>
                    <small>Orders waiting for your branch</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-5">
                    <i class="bi bi-cart display-1 text-primary"></i>
                    <h4 class="mt-3">Online Orders</h4>
                    <p class="text-muted">View and process online orders assigned to your branch</p>
                    <a href="{{ route('driver.online-orders.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-arrow-right"></i> Manage Orders
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-5">
                    <i class="bi bi-truck display-1 text-success"></i>
                    <h4 class="mt-3">My Deliveries</h4>
                    <p class="text-muted">Track and update ALL your deliveries</p>
                    <a href="{{ route('driver.deliveries') }}" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-arrow-right"></i> View All Deliveries
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deliveries Table - Shows driver's recent deliveries -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Your Recent Deliveries</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tracking #</th>
                            <th>Order #</th>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeliveries ?? [] as $delivery)
                        @php
                            $statusColors = [
                                'pending' => 'secondary',
                                'assigned' => 'info',
                                'picked_up' => 'primary',
                                'in_transit' => 'warning',
                                'delivered' => 'success',
                                'failed' => 'danger',
                            ];
                        @endphp
                        <tr>
                            <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                            <td>{{ $delivery->order->order_number ?? 'N/A' }}</td>
                            <td><span class="badge bg-secondary">{{ $delivery->order->branch->name ?? 'N/A' }}</span></td>
                            <td>{{ $delivery->recipient_name }}</td>
                            <td>{{ Str::limit($delivery->delivery_address, 30) }}</td>
                            <td><span class="badge bg-{{ $statusColors[$delivery->status] ?? 'secondary' }}">{{ ucfirst($delivery->status) }}</span></td>
                            <td class="pe-4">
                                <a href="{{ route('driver.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Manage
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No deliveries assigned to you yet
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection