@extends('layouts.driver')

@section('title', 'Online Orders - Driver')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-cart"></i> Online Orders</h1>
        <div>
            <span class="badge bg-primary">{{ Auth::user()->branch->name ?? 'No Branch' }}</span>
        </div>
    </div>
    
    <!-- Status Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Pending</h6>
                    <h2 class="mb-0">{{ $counts['pending'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Confirmed</h6>
                    <h2 class="mb-0">{{ $counts['confirmed'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Processing</h6>
                    <h2 class="mb-0">{{ $counts['processing'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Ready</h6>
                    <h2 class="mb-0">{{ $counts['ready'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6>Out for Delivery</h6>
                    <h2 class="mb-0">{{ $counts['out_for_delivery'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h6>Delivered</h6>
                    <h2 class="mb-0">{{ $counts['delivered'] }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Delivery Type</th>
                            <th>Status</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'processing' => 'primary',
                                'ready' => 'success',
                                'out_for_delivery' => 'secondary',
                                'delivered' => 'dark',
                                'cancelled' => 'danger',
                            ];
                        @endphp
                        <tr>
                            <td class="ps-4"><code>{{ $order->order_number }}</code></td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>{{ ucfirst($order->delivery_type) }}</td>
                            <td><span class="badge bg-{{ $statusColors[$order->order_status] }}">{{ ucfirst($order->order_status) }}</span></td>
                            <td class="pe-4">
                                <a href="{{ route('driver.online-orders.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Manage
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <p class="mt-3">No online orders found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection