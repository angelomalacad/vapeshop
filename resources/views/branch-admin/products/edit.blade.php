@extends('layouts.branch-admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Edit Product: {{ $product->name }}</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-pencil-square me-1"></i> Update product information and specifications
                </p>
            </div>
            <a href="{{ route('branch-admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Catalog
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <!-- Main Product Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Product Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('branch-admin.products.update', $product) }}"
                            enctype="multipart/form-data" id="editProductForm">
                            @csrf
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}"
                                        required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Brand <span class="text-danger">*</span></label>
                                    <select name="brand" class="form-select" required>
                                        <option value="X-Vape" {{ $product->brand == 'X-Vape' ? 'selected' : '' }}>X-Vape
                                        </option>
                                        <option value="Slimbar" {{ $product->brand == 'Slimbar' ? 'selected' : '' }}>Slimbar
                                        </option>
                                        <option value="Relx" {{ $product->brand == 'Relx' ? 'selected' : '' }}>Relx
                                        </option>
                                        <option value="Other"
                                            {{ !in_array($product->brand, ['X-Vape', 'Slimbar', 'Relx']) ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row" id="customBrandRow"
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
                                <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category" class="form-select" required>
                                        <option value="Ultra" {{ $product->category == 'Ultra' ? 'selected' : '' }}>X-Vape
                                            Ultra</option>
                                        <option value="Slimbar" {{ $product->category == 'Slimbar' ? 'selected' : '' }}>
                                            Slimbar</option>
                                        <option value="Relx" {{ $product->category == 'Relx' ? 'selected' : '' }}>Relx
                                        </option>
                                        <option value="New"
                                            {{ !in_array($product->category, ['Ultra', 'Slimbar', 'Relx']) ? 'selected' : '' }}>
                                            New Category</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3" id="newCategoryRow"
                                    style="display: {{ !in_array($product->category, ['Ultra', 'Slimbar', 'Relx']) ? 'block' : 'none' }};">
                                    <label class="form-label">New Category Name</label>
                                    <input type="text" name="new_category" class="form-control"
                                        value="{{ !in_array($product->category, ['Ultra', 'Slimbar', 'Relx']) ? $product->category : '' }}"
                                        placeholder="Enter new category name">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Product Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="pod-system" {{ $product->type == 'pod-system' ? 'selected' : '' }}>
                                            Pod System</option>
                                        <option value="disposable" {{ $product->type == 'disposable' ? 'selected' : '' }}>
                                            Disposable</option>
                                        <option value="mod" {{ $product->type == 'mod' ? 'selected' : '' }}>Box Mod
                                        </option>
                                        <option value="liquid" {{ $product->type == 'liquid' ? 'selected' : '' }}>E-Liquid
                                        </option>
                                        <option value="coil" {{ $product->type == 'coil' ? 'selected' : '' }}>Coil
                                        </option>
                                        <option value="accessory" {{ $product->type == 'accessory' ? 'selected' : '' }}>
                                            Accessory</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Specifications Section -->
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bi bi-gear me-2"></i> Product Specifications</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Nicotine Strength</label>
                                            <input type="text" name="nicotine_strength" class="form-control"
                                                value="{{ $product->nicotine_strength }}"
                                                placeholder="e.g., 10mg, 20mg, 0mg">
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
                                                value="{{ $product->charging_type }}"
                                                placeholder="e.g., Type-C, Micro-USB">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Liquid Capacity (ml)</label>
                                            <input type="number" step="0.1" name="liquid_capacity"
                                                class="form-control" value="{{ $product->liquid_capacity }}"
                                                placeholder="e.g., 10">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" id="adjustable_airflow"
                                                    name="adjustable_airflow" value="1"
                                                    {{ $product->adjustable_airflow ? 'checked' : '' }}>
                                                <label class="form-check-label" for="adjustable_airflow">
                                                    Adjustable Airflow
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="smart_display"
                                                    name="smart_display" value="1"
                                                    {{ $product->smart_display ? 'checked' : '' }}>
                                                <label class="form-check-label" for="smart_display">
                                                    Smart Display
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Selling Price (₱) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" name="price" class="form-control"
                                            value="{{ $product->price }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cost Price (₱)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" name="cost" class="form-control"
                                            value="{{ $product->cost }}" placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Your purchase cost (for profit calculation)</small>
                                </div>
                            </div>

                            <!-- Product Image -->
                            <div class="mb-3">
                                <label class="form-label">Product Image</label>
                                @if ($product->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            style="max-height: 100px;" class="img-thumbnail">
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">Leave empty to keep current image</small>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('branch-admin.products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Flavors Management Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-droplet me-2"></i> Item or Flavors
                            ({{ $product->flavors->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if ($product->flavors->count() > 0)
                            <div class="list-group mb-3">
                                @foreach ($product->flavors as $flavor)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $flavor->name }}</strong>
                                            @if ($flavor->code)
                                                <br><small class="text-muted">Code: {{ $flavor->code }}</small>
                                            @endif
                                            @if ($flavor->category)
                                                <br><small class="text-muted">Category: {{ $flavor->category }}</small>
                                            @endif
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ $flavor->code ?? '—' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">No flavors added yet</p>
                        @endif

                        <hr>
                        <h6 class="mb-3">Add New Flavor</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" class="form-control form-control-sm" placeholder="Flavor name"
                                    id="newFlavorName">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" placeholder="Code"
                                    id="newFlavorCode">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-success w-100"
                                    onclick="alert('Flavor management is handled in product creation. Edit flavors by recreating the product.')">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Note: Flavors can only be added during product creation</small>
                    </div>
                </div>

                <!-- Inventory Status Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Inventory Status</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $branchInventory = $product->branchInventories
                                ->where('branch_id', Auth::user()->branch_id)
                                ->first();
                        @endphp

                        @if ($branchInventory)
                            <div class="mb-3">
                                <label class="form-label">Current Stock</label>
                                <h3>{{ $branchInventory->quantity }} units</h3>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Low Stock Threshold</label>
                                <p>{{ $branchInventory->low_stock_threshold }} units</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Restocked</label>
                                <p>{{ $branchInventory->last_restocked_at ? $branchInventory->last_restocked_at->format('M d, Y') : 'Never' }}
                                </p>
                            </div>
                            <a href="{{ route('branch-admin.inventory.add-stock', $branchInventory) }}"
                                class="btn btn-sm btn-success w-100">
                                <i class="bi bi-plus-circle"></i> Add Stock
                            </a>
                        @else
                            <p class="text-muted text-center py-3">This product is not in your branch inventory.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Show/hide custom brand field
        document.querySelector('select[name="brand"]').addEventListener('change', function() {
            const customBrandRow = document.getElementById('customBrandRow');
            if (this.value === 'Other') {
                customBrandRow.style.display = 'block';
            } else {
                customBrandRow.style.display = 'none';
            }
        });

        // Show/hide new category field
        document.querySelector('select[name="category"]').addEventListener('change', function() {
            const newCategoryRow = document.getElementById('newCategoryRow');
            if (this.value === 'New') {
                newCategoryRow.style.display = 'block';
            } else {
                newCategoryRow.style.display = 'none';
            }
        });
    </script>
@endpush
