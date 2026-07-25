@php
    use App\Helpers\GoogleDriveHelper;
@endphp

<style>
    /* === MODERN MINIMALIST MODAL STYLES (from admin-modal.blade.php) === */
    .admin-modal-container {
        padding: 1.5rem;
        max-height: 85vh;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .admin-modal-container::-webkit-scrollbar {
        width: 6px;
    }

    .admin-modal-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .admin-modal-container::-webkit-scrollbar-thumb {
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

    .btn-secondary-minimal {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary-minimal:hover {
        background: #e2e8f0;
    }

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
        width: 100%;
    }

    .form-control-minimal:focus,
    .form-select-minimal:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .admin-modal-container {
            padding: 1rem;
        }
    }
</style>

<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="admin-modal-container">
                <!-- Modal Header -->
                <div class="modal-header-minimal">
                    <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Edit Product: {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('branch-admin.products.update', $product) }}"
                    enctype="multipart/form-data" id="editProductForm{{ $product->id }}">
                    @csrf
                    @method('PUT')

                    <div class="modal-body p-0">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Basic Information -->
                                <div class="info-card">
                                    <div class="card-header-minimal">
                                        <h6><i class="bi bi-info-circle"></i> Basic Information</h6>
                                    </div>
                                    <div class="card-body-minimal">
                                        <div class="row">
                                            <div class="col-md-8 mb-3">
                                                <label class="form-label-minimal">Product Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control-minimal"
                                                    value="{{ $product->name }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Brand <span class="text-danger">*</span></label>
                                                <select name="brand" class="form-select-minimal" required>
                                                    <option value="X-Vape"
                                                        {{ $product->brand == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                                                    <option value="Slimbar"
                                                        {{ $product->brand == 'Slimbar' ? 'selected' : '' }}>Slimbar
                                                    </option>
                                                    <option value="Relx"
                                                        {{ $product->brand == 'Relx' ? 'selected' : '' }}>Relx</option>
                                                    <option value="Other"
                                                        {{ !in_array($product->brand, ['X-Vape', 'Slimbar', 'Relx']) ? 'selected' : '' }}>
                                                        Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row" id="customBrandRow{{ $product->id }}"
                                            style="display: {{ !in_array($product->brand, ['X-Vape', 'Slimbar', 'Relx']) ? 'block' : 'none' }};">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label-minimal">Custom Brand Name</label>
                                                <input type="text" name="custom_brand" class="form-control-minimal"
                                                    value="{{ !in_array($product->brand, ['X-Vape', 'Slimbar', 'Relx']) ? $product->brand : '' }}"
                                                    placeholder="Enter custom brand name">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label-minimal">Description</label>
                                            <textarea name="description" class="form-control-minimal" rows="2">{{ $product->description }}</textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Category <span
                                                        class="text-danger">*</span></label>
                                                <select name="category" class="form-select-minimal" required>
                                                    <option value="Ultra"
                                                        {{ $product->category == 'Ultra' ? 'selected' : '' }}>X-Vape Ultra
                                                    </option>
                                                    <option value="Slimbar"
                                                        {{ $product->category == 'Slimbar' ? 'selected' : '' }}>Slimbar
                                                    </option>
                                                    <option value="Relx"
                                                        {{ $product->category == 'Relx' ? 'selected' : '' }}>Relx</option>
                                                    <option value="New"
                                                        {{ !in_array($product->category, ['Ultra', 'Slimbar', 'Relx']) ? 'selected' : '' }}>
                                                        New Category</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3" id="newCategoryRow{{ $product->id }}"
                                                style="display: {{ !in_array($product->category, ['Ultra', 'Slimbar', 'Relx']) ? 'block' : 'none' }};">
                                                <label class="form-label-minimal">New Category Name</label>
                                                <input type="text" name="new_category" class="form-control-minimal"
                                                    value="{{ !in_array($product->category, ['Ultra', 'Slimbar', 'Relx']) ? $product->category : '' }}"
                                                    placeholder="Enter new category name">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Product Type <span
                                                        class="text-danger">*</span></label>
                                                <select name="type" class="form-select-minimal" required>
                                                    <option value="pod-system"
                                                        {{ $product->type == 'pod-system' ? 'selected' : '' }}>Pod System
                                                    </option>
                                                    <option value="disposable"
                                                        {{ $product->type == 'disposable' ? 'selected' : '' }}>Disposable
                                                    </option>
                                                    <option value="mod" {{ $product->type == 'mod' ? 'selected' : '' }}>
                                                        Box Mod</option>
                                                    <option value="liquid"
                                                        {{ $product->type == 'liquid' ? 'selected' : '' }}>E-Liquid
                                                    </option>
                                                    <option value="coil"
                                                        {{ $product->type == 'coil' ? 'selected' : '' }}>Coil</option>
                                                    <option value="accessory"
                                                        {{ $product->type == 'accessory' ? 'selected' : '' }}>Accessory
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Specifications -->
                                <div class="info-card">
                                    <div class="card-header-minimal">
                                        <h6><i class="bi bi-gear"></i> Specifications</h6>
                                    </div>
                                    <div class="card-body-minimal">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Nicotine Strength</label>
                                                <input type="text" name="nicotine_strength" class="form-control-minimal"
                                                    value="{{ $product->nicotine_strength }}" placeholder="e.g., 10mg">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Puff Count</label>
                                                <input type="number" name="puff_count" class="form-control-minimal"
                                                    value="{{ $product->puff_count }}" placeholder="e.g., 10000">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Battery Capacity (mAh)</label>
                                                <input type="number" name="battery_capacity" class="form-control-minimal"
                                                    value="{{ $product->battery_capacity }}" placeholder="e.g., 650">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Charging Type</label>
                                                <input type="text" name="charging_type" class="form-control-minimal"
                                                    value="{{ $product->charging_type }}" placeholder="e.g., Type-C">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label-minimal">Liquid Capacity (ml)</label>
                                                <input type="number" step="0.1" name="liquid_capacity"
                                                    class="form-control-minimal" value="{{ $product->liquid_capacity }}"
                                                    placeholder="e.g., 10">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-check mt-4">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="adjustable_airflow{{ $product->id }}"
                                                        name="adjustable_airflow" value="1"
                                                        {{ $product->adjustable_airflow ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="adjustable_airflow{{ $product->id }}">
                                                        Adjustable Airflow
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="smart_display{{ $product->id }}" name="smart_display"
                                                        value="1" {{ $product->smart_display ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="smart_display{{ $product->id }}">
                                                        Smart Display
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Information -->
                                <div class="info-card">
                                    <div class="card-header-minimal">
                                        <h6><i class="bi bi-currency-dollar"></i> Price Information</h6>
                                    </div>
                                    <div class="card-body-minimal">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label-minimal">Selling Price (₱) <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" name="price"
                                                        class="form-control-minimal" value="{{ $product->price }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label-minimal">Cost Price (₱)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₱</span>
                                                    <input type="number" step="0.01" name="cost"
                                                        class="form-control-minimal" value="{{ $product->cost }}"
                                                        placeholder="0.00">
                                                </div>
                                                <small class="text-muted">Your purchase cost (for profit
                                                    calculation)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Storage Image Upload Section -->
                                <div class="info-card">
                                    <div class="card-header-minimal">
                                        <h6><i class="bi bi-cloud-upload"></i> Upload from Computer (Storage)</h6>
                                    </div>
                                    <div class="card-body-minimal">
                                        @if ($product->image && !$product->image_url)
                                            <div class="mb-3 text-center" id="currentStoragePreview{{ $product->id }}">
                                                <img src="{{ Storage::url($product->image) }}"
                                                    alt="{{ $product->name }}" style="max-height: 150px;"
                                                    class="img-thumbnail">
                                                <p class="text-muted small mt-1">Current image from storage</p>
                                            </div>
                                        @endif

                                        <div class="mb-3 text-center" id="newStoragePreview{{ $product->id }}"
                                            style="display: none;">
                                            <img id="storagePreviewImg{{ $product->id }}" src="" alt="Preview"
                                                style="max-height: 150px;" class="img-thumbnail">
                                            <p class="text-muted small mt-1">New image preview</p>
                                        </div>

                                        <div class="mb-3">
                                            <input type="file" name="image" class="form-control-minimal" accept="image/*"
                                                id="storageFileInput{{ $product->id }}">
                                            <small class="text-muted">Upload new image from computer (max 2MB)</small>
                                            <small class="text-muted d-block text-warning">Note: This will replace any
                                                existing Google Drive image</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Google Drive Image Section -->
                                <div class="info-card">
                                    <div class="card-header-minimal">
                                        <h6><i class="bi bi-google"></i> Google Drive Image</h6>
                                    </div>
                                    <div class="card-body-minimal">
                                        @if ($product->image_url)
                                            <div class="mb-3 text-center" id="currentDrivePreview{{ $product->id }}">
                                                <img src="{{ GoogleDriveHelper::getThumbnailUrl($product->image_url, 150) }}"
                                                    alt="{{ $product->name }}" style="max-height: 150px;"
                                                    class="img-thumbnail">
                                                <p class="text-muted small mt-1">Current image from Google Drive</p>
                                            </div>
                                        @endif

                                        <div class="mb-3 text-center" id="newDrivePreview{{ $product->id }}"
                                            style="display: none;">
                                            <img id="drivePreviewImg{{ $product->id }}" src="" alt="Preview"
                                                style="max-height: 150px;" class="img-thumbnail">
                                            <p class="text-muted small mt-1">Google Drive image preview</p>
                                        </div>

                                        <div class="mb-3">
                                            <div class="input-group">
                                                <input type="text" name="image_url" class="form-control-minimal"
                                                    placeholder="Paste Google Drive shareable link"
                                                    value="{{ $product->image_url }}"
                                                    id="googleDriveUrl{{ $product->id }}">
                                                <button class="btn btn-outline-primary" type="button"
                                                    onclick="convertGoogleDriveUrl({{ $product->id }})">
                                                    <i class="bi bi-google"></i> Use
                                                </button>
                                            </div>
                                            <small class="text-muted">Paste Google Drive shareable link</small>
                                            <small class="text-muted d-block text-warning">Note: This will replace any
                                                existing storage image</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Stock Info -->
                                <div class="info-card">
                                    <div class="card-header-minimal">
                                        <h6><i class="bi bi-box-seam"></i> Current Stock</h6>
                                    </div>
                                    <div class="card-body-minimal">
                                        @php
                                            $branchInventory = $product->branchInventories
                                                ->where('branch_id', Auth::user()->branch_id)
                                                ->first();
                                        @endphp

                                        @if ($branchInventory)
                                            <div class="mb-2">
                                                <strong>Quantity:</strong> {{ $branchInventory->quantity }} units
                                            </div>
                                            <div class="mb-2">
                                                <strong>Low Stock Threshold:</strong>
                                                {{ $branchInventory->low_stock_threshold }} units
                                            </div>
                                            <div class="mb-2">
                                                <strong>Last Restocked:</strong>
                                                {{ $branchInventory->last_restocked_at ? $branchInventory->last_restocked_at->format('M d, Y') : 'Never' }}
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">Product not in inventory</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer (Global UI, no modal-footer) -->
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-update" style="width: auto; padding: 0.5rem 1.25rem;">
                            <i class="bi bi-check-circle"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Live preview for storage file upload
    const storageFileInput{{ $product->id }} = document.getElementById('storageFileInput{{ $product->id }}');
    if (storageFileInput{{ $product->id }}) {
        storageFileInput{{ $product->id }}.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewImg = document.getElementById('storagePreviewImg{{ $product->id }}');
                    previewImg.src = event.target.result;
                    document.getElementById('newStoragePreview{{ $product->id }}').style.display =
                    'block';
                    // Hide current storage preview if exists
                    const currentPreview = document.getElementById(
                        'currentStoragePreview{{ $product->id }}');
                    if (currentPreview) {
                        currentPreview.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function convertGoogleDriveUrl(productId) {
        const urlInput = document.getElementById(`googleDriveUrl${productId}`);
        let url = urlInput.value.trim();

        if (!url) return;

        let fileId = null;

        let match = url.match(/\/file\/d\/([a-zA-Z0-9_-]+)/);
        if (match) {
            fileId = match[1];
        }

        match = url.match(/[?&]id=([a-zA-Z0-9_-]+)/);
        if (match) {
            fileId = match[1];
        }

        if (fileId) {
            const directUrl = `https://drive.google.com/uc?export=download&id=${fileId}`;
            urlInput.value = directUrl;

            // Show preview for Google Drive image
            const previewImg = document.getElementById(`drivePreviewImg${productId}`);
            if (previewImg) {
                previewImg.src = `https://drive.google.com/uc?export=view&id=${fileId}`;
                document.getElementById(`newDrivePreview${productId}`).style.display = 'block';
                // Hide current drive preview if exists
                const currentPreview = document.getElementById(`currentDrivePreview${productId}`);
                if (currentPreview) {
                    currentPreview.style.display = 'none';
                }
            }

            // Use global notification instead of toast
            if (typeof window.showNotification === 'function') {
                window.showNotification('Google Drive URL converted successfully!', 'success');
            } else {
                // Fallback to toast
                showToast('success', 'Google Drive URL converted successfully!');
            }
        } else {
            if (typeof window.showNotification === 'function') {
                window.showNotification('Invalid Google Drive URL.', 'error');
            } else {
                showToast('error', 'Invalid Google Drive URL.');
            }
        }
    }

    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML =
            `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // Brand select handler
    const brandSelect{{ $product->id }} = document.querySelector(
        '#editProductModal{{ $product->id }} select[name="brand"]');
    if (brandSelect{{ $product->id }}) {
        brandSelect{{ $product->id }}.addEventListener('change', function() {
            const customBrandRow = document.getElementById('customBrandRow{{ $product->id }}');
            if (this.value === 'Other') {
                customBrandRow.style.display = 'block';
            } else {
                customBrandRow.style.display = 'none';
            }
        });
    }

    // Category select handler
    const categorySelect{{ $product->id }} = document.querySelector(
        '#editProductModal{{ $product->id }} select[name="category"]');
    if (categorySelect{{ $product->id }}) {
        categorySelect{{ $product->id }}.addEventListener('change', function() {
            const newCategoryRow = document.getElementById('newCategoryRow{{ $product->id }}');
            if (this.value === 'New') {
                newCategoryRow.style.display = 'block';
            } else {
                newCategoryRow.style.display = 'none';
            }
        });
    }

    // ============================================================
    // ADDED: Global Notification Form Submission (AJAX)
    // ============================================================

    document.addEventListener('DOMContentLoaded', function() {
        const editForm = document.getElementById('editProductForm{{ $product->id }}');
        
        if (editForm) {
            // Clone to prevent duplicate listeners
            const newForm = editForm.cloneNode(true);
            editForm.parentNode.replaceChild(newForm, editForm);

            newForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                // Show processing notification
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Updating product...', 'info');
                }

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(data.message || 'Product updated successfully!', 'success');
                        }
                        // Close modal
                        const modalElement = document.querySelector('.modal.show');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) modal.hide();
                        }
                        // Remove backdrop
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                        document.body.classList.remove('modal-open');
                        // Reload page after 1.5 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        if (data.errors) {
                            let errorMsg = '';
                            for (const [field, errors] of Object.entries(data.errors)) {
                                errorMsg += errors[0] + '\n';
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback';
                                    feedback.innerText = errors[0];
                                    input.parentNode.insertBefore(feedback, input.nextSibling);
                                }
                            }
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(errorMsg || data.message || 'Validation failed', 'error');
                            }
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Failed to update product.', 'error');
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Network error. Please try again.', 'error');
                    }
                });
            });
        }
    });
</script>