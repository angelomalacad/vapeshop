@extends('layouts.modal')

@section('title', 'Order #' . $order->order_number)

@section('content')
    <div style="padding: 20px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                <p class="text-muted small mb-0">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <button type="button" class="btn-close" onclick="closeModal()"></button>
        </div>
        <hr>

        <div class="row">
            <!-- LEFT COLUMN - Order Items & Customer Information -->
            <div class="col-md-7">
                <!-- Order Items Card -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white py-2">
                        <h6 class="mb-0"><i class="bi bi-receipt me-1"></i> Order Items</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center" style="width: 60px">Qty</th>
                                        <th class="text-end" style="width: 100px">Price</th>
                                        <th class="text-end" style="width: 100px">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td><strong>{{ $item->product->name }}</strong></td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                            <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end fw-bold">₱{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Tax (12%):</td>
                                        <td class="text-end fw-bold">₱{{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td colspan="3" class="text-end fw-bold fs-6">Total:</td>
                                        <td class="text-end fw-bold text-danger fs-6">
                                            ₱{{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Customer Information Card -->
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
                                @if ($order->delivery_type == 'delivery')
                                    <p class="mb-1 text-muted small">Address</p>
                                    <p class="mb-2">{{ $order->delivery_address }}</p>
                                    <p class="mb-1 text-muted small">City/Barangay</p>
                                    <p class="mb-2">{{ $order->city ?? 'N/A' }}, {{ $order->barangay ?? 'N/A' }}</p>
                                    @if ($order->landmark)
                                        <p class="mb-1 text-muted small">Landmark</p>
                                        <p class="mb-2">{{ $order->landmark }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - Update Order Status -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white py-2">
                        <h6 class="mb-0"><i class="bi bi-arrow-repeat me-1"></i> Update Order Status</h6>
                    </div>
                    <div class="card-body">
                        @if ($order->order_status == 'pending')
                            <button class="btn btn-success w-100 py-2"
                                onclick="handleStatus('confirm', {{ $order->id }})">
                                <i class="bi bi-check-circle me-1"></i> Confirm Order
                            </button>
                        @elseif($order->order_status == 'confirmed')
                            <button class="btn btn-primary w-100 py-2"
                                onclick="handleStatus('processing', {{ $order->id }})">
                                <i class="bi bi-gear me-1"></i> Mark as Processing
                            </button>
                        @elseif($order->order_status == 'processing')
                            <button class="btn btn-success w-100 py-2"
                                onclick="handleStatus('ready', {{ $order->id }})">
                                <i class="bi bi-box-seam me-1"></i> Mark as Ready
                            </button>
                        @elseif($order->order_status == 'ready')
                            @if ($order->delivery_type == 'delivery')
                                <button class="btn btn-primary w-100 py-2"
                                    onclick="handleStatus('ready', {{ $order->id }})">
                                    <i class="bi bi-truck me-1"></i> Start Delivery
                                </button>
                            @else
                                <div class="alert alert-success text-center py-3">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    <strong>Ready for Pickup</strong><br>
                                    <small>Order is ready for pickup by customer</small>
                                </div>
                            @endif
                        @elseif($order->order_status == 'out_for_delivery')
                            <div class="alert alert-info mb-3 py-2">
                                <i class="bi bi-info-circle me-1"></i> Delivery is in progress
                            </div>
                            @if ($order->delivery)
                                <button class="btn btn-primary w-100 py-2"
                                    onclick="openDeliveryModal({{ $order->delivery->id }})">
                                    <i class="bi bi-truck me-1"></i> Manage Delivery
                                </button>
                            @endif
                        @elseif($order->order_status == 'delivered')
                            <div class="alert alert-success text-center py-3">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                <strong>Order Completed</strong><br>
                                <small>Delivered on {{ $order->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        @endif

                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th,
        .table td {
            padding: 8px 10px;
            vertical-align: middle;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .btn {
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn:active {
            transform: translateY(0);
        }

        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
            padding: 0;
        }

        /* Custom scrollbar for modal */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>

    <script>
        function handleStatus(action, orderId) {
            const resultDiv = document.getElementById('result');
            const button = event.target.closest('button');
            const originalText = button.innerHTML;

            // Disable button and show loading
            button.disabled = true;
            button.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Processing...';
            resultDiv.innerHTML = '<div class="alert alert-info">Processing...</div>';

            let url = '';
            let method = 'POST';

            // Map actions to controller methods
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
                        // Reload after 1.5 seconds to show updated status
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Error occurred') +
                            '</div>';
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
            // Try different modal implementations
            const modal = document.getElementById('customModal');
            if (modal) {
                modal.style.display = 'none';
                const content = document.getElementById('customModalContent');
                if (content) content.innerHTML = '';
            }

            // Try Bootstrap modal if used
            const bootstrapModal = document.getElementById('orderModal');
            if (bootstrapModal) {
                const modal = bootstrap.Modal.getInstance(bootstrapModal);
                if (modal) modal.hide();
            }

            // Try simple modal close
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
