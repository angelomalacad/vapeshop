@extends('layouts.branch-admin')

@section('title', 'Create Product - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Create New Product</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-plus-circle me-1"></i> Add a new product to the catalog
                </p>
            </div>
        </div>
        <a href="{{ route('branch-admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('branch-admin.products.store') }}" enctype="multipart/form-data" id="productForm">
                @csrf

                <!-- Basic Information -->
                <h5 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Basic Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Brand <span class="text-danger">*</span></label>
                        <select name="brand" class="form-select @error('brand') is-invalid @enderror" id="brand" required>
                            <option value="">Select Brand</option>
                            <option value="X-Vape" {{ old('brand') == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                            <option value="Slimbar" {{ old('brand') == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                            <option value="Relx" {{ old('brand') == 'Relx' ? 'selected' : '' }}>Relx</option>
                            <option value="Other">Other (Custom)</option>
                        </select>
                        <small class="text-muted"><i class="bi bi-info-circle"></i> X-Vape: High-performance disposables, Slimbar: Sleek pod systems, Relx: Popular pod systems</small>
                        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row" id="customBrandRow" style="display: {{ old('brand') == 'Other' ? 'block' : 'none' }};">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Custom Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="custom_brand" class="form-control" value="{{ old('custom_brand') }}" maxlength="255">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Model <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" id="category" required disabled>
                            <option value="">Select Model</option>
                        </select>
                        <small class="text-muted" id="modelHelp"><i class="bi bi-info-circle"></i> Models depend on selected brand</small>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 mb-3" id="newCategoryRow" style="display: none;">
                        <label class="form-label fw-semibold">New Model Name <span class="text-danger">*</span></label>
                        <input type="text" name="new_category" class="form-control" value="{{ old('new_category') }}" maxlength="255">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Product Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="pod-system" {{ old('type') == 'pod-system' ? 'selected' : '' }}>Pod System</option>
                            <option value="disposable" {{ old('type') == 'disposable' ? 'selected' : '' }}>Disposable</option>
                            <option value="mod">Box Mod</option>
                            <option value="liquid">E-Liquid</option>
                            <option value="coil">Coil</option>
                            <option value="accessory">Accessory</option>
                        </select>
                        <small class="text-muted"><i class="bi bi-info-circle"></i> Pod System: Refillable devices, Disposable: Single-use devices, Box Mod: Advanced mods, E-Liquid: Vape juices, Coil: Replacement coils, Accessory: Other accessories</small>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Specifications -->
                <h5 class="fw-semibold mt-4 mb-3"><i class="bi bi-gear me-2 text-primary"></i>Specifications</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nicotine Strength (mg)</label>
                        <input type="number" name="nicotine_strength" class="form-control" value="{{ old('nicotine_strength') }}" min="0" max="50" step="1" onkeydown="return event.key !== '-'">
                        <small class="text-muted">0-50 mg, whole numbers only</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Puff Count</label>
                        <input type="number" name="puff_count" class="form-control" value="{{ old('puff_count') }}" min="0" max="50000" step="1" onkeydown="return event.key !== '-'">
                        <small class="text-muted">Maximum 50,000 puffs</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Battery Capacity (mAh)</label>
                        <input type="number" name="battery_capacity" class="form-control" value="{{ old('battery_capacity') }}" min="0" max="10000" step="1" onkeydown="return event.key !== '-'">
                        <small class="text-muted">Maximum 10,000 mAh</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Charging Type</label>
                        <select name="charging_type" class="form-select">
                            <option value="">Select Charging Type</option>
                            <option value="Type-C" {{ old('charging_type') == 'Type-C' ? 'selected' : '' }}>Type-C</option>
                            <option value="Micro-USB" {{ old('charging_type') == 'Micro-USB' ? 'selected' : '' }}>Micro-USB</option>
                            <option value="Lightning" {{ old('charging_type') == 'Lightning' ? 'selected' : '' }}>Lightning</option>
                            <option value="Magnetic" {{ old('charging_type') == 'Magnetic' ? 'selected' : '' }}>Magnetic</option>
                            <option value="Wireless" {{ old('charging_type') == 'Wireless' ? 'selected' : '' }}>Wireless</option>
                            <option value="None">None / N/A</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Liquid Capacity (ml)</label>
                        <input type="number" name="liquid_capacity" class="form-control" value="{{ old('liquid_capacity') }}" min="0" max="100" step="0.1" onkeydown="return event.key !== '-'">
                        <small class="text-muted">Maximum 100 ml, up to 2 decimal places</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="adjustable_airflow" class="form-check-input" id="adjustable_airflow" value="1" {{ old('adjustable_airflow') ? 'checked' : '' }}>
                            <label class="form-check-label" for="adjustable_airflow">Adjustable Airflow</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="smart_display" class="form-check-input" id="smart_display" value="1" {{ old('smart_display') ? 'checked' : '' }}>
                            <label class="form-check-label" for="smart_display">Smart Display</label>
                        </div>
                    </div>
                </div>

                <!-- Flavors -->
                <h5 class="fw-semibold mt-4 mb-3"><i class="bi bi-droplet me-2 text-primary"></i>Flavors</h5>
                <div id="flavors-container">
                    <div class="flavor-item mb-2">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="flavors[0][name]" placeholder="Flavor name" maxlength="100">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="flavors[0][code]" placeholder="Code" maxlength="50">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="flavors[0][category]">
                                    <option value="">Category</option>
                                    <option value="fruit">Fruit</option>
                                    <option value="mint">Mint</option>
                                    <option value="tea">Tea</option>
                                    <option value="dessert">Dessert</option>
                                    <option value="tobacco">Tobacco</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-flavor" onclick="removeFlavor(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addFlavor()">
                    <i class="bi bi-plus-circle"></i> Add Flavor
                </button>

                <!-- Price -->
                <h5 class="fw-semibold mt-4 mb-3"><i class="bi bi-currency-dollar me-2 text-primary"></i>Pricing</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Selling Price (₱) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" min="0" required onkeydown="return event.key !== '-'">
                        </div>
                        <small class="text-muted">Maximum ₱100,000</small>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cost Price (₱)</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="cost" class="form-control" value="{{ old('cost') }}" min="0" onkeydown="return event.key !== '-'">
                        </div>
                    </div>
                </div>

                <!-- Stock -->
                <h5 class="fw-semibold mt-4 mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Stock Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Initial Stock Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', 0) }}" min="0" required onkeydown="return event.key !== '-'">
                        <small class="text-muted">This will be added to your branch inventory</small>
                        @error('stock_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" class="form-control" value="{{ old('low_stock_threshold', 10) }}" min="1" onkeydown="return event.key !== '-'">
                        <small class="text-muted">Alert when stock reaches this level</small>
                    </div>
                </div>

                <!-- Image -->
                <h5 class="fw-semibold mt-4 mb-3"><i class="bi bi-image me-2 text-primary"></i>Product Image</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="border rounded p-3 bg-light">
                            <div class="mb-3 text-center">
                                <img id="imagePreview" src="https://via.placeholder.com/200x200?text=No+Image" alt="Preview" style="max-height: 200px; object-fit: contain;">
                            </div>
                            <ul class="nav nav-tabs" id="imageTab" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload" type="button">Upload File</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="gdrive-tab" data-bs-toggle="tab" data-bs-target="#gdrive" type="button">Google Drive Link</button>
                                </li>
                            </ul>
                            <div class="tab-content mt-3">
                                <div class="tab-pane active" id="upload">
                                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                                    <small class="text-muted">Max 2MB. JPG, PNG, GIF</small>
                                </div>
                                <div class="tab-pane" id="gdrive">
                                    <input type="url" name="image_url" class="form-control" placeholder="https://drive.google.com/file/d/.../view" onchange="previewGdrive(this)">
                                    <small class="text-muted">Make sure the file is shared publicly</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('branch-admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <div>
                        <a href="{{ route('branch-admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5" onclick="return validateForm()">
                            <i class="bi bi-check-circle me-1"></i> Create Product
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let flavorIndex = 1;

    // Model options based on brand
    const modelOptions = {
        'X-Vape': [
            { value: 'Ultra', label: 'X-Vape Ultra' },
            { value: 'Pro', label: 'X-Vape Pro' },
            { value: 'Max', label: 'X-Vape Max' }
        ],
        'Slimbar': [
            { value: 'Slimbar Original', label: 'Slimbar Original' },
            { value: 'Slimbar Pro', label: 'Slimbar Pro' },
            { value: 'Slimbar Max', label: 'Slimbar Max' }
        ],
        'Relx': [
            { value: 'Relx Classic', label: 'Relx Classic' },
            { value: 'Relx Infinity', label: 'Relx Infinity' },
            { value: 'Relx Essential', label: 'Relx Essential' }
        ],
        'Other': []
    };

    // Brand change handler - updates model dropdown
    document.getElementById('brand').addEventListener('change', function() {
        const brand = this.value;
        const categorySelect = document.getElementById('category');
        const newCategoryRow = document.getElementById('newCategoryRow');
        const modelHelp = document.getElementById('modelHelp');
        
        if (brand && brand !== 'Other' && modelOptions[brand]) {
            categorySelect.innerHTML = '<option value="">Select Model</option>';
            modelOptions[brand].forEach(model => {
                categorySelect.innerHTML += `<option value="${model.value}">${model.label}</option>`;
            });
            categorySelect.disabled = false;
            categorySelect.required = true;
            newCategoryRow.style.display = 'none';
            modelHelp.innerHTML = '<i class="bi bi-info-circle"></i> Select a model for ' + brand;
        } else if (brand === 'Other') {
            categorySelect.innerHTML = '<option value="">Select Model</option>';
            categorySelect.disabled = true;
            categorySelect.required = false;
            newCategoryRow.style.display = 'block';
            modelHelp.innerHTML = '<i class="bi bi-info-circle"></i> Enter a custom model name below';
        } else {
            categorySelect.innerHTML = '<option value="">Select Model</option>';
            categorySelect.disabled = true;
            categorySelect.required = false;
            newCategoryRow.style.display = 'none';
            modelHelp.innerHTML = '<i class="bi bi-info-circle"></i> Models depend on selected brand';
        }
        
        document.getElementById('customBrandRow').style.display = brand === 'Other' ? 'block' : 'none';
    });

    // Form validation
    function validateForm() {
        const requiredFields = document.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        const brand = document.getElementById('brand').value;
        if (brand === 'Other') {
            const customBrand = document.querySelector('input[name="custom_brand"]');
            if (!customBrand.value.trim()) {
                customBrand.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (brand && brand !== 'Other') {
            const model = document.getElementById('category').value;
            if (!model) {
                document.getElementById('category').classList.add('is-invalid');
                isValid = false;
            }
        } else if (brand === 'Other') {
            const newModel = document.querySelector('input[name="new_category"]');
            if (!newModel.value.trim()) {
                newModel.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (!isValid) {
            alert('Please fill in all required fields.');
        }
        
        return isValid;
    }

    // Limit decimal places for price inputs
    document.querySelectorAll('input[type="number"][step="0.01"]').forEach(input => {
        input.addEventListener('change', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });

    // Prevent negative numbers
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.key === 'e') {
                e.preventDefault();
            }
        });
    });

    // Add max attribute to number inputs if not set
    document.querySelectorAll('input[type="number"]').forEach(input => {
        if (!input.hasAttribute('max')) {
            if (input.name === 'puff_count') input.max = 50000;
            if (input.name === 'battery_capacity') input.max = 10000;
            if (input.name === 'price') input.max = 100000;
            if (input.name === 'cost') input.max = 100000;
            if (input.name === 'liquid_capacity') input.max = 100;
            if (input.name === 'nicotine_strength') input.max = 50;
            if (input.name === 'stock_quantity') input.max = 999999;
        }
    });

    function addFlavor() {
        const container = document.getElementById('flavors-container');
        const html = `
            <div class="flavor-item mb-2">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="flavors[${flavorIndex}][name]" placeholder="Flavor name" maxlength="100">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="flavors[${flavorIndex}][code]" placeholder="Code" maxlength="50">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="flavors[${flavorIndex}][category]">
                            <option value="">Category</option>
                            <option value="fruit">Fruit</option>
                            <option value="mint">Mint</option>
                            <option value="tea">Tea</option>
                            <option value="dessert">Dessert</option>
                            <option value="tobacco">Tobacco</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-flavor" onclick="removeFlavor(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        flavorIndex++;
        updateFlavorPreview();
    }

    function removeFlavor(button) {
        button.closest('.flavor-item').remove();
        updateFlavorPreview();
    }

    function updateFlavorPreview() {
        const preview = document.getElementById('flavorPreview');
        if (!preview) return;
        const flavors = document.querySelectorAll('input[name*="[name]"]');
        let html = '<strong>Flavors to be added:</strong> ';
        let count = 0;
        flavors.forEach(flavor => {
            if (flavor.value.trim()) {
                html += `<span class="flavor-tag">${flavor.value}</span> `;
                count++;
            }
        });
        if (count === 0) {
            html += '<small class="text-muted">No flavors specified</small>';
        }
        preview.innerHTML = html;
    }

    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('flavors')) {
            updateFlavorPreview();
        }
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const fileSize = input.files[0].size / 1024 / 1024;
            if (fileSize > 2) {
                alert('File size exceeds 2MB. Please choose a smaller image.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewGdrive(input) {
        const url = input.value;
        if (url) {
            let directUrl = url;
            if (url.includes('drive.google.com')) {
                const fileId = extractGoogleDriveId(url);
                if (fileId) {
                    directUrl = `https://drive.google.com/uc?export=view&id=${fileId}`;
                }
            }
            document.getElementById('imagePreview').src = directUrl;
        }
    }

    function extractGoogleDriveId(url) {
        const patterns = [
            /\/d\/([a-zA-Z0-9_-]+)/,
            /id=([a-zA-Z0-9_-]+)/,
            /\/folders\/([a-zA-Z0-9_-]+)/
        ];
        for (let pattern of patterns) {
            const match = url.match(pattern);
            if (match) return match[1];
        }
        return null;
    }

    function previewGdriveImage() {
        const url = document.getElementById('image_url').value;
        const preview = document.getElementById('imagePreview');
        if (!url) {
            alert('Please enter a Google Drive URL');
            return;
        }
        let directUrl = url;
        if (url.includes('drive.google.com')) {
            const fileId = extractGoogleDriveId(url);
            if (fileId) {
                directUrl = `https://drive.google.com/uc?export=view&id=${fileId}`;
            }
        }
        preview.src = directUrl;
    }

    // Trigger brand change on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(old('brand'))
            document.getElementById('brand').value = "{{ old('brand') }}";
            document.getElementById('brand').dispatchEvent(new Event('change'));
        @endif
        
        updateFlavorPreview();
    });
</script>
@endpush