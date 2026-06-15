@extends('layouts.admin-modal')

@section('title', 'Edit Product')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i> Edit Product
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body" style="padding: 0;">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
            id="editProductForm">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="info-card">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-info-circle"></i> Basic Information</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-minimal">Product Name *</label>
                            <input type="text" name="name" class="form-control-minimal" value="{{ $product->name }}"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-minimal">Brand *</label>
                            <select name="brand" class="form-select-minimal" id="modalBrand">
                                <option value="X-Vape" {{ $product->brand == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                                <option value="Slimbar" {{ $product->brand == 'Slimbar' ? 'selected' : '' }}>Slimbar
                                </option>
                                <option value="Relx" {{ $product->brand == 'Relx' ? 'selected' : '' }}>Relx</option>
                                <option value="Other">Other (Custom)</option>
                            </select>
                        </div>
                    </div>
                    <div id="modalCustomBrandRow" style="display: {{ $product->brand == 'Other' ? 'block' : 'none' }};"
                        class="mb-3">
                        <label class="form-label-minimal">Custom Brand Name</label>
                        <input type="text" name="custom_brand" class="form-control-minimal"
                            value="{{ $product->brand }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label-minimal">Description</label>
                        <textarea name="description" class="form-control-minimal" rows="2">{{ $product->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Category *</label>
                            <select name="category" class="form-select-minimal" id="modalCategory">
                                <option value="Ultra" {{ $product->category == 'Ultra' ? 'selected' : '' }}>X-Vape Ultra
                                </option>
                                <option value="Slimbar" {{ $product->category == 'Slimbar' ? 'selected' : '' }}>Slimbar
                                </option>
                                <option value="Relx" {{ $product->category == 'Relx' ? 'selected' : '' }}>Relx</option>
                                <option value="New">New Category</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3" id="modalNewCategoryRow" style="display: none;">
                            <label class="form-label-minimal">New Category Name</label>
                            <input type="text" name="new_category" class="form-control-minimal">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Product Type *</label>
                            <select name="type" class="form-select-minimal" required>
                                <option value="pod-system" {{ $product->type == 'pod-system' ? 'selected' : '' }}>Pod
                                    System</option>
                                <option value="disposable" {{ $product->type == 'disposable' ? 'selected' : '' }}>
                                    Disposable</option>
                                <option value="mod" {{ $product->type == 'mod' ? 'selected' : '' }}>Box Mod</option>
                                <option value="liquid" {{ $product->type == 'liquid' ? 'selected' : '' }}>E-Liquid</option>
                                <option value="coil" {{ $product->type == 'coil' ? 'selected' : '' }}>Coil</option>
                                <option value="accessory" {{ $product->type == 'accessory' ? 'selected' : '' }}>Accessory
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-gear"></i> Specifications</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Nicotine Strength</label>
                            <input type="text" name="nicotine_strength" class="form-control-minimal"
                                value="{{ $product->nicotine_strength }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Puff Count</label>
                            <input type="number" name="puff_count" class="form-control-minimal"
                                value="{{ $product->puff_count }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-minimal">Battery (mAh)</label>
                            <input type="number" name="battery_capacity" class="form-control-minimal"
                                value="{{ $product->battery_capacity }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-currency-dollar"></i> Pricing</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-minimal">Selling Price (₱) *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="price" class="form-control-minimal"
                                    value="{{ $product->price }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-minimal">Cost Price (₱)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="cost" class="form-control-minimal"
                                    value="{{ $product->cost }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Image Upload -->
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-upload"></i> Upload New Image</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="mb-3">
                        <input type="file" name="image" class="form-control-minimal" accept="image/*">
                        <small class="text-muted">Upload a new image (optional). Leave empty to keep current image.</small>
                    </div>
                    @if ($product->image)
                        <div class="alert alert-info small mb-0">
                            <i class="bi bi-image me-1"></i> Current image:
                            <strong>{{ basename($product->image) }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Google Drive URL - Separate Card (Only for external URLs) -->
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-google"></i> Google Drive URL</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="mb-3">
                        <input type="url" name="image_url" class="form-control-minimal"
                            placeholder="https://drive.google.com/file/d/.../view"
                            value="{{ filter_var($product->image_url, FILTER_VALIDATE_URL) ? $product->image_url : '' }}">
                        <small class="text-muted">Paste Google Drive shareable link (e.g.,
                            https://drive.google.com/file/d/.../view)</small>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="remove_image" class="form-check-input" id="modalRemoveImage"
                            value="1">
                        <label class="form-check-label text-danger" for="modalRemoveImage">Remove current image</label>
                    </div>
                </div>
            </div>

            <div class="alert alert-info small mt-3">
                <i class="bi bi-info-circle me-1"></i> Flavors can be managed from the full edit page.
            </div>

            <div class="d-flex gap-2 justify-content-end mt-3">
                <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-update" style="width: auto;" form="editProductForm">
                    <i class="bi bi-save me-1"></i> Update Product
                </button>
            </div>
        </form>
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
@endsection
