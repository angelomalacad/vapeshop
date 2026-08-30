<style>
    /* Modal Styles */
    .modal-body-custom {
        padding: 0;
        max-height: 85vh;
        overflow-y: auto;
    }

    .modal-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #eef2f6;
        background: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }

    .order-date {
        font-size: 0.75rem;
        color: #64748b;
        margin: 0;
    }

    /* Cards */
    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 1rem;
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
        margin: 0;
    }

    /* Product Image */
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8f9fa;
    }

    /* Table */
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
        margin: 0;
    }

    .product-flavor {
        font-size: 0.7rem;
        color: #64748b;
    }

    /* Info Labels */
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

    /* Status Buttons */
    .status-btn {
        width: 100%;
        padding: 0.75rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: none;
        margin-bottom: 0.5rem;
        cursor: pointer;
    }

    .status-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-delivery {
        background: #1a1a2e;
        color: white;
    }

    .btn-delivery:hover {
        background: #16213e;
    }

    /* ✅ FIXED: Close Button - Absolute Position in Upper Right Corner */
    .btn-close-modal {
        position: absolute;
        top: 20px;
        right: 20px;
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer !important;
        color: #666;
        padding: 0;
        z-index: 99999 !important;
    }

    .btn-close-modal:hover {
        color: #333;
    }

    /* Alerts */
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

    .alert-warning-custom {
        background: #fef3c7;
        border: 1px solid #fde68a;
        color: #92400e;
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

    /* Badges */
    .badge-ready {
        background: #d1fae5;
        color: #059669;
    }

    .badge-out_for_delivery {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-picked_up {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-in_transit {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .badge-delivered {
        background: #d1fae5;
        color: #059669;
    }

    .badge-delivery_failed {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-cancelled {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge {
        padding: 0.35rem 0.65rem;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.7rem;
    }

    /* Branch Badge */
    .branch-badge {
        padding: 0.25rem 0.65rem;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.7rem;
        background: #e0f2fe;
        color: #0369a1;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
</style>

<div class="modal-body-custom">
    <div style="padding: 1.5rem; position: relative;">
        <!-- ✅ FIXED: X Button in Upper Right Corner -->
        <button type="button" class="btn-close-modal" onclick="window.closeModal()">&times;</button>

        <div class="modal-header-custom" style="padding: 0 0 1rem 0;">
            <div>
                <h5 class="order-number">Order #{{ $order->order_number }}</h5>
                <p class="order-date">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="row g-3">
            <!-- LEFT COLUMN -->
            <div class="col-md-7">
                <!-- Order Items (LEFT - FIRST) -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-receipt"></i> Order Items</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table order-items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
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
                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                                        class="product-image">
                                                @else
                                                    <div
                                                        class="product-image bg-light d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-box-seam text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="product-name">{{ $item->product->name }}</div>
                                                    @if ($item->flavor)
                                                        <div class="product-flavor">Flavor: {{ $item->flavor->name }}
                                                        </div>
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
                        <div class="totals-row totals-total">
                            <span class="totals-label">Total</span>
                            <span class="totals-value">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Branch Information (LEFT - BELOW ORDER ITEMS) -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-shop"></i> Branch Information</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-12">
                                <p class="info-label">Branch</p>
                                <p class="info-value">
                                    @if($order->branch)
                                        <span class="branch-badge">
                                            <i class="bi bi-shop me-1"></i>{{ $order->branch->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                            @if($order->branch && $order->branch->address)
                                <div class="col-12">
                                    <p class="info-label">Branch Address</p>
                                    <p class="info-value">{{ $order->branch->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer Information (LEFT - THIRD) -->
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
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-5">
                <!-- Update Status (RIGHT - FIRST) -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-arrow-repeat"></i> Update Status</h6>
                    </div>
                    <div class="card-body p-3">
                        @php
                            $statusClass = match ($order->order_status) {
                                'ready' => 'badge-ready',
                                'out_for_delivery' => 'badge-out_for_delivery',
                                'picked_up' => 'badge-picked_up',
                                'in_transit' => 'badge-in_transit',
                                'delivered' => 'badge-delivered',
                                'delivery_failed' => 'badge-delivery_failed',
                                'cancelled' => 'badge-cancelled',
                                default => 'badge-secondary',
                            };
                            $displayStatus = ucfirst(str_replace('_', ' ', $order->order_status));
                            if ($order->order_status == 'delivery_failed') {
                                $displayStatus = 'Delivery Failed';
                            }
                        @endphp

                        <div class="mb-3">
                            <p class="info-label">Current Status</p>
                            <span class="badge {{ $statusClass }}">{{ $displayStatus }}</span>
                        </div>

                        @if ($order->order_status == 'ready')
                            <button type="button" class="status-btn btn-delivery"
                                onclick="handleStatus('start-delivery', {{ $order->id }})">
                                <i class="bi bi-truck me-2"></i> Start Delivery
                            </button>
                        @elseif(in_array($order->order_status, ['out_for_delivery', 'picked_up', 'in_transit']))
                            <div class="alert-custom alert-info-custom mb-3">
                                <i class="bi bi-truck me-2"></i>
                                <strong>Delivery in Progress</strong><br>
                                <small class="text-muted">
                                    Status: {{ $displayStatus }}<br>
                                    Update delivery status below.
                                </small>
                            </div>
                            @if ($order->delivery)
                                <button type="button" class="status-btn btn-delivery"
                                    onclick="window.openDeliveryModal({{ $order->delivery->id }})">
                                    <i class="bi bi-truck me-2"></i> Manage Delivery
                                </button>
                            @endif
                        @elseif($order->order_status == 'delivered')
                            <div class="alert-custom alert-success-custom text-center">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                <strong>Order Completed</strong><br>
                                <small class="text-muted">Delivered on
                                    {{ $order->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        @elseif($order->order_status == 'delivery_failed')
                            <div class="alert-custom alert-warning-custom text-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Delivery Failed</strong>
                            </div>
                        @endif

                        <!-- ✅ FIXED: Delivery Date with Save Button -->
                        <div class="mb-3">
                            <label class="info-label">Delivery Date</label>
                            <div class="d-flex gap-2">
                                <input type="date" name="delivery_date" id="delivery_date" class="form-control"
                                    value="{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : '' }}"
                                    min="{{ date('Y-m-d') }}">
                                <button type="button" class="btn btn-primary btn-sm" id="saveDeliveryDateBtn"
                                    onclick="saveDeliveryDate()">
                                    Save
                                </button>
                            </div>
                        </div>

                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ✅ GLOBAL FUNCTIONS - Available everywhere
    window.closeModal = function() {
        const modal = document.getElementById('customModal');
        if (modal) {
            modal.style.display = 'none';
        }
        const content = document.getElementById('customModalContent');
        if (content) {
            content.innerHTML = '';
        }
    };

    // ✅ CHECK FOR STORED SUCCESS MESSAGE ON PAGE LOAD
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = sessionStorage.getItem('delivery_success_message');
        if (successMessage) {
            if (typeof showNotification === 'function') {
                showNotification(successMessage, 'success');
            }
            sessionStorage.removeItem('delivery_success_message');
        }
    });

    window.handleStatus = function(action, orderId) {
        const resultDiv = document.getElementById('result');
        const button = event.target.closest('button');

        if (!button) return;

        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';

        let url = '';
        switch (action) {
            case 'start-delivery':
                url = `/driver/online-orders/${orderId}/start-delivery`;
                break;
            default:
                return;
        }

        fetch(url, {
                method: 'POST',
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
                    sessionStorage.setItem('delivery_success_message', data.message || 'Status updated successfully!');
                    window.location.reload();
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification(data.message || 'Error occurred', 'error');
                    } else {
                        alert(data.message || 'Error occurred');
                    }
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showNotification === 'function') {
                    showNotification('Network error. Please try again.', 'error');
                } else {
                    alert('Network error. Please try again.');
                }
                button.disabled = false;
                button.innerHTML = originalText;
            });
    };

    // ✅ FIXED: Delivery Date Save Function - Shows message inside modal then refreshes
    function saveDeliveryDate() {
        const orderId = {{ $order->id }};
        const deliveryDate = document.getElementById('delivery_date').value;
        const resultDiv = document.getElementById('result');
        
        if (!deliveryDate) {
            if (resultDiv) {
                resultDiv.innerHTML = '<div class="alert alert-danger">Please select a delivery date first.</div>';
            }
            return;
        }

        const saveBtn = document.getElementById('saveDeliveryDateBtn');
        const originalBtnText = 'Save';
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

        if (resultDiv) {
            resultDiv.innerHTML = '<div class="alert alert-info">Saving delivery date...</div>';
        }

        // ✅ USE ABSOLUTE URL TO ENSURE IT WORKS
        fetch(`{{ url('/driver/online-orders') }}/${orderId}/delivery-date`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ delivery_date: deliveryDate })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalBtnText;

            if (data.success) {
                // ✅ SHOW SUCCESS MESSAGE INSIDE THE MODAL
                if (resultDiv) {
                    resultDiv.innerHTML = '<div class="alert alert-success">' + (data.message || 'Delivery date saved successfully!') + '</div>';
                }
                
                // ✅ REFRESH PAGE AFTER 1.5 SECONDS TO CLEAR THE MESSAGE
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // ✅ SHOW ERROR MESSAGE INSIDE THE MODAL
                if (resultDiv) {
                    resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Error saving delivery date') + '</div>';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalBtnText;
            
            // ✅ SHOW NETWORK ERROR INSIDE THE MODAL
            if (resultDiv) {
                resultDiv.innerHTML = '<div class="alert alert-danger">Network error. Please try again.</div>';
            }
        });
    }
</script>