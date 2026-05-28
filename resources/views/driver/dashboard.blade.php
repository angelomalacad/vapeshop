@extends('layouts.driver')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2"></i> {{ Auth::user()->name }}</h2>
                            <p class="mb-0 mt-1 opacity-75">
                                <i class="bi bi-truck me-1"></i> Driver Dashboard
                            </p>
                        </div>
                        <div>
                            @if(isset($todayShift) && $todayShift)
                                <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle-fill me-1"></i> On Duty Today</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 fs-6"><i class="bi bi-clock me-1"></i> Off Duty</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards - Order Status Summary -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Pending Orders</span>
                            <h2 class="mb-0 fw-bold mt-2 text-warning">{{ $pendingOrdersCount ?? 0 }}</h2>
                            <small class="text-muted">Awaiting confirmation</small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Ready Orders</span>
                            <h2 class="mb-0 fw-bold mt-2 text-success">{{ $readyOrdersCount ?? 0 }}</h2>
                            <small class="text-muted">Ready for delivery</small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-box-seam fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Out for Delivery</span>
                            <h2 class="mb-0 fw-bold mt-2 text-info">{{ $outForDeliveryCount ?? 0 }}</h2>
                            <small class="text-muted">On the way</small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-truck fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">My Deliveries</span>
                            <h2 class="mb-0 fw-bold mt-2 text-primary">{{ $totalDeliveries ?? 0 }}</h2>
                            <small class="text-muted">Assigned to you</small>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-truck fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm text-center h-100 hover-lift transition">
                <div class="card-body py-5">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-cart display-3 text-primary"></i>
                    </div>
                    <h4 class="mb-2">Online Orders</h4>
                    <p class="text-muted mb-4">View and process customer orders</p>
                    <a href="{{ route('driver.online-orders.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="bi bi-arrow-right me-2"></i> Manage Orders
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm text-center h-100 hover-lift transition">
                <div class="card-body py-5">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-truck display-3 text-success"></i>
                    </div>
                    <h4 class="mb-2">My Deliveries</h4>
                    <p class="text-muted mb-4">Track and update your deliveries</p>
                    <a href="{{ route('driver.deliveries') }}" class="btn btn-success rounded-pill px-4 py-2">
                        <i class="bi bi-arrow-right me-2"></i> View Deliveries
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deliveries -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-clock-history me-2 text-primary"></i> Recent Deliveries
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tracking #</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th class="pe-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeliveries ?? [] as $delivery)
                        <tr>
                            <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                            <td>{{ $delivery->order->order_number ?? 'N/A' }}</td>
                            <td>{{ $delivery->recipient_name }}</td>
                            <td>
                                <span class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : 'info') }}">
                                    {{ ucfirst($delivery->status) }}
                                </span>
                            </td>
                            <td>{{ $delivery->updated_at->diffForHumans() }}</td>
                            <td class="pe-4">
                                <a href="{{ route('driver.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <p class="text-muted mb-0">No recent deliveries</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-lift {
        transition: transform 0.2s;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
    }
    .transition {
        transition: all 0.2s;
    }
</style>
@endsection