@extends('layouts.modal')

@section('title', 'Order #' . $order->order_number)

@section('content')
<style>
    /* Modern Minimalist Styles */
    .modal-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eef2f6;
        background: white;
    }
    
    .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
    }
    
    .order-date {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0;
    }
    
    /* Cards */
    .info-card {
        border: none;
        border-radius: 16px;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    
    .card-header-custom {
        padding: 0.875rem 1.25rem;
        border-bottom: 1px solid #eef2f6;
        background: white;
    }
    
    .card-header-custom h6 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .card-header-custom i {
        color: #e74c3c;
        margin-right: 0.5rem;
    }
    
    /* Product Image */
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8f9fa;
    }
    
    /* Table Styles */
    .order-items-table {
        margin-bottom: 0;
    }
    
    .order-items-table th {
        background: #f8f9fa;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #eef2f6;
    }
    
    .order-items-table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f6;
        font-size: 0.8rem;
        color: #334155;
    }
    
    .product-name {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .product-flavor {
        font-size: 0.7rem;
        color: #64748b;
    }
    
    /* Customer Info */
    .info-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 0.875rem;
        color: #1a1a2e;
        margin-bottom: 0.75rem;
        font-weight: 500;
    }
    
    /* Status Buttons - Minimalist */
    .status-btn {
        width: 100%;
        padding: 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: none;
    }
    
    .status-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-confirm { background: #1a1a2e; color: white; }
    .btn-confirm:hover { background: #16213e; }
    
    .btn-processing { background: #3b82f6; color: white; }
    .btn-processing:hover { background: #2563eb; }
    
    .btn-ready { background: #10b981; color: white; }
    .btn-ready:hover { background: #059669; }
    
    .btn-delivery { background: #1a1a2e; color: white; }
    .btn-delivery:hover { background: #16213e; }
    
    /* Alert Styles */
    .alert-custom {
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .alert-info-custom {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #1e40af;
    }
    
    .alert-success-custom {
        background: #ecfdf5;
        border: 1px solid #d1fae5;
        color: #065f46;
    }
    
    /* Totals */
    .totals-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 1rem;
    }
    
    .totals-label {
        font-size: 0.8rem;
        color: #64748b;
    }
    
    .totals-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .totals-total {
        border-top: 1px solid #eef2f6;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
    }
    
    .totals-total .totals-label {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1a1a2e;
    }
    
    .totals-total .totals-value {
        font-weight: 700;
        font-size: 0.9rem;
        color: #e74c3c;
    }
    
    /* Modal Body Scroll */
    .modal-body-custom {
        max-height: 85vh;
        overflow-y: auto;
        padding: 0;
    }
    
    .modal-body-custom::-webkit-scrollbar {
        width: 6px;
    }
    
    .modal-body-custom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .modal-body-custom::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .product-image {
            width: 40px;
            height: 40px;
        }
        
        .order-items-table th,
        .order-items-table td {
            padding: 0.5rem;
        }
    }
</style>

<div class="modal-body-custom">
    <div style="padding: 1.5rem;">
        <!-- Header -->
        <div class="modal-header-custom" style="padding: 0 0 1rem 0;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="order-number">Order #{{ $order->order_number }}</h5>
                    <p class="order-date">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
        </div>

        <div class="row g-3">
            <!-- LEFT COLUMN - Order Items & Customer Information -->
            <div class="col-md-7">
                <!-- Order Items Card -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-receipt"></i> Order Items</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table order-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center" style="width: 60px">Qty</th>
                                    <th class="text-end" style="width: 90px">Price</th>
                                    <th class="text-end" style="width: 90px">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @php
                                                $product = $item->product;
                                                $imageUrl = null;
                                                if ($product && $product->image) {
                                                    if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                                        $imageUrl = $product->image;
                                                    } elseif (Storage::disk('public')->exists($product->image)) {
                                                        $imageUrl = Storage::url($product->image);
                                                    }
                                                }
                                            @endphp
                                            @if($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-image">
                                            @else
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-box-seam text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="product-name">{{ $item->product->name }}</div>
                                                @if($item->flavor)
                                                    <div class="product-flavor">Flavor: {{ $item->flavor->name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 bg-light">
                        <div class="totals-row">
                            <span class="totals-label">Subtotal</span>
                            <span class="totals-value">₱{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="totals-row">
                            <span class="totals-label">Tax (12%)</span>
                            <span class="totals-value">₱{{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="totals-row totals-total">
                            <span class="totals-label">Total</span>
                            <span class="totals-value">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information Card -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-person"></i> Customer Information</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-6">
                                <p class="info-label">Name</p>
                                <p class="info-value">{{ $order->customer_name }}</p>
                                <p class="info-label">Phone</p>
                                <p class="info-value">{{ $order->customer_phone }}</p>
                            </div>
                            <div class="col-6">
                                <p class="info-label">Email</p>
                                <p class="info-value">{{ $order->customer_email ?? 'N/A' }}</p>
                                @if ($order->delivery_type == 'delivery')
                                    <p class="info-label">Address</p>
                                    <p class="info-value">{{ $order->delivery_address }}</p>
                                @endif
                            </div>
                        </div>
                        @if ($order->delivery_type == 'delivery')
                            <div class="mt-2">
                                <p class="info-label">City/Barangay</p>
                                <p class="info-value">{{ $order->city ?? 'N/A' }}, {{ $order->barangay ?? 'N/A' }}</p>
                                @if ($order->landmark)
                                    <p class="info-label">Landmark</p>
                                    <p class="info-value">{{ $order->landmark }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - Update Order Status -->
            <div class="col-md-5">
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-arrow-repeat"></i> Update Order Status</h6>
                    </div>
                    <div class="card-body p-3">
                        @if ($order->order_status == 'pending')
                            <button class="status-btn btn-confirm" onclick="handleStatus('confirm', {{ $order->id }})">
                                <i class="bi bi-check-circle me-2"></i> Confirm Order
                            </button>
                        @elseif($order->order_status == 'confirmed')
                            <button class="status-btn btn-processing" onclick="handleStatus('processing', {{ $order->id }})">
                                <i class="bi bi-gear me-2"></i> Mark as Packing
                            </button>
                        @elseif($order->order_status == 'processing')
                            <button class="status-btn btn-ready" onclick="handleStatus('ready', {{ $order->id }})">
                                <i class="bi bi-box-seam me-2"></i> Mark as Ready
                            </button>
                        @elseif($order->order_status == 'ready')
                            @if ($order->delivery_type == 'delivery')
                                <button class="status-btn btn-delivery" onclick="handleStatus('ready', {{ $order->id }})">
                                    <i class="bi bi-truck me-2"></i> Start Delivery
                                </button>
                            @else
                                <div class="alert-custom alert-success-custom text-center">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong>Ready for Pickup</strong><br>
                                    <small class="text-muted">Order is ready for pickup by customer</small>
                                </div>
                            @endif
                        @elseif($order->order_status == 'out_for_delivery')
                            <div class="alert-custom alert-info-custom mb-3">
                                <i class="bi bi-info-circle me-2"></i> Delivery is in progress
                            </div>
                            @if ($order->delivery)
                                <button class="status-btn btn-delivery" onclick="openDeliveryModal({{ $order->delivery->id }})">
                                    <i class="bi bi-truck me-2"></i> Manage Delivery
                                </button>
                            @endif
                        @elseif($order->order_status == 'delivered')
                            <div class="alert-custom alert-success-custom text-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Order Completed</strong><br>
                                <small class="text-muted">Delivered on {{ $order->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        @endif

                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function handleStatus(action, orderId) {
        const resultDiv = document.getElementById('result');
        const button = event.target.closest('button');
        const originalText = button.innerHTML;

        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
        resultDiv.innerHTML = '<div class="alert alert-info">Processing...</div>';

        let url = '';
        let method = 'POST';

        switch (action) {
            case 'confirm':
                url = `/driver/online-orders/${orderId}/confirm`;
                break;
            case 'processing':
                url = `/driver/online-orders/${orderId}/processing`;
                break;
            case 'ready':
                url = `/driver/online-orders/${orderId}/ready`;
                break;
            default:
                resultDiv.innerHTML = '<div class="alert alert-danger">Invalid action</div>';
                button.disabled = false;
                button.innerHTML = originalText;
                return;
        }

        fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Error occurred') + '</div>';
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
                button.disabled = false;
                button.innerHTML = originalText;
            });
    }

    function openDeliveryModal(deliveryId) {
        window.location.href = `/driver/deliveries/${deliveryId}`;
    }

    function closeModal() {
        const modal = document.getElementById('customModal');
        if (modal) {
            modal.style.display = 'none';
            const content = document.getElementById('customModalContent');
            if (content) content.innerHTML = '';
        }
        const bootstrapModal = document.getElementById('orderModal');
        if (bootstrapModal) {
            const modal = bootstrap.Modal.getInstance(bootstrapModal);
            if (modal) modal.hide();
        }
        const modalElement = document.querySelector('.modal.show');
        if (modalElement) {
            modalElement.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
        }
    }
</script>
@endsection