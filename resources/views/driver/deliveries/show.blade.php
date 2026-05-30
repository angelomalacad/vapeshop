<style>
    /* Modern Minimalist Modal Styles */
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
    
    /* Modal Header */
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
    
    /* Cards */
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
    
    /* Info Rows */
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
    
    .info-value .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.65rem;
    }
    
    /* Status Badges */
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
    
    /* Form Styles */
    .form-label-minimal {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    
    .form-control-minimal,
    .form-select-minimal {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        transition: all 0.2s;
    }
    
    .form-control-minimal:focus,
    .form-select-minimal:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.1);
        outline: none;
    }
    
    /* Proof Images */
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
    
    /* Buttons */
    .btn-update {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-update:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    
    .btn-download {
        font-size: 0.7rem;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
    }
    
    /* Alert Styles */
    .alert-minimal {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .alert-danger-minimal {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #dc2626;
    }
    
    .alert-success-minimal {
        background: #ecfdf5;
        border: 1px solid #d1fae5;
        color: #059669;
    }
    
    .alert-info-minimal {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
    }
    
    /* Image Preview Modal */
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .delivery-modal-container {
            padding: 1rem;
        }
        
        .info-label {
            width: 80px;
        }
        
        .card-header-minimal {
            padding: 0.75rem 1rem;
        }
        
        .card-body-minimal {
            padding: 0.75rem 1rem;
        }
    }
</style>

<div class="delivery-modal-container">
    <!-- Header -->
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-truck"></i> Delivery #{{ $delivery->tracking_number }}
        </h5>
        <button type="button" class="btn-close" onclick="closeModal()"></button>
    </div>

    <div class="row g-3">
        <!-- LEFT COLUMN -->
        <div class="col-md-6">
            <!-- Delivery Information Card -->
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

            <!-- Customer Details Card -->
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

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">
            @if($delivery->status == 'delivered')
                <!-- Completed Delivery View -->
                <div class="info-card">
                    <div class="card-header-minimal">
                        <h6><i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Delivery Completed</h6>
                    </div>
                    <div class="card-body-minimal">
                        @if($delivery->delivery_proof || $delivery->payment_proof)
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
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-image fs-1 text-muted"></i>
                                <p class="text-muted small mt-2">No proof images available</p>
                            </div>
                        @endif
                        
                        @if($delivery->delivered_at)
                        <div class="alert-minimal alert-success-minimal text-center mt-3">
                            <i class="bi bi-check-circle-fill me-1"></i> Delivered on {{ $delivery->delivered_at->format('M d, Y h:i A') }}
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Active Delivery - Update Status Form -->
                <div class="info-card">
                    <div class="card-header-minimal">
                        <h6><i class="bi bi-arrow-repeat"></i> Update Status</h6>
                    </div>
                    <div class="card-body-minimal">
                        <div id="errorAlert" class="alert-minimal alert-danger-minimal" style="display: none;"></div>
                        <div id="successAlert" class="alert-minimal alert-success-minimal" style="display: none;"></div>

                        <form id="updateStatusForm" action="{{ route('driver.delivery.update', $delivery) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label-minimal">Status</label>
                                <select name="status" id="statusSelect" class="form-select-minimal w-100" required>
                                    <option value="picked_up" {{ $delivery->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                    <option value="in_transit" {{ $delivery->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="delivered" {{ $delivery->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="failed" {{ $delivery->status == 'failed' ? 'selected' : '' }}>Failed Delivery</option>
                                </select>
                            </div>

                            <div class="mb-3" id="deliveryProofField">
                                <label class="form-label-minimal">Delivery Proof Photo <span class="text-danger" id="deliveryProofRequired">*</span></label>
                                <input type="file" name="delivery_proof" id="deliveryProof" class="form-control-minimal w-100" accept="image/*">
                                <small class="text-muted" style="font-size: 0.65rem;">Required when marking as delivered</small>
                                <div id="deliveryProofPreview" class="mt-2" style="display: none;">
                                    <img id="deliveryProofImg" src="#" style="max-width: 100%; max-height: 80px; border-radius: 8px;">
                                </div>
                            </div>

                            <div class="mb-3" id="paymentProofField">
                                <label class="form-label-minimal">Payment Proof Photo <span class="text-danger" id="paymentProofRequired">*</span></label>
                                <input type="file" name="payment_proof" id="paymentProof" class="form-control-minimal w-100" accept="image/*">
                                <small class="text-muted" style="font-size: 0.65rem;">Required when marking as delivered</small>
                                <div id="paymentProofPreview" class="mt-2" style="display: none;">
                                    <img id="paymentProofImg" src="#" style="max-width: 100%; max-height: 80px; border-radius: 8px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label-minimal">Notes</label>
                                <textarea name="notes" class="form-control-minimal w-100" rows="2" placeholder="Optional delivery notes...">{{ $delivery->driver_notes }}</textarea>
                            </div>

                            <button type="submit" class="btn-update" id="submitBtn">
                                <i class="bi bi-check-circle me-2"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="image-preview-modal">
    <div class="image-preview-content">
        <div class="image-preview-header">
            <h6 class="mb-0" id="previewTitle">Image Preview</h6>
            <button type="button" class="btn-close" onclick="closeImagePreview()"></button>
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
    const statusSelect = document.getElementById('statusSelect');
    const deliveryProofInput = document.getElementById('deliveryProof');
    const paymentProofInput = document.getElementById('paymentProof');
    const deliveryProofPreview = document.getElementById('deliveryProofPreview');
    const paymentProofPreview = document.getElementById('paymentProofPreview');
    const deliveryProofImg = document.getElementById('deliveryProofImg');
    const paymentProofImg = document.getElementById('paymentProofImg');
    const submitBtn = document.getElementById('submitBtn');
    const updateForm = document.getElementById('updateStatusForm');
    const errorAlert = document.getElementById('errorAlert');
    const successAlert = document.getElementById('successAlert');

    // Preview delivery proof before upload
    if (deliveryProofInput) {
        deliveryProofInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
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
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file');
                    this.value = '';
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
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

    // Toggle required fields based on status
    function toggleRequired() {
        const isDelivered = statusSelect && statusSelect.value === 'delivered';
        
        if (deliveryProofInput) {
            deliveryProofInput.required = isDelivered;
            const deliveryProofRequired = document.getElementById('deliveryProofRequired');
            if (deliveryProofRequired) {
                deliveryProofRequired.style.display = isDelivered ? 'inline' : 'none';
            }
        }
        
        if (paymentProofInput) {
            paymentProofInput.required = isDelivered;
            const paymentProofRequired = document.getElementById('paymentProofRequired');
            if (paymentProofRequired) {
                paymentProofRequired.style.display = isDelivered ? 'inline' : 'none';
            }
        }
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', toggleRequired);
        toggleRequired();
    }
    
    // Form validation before submit
    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            const isDelivered = statusSelect && statusSelect.value === 'delivered';
            
            errorAlert.style.display = 'none';
            successAlert.style.display = 'none';
            
            if (isDelivered) {
                if (!deliveryProofInput.files.length && !deliveryProofInput.value) {
                    e.preventDefault();
                    errorAlert.textContent = 'Please upload a delivery proof photo. Required when marking as delivered.';
                    errorAlert.style.display = 'block';
                    deliveryProofInput.focus();
                    return false;
                }
                
                if (!paymentProofInput.files.length && !paymentProofInput.value) {
                    e.preventDefault();
                    errorAlert.textContent = 'Please upload a payment proof photo. Required when marking as delivered.';
                    errorAlert.style.display = 'block';
                    paymentProofInput.focus();
                    return false;
                }
            }
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
            }
        });
    }
    
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
    
    function closeModal() {
        const modalElement = document.getElementById('deliveryModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
        }
        const container = document.getElementById('modalContainer');
        if (container) container.innerHTML = '';
        const customModal = document.getElementById('customModal');
        if (customModal) customModal.style.display = 'none';
    }
</script>