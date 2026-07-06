@extends('layouts.branch-admin')

@section('title', 'Order Details - Vape Expo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-receipt me-2 text-primary"></i>Order Receipt
                        </h5>
                        <div>
                            <button onclick="window.print()" class="btn btn-sm btn-outline-primary me-2">
                                <i class="bi bi-printer"></i> Print
                            </button>
                            <a href="{{ route('branch-admin.pos.history') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Receipt Header -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="60">
                        <h4 class="mt-2 mb-0">Vape Expo</h4>
                        <p class="text-muted small mb-0">{{ $order->branch->name }}</p>
                        <p class="text-muted small">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    
                    <!-- Order Info -->
                    <div class="mb-3">
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
                        @if($order->customer_phone)
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Contact:</span>
                            <span>{{ $order->customer_phone }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <hr>
                    
                    <!-- Items -->
                    <div class="mb-3">
                        @foreach($order->items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <span>{{ $item->product->name }}</span>
                                <br><small class="text-muted">{{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}</small>
                            </div>
                            <span class="fw-semibold">₱{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <hr>
                    
                    <!-- Totals -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>TOTAL:</span>
                            <span class="text-primary">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Payment -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Payment Method:</span>
                            <span class="text-uppercase">{{ $order->payment_method }}</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Footer -->
                    <div class="text-center">
                        <p class="small text-muted mb-0">Thank you for shopping at Vape Expo!</p>
                        <p class="small text-muted">Business Hours: 9:00 AM - 10:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .navbar, .sidebar, .top-navbar, .card-header .btn {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endsection