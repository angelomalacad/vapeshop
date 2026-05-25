@extends('layouts.customer')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-receipt"></i> My Orders</h2>

    @if($orders->count())
        @foreach($orders as $order)
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <small class="text-muted">Order #</small>
                        <div><strong>{{ $order->order_number }}</strong></div>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Date</small>
                        <div>{{ $order->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Total</small>
                        <div class="fw-bold text-danger">₱{{ number_format($order->total_amount,2) }}</div>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">Status</small>
                        <div><span class="badge {{ $order->order_status_badge_class }}">{{ $order->order_status_label }}</span></div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-primary rounded-pill">View Details</a>
                        @if($order->order_status == 'pending')
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-danger rounded-pill ms-2" onclick="return confirm('Cancel this order?')">Cancel</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        {{ $orders->links() }}
    @else
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h3 class="mt-3">No orders yet</h3>
            <a href="{{ route('customer.products.index') }}" class="btn btn-primary rounded-pill mt-3">Start Shopping</a>
        </div>
    @endif
</div>
@endsection