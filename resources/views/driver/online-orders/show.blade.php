@extends('layouts.modal')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div style="padding: 20px; max-height: 85vh; overflow-y: auto;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
            <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <button type="button" class="btn-close" onclick="closeModal()"></button>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0"><i class="bi bi-receipt me-1"></i> Order Items</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="bg-light">
                                <tr><th>Product</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr><td><strong>{{ $item->product->name }}</strong></div>
                                    <td class="text-center">{{ $item->quantity }}</div>
                                    <td class="text-end">₱{{ number_format($item->price, 2) }}</div>
                                    <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</div>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr><td colspan="3" class="text-end fw-bold">Subtotal:</div><td class="text-end fw-bold">₱{{ number_format($order->subtotal, 2) }}</div></tr>
                                <tr><td colspan="3" class="text-end fw-bold">Tax (12%):</div><td class="text-end fw-bold">₱{{ number_format($order->tax, 2) }}</div></tr>
                                <tr><td colspan="3" class="text-end fw-bold">Total:</div><td class="text-end fw-bold text-danger">₱{{ number_format($order->total_amount, 2) }}</div></tr>
                            </tfoot>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0"><i class="bi bi-person me-1"></i> Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1 text-muted small">Name</p>
                            <p class="mb-2 fw-semibold">{{ $order->customer_name }}</p>
                            <p class="mb-1 text-muted small">Phone</p>
                            <p class="mb-2">{{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-6">
                            <p class="mb-1 text-muted small">Email</p>
                            <p class="mb-2">{{ $order->customer_email ?? 'N/A' }}</p>
                            @if($order->delivery_type == 'delivery')
                                <p class="mb-1 text-muted small">Address</p>
                                <p class="mb-2">{{ $order->delivery_address }}</p>
                                <p class="mb-1 text-muted small">City/Barangay</p>
                                <p class="mb-2">{{ $order->city ?? 'N/A' }}, {{ $order->barangay ?? 'N/A' }}</p>
                                @if($order->landmark)
                                    <p class="mb-1 text-muted small">Landmark</p>
                                    <p class="mb-2">{{ $order->landmark }}</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="mb-0"><i class="bi bi-arrow-repeat me-1"></i> Update Order Status</h6>
                </div>
                <div class="card-body">
                    @if($order->order_status == 'pending')
                        <form action="{{ route('driver.online-orders.confirm', $order) }}" method="POST" class="status-form">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i> Confirm Order
                            </button>
                        </form>
                    @elseif($order->order_status == 'confirmed')
                        <form action="{{ route('driver.online-orders.processing', $order) }}" method="POST" class="status-form">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-gear me-1"></i> Mark as Processing
                            </button>
                        </form>
                    @elseif(in_array($order->order_status, ['confirmed', 'processing']))
                        <form action="{{ route('driver.online-orders.ready', $order) }}" method="POST" class="status-form mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-box-seam me-1"></i> Mark as Ready
                            </button>
                        </form>
                    @elseif($order->order_status == 'out_for_delivery')
                        <!-- THIS IS THE BUTTON YOU NEED -->
                        <div class="alert alert-info mb-3 py-2">
                            <i class="bi bi-info-circle me-1"></i> Delivery is in progress
                        </div>
                        @if($order->delivery)
                            <button type="button" class="btn btn-primary w-100" onclick="openDeliveryModal({{ $order->delivery->id }})">
                                <i class="bi bi-truck me-1"></i> Manage Delivery
                            </button>
                        @else
                            <div class="alert alert-warning mb-3 py-2">
                                <i class="bi bi-exclamation-triangle me-1"></i> No delivery record found
                            </div>
                        @endif
                    @elseif($order->order_status == 'delivered')
                        <div class="alert alert-success mb-0 py-3 text-center">
                            <i class="bi bi-check-circle-fill me-1"></i> 
                            <strong>Order Completed</strong><br>
                            <small>Delivered on {{ $order->updated_at->format('M d, Y h:i A') }}</small>
                        </div>
                    @endif
                    
                    <!-- DEBUG INFO -->
                    <div class="alert alert-secondary small mt-3">
                        <strong>Debug:</strong><br>
                        Status: <strong>{{ $order->order_status }}</strong><br>
                        Delivery Type: <strong>{{ $order->delivery_type }}</strong><br>
                        Has Delivery: <strong>{{ $order->delivery ? 'Yes (ID: '.$order->delivery->id.')' : 'No' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .table th, .table td {
        padding: 8px 10px;
        vertical-align: middle;
    }
</style>

<script>
    document.querySelectorAll('.status-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', data.message || 'An error occurred');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Error processing request');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    });
    
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = 9999;
        alertDiv.style.minWidth = '280px';
        alertDiv.style.zIndex = 1060;
        alertDiv.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'} me-2"></i>
                <span class="flex-grow-1">${message}</span>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert"></button>
            </div>
        `;
        document.body.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 3000);
    }
    
    function openDeliveryModal(deliveryId) {
        const container = document.getElementById('customModal');
        const modalContent = document.getElementById('customModalContent');
        if (container && modalContent) {
            modalContent.innerHTML = '<div style="padding:20px;text-align:center;"><div class="spinner-border text-primary"></div><p class="mt-2">Loading...</p></div>';
            container.style.display = 'flex';
            fetch(`/driver/deliveries/${deliveryId}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                });
        }
    }
    
    function closeModal() {
        const customModal = document.getElementById('customModal');
        if (customModal) {
            customModal.style.display = 'none';
            document.getElementById('customModalContent').innerHTML = '';
        }
    }
</script>
@endsection