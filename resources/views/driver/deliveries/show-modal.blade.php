<!-- resources/views/driver/deliveries/show-modal.blade.php -->
<div id="deliveryStatusModal" style="display:none;">
<style>
    /* ✅ ADD THIS - Make modal a proper overlay */
    #deliveryStatusModal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 99999;
        display: none;
        justify-content: center;
        align-items: center;
        overflow-y: auto;
        padding: 20px;
    }

    #deliveryStatusModal .modal-body-custom {
        background: white;
        width: 95%;
        max-width: 1200px;
        max-height: 90vh;
        overflow-y: auto;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    /* Modal Styles - Matching Online Orders */
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
    .badge {
        display: inline-block;
        padding: 0.35rem 0.65rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: capitalize;
        line-height: 1;
    }

    .badge-ready {
        background-color: #d1fae5 !important;
        color: #059669 !important;
    }

    .badge-out_for_delivery {
        background-color: #fef3c7 !important;
        color: #d97706 !important;
    }

    .badge-picked_up {
        background-color: #dbeafe !important;
        color: #2563eb !important;
    }

    .badge-in_transit {
        background-color: #e0e7ff !important;
        color: #4f46e5 !important;
    }

    .badge-delivered {
        background-color: #d1fae5 !important;
        color: #059669 !important;
    }

    .badge-delivery_failed {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .badge-cancelled {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .badge-pending {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
    }

    .badge-assigned {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
    }

    .badge-secondary {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
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
        <button type="button" class="btn-close-modal" onclick="window.closeDeliveryModal()">&times;</button>

        <div class="modal-header-custom" style="padding: 0 0 1rem 0;">
            <div>
                <h5 class="order-number">Delivery Details</h5>
                <p class="order-date">Update delivery status</p>
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
                            <tbody id="modal-items-list">
                                <!-- Items will be populated -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Delivery Information (LEFT - SECOND) -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-truck"></i> Delivery Information</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-6">
                                <p class="info-label">Status</p>
                                <p class="info-value" id="modal-current-status"></p>
                                <p class="info-label">Assigned</p>
                                <p class="info-value" id="modal-assigned-date"></p>
                            </div>
                            <div class="col-6">
                                <p class="info-label">Order #</p>
                                <p class="info-value" id="modal-order-number"></p>
                                <p class="info-label">Picked Up</p>
                                <p class="info-value" id="modal-picked-up-date"></p>
                            </div>
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
                                <p class="info-value" id="modal-customer-name"></p>
                                <p class="info-label">Phone</p>
                                <p class="info-value" id="modal-customer-phone"></p>
                            </div>
                            <div class="col-6">
                                <p class="info-label">Address</p>
                                <p class="info-value" id="modal-customer-address"></p>
                                <p class="info-label">City</p>
                                <p class="info-value" id="modal-customer-city"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing Proofs (LEFT - FOURTH) -->
                <div class="info-card" id="existing-proofs-section" style="display:none;">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-images"></i> Existing Proofs</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row" id="existing-proofs-container">
                            <!-- Proofs will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-5">
                <!-- Update Delivery Status (RIGHT - FIRST) -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-arrow-repeat"></i> Update Delivery Status</h6>
                    </div>
                    <div class="card-body p-3">
                        <form id="statusUpdateForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="delivery_id" name="delivery_id">
                            
                            <!-- Status Selection -->
                            <div class="mb-3">
                                <label class="info-label">Select Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="picked_up">Picked Up</option>
                                    <option value="in_transit">In Transit</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                            
                            <!-- Delivery Proof Section (for Delivered status) -->
                            <div id="delivery-proof-section" style="display:none;">
                                <div class="mb-3">
                                    <label class="info-label">Delivery Proof Photo *</label>
                                    <input type="file" class="form-control" id="delivery_proof" name="delivery_proof" accept="image/*">
                                    <small class="text-muted">Max size: 5MB. Allowed: JPEG, PNG, JPG</small>
                                </div>
                            </div>
                            
                            <!-- Payment Proof Section (for Delivered status) -->
                            <div id="payment-proof-section" style="display:none;">
                                <div class="mb-3">
                                    <label class="info-label">Payment Proof Photo *</label>
                                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                                    <small class="text-muted">Max size: 5MB. Allowed: JPEG, PNG, JPG</small>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            <div class="mb-3">
                                <label class="info-label">Notes / Reason</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Enter any notes or reason for status update..."></textarea>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-success btn-block" id="submitStatusBtn">
                                <i class="fas fa-check-circle"></i> Update Delivery Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Delivery Timeline (RIGHT - SECOND) -->
                <div class="info-card">
                    <div class="card-header-custom">
                        <h6><i class="bi bi-clock-history"></i> Delivery Timeline</h6>
                    </div>
                    <div class="card-body p-3">
                        <p class="info-label">In Transit</p>
                        <p class="info-value" id="modal-in-transit-date"></p>
                        <p class="info-label">Delivered</p>
                        <p class="info-value" id="modal-delivered-date"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- JavaScript for the modal -->
<script>
// ✅ GLOBAL FUNCTIONS - Available immediately
window.showDeliveryStatusModal = function(deliveryData) {
    // Hide the order modal first
    if (typeof window.closeModal === 'function') {
        window.closeModal();
    }
    
    // Populate delivery info
    document.getElementById('delivery_id').value = deliveryData.id;
    document.getElementById('modal-order-number').textContent = (deliveryData.order && deliveryData.order.order_number) || 'N/A';
    
    // ✅ Status badge - USING INLINE STYLES SO IT CAN'T BE OVERRIDDEN
    const statusBadges = {
        'pending': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#f1f5f9; color:#475569; text-transform:capitalize;">Pending</span>',
        'assigned': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#e0f2fe; color:#0369a1; text-transform:capitalize;">Assigned</span>',
        'picked_up': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#dbeafe; color:#2563eb; text-transform:capitalize;">Picked Up</span>',
        'in_transit': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#e0e7ff; color:#4f46e5; text-transform:capitalize;">In Transit</span>',
        'delivered': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#d1fae5; color:#059669; text-transform:capitalize;">Delivered</span>',
        'failed': '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#fee2e2; color:#dc2626; text-transform:capitalize;">Failed</span>'
    };
    document.getElementById('modal-current-status').innerHTML = statusBadges[deliveryData.status] || '<span style="display:inline-block; padding:4px 10px; border-radius:30px; font-weight:600; font-size:0.7rem; background-color:#f1f5f9; color:#475569; text-transform:capitalize;">' + deliveryData.status + '</span>';
    
    // Dates
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleString('en-US', {
            year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });
    }
    
    document.getElementById('modal-assigned-date').textContent = formatDate(deliveryData.assigned_at);
    document.getElementById('modal-picked-up-date').textContent = formatDate(deliveryData.picked_up_at);
    document.getElementById('modal-in-transit-date').textContent = formatDate(deliveryData.in_transit_at);
    document.getElementById('modal-delivered-date').textContent = formatDate(deliveryData.delivered_at);
    
    // Customer info
    const order = deliveryData.order || {};
    document.getElementById('modal-customer-name').textContent = order.customer_name || 'N/A';
    document.getElementById('modal-customer-phone').textContent = order.customer_phone || 'N/A';
    document.getElementById('modal-customer-address').textContent = order.delivery_address || 'N/A';
    document.getElementById('modal-customer-city').textContent = order.city || 'N/A';
    
    // Populate items with images
    let itemsHtml = '';
    if (order.items && order.items.length > 0) {
        order.items.forEach(function(item) {
            const productName = item.product ? item.product.name : 'Product';
            const quantity = item.quantity || 0;
            const price = item.price || 0;
            const total = quantity * price;
            const productImage = item.product && item.product.image ? item.product.image : null;
            const imageUrl = productImage ? (productImage.startsWith('http') ? productImage : '/storage/' + productImage) : null;
            
            itemsHtml += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            ${imageUrl ? `<img src="${imageUrl}" alt="${productName}" class="product-image">` : `<div class="product-image bg-light d-flex align-items-center justify-content-center"><i class="bi bi-box-seam text-muted"></i></div>`}
                            <div>
                                <div class="product-name">${productName}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">${quantity}</td>
                    <td class="text-end">₱${parseFloat(price).toFixed(2)}</td>
                    <td class="text-end">₱${parseFloat(total).toFixed(2)}</td>
                </tr>
            `;
        });
    } else {
        itemsHtml = '<tr><td colspan="4" class="text-center">No items found</td></tr>';
    }
    document.getElementById('modal-items-list').innerHTML = itemsHtml;
    
    // Show existing proofs
    document.getElementById('existing-proofs-section').style.display = 'block';
    let proofsHtml = '';
    
    if (deliveryData.delivery_proof) {
        proofsHtml += `
            <div class="col-md-4 text-center mb-3">
                <div class="card">
                    <div class="card-header">Delivery Proof</div>
                    <div class="card-body">
                        <img src="/storage/${deliveryData.delivery_proof}" class="img-fluid" style="max-height: 150px; object-fit: cover;">
                        <a href="/storage/${deliveryData.delivery_proof}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
        `;
    }
    
    if (deliveryData.payment_proof) {
        proofsHtml += `
            <div class="col-md-4 text-center mb-3">
                <div class="card">
                    <div class="card-header">Payment Proof</div>
                    <div class="card-body">
                        <img src="/storage/${deliveryData.payment_proof}" class="img-fluid" style="max-height: 150px; object-fit: cover;">
                        <a href="/storage/${deliveryData.payment_proof}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>
            </div>
        `;
    }
    
    if (proofsHtml === '') {
        proofsHtml = '<div class="col-12 text-center text-muted">No proofs uploaded yet</div>';
    }
    
    document.getElementById('existing-proofs-container').innerHTML = proofsHtml;
    
    // Disable status update if delivered or failed
    const form = document.getElementById('statusUpdateForm');
    const submitBtn = document.getElementById('submitStatusBtn');
    
    if (deliveryData.status === 'delivered' || deliveryData.status === 'failed') {
        // Disable all form elements
        const elements = form.querySelectorAll('input, select, textarea, button');
        elements.forEach(el => el.disabled = true);
        submitBtn.innerHTML = '<i class="fas fa-lock"></i> Delivery Completed';
        submitBtn.className = 'btn btn-secondary btn-block';
    } else {
        // Enable all form elements
        const elements = form.querySelectorAll('input, select, textarea, button');
        elements.forEach(el => el.disabled = false);
        submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Update Delivery Status';
        submitBtn.className = 'btn btn-success btn-block';
    }
    
    // ✅ Show modal using vanilla JavaScript
    const modal = document.getElementById('deliveryStatusModal');
    if (modal) {
        modal.style.display = 'flex';
        document.body.classList.add('modal-open');
    }
};

// ✅ ADD THIS FUNCTION - Called from online-orders/show.blade.php
window.openDeliveryModal = function(deliveryId) {
    // Hide the order modal first
    if (typeof window.closeModal === 'function') {
        window.closeModal();
    }
    
    // Fetch delivery data
    fetch(`/driver/deliveries/${deliveryId}/modal-data`)
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Call showDeliveryStatusModal with the delivery data
                showDeliveryStatusModal(data.delivery);
            } else {
                alert(data.message || 'Failed to load delivery data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading delivery data: ' + error.message);
        });
};

// ✅ CLOSE MODAL FUNCTION
window.closeDeliveryModal = function() {
    const modal = document.getElementById('deliveryStatusModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    }
};

// ✅ Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Status change handler
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const status = this.value;
            
            // Reset all sections
            document.getElementById('delivery-proof-section').style.display = 'none';
            document.getElementById('payment-proof-section').style.display = 'none';
            
            // Show relevant sections based on status
            if (status === 'delivered') {
                document.getElementById('delivery-proof-section').style.display = 'block';
                document.getElementById('payment-proof-section').style.display = 'block';
            }
            
            // Update notes placeholder based on status
            const notes = document.getElementById('notes');
            if (status === 'failed') {
                notes.placeholder = 'Please provide reason for failed delivery...';
                notes.required = true;
            } else {
                notes.placeholder = 'Enter any notes for status update...';
                notes.required = false;
            }
        });
    }
    
    // Close button click
    const closeBtn = document.querySelector('#deliveryStatusModal .close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            window.closeDeliveryModal();
        });
    }
    
    // Click outside to close
    const modal = document.getElementById('deliveryStatusModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                window.closeDeliveryModal();
            }
        });
    }
    
    // Form submission
    const form = document.getElementById('statusUpdateForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const deliveryId = document.getElementById('delivery_id').value;
            const status = document.getElementById('status').value;
            
            // Validate status selection
            if (!status) {
                alert('Please select a status.');
                return;
            }
            
            // Validate required proofs for delivered status
            if (status === 'delivered') {
                const hasDeliveryProof = document.getElementById('delivery_proof').files.length > 0;
                const hasPaymentProof = document.getElementById('payment_proof').files.length > 0;
                
                if (!hasDeliveryProof) {
                    alert('Please upload delivery proof photo.');
                    return;
                }
                if (!hasPaymentProof) {
                    alert('Please upload payment proof photo.');
                    return;
                }
            }
            
            // Validate reason for failed status
            if (status === 'failed') {
                const notes = document.getElementById('notes').value;
                if (!notes) {
                    alert('Please provide a reason for failed delivery.');
                    return;
                }
            }
            
            // Show loading
            const submitBtn = document.getElementById('submitStatusBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            
            // Create FormData
            const formData = new FormData(form);
            
            // AJAX request
            fetch(`/driver/deliveries/${deliveryId}/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Delivery status updated successfully!');
                    window.closeDeliveryModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update delivery status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating delivery status: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Update Delivery Status';
            });
        });
    }
});
</script>