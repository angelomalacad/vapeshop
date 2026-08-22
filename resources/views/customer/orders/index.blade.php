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
                        
                        {{-- Product Image & Name --}}
                        @php
                            $firstItem = $order->items->first();
                            $imageUrl = null;
                            if ($firstItem) {
                                $inventory = \App\Models\BranchInventory::with('product')->find($firstItem->inventory_id);
                                if ($inventory && $inventory->product && $inventory->product->image) {
                                    $imageUrl = \Storage::url($inventory->product->image);
                                }
                            }
                        @endphp
                        
                        <div class="d-flex align-items-center gap-2 mt-1">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="Product" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            @else
                                <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                    <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                </div>
                            @endif
                            <div>
                                <strong>{{ $firstItem->product->name ?? 'Order Items' }}</strong>
                                
                                {{-- ================================================ --}}
                                {{-- ADDED: Display the Flavor/Variant below the name --}}
                                {{-- ================================================ --}}
                                @if($firstItem && $firstItem->flavor)
                                    <br><small class="text-muted">Variant: {{ $firstItem->flavor->name }}</small>
                                @endif
                                {{-- ================================================ --}}

                                @if($order->items->count() > 1)
                                    <br><small class="text-muted">+ {{ $order->items->count() - 1 }} more item(s)</small>
                                @endif
                            </div>
                        </div>

                       

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
                        <div>
                            <span class="badge {{ $order->order_status_badge_class }}">
                                {{ $order->order_status_label }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-primary rounded-pill">View Details</a>
                        
                         {{-- ONLY ALLOW CANCELLATION IF STATUS IS STRICTLY 'pending' --}}
                        @if($order->order_status === 'pending')
                        <button type="button" class="btn btn-outline-danger rounded-pill ms-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal{{ $order->id }}">
                            Cancel
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- ============================================================ --}}
        {{-- ADD THE CANCEL MODAL HERE (Inside the Loop, right after the card) --}}
        {{-- ============================================================ --}}
        @if($order->order_status === 'pending')
        <div class="modal fade" id="cancelOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div class="modal-header" style="border-bottom: 1px solid #eef2f6; padding: 1.25rem 1.5rem;">
                        <h5 class="modal-title fw-bold" style="color: #dc3545;">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Cancel Order
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 1.5rem;">
                        <p class="mb-0">Are you sure you want to cancel <strong>Order #{{ $order->order_number }}</strong>?</p>
                        <p class="text-muted small mt-2">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                        
                        {{-- ✅ THE ACTUAL CANCEL FORM INSIDE THE MODAL --}}
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger rounded-pill px-4">
                                <i class="bi bi-check-circle me-1"></i> Yes, Cancel Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{-- ============================================================ --}}
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