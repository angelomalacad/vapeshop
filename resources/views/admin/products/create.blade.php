@extends('layouts.admin')

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
            <a href="{{ url()->previous() }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <!-- Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Basic Information -->
                    <h5 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Basic Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Product Name *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Brand *</label>
                            <select name="brand" class="form-select @error('brand') is-invalid @enderror" id="brand"
                                required>
                                <option value="">Select Brand</option>
                                <option value="X-Vape" {{ old('brand') == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                                <option value="Slimbar" {{ old('brand') == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                                <option value="Relx" {{ old('brand') == 'Relx' ? 'selected' : '' }}>Relx</option>
                                <option value="Other">Other (Custom)</option>
                            </select>
                            @error('brand')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row" id="customBrandRow"
                        style="display: {{ old('brand') == 'Other' ? 'block' : 'none' }};">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Custom Brand Name</label>
                            <input type="text" name="custom_brand" class="form-control"
                                value="{{ old('custom_brand') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Category *</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror"
                                id="category" required>
                                <option value="">Select Category</option>
                                <option value="Ultra" {{ old('category') == 'Ultra' ? 'selected' : '' }}>X-Vape Ultra
                                </option>
                                <option value="Slimbar" {{ old('category') == 'Slimbar' ? 'selected' : '' }}>Slimbar
                                </option>
                                <option value="Relx" {{ old('category') == 'Relx' ? 'selected' : '' }}>Relx</option>
                                <option value="New">New Category</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3" id="newCategoryRow"
                            style="display: {{ old('category') == 'New' ? 'block' : 'none' }};">
                            <label class="form-label fw-semibold">New Category Name</label>
                            <input type="text" name="new_category" class="form-control"
                                value="{{ old('new_category') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Product Type *</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="pod-system" {{ old('type') == 'pod-system' ? 'selected' : '' }}>Pod System
                                </option>
                                <option value="disposable" {{ old('type') == 'disposable' ? 'selected' : '' }}>Disposable
                                </option>
                                <option value="mod">Box Mod</option>
                                <option value="liquid">E-Liquid</option>
                                <option value="coil">Coil</option>
                                <option value="accessory">Accessory</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Specifications -->
                    <h5 class="fw-semibold mt-4 mb-3"><i class="bi bi-gear me-2 text-primary"></i>Specifications</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nicotine Strength</label>
                            <input type="text" name="nicotine_strength" class="form-control"
                                value="{{ old('nicotine_strength') }}" placeholder="e.g., 10mg, 20mg">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Puff Count</label>
                            <input type="number" name="puff_count" class="form-control" value="{{ old('puff_count') }}"
                                placeholder="e.g., 10000">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Battery Capacity (mAh)</label>
                            <input type="number" name="battery_capacity" class="form-control"
                                value="{{ old('battery_capacity') }}" placeholder="e.g., 650">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Charging Type</label>
                            <input type="text" name="charging_type" class="form-control"
                                value="{{ old('charging_type') }}" placeholder="e.g., Type-C">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Liquid Capacity (ml)</label>
                            <input type="number" step="0.1" name="liquid_capacity" class="form-control"
                                value="{{ old('liquid_capacity') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="adjustable_airflow" class="form-check-input"
                                    id="adjustable_airflow" value="1"
                                    {{ old('adjustable_airflow') ? 'checked' : '' }}>
                                <label class="form-check-label" for="adjustable_airflow">Adjustable Airflow</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="smart_display" class="form-check-input" id="smart_display"
                                    value="1" {{ old('smart_display') ? 'checked' : '' }}>
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
                                    <input type="text" class="form-control" name="flavors[0][name]"
                                        placeholder="Flavor name">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="flavors[0][code]"
                                        placeholder="Code">
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
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-flavor"
                                        onclick="removeFlavor(this)">
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
                            <label class="form-label fw-semibold">Selling Price (₱) *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="price"
                                    class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}"
                                    required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cost Price (₱)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="cost" class="form-control"
                                    value="{{ old('cost') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <h5 class="fw-semibold mt-4 mb-3"><i class="bi bi-image me-2 text-primary"></i>Product Image</h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="mb-3 text-center">
                                    <img id="imagePreview" src="https://via.placeholder.com/200x200?text=No+Image"
                                        alt="Preview" style="max-height: 200px; object-fit: contain;">
                                </div>
                                <ul class="nav nav-tabs" id="imageTab" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="upload-tab" data-bs-toggle="tab"
                                            data-bs-target="#upload" type="button">Upload File</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="gdrive-tab" data-bs-toggle="tab"
                                            data-bs-target="#gdrive" type="button">Google Drive Link</button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3">
                                    <div class="tab-pane active" id="upload">
                                        <input type="file" name="image" class="form-control" accept="image/*"
                                            onchange="previewImage(this)">
                                    </div>
                                    <div class="tab-pane" id="gdrive">
                                        <input type="url" name="image_url" class="form-control"
                                            placeholder="https://drive.google.com/file/d/.../view"
                                            onchange="previewGdrive(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Submit Buttons with Dashboard -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.products.index') }}"
                            class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <div>
                            <button type="submit" class="btn btn-primary rounded-pill px-5">
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

        function addFlavor() {
            const container = document.getElementById('flavors-container');
            const html = `
            <div class="flavor-item mb-2">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="flavors[${flavorIndex}][name]" placeholder="Flavor name">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="flavors[${flavorIndex}][code]" placeholder="Code">
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
        }

        function removeFlavor(button) {
            button.closest('.flavor-item').remove();
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
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
                document.getElementById('imagePreview').src = url;
            }
        }

        document.getElementById('brand').addEventListener('change', function() {
            document.getElementById('customBrandRow').style.display = this.value === 'Other' ? 'block' : 'none';
        });

        document.getElementById('category').addEventListener('change', function() {
            document.getElementById('newCategoryRow').style.display = this.value === 'New' ? 'block' : 'none';
        });
    </script>
@endpush
