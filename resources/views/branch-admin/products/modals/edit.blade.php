@php
    use App\Helpers\GoogleDriveHelper;
@endphp

<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Product: {{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('branch-admin.products.update', $product) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label">Product Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $product->name }}" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Brand <span class="text-danger">*</span></label>
                                            <select name="brand" class="form-select" required>
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
                                            <label class="form-label">Custom Brand Name</label>
                                            <input type="text" name="custom_brand" class="form-control"
                                                value="{{ !in_array($product->brand, ['X-Vape', 'Slimbar', 'Relx']) ? $product->brand : '' }}"
                                                placeholder="Enter custom brand name">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ $product->description }}</textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Category <span
                                                    class="text-danger">*</span></label>
                                            <select name="category" class="form-select" required>
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
                                            <label class="form-label">New Category Name</label>
                                            <input type="text" name="new_category" class="form-control"
                                                value="{{ !in_array($product->category, ['Ultra', 'Slimbar', 'Relx']) ? $product->category : '' }}"
                                                placeholder="Enter new category name">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Product Type <span
                                                    class="text-danger">*</span></label>
                                            <select name="type" class="form-select" required>
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
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Specifications</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Nicotine Strength</label>
                                            <input type="text" name="nicotine_strength" class="form-control"
                                                value="{{ $product->nicotine_strength }}" placeholder="e.g., 10mg">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Puff Count</label>
                                            <input type="number" name="puff_count" class="form-control"
                                                value="{{ $product->puff_count }}" placeholder="e.g., 10000">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Battery Capacity (mAh)</label>
                                            <input type="number" name="battery_capacity" class="form-control"
                                                value="{{ $product->battery_capacity }}" placeholder="e.g., 650">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Charging Type</label>
                                            <input type="text" name="charging_type" class="form-control"
                                                value="{{ $product->charging_type }}" placeholder="e.g., Type-C">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Liquid Capacity (ml)</label>
                                            <input type="number" step="0.1" name="liquid_capacity"
                                                class="form-control" value="{{ $product->liquid_capacity }}"
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
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Price Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Selling Price (₱) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" step="0.01" name="price"
                                                    class="form-control" value="{{ $product->price }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cost Price (₱)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₱</span>
                                                <input type="number" step="0.01" name="cost"
                                                    class="form-control" value="{{ $product->cost }}"
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
                            <!-- Storage Image Upload Section (Only for local storage images) -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Upload from Computer (Storage)</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Current Storage Image Preview -->
                                    @if ($product->image && !$product->image_url)
                                        <div class="mb-3 text-center" id="currentStoragePreview{{ $product->id }}">
                                            <img src="{{ Storage::url($product->image) }}"
                                                alt="{{ $product->name }}" style="max-height: 150px;"
                                                class="img-thumbnail">
                                            <p class="text-muted small mt-1">Current image from storage</p>
                                        </div>
                                    @endif

                                    <!-- New Storage Image Preview (shows when file is selected) -->
                                    <div class="mb-3 text-center" id="newStoragePreview{{ $product->id }}"
                                        style="display: none;">
                                        <img id="storagePreviewImg{{ $product->id }}" src="" alt="Preview"
                                            style="max-height: 150px;" class="img-thumbnail">
                                        <p class="text-muted small mt-1">New image preview</p>
                                    </div>

                                    <div class="mb-3">
                                        <input type="file" name="image" class="form-control" accept="image/*"
                                            id="storageFileInput{{ $product->id }}">
                                        <small class="text-muted">Upload new image from computer (max 2MB)</small>
                                        <small class="text-muted d-block text-warning">Note: This will replace any
                                            existing Google Drive image</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Google Drive Image Section (Only for Google Drive images) -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Google Drive Image</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Current Google Drive Image Preview -->
                                    @if ($product->image_url)
                                        <div class="mb-3 text-center" id="currentDrivePreview{{ $product->id }}">
                                            <img src="{{ GoogleDriveHelper::getThumbnailUrl($product->image_url, 150) }}"
                                                alt="{{ $product->name }}" style="max-height: 150px;"
                                                class="img-thumbnail">
                                            <p class="text-muted small mt-1">Current image from Google Drive</p>
                                        </div>
                                    @endif

                                    <!-- New Google Drive Image Preview -->
                                    <div class="mb-3 text-center" id="newDrivePreview{{ $product->id }}"
                                        style="display: none;">
                                        <img id="drivePreviewImg{{ $product->id }}" src="" alt="Preview"
                                            style="max-height: 150px;" class="img-thumbnail">
                                        <p class="text-muted small mt-1">Google Drive image preview</p>
                                    </div>

                                    <div class="mb-3">
                                        <div class="input-group">
                                            <input type="text" name="image_url" class="form-control"
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
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Current Stock</h6>
                                </div>
                                <div class="card-body">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Product
                    </button>
                </div>
            </form>
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

            showToast('success', 'Google Drive URL converted successfully!');
        } else {
            showToast('error', 'Invalid Google Drive URL.');
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
</script>

<script>
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
</script>
