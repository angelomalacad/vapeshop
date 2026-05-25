@extends('layouts.driver')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-truck"></i> My Deliveries</h2>
        <span class="badge bg-primary">{{ $deliveries->total() }} total deliveries</span>
    </div>
    
    @if($deliveries->count() > 0)
        <div class="row">
            @foreach($deliveries as $delivery)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : ($delivery->status == 'picked_up' ? 'info' : 'secondary')) }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-receipt"></i> Order #{{ $delivery->order->order_number }}</strong>
                            <span class="badge bg-light text-dark">{{ ucfirst($delivery->status) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-person text-muted"></i> <strong>{{ $delivery->recipient_name }}</strong>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-geo-alt text-muted"></i> {{ Str::limit($delivery->delivery_address, 50) }}
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-telephone text-muted"></i> {{ $delivery->recipient_phone }}
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-clock text-muted"></i> Assigned: {{ $delivery->assigned_at ? $delivery->assigned_at->diffForHumans() : 'N/A' }}
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="{{ route('driver.deliveries.show', $delivery) }}" class="btn btn-primary w-100">
                            <i class="bi bi-eye"></i> Manage Delivery
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $deliveries->links() }}
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-truck display-1 text-muted"></i>
                <h4 class="mt-3">No Deliveries Assigned</h4>
                <p class="text-muted">You don't have any deliveries assigned yet.</p>
                <p class="text-muted small">Once a branch admin assigns a delivery to you, it will appear here.</p>
            </div>
        </div>
    @endif
</div>
@endsection