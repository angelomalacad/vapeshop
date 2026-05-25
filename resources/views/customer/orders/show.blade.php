@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order #{{ $order->order_number }}</h2>
        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white fw-semibold">Items</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
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
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-end">
                    <div>Subtotal: ₱{{ number_format($order->subtotal,2) }}</div>
                    <div>Tax (12%): ₱{{ number_format($order->tax,2) }}</div>
                    <h5 class="mt-2">Total: ₱{{ number_format($order->total_amount,2) }}</h5>
                </div>
            </div>

            @if($order->delivery_type == 'delivery' && $order->delivery)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">Delivery Details</div>
                <div class="card-body">
                    <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                    <p><strong>Contact:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Tracking #:</strong> {{ $order->delivery->tracking_number ?? 'N/A' }}</p>
                    <p><strong>Driver:</strong> {{ $order->delivery->driver->name ?? 'Not assigned' }}</p>
                    <p><strong>Driver Contact:</strong> {{ $order->delivery->driver->phone ?? 'N/A' }}</p>
                    <p><strong>Status:</strong> <span class="badge {{ $order->delivery->status_badge_class ?? 'bg-secondary' }}">{{ ucfirst($order->delivery->status ?? 'Unknown') }}</span></p>
                    
                    @if($order->delivery->delivery_proof)
                    <div class="mt-3">
                        <strong>Delivery Proof:</strong><br>
                        <a href="{{ Storage::url($order->delivery->delivery_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                            <i class="bi bi-image"></i> View Delivery Photo
                        </a>
                    </div>
                    @endif
                    
                    @if($order->delivery->payment_proof)
                    <div class="mt-2">
                        <strong>Payment Proof:</strong><br>
                        <a href="{{ Storage::url($order->delivery->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-success mt-1">
                            <i class="bi bi-receipt"></i> View Payment Photo
                        </a>
                    </div>
                    @endif
                    
                    @if($order->order_status == 'out_for_delivery')
                    <div class="mt-3">
                        <a href="{{ route('customer.orders.track', $order) }}" class="btn btn-info rounded-pill">
                            <i class="bi bi-geo-alt"></i> Track Delivery
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">Order Information</div>
                <div class="card-body">
                    <p><strong>Date placed:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
                    <p><strong>Branch:</strong> {{ $order->branch->name }}</p>
                    <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                    <p><strong>Payment Status:</strong> <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($order->payment_status) }}</span></p>
                    @if($order->notes)<p><strong>Notes:</strong> {{ $order->notes }}</p>@endif
                    
                    @if($order->delivery && $order->delivery->driver_notes)
                    <hr>
                    <p><strong>Driver Notes:</strong><br>{{ $order->delivery->driver_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection