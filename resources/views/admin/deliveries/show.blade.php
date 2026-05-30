<style>
    .delivery-modal-container {
        padding: 1.5rem;
        max-height: 85vh;
        overflow-y: auto;
        background: #f8f9fa;
    }
    
    .delivery-modal-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .delivery-modal-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .delivery-modal-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .modal-header-minimal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eef2f6;
    }
    
    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .modal-title i {
        color: #3b82f6;
        margin-right: 0.5rem;
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    
    .card-header-minimal {
        padding: 0.875rem 1.25rem;
        background: white;
        border-bottom: 1px solid #eef2f6;
    }
    
    .card-header-minimal h6 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .card-header-minimal h6 i {
        margin-right: 0.5rem;
        color: #3b82f6;
    }
    
    .card-body-minimal {
        padding: 1rem 1.25rem;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
    }
    
    .info-label {
        width: 100px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }
    
    .info-value {
        flex: 1;
        font-size: 0.8rem;
        color: #1a1a2e;
        font-weight: 500;
    }
    
    .badge-delivered {
        background: #d1fae5;
        color: #059669;
    }
    
    .badge-in_transit {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-picked_up {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .badge-assigned {
        background: #f1f5f9;
        color: #475569;
    }
    
    .timeline-container {
        padding: 0.5rem 0;
    }
    
    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-right: 1rem;
        z-index: 1;
        background: white;
        border: 2px solid;
    }
    
    .timeline-icon.completed {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }
    
    .timeline-icon.pending {
        background: white;
        border-color: #cbd5e1;
        color: #94a3b8;
    }
    
    .timeline-icon i {
        font-size: 1rem;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .timeline-title {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1a1a2e;
        margin-bottom: 0.25rem;
    }
    
    .timeline-date {
        font-size: 0.7rem;
        color: #64748b;
    }
    
    .timeline-details {
        font-size: 0.75rem;
        color: #475569;
        margin-top: 0.25rem;
    }
    
    .timeline-line {
        position: absolute;
        left: 20px;
        top: 40px;
        width: 2px;
        height: calc(100% - 20px);
        background: #e2e8f0;
    }
    
    .timeline-item:last-child .timeline-line {
        display: none;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 10px;
        background: #f8f9fa;
    }
    
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
        padding: 0.75rem;
        border-bottom: 1px solid #eef2f6;
    }
    
    .order-items-table td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #eef2f6;
        font-size: 0.8rem;
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
    
    .proof-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .proof-image:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-download {
        font-size: 0.7rem;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
    }
    
    .alert-minimal {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .alert-info-minimal {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
    }
    
    .image-preview-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }
    
    .image-preview-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        overflow: hidden;
    }
    
    .image-preview-header {
        padding: 1rem 1.25rem;
        background: white;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .image-preview-body {
        padding: 1.5rem;
        text-align: center;
    }
    
    .image-preview-body img {
        max-width: 100%;
        max-height: 400px;
        border-radius: 12px;
    }
    
    .image-preview-footer {
        padding: 1rem 1.25rem;
        background: #f8f9fa;
        border-top: 1px solid #eef2f6;
        text-align: right;
    }
    
    @media (max-width: 768px) {
        .delivery-modal-container {
            padding: 1rem;
        }
        
        .info-label {
            width: 80px;
        }
        
        .timeline-icon {
            width: 32px;
            height: 32px;
        }
        
        .timeline-line {
            left: 16px;
        }
        
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

<div class="delivery-modal-container">
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-truck"></i> Delivery #{{ $delivery->tracking_number }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    @if($delivery->order && $delivery->order->items && $delivery->order->items->count() > 0)
    <div class="info-card mb-3">
        <div class="card-header-minimal">
            <h6><i class="bi bi-receipt"></i> Order Items</h6>
        </div>
        <div class="card-body-minimal p-0">
            <div class="table-responsive">
                <table class="table order-items-table">
                    <thead>
                        <tr>
                            <th style="width: 60px">Image</th>
                            <th>Product</th>
                            <th class="text-center" style="width: 70px">Qty</th>
                            <th class="text-end" style="width: 100px">Price</th>
                            <th class="text-end" style="width: 100px">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($delivery->order->items as $item)
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
                        <tr>
                            <td>
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-seam text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="product-name">{{ $item->product->name }}</div>
                                @if($item->flavor)
                                    <div class="product-flavor">Flavor: {{ $item->flavor->name }}</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                            <td class="text-end">₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total:</td>
                            <td class="text-end fw-bold text-danger">
                                ₱{{ number_format($delivery->order->total_amount, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-info-circle"></i> Delivery Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="info-row">
                        <div class="info-label">Order #</div>
                        <div class="info-value">{{ $delivery->order->order_number ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge badge-{{ $delivery->status }}">
                                <i class="bi bi-{{ $delivery->status == 'delivered' ? 'check-circle-fill' : ($delivery->status == 'in_transit' ? 'truck' : 'clock') }} me-1"></i>
                                {{ ucfirst($delivery->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Assigned</div>
                        <div class="info-value">{{ $delivery->assigned_at ? $delivery->assigned_at->format('M d, Y h:i A') : 'N/A' }}</div>
                    </div>
                    @if($delivery->picked_up_at)
                    <div class="info-row">
                        <div class="info-label">Picked Up</div>
                        <div class="info-value">{{ $delivery->picked_up_at->format('M d, Y h:i A') }}</div>
                    </div>
                    @endif
                    @if($delivery->delivered_at)
                    <div class="info-row">
                        <div class="info-label">Delivered</div>
                        <div class="info-value">{{ $delivery->delivered_at->format('M d, Y h:i A') }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-person"></i> Customer Details</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="info-row">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $delivery->recipient_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $delivery->recipient_phone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $delivery->delivery_address }}</div>
                    </div>
                    @if($delivery->order)
                    <div class="info-row">
                        <div class="info-label">City/Barangay</div>
                        <div class="info-value">{{ $delivery->order->city ?? 'N/A' }}, {{ $delivery->order->barangay ?? 'N/A' }}</div>
                    </div>
                    @if($delivery->order->landmark)
                    <div class="info-row">
                        <div class="info-label">Landmark</div>
                        <div class="info-value">{{ $delivery->order->landmark }}</div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-clock-history"></i> Delivery Logs</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="timeline-container">
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $delivery->assigned_at ? 'completed' : 'pending' }}">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Assigned to Driver</div>
                                @if($delivery->assigned_at)
                                    <div class="timeline-date">{{ $delivery->assigned_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Pending</div>
                                @endif
                                @if($delivery->driver)
                                    <div class="timeline-details">
                                        <i class="bi bi-person-badge me-1"></i> Driver: {{ $delivery->driver->name }}
                                    </div>
                                @endif
                            </div>
                            <div class="timeline-line"></div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-icon {{ $delivery->picked_up_at ? 'completed' : 'pending' }}">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Picked Up</div>
                                @if($delivery->picked_up_at)
                                    <div class="timeline-date">{{ $delivery->picked_up_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Waiting for driver to pick up</div>
                                @endif
                            </div>
                            <div class="timeline-line"></div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-icon {{ ($delivery->status == 'in_transit' || $delivery->delivered_at) ? 'completed' : 'pending' }}">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">In Transit</div>
                                @if($delivery->status == 'in_transit')
                                    <div class="timeline-date">{{ $delivery->updated_at->format('F d, Y h:i A') }}</div>
                                    <div class="timeline-details">Driver is on the way to customer</div>
                                @elseif($delivery->delivered_at)
                                    <div class="timeline-date">{{ $delivery->picked_up_at ? $delivery->picked_up_at->format('F d, Y h:i A') : 'N/A' }}</div>
                                @else
                                    <div class="timeline-date text-muted">Not yet in transit</div>
                                @endif
                            </div>
                            <div class="timeline-line"></div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-icon {{ $delivery->delivered_at ? 'completed' : 'pending' }}">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Delivered</div>
                                @if($delivery->delivered_at)
                                    <div class="timeline-date">{{ $delivery->delivered_at->format('F d, Y h:i A') }}</div>
                                @else
                                    <div class="timeline-date text-muted">Not yet delivered</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($delivery->status == 'delivered' && ($delivery->delivery_proof || $delivery->payment_proof))
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-image"></i> Proof of Delivery</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="row g-2">
                        @if($delivery->delivery_proof)
                        <div class="col-md-6">
                            <strong class="small text-muted">Delivery Proof</strong>
                            <div class="mt-1">
                                <img src="{{ Storage::url($delivery->delivery_proof) }}" 
                                     class="proof-image" 
                                     onclick="showImagePreview('{{ Storage::url($delivery->delivery_proof) }}', 'Delivery Proof')">
                            </div>
                            <div class="mt-2 text-center">
                                <a href="{{ Storage::url($delivery->delivery_proof) }}" download class="btn btn-outline-primary btn-download">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($delivery->payment_proof)
                        <div class="col-md-6">
                            <strong class="small text-muted">Payment Proof</strong>
                            <div class="mt-1">
                                <img src="{{ Storage::url($delivery->payment_proof) }}" 
                                     class="proof-image" 
                                     onclick="showImagePreview('{{ Storage::url($delivery->payment_proof) }}', 'Payment Proof')">
                            </div>
                            <div class="mt-2 text-center">
                                <a href="{{ Storage::url($delivery->payment_proof) }}" download class="btn btn-outline-success btn-download">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if($delivery->status != 'delivered')
            <div class="alert-minimal alert-info-minimal mt-3">
                <i class="bi bi-info-circle me-2"></i> 
                This delivery is currently <strong>{{ ucfirst($delivery->status) }}</strong>. The driver is handling the delivery process. You can monitor the progress here.
            </div>
            @endif
        </div>
    </div>
</div>

<div id="imagePreviewModal" class="image-preview-modal">
    <div class="image-preview-content">
        <div class="image-preview-header">
            <h6 class="mb-0" id="previewTitle">Image Preview</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="image-preview-body">
            <img id="previewImage" src="">
        </div>
        <div class="image-preview-footer">
            <button type="button" class="btn btn-sm btn-secondary me-2" onclick="closeImagePreview()">Close</button>
            <a id="downloadLink" href="#" download class="btn btn-sm btn-primary">Download</a>
        </div>
    </div>
</div>

<script>
    function showImagePreview(imageUrl, title) {
        document.getElementById('previewImage').src = imageUrl;
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('downloadLink').href = imageUrl;
        document.getElementById('imagePreviewModal').style.display = 'flex';
    }
    
    function closeImagePreview() {
        document.getElementById('imagePreviewModal').style.display = 'none';
    }
    
    document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImagePreview();
        }
    });
</script>