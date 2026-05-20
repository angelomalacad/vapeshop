<div class="modal-content">
    <div class="modal-header bg-warning text-white">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Product</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" id="editProductForm">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <h6 class="fw-semibold mb-2"><i class="bi bi-info-circle me-1"></i>Basic Information</h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Brand *</label>
                    <select name="brand" class="form-select" id="modalBrand">
                        <option value="X-Vape" {{ $product->brand == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                        <option value="Slimbar" {{ $product->brand == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                        <option value="Relx" {{ $product->brand == 'Relx' ? 'selected' : '' }}>Relx</option>
                        <option value="Other">Other (Custom)</option>
                    </select>
                </div>
            </div>
            <div id="modalCustomBrandRow" style="display: {{ $product->brand == 'Other' ? 'block' : 'none' }};" class="mb-2">
                <label class="form-label">Custom Brand Name</label>
                <input type="text" name="custom_brand" class="form-control" value="{{ $product->brand }}">
            </div>
            <div class="mb-2">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ $product->description }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-select" id="modalCategory">
                        <option value="Ultra" {{ $product->category == 'Ultra' ? 'selected' : '' }}>X-Vape Ultra</option>
                        <option value="Slimbar" {{ $product->category == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                        <option value="Relx" {{ $product->category == 'Relx' ? 'selected' : '' }}>Relx</option>
                        <option value="New">New Category</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2" id="modalNewCategoryRow" style="display: none;">
                    <label class="form-label">New Category Name</label>
                    <input type="text" name="new_category" class="form-control">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Product Type *</label>
                    <select name="type" class="form-select" required>
                        <option value="pod-system" {{ $product->type == 'pod-system' ? 'selected' : '' }}>Pod System</option>
                        <option value="disposable" {{ $product->type == 'disposable' ? 'selected' : '' }}>Disposable</option>
                        <option value="mod" {{ $product->type == 'mod' ? 'selected' : '' }}>Box Mod</option>
                        <option value="liquid" {{ $product->type == 'liquid' ? 'selected' : '' }}>E-Liquid</option>
                        <option value="coil" {{ $product->type == 'coil' ? 'selected' : '' }}>Coil</option>
                        <option value="accessory" {{ $product->type == 'accessory' ? 'selected' : '' }}>Accessory</option>
                    </select>
                </div>
            </div>

            <!-- Specifications (simplified) -->
            <h6 class="fw-semibold mt-3 mb-2"><i class="bi bi-gear me-1"></i>Specifications</h6>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Nicotine Strength</label>
                    <input type="text" name="nicotine_strength" class="form-control" value="{{ $product->nicotine_strength }}">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Puff Count</label>
                    <input type="number" name="puff_count" class="form-control" value="{{ $product->puff_count }}">
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Battery (mAh)</label>
                    <input type="number" name="battery_capacity" class="form-control" value="{{ $product->battery_capacity }}">
                </div>
            </div>

            <!-- Pricing -->
            <h6 class="fw-semibold mt-3 mb-2"><i class="bi bi-currency-dollar me-1"></i>Pricing</h6>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Selling Price (₱) *</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <label class="form-label">Cost Price (₱)</label>
                    <div class="input-group">
                        <span class="input-group-text">₱</span>
                        <input type="number" step="0.01" name="cost" class="form-control" value="{{ $product->cost }}">
                    </div>
                </div>
            </div>

            <!-- Image (simplified) -->
            <h6 class="fw-semibold mt-3 mb-2"><i class="bi bi-image me-1"></i>Product Image</h6>
            <div class="mb-2">
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Leave empty to keep current image</small>
            </div>
            <div class="mb-2">
                <input type="url" name="image_url" class="form-control" placeholder="Google Drive URL" value="{{ $product->image_url }}">
                <div class="form-check mt-1">
                    <input type="checkbox" name="remove_image" class="form-check-input" id="modalRemoveImage" value="1">
                    <label class="form-check-label text-danger" for="modalRemoveImage">Remove current image</label>
                </div>
            </div>

            <div class="alert alert-info small mt-2">
                <i class="bi bi-info-circle me-1"></i> Flavors can be managed from the full edit page.
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" form="editProductForm">
            <i class="bi bi-save me-1"></i> Update Product
        </button>
    </div>
</div>

<script>
    // Handle brand and category toggles inside modal
    document.getElementById('modalBrand')?.addEventListener('change', function() {
        const customRow = document.getElementById('modalCustomBrandRow');
        customRow.style.display = this.value === 'Other' ? 'block' : 'none';
    });
    document.getElementById('modalCategory')?.addEventListener('change', function() {
        const newRow = document.getElementById('modalNewCategoryRow');
        newRow.style.display = this.value === 'New' ? 'block' : 'none';
    });
</script>