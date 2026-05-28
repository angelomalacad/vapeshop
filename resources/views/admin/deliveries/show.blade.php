<div style="padding: 20px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-truck"></i> Delivery #{{ $delivery->tracking_number }}</h5>
        <button type="button" class="btn-close" onclick="closeModal()"></button>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0">Delivery Information</h6>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Order #:</strong> {{ $delivery->order->order_number ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Status:</strong> 
                        <span class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : 'info') }}">
                            {{ ucfirst($delivery->status) }}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Assigned:</strong> {{ $delivery->assigned_at ? $delivery->assigned_at->format('M d, Y h:i A') : 'N/A' }}</p>
                    @if($delivery->picked_up_at)
                        <p class="mb-1"><strong>Picked Up:</strong> {{ $delivery->picked_up_at->format('M d, Y h:i A') }}</p>
                    @endif
                    @if($delivery->delivered_at)
                        <p class="mb-1"><strong>Delivered:</strong> {{ $delivery->delivered_at->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
    <div class="card-header bg-info text-white py-2">
        <h6 class="mb-0">Customer Details</h6>
    </div>
    <div class="card-body">
        <p class="mb-1"><strong>Name:</strong> {{ $delivery->recipient_name }}</p>
        <p class="mb-1"><strong>Phone:</strong> {{ $delivery->recipient_phone }}</p>
        <p class="mb-1"><strong>Address:</strong> {{ $delivery->delivery_address }}</p>
        <p class="mb-1"><strong>City/Barangay:</strong> {{ $delivery->order->city ?? 'N/A' }}, {{ $delivery->order->barangay ?? 'N/A' }}</p>
        @if($delivery->order->landmark)
            <p class="mb-1"><strong>Landmark:</strong> {{ $delivery->order->landmark }}</p>
        @endif
    </div>
</div>

        <div class="col-md-6">
            @if($delivery->status == 'delivered')
                <!-- COMPLETED DELIVERY VIEW - Show only proof images, no upload -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white py-2">
                        <h6 class="mb-0"><i class="bi bi-check-circle"></i> Delivery Completed</h6>
                    </div>
                    <div class="card-body">
                        @if($delivery->delivery_proof || $delivery->payment_proof)
                            <div class="row">
                                @if($delivery->delivery_proof)
                                <div class="col-md-6 mb-2 text-center">
                                    <strong>Delivery Proof:</strong>
                                    <div class="mt-1">
                                        <img src="{{ Storage::url($delivery->delivery_proof) }}" 
                                             class="img-thumbnail" 
                                             style="width: 100%; height: 120px; object-fit: cover; cursor: pointer;" 
                                             onclick="showImagePreview('{{ Storage::url($delivery->delivery_proof) }}', 'Delivery Proof')">
                                    </div>
                                    <a href="{{ Storage::url($delivery->delivery_proof) }}" download class="btn btn-sm btn-outline-primary mt-1">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                                @endif
                                @if($delivery->payment_proof)
                                <div class="col-md-6 mb-2 text-center">
                                    <strong>Payment Proof:</strong>
                                    <div class="mt-1">
                                        <img src="{{ Storage::url($delivery->payment_proof) }}" 
                                             class="img-thumbnail" 
                                             style="width: 100%; height: 120px; object-fit: cover; cursor: pointer;" 
                                             onclick="showImagePreview('{{ Storage::url($delivery->payment_proof) }}', 'Payment Proof')">
                                    </div>
                                    <a href="{{ Storage::url($delivery->payment_proof) }}" download class="btn btn-sm btn-outline-success mt-1">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                </div>
                                @endif
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">No proof images available</p>
                        @endif
                        
                        @if($delivery->delivered_at)
                            <hr>
                            <p class="mb-0 text-center text-success">
                                <i class="bi bi-check-circle-fill"></i> Delivered on {{ $delivery->delivered_at->format('M d, Y h:i A') }}
                            </p>
                        @endif
                    </div>
                </div>
            @else
                <!-- ACTIVE DELIVERY - Show update form with required proof uploads -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white py-2">
                        <h6 class="mb-0">Update Status</h6>
                    </div>
                    <div class="card-body">
                        <form id="updateStatusForm" action="{{ route('driver.delivery.update', $delivery) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Status</label>
                                <select name="status" id="statusSelect" class="form-select form-select-sm" required>
                                    <option value="picked_up" {{ $delivery->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                    <option value="in_transit" {{ $delivery->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="delivered" {{ $delivery->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="failed" {{ $delivery->status == 'failed' ? 'selected' : '' }}>Failed Delivery</option>
                                </select>
                            </div>

                            <!-- Delivery Proof Upload -->
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Delivery Proof Photo <span class="text-danger">*</span></label>
                                <input type="file" name="delivery_proof" id="deliveryProof" class="form-control form-control-sm" accept="image/*">
                                <small class="text-muted">Required when marking as delivered</small>
                                <div id="deliveryProofPreview" class="mt-2" style="display: none;">
                                    <img id="deliveryProofImg" src="#" style="max-width: 100%; max-height: 100px; border-radius: 5px;">
                                </div>
                            </div>

                            <!-- Payment Proof Upload -->
                            <div class="mb-2">
                                <label class="form-label fw-bold small">Payment Proof Photo <span class="text-danger">*</span></label>
                                <input type="file" name="payment_proof" id="paymentProof" class="form-control form-control-sm" accept="image/*">
                                <small class="text-muted">Required when marking as delivered</small>
                                <div id="paymentProofPreview" class="mt-2" style="display: none;">
                                    <img id="paymentProofImg" src="#" style="max-width: 100%; max-height: 100px; border-radius: 5px;">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold small">Notes</label>
                                <textarea name="notes" class="form-control form-control-sm" rows="2">{{ $delivery->driver_notes }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100" id="submitBtn">
                                <i class="bi bi-check-circle"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 8px; width: 90%; max-width: 500px; overflow: hidden;">
        <div style="padding: 10px 15px; background: #f8f9fa; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
            <h6 class="mb-0" id="previewTitle">Image Preview</h6>
            <button type="button" class="btn-close" onclick="closeImagePreview()"></button>
        </div>
        <div style="padding: 20px; text-align: center;">
            <img id="previewImage" src="" style="max-width: 100%; max-height: 400px; border-radius: 5px;">
        </div>
        <div style="padding: 10px 15px; background: #f8f9fa; border-top: 1px solid #ddd; text-align: right;">
            <button type="button" class="btn btn-sm btn-secondary" onclick="closeImagePreview()">Close</button>
            <a id="downloadLink" href="#" download class="btn btn-sm btn-primary">Download</a>
        </div>
    </div>
</div>

<script>
    const statusSelect = document.getElementById('statusSelect');
    const deliveryProofInput = document.getElementById('deliveryProof');
    const paymentProofInput = document.getElementById('paymentProof');
    const deliveryProofPreview = document.getElementById('deliveryProofPreview');
    const paymentProofPreview = document.getElementById('paymentProofPreview');
    const deliveryProofImg = document.getElementById('deliveryProofImg');
    const paymentProofImg = document.getElementById('paymentProofImg');
    const submitBtn = document.getElementById('submitBtn');
    
    // Preview delivery proof before upload
    if (deliveryProofInput) {
        deliveryProofInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    deliveryProofImg.src = event.target.result;
                    deliveryProofPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                deliveryProofPreview.style.display = 'none';
                deliveryProofImg.src = '';
            }
        });
    }
    
    // Preview payment proof before upload
    if (paymentProofInput) {
        paymentProofInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    paymentProofImg.src = event.target.result;
                    paymentProofPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                paymentProofPreview.style.display = 'none';
                paymentProofImg.src = '';
            }
        });
    }
    
    // Make proof fields required when status is delivered
    function toggleRequired() {
        const isDelivered = statusSelect && statusSelect.value === 'delivered';
        if (deliveryProofInput) {
            deliveryProofInput.required = isDelivered;
        }
        if (paymentProofInput) {
            paymentProofInput.required = isDelivered;
        }
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', toggleRequired);
        toggleRequired();
    }
    
    // Form validation before submit
    if (document.getElementById('updateStatusForm')) {
        document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
            const isDelivered = statusSelect && statusSelect.value === 'delivered';
            
            if (isDelivered) {
                if (!deliveryProofInput.files.length) {
                    e.preventDefault();
                    alert('Please upload a delivery proof photo');
                    deliveryProofInput.focus();
                    return false;
                }
                if (!paymentProofInput.files.length) {
                    e.preventDefault();
                    alert('Please upload a payment proof photo');
                    paymentProofInput.focus();
                    return false;
                }
            }
            
            // Show loading
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
            }
        });
    }
    
    function showImagePreview(imageUrl, title) {
        const modal = document.getElementById('imagePreviewModal');
        const previewImage = document.getElementById('previewImage');
        const previewTitle = document.getElementById('previewTitle');
        const downloadLink = document.getElementById('downloadLink');
        
        previewImage.src = imageUrl;
        previewTitle.textContent = title;
        downloadLink.href = imageUrl;
        modal.style.display = 'flex';
    }
    
    function closeImagePreview() {
        document.getElementById('imagePreviewModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    document.getElementById('imagePreviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImagePreview();
        }
    });
    
    function closeModal() {
        const modalElement = document.getElementById('deliveryModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
        const container = document.getElementById('modalContainer');
        if (container) {
            container.innerHTML = '';
        }
    }
</script>