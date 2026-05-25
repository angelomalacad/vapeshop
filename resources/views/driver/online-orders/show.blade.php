@extends('layouts.driver')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Order #{{ $order->order_number }}</h1>
            <p class="text-muted">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <a href="{{ route('driver.online-orders.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Order Items</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₱{{ number_format($item->price,2) }}</td>
                                    <td>₱{{ number_format($item->subtotal,2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr><td colspan="3" class="text-end fw-bold">Subtotal:</td><td>₱{{ number_format($order->subtotal,2) }}</td></tr>
                                <tr><td colspan="3" class="text-end fw-bold">Tax (12%):</td><td>₱{{ number_format($order->tax,2) }}</td></tr>
                                <tr><td colspan="3" class="text-end fw-bold fs-5">Total:</td><td class="fw-bold fs-5 text-danger">₱{{ number_format($order->total_amount,2) }}</td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">Customer Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $order->customer_email ?? 'N/A' }}</p>
                            @if($order->delivery_type == 'delivery')
                                <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                                <p><strong>City/Barangay:</strong> {{ $order->city }}, {{ $order->barangay }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-semibold">Update Order Status</div>
                <div class="card-body">
                    @if($order->order_status == 'pending')
                        <form action="{{ route('driver.online-orders.confirm', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Confirm this order? Stock will be deducted.')">
                                <i class="bi bi-check-circle"></i> Confirm Order
                            </button>
                        </form>
                    @endif
                    
                    @if($order->order_status == 'confirmed')
                        <form action="{{ route('driver.online-orders.processing', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">Mark as Processing</button>
                        </form>
                    @endif
                    
                    @if(in_array($order->order_status, ['confirmed', 'processing']))
                        <form action="{{ route('driver.online-orders.ready', $order) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Mark as Ready</button>
                        </form>
                    @endif
                    
                    @if($order->order_status == 'ready')
                        <form action="{{ route('driver.online-orders.start-delivery', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info w-100" onclick="return confirm('Start delivery? Customer will be notified.')">
                                <i class="bi bi-truck"></i> Start Delivery
                            </button>
                        </form>
                    @endif
                    
                    @if($order->order_status == 'out_for_delivery' && $order->delivery)
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle"></i> Delivery in progress
                        </div>
                        <a href="{{ route('driver.deliveries.show', $order->delivery) }}" class="btn btn-primary w-100">
                            <i class="bi bi-truck"></i> Manage Delivery
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection