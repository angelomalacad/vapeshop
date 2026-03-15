<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product - Vape Expo</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            width: 260px;
            background: linear-gradient(145deg, #f5f7fa 0%, #e9ecef 100%);
            box-shadow: 2px 0 15px rgba(0,0,0,0.03);
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #4a5568;
            padding: 0.85rem 1.25rem;
            transition: all 0.2s ease;
            margin: 2px 8px;
            border-radius: 8px;
        }
        
        .sidebar .nav-link:hover {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.05);
        }
        
        .sidebar .nav-link.active {
            color: #0d6efd;
            background: rgba(13, 110, 253, 0.1);
            font-weight: 600;
        }
        
        .sidebar .nav-link i {
            color: #0d6efd;
            margin-right: 0.75rem;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        .sidebar-heading {
            font-size: .7rem;
            text-transform: uppercase;
            color: #6c757d;
            padding: 0.5rem 1.25rem;
            margin-top: 1rem;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .main-content {
            margin-left: 260px;
            padding: 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .navbar-brand {
            padding: 1rem 1.25rem;
            font-size: 1.2rem;
            background: rgba(255,255,255,0.5);
            width: 260px;
            text-align: left;
            color: #2c3e50 !important;
            font-weight: 700;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .navbar-brand small {
            font-size: 0.8rem;
            color: #6c757d;
            display: block;
            margin-top: 0.2rem;
            font-weight: normal;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .main-content {
                margin-left: 0;
            }
            .navbar-brand {
                width: 100%;
            }
        }
        
        .branch-info-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        
        .branch-info-card i {
            color: #0d6efd;
            margin-right: 0.5rem;
            width: 20px;
        }
        
        .branch-info-card div {
            color: #2c3e50;
            font-size: 0.9rem;
        }
        
        .branch-info-card .small {
            color: #6c757d;
        }
        
        .top-navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .badge-count {
            background: #0d6efd;
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            margin-left: 0.5rem;
            font-weight: 500;
        }
        
        .footer-info {
            margin: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            color: #2c3e50;
            font-size: 0.9rem;
        }
        
        .footer-info i {
            color: #0d6efd;
            margin-right: 0.5rem;
            width: 20px;
        }
        
        .footer-info div {
            margin-bottom: 0.5rem;
        }
        
        .owner-name {
            color: #0d6efd;
            font-weight: 600;
        }
        
        .btn-outline-primary {
            border: 1px solid #0d6efd;
            color: #0d6efd;
            border-radius: 20px;
            padding: 0.3rem 1rem;
        }
        
        .btn-outline-primary:hover {
            background: #0d6efd;
            color: white;
        }
        
        .text-primary-custom {
            color: #0d6efd;
        }
        
        /* Soft shadows and rounded corners */
        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            border-radius: 12px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            padding: 1rem 1.25rem;
        }
        
        .list-group-item {
            border: none;
            margin-bottom: 2px;
            border-radius: 8px !important;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 12px;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        
        .flavor-section {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
            border-left: 4px solid #0d6efd;
        }
        
        .add-flavor-btn {
            border: 2px dashed #dee2e6;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s;
        }
        
        .add-flavor-btn:hover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        
        .flavor-item {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        
        .remove-flavor {
            cursor: pointer;
            color: #dc3545;
        }
        
        .remove-flavor:hover {
            color: #bb2d3b;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.1);
        }
        
        .btn-primary {
            background: #0d6efd;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
        }
        
        .btn-primary:hover {
            background: #0b5ed7;
        }
        
        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
            border-radius: 8px;
        }
        
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .flavor-preview {
            background: white;
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            border: 1px solid #dee2e6;
        }
        
        .flavor-tag {
            display: inline-block;
            background: #e7f1ff;
            color: #0d6efd;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            margin: 0.25rem;
            font-size: 0.85rem;
        }
        
        .flavor-tag i {
            cursor: pointer;
            margin-left: 0.25rem;
        }
        
        /* NEW: Image preview and tab styles */
        .image-preview-container {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 2px dashed #dee2e6;
            text-align: center;
        }
        
        .image-preview {
            max-height: 200px;
            max-width: 100%;
            object-fit: contain;
            border-radius: 8px;
        }
        
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            padding: 0.75rem 1rem;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link:hover {
            border: none;
            color: #0d6efd;
        }
        
        .nav-tabs .nav-link.active {
            color: #0d6efd;
            background: transparent;
            border-bottom: 2px solid #0d6efd;
        }
        
        .tab-pane {
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar d-md-block">
        <div class="navbar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="30" class="me-2">
            VAPE EXPO
            <br>
            <small>{{ Auth::user()->branch->name ?? 'Branch' }}</small>
        </div>
        
        <div class="sidebar-sticky">
            <!-- Branch Staff Info -->
            <div class="branch-info-card">
                <div><i class="bi bi-person-circle"></i> {{ Auth::user()->name }}</div>
                <div class="small mt-1"><i class="bi bi-envelope"></i> {{ Auth::user()->email }}</div>
                <div class="small mt-1"><i class="bi bi-shield-check"></i> Branch Staff</div>
            </div>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                
                <li class="sidebar-heading">INVENTORY</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.inventory.index') }}">
                        <i class="bi bi-box-seam"></i> Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.inventory.add-product') }}">
                        <i class="bi bi-plus-circle"></i> Add Stock
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.inventory.low-stock') }}">
                        <i class="bi bi-exclamation-triangle"></i> Low Stock
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.inventory.transfer.form') }}">
                        <i class="bi bi-arrow-left-right"></i> Transfers
                    </a>
                </li>
                
                <li class="sidebar-heading">PRODUCTS</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('branch-admin.products.index') }}">
                        <i class="bi bi-tags"></i> Catalog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('branch-admin.products.create') }}">
                        <i class="bi bi-plus-lg"></i> New Product
                    </a>
                </li>
                
                <li class="sidebar-heading">ACCOUNT</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation Bar -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Create New Product</h5>
                <small class="text-muted">{{ Auth::user()->branch->name }}</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted small">
                    <i class="bi bi-calendar3 me-1"></i> {{ now()->format('M d, Y') }}
                </span>
                <span class="me-3 text-muted small">
                    <i class="bi bi-clock me-1"></i> {{ now()->format('h:i A') }}
                </span>
                
                <!-- Low Stock Quick View -->
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-exclamation-triangle"></i> Stock
                        <span class="badge-count" id="lowStockBadge">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('branch-admin.inventory.low-stock') }}">View Low Stock</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> 
                <strong>Please fix the following errors:</strong>
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Main Content - Create Product Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Product Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('branch-admin.products.store') }}" enctype="multipart/form-data" id="productForm">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="e.g., X-Vape Ultra, Slimbar, Relx" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="brand" class="form-label">Brand *</label>
                            <select class="form-select @error('brand') is-invalid @enderror" id="brand" name="brand" required>
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
                    
                    <div class="row" id="customBrandRow" style="display: {{ old('brand') == 'Other' ? 'block' : 'none' }};">
                        <div class="col-md-12 mb-3">
                            <label for="custom_brand" class="form-label">Custom Brand Name</label>
                            <input type="text" class="form-control" id="custom_brand" name="custom_brand" 
                                   value="{{ old('custom_brand') }}" placeholder="Enter custom brand name">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Product description...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Ultra" {{ old('category') == 'Ultra' ? 'selected' : '' }}>X-Vape Ultra</option>
                                <option value="Slimbar" {{ old('category') == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                                <option value="Relx" {{ old('category') == 'Relx' ? 'selected' : '' }}>Relx</option>
                                <option value="New">New Category</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3" id="newCategoryRow" style="display: {{ old('category') == 'New' ? 'block' : 'none' }};">
                            <label for="new_category" class="form-label">New Category Name</label>
                            <input type="text" class="form-control" id="new_category" name="new_category" 
                                   value="{{ old('new_category') }}" placeholder="Enter new category name">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="type" class="form-label">Product Type *</label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="pod-system" {{ old('type') == 'pod-system' ? 'selected' : '' }}>Pod System</option>
                                <option value="disposable" {{ old('type') == 'disposable' ? 'selected' : '' }}>Disposable</option>
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
                    
                    <!-- Specifications Section -->
                    <div class="flavor-section">
                        <h6 class="mb-3"><i class="bi bi-gear me-2"></i> Product Specifications</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="nicotine_strength" class="form-label">Nicotine Strength</label>
                                <input type="text" class="form-control" id="nicotine_strength" name="nicotine_strength" 
                                       value="{{ old('nicotine_strength') }}" placeholder="e.g., 10mg, 20mg, 0mg">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="puff_count" class="form-label">Puff Count</label>
                                <input type="number" class="form-control" id="puff_count" name="puff_count" 
                                       value="{{ old('puff_count') }}" placeholder="e.g., 10000">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="battery_capacity" class="form-label">Battery Capacity (mAh)</label>
                                <input type="number" class="form-control" id="battery_capacity" name="battery_capacity" 
                                       value="{{ old('battery_capacity') }}" placeholder="e.g., 650">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="charging_type" class="form-label">Charging Type</label>
                                <input type="text" class="form-control" id="charging_type" name="charging_type" 
                                       value="{{ old('charging_type') }}" placeholder="e.g., Type-C, Micro-USB">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="liquid_capacity" class="form-label">Liquid Capacity (ml)</label>
                                <input type="number" step="0.1" class="form-control" id="liquid_capacity" name="liquid_capacity" 
                                       value="{{ old('liquid_capacity') }}" placeholder="e.g., 10">
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="adjustable_airflow" name="adjustable_airflow" value="1" {{ old('adjustable_airflow') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="adjustable_airflow">
                                        Adjustable Airflow
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="smart_display" name="smart_display" value="1" {{ old('smart_display') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="smart_display">
                                        Smart Display
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Flavors Section - Dynamic -->
                    <div class="flavor-section">
                        <h6 class="mb-3"><i class="bi bi-droplet me-2"></i> Flavors</h6>
                        <p class="text-muted small mb-3">Add flavors for this product. These will appear in inventory when you add stock.</p>
                        <div id="flavors-container">
                            <div class="flavor-item">
                                <div class="row">
                                    <div class="col-md-5 mb-2">
                                        <input type="text" class="form-control" name="flavors[0][name]" placeholder="Flavor name (e.g., Purple Twilight)" value="{{ old('flavors.0.name') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <input type="text" class="form-control" name="flavors[0][code]" placeholder="Code (e.g., PT)" value="{{ old('flavors.0.code') }}">
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <select class="form-select" name="flavors[0][category]">
                                            <option value="">Category</option>
                                            <option value="fruit" {{ old('flavors.0.category') == 'fruit' ? 'selected' : '' }}>Fruit</option>
                                            <option value="mint" {{ old('flavors.0.category') == 'mint' ? 'selected' : '' }}>Mint</option>
                                            <option value="tea" {{ old('flavors.0.category') == 'tea' ? 'selected' : '' }}>Tea</option>
                                            <option value="dessert" {{ old('flavors.0.category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                                            <option value="beverage" {{ old('flavors.0.category') == 'beverage' ? 'selected' : '' }}>Beverage</option>
                                            <option value="tobacco" {{ old('flavors.0.category') == 'tobacco' ? 'selected' : '' }}>Tobacco</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 mb-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-flavor" onclick="removeFlavor(this)" title="Remove flavor">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addFlavor()">
                            <i class="bi bi-plus-circle"></i> Add Another Flavor
                        </button>
                        
                        <!-- Flavor Preview (for visual feedback) -->
                        <div class="flavor-preview mt-3" id="flavorPreview">
                            <small class="text-muted">Flavors will be added to product catalog</small>
                        </div>
                    </div>
                    
                    <!-- Price and Stock -->
                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Selling Price (₱) *</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" placeholder="0.00" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="cost" class="form-label">Cost Price (₱)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" class="form-control @error('cost') is-invalid @enderror" 
                                       id="cost" name="cost" value="{{ old('cost') }}" placeholder="0.00">
                            </div>
                            <small class="text-muted">Your purchase cost (for profit calculation)</small>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="stock_quantity" class="form-label">Initial Stock Quantity *</label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required>
                            <small class="text-muted">This will be added to your branch inventory</small>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                            <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                   id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 10) }}" min="1">
                            <small class="text-muted">Alert when stock reaches this level</small>
                            @error('low_stock_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Important Note about Flavors -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Note:</strong> After creating this product, you can add stock for each flavor separately in the inventory section.
                    </div>
                    
                    <!-- NEW: Product Image Section with Google Drive Support -->
                    <div class="flavor-section">
                        <h6 class="mb-3"><i class="bi bi-image me-2"></i> Product Image</h6>
                        
                        <!-- Image Preview -->
                        <div class="image-preview-container">
                            <img id="imagePreview" 
                                 src="https://via.placeholder.com/300x200?text=No+Image+Selected" 
                                 alt="Product Image Preview"
                                 class="image-preview">
                        </div>

                        <!-- Tab Navigation for Image Sources -->
                        <ul class="nav nav-tabs" id="imageTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="gdrive-tab" data-bs-toggle="tab" 
                                        data-bs-target="#gdrive" type="button" role="tab">
                                    <i class="bi bi-google"></i> Google Drive Link
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="upload-tab" data-bs-toggle="tab" 
                                        data-bs-target="#upload" type="button" role="tab">
                                    <i class="bi bi-upload"></i> Upload File
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="imageTabContent">
                            <!-- Google Drive Tab -->
                            <div class="tab-pane fade show active" id="gdrive" role="tabpanel">
                                <div class="mb-3">
                                    <label for="image_url" class="form-label">Google Drive Image URL</label>
                                    <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                           id="image_url" name="image_url" 
                                           value="{{ old('image_url') }}"
                                           placeholder="https://drive.google.com/file/d/.../view">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> 
                                        Paste the Google Drive share link. Make sure the file is shared publicly.
                                    </small>
                                    @error('image_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="previewGdriveImage()">
                                    <i class="bi bi-eye"></i> Preview Image
                                </button>
                            </div>

                            <!-- Upload File Tab -->
                            <div class="tab-pane fade" id="upload" role="tabpanel">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image File</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i> 
                                        Max 2MB. Supported formats: JPG, PNG, GIF
                                    </small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Image Guidelines -->
                        <div class="alert alert-info mt-3 small">
                            <i class="bi bi-info-circle"></i>
                            <strong>Image Guidelines:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Use Google Drive for large files (no size limit)</li>
                                <li>Make sure the image is shared publicly</li>
                                <li>Recommended size: 500x500px or larger</li>
                                <li>Clear product image on white/light background preferred</li>
                            </ul>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('branch-admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="fw-bold mb-1">About Product Categories</h6>
                        <p class="small text-muted mb-0">
                            <strong>X-Vape Ultra:</strong> 10,000 puffs, 650mAh battery, Type-C charging<br>
                            <strong>Slimbar:</strong> Sleek pod system with various flavors<br>
                            <strong>Relx:</strong> Popular pod system with 10+ flavors
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
        <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @push('scripts')
    <script>
        // Update low stock badge
        document.addEventListener('DOMContentLoaded', function() {
            const lowStockCount = {{ $lowStockCount ?? 0 }};
            document.getElementById('lowStockBadge').textContent = lowStockCount;
            
            // Initialize flavor preview
            updateFlavorPreview();
        });
        
        // Dynamic flavor addition
        let flavorIndex = 1;
        
        function addFlavor() {
            const container = document.getElementById('flavors-container');
            const newFlavor = `
                <div class="flavor-item">
                    <div class="row">
                        <div class="col-md-5 mb-2">
                            <input type="text" class="form-control" name="flavors[${flavorIndex}][name]" placeholder="Flavor name">
                        </div>
                        <div class="col-md-3 mb-2">
                            <input type="text" class="form-control" name="flavors[${flavorIndex}][code]" placeholder="Code">
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" name="flavors[${flavorIndex}][category]">
                                <option value="">Category</option>
                                <option value="fruit">Fruit</option>
                                <option value="mint">Mint</option>
                                <option value="tea">Tea</option>
                                <option value="dessert">Dessert</option>
                                <option value="beverage">Beverage</option>
                                <option value="tobacco">Tobacco</option>
                            </select>
                        </div>
                        <div class="col-md-1 mb-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-flavor" onclick="removeFlavor(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newFlavor);
            flavorIndex++;
            updateFlavorPreview();
        }
        
        function removeFlavor(button) {
            button.closest('.flavor-item').remove();
            updateFlavorPreview();
        }
        
        function updateFlavorPreview() {
            const preview = document.getElementById('flavorPreview');
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
        
        // Update preview when flavors change
        document.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.includes('flavors')) {
                updateFlavorPreview();
            }
        });
        
        // Show/hide custom fields
        document.getElementById('brand').addEventListener('change', function() {
            const customBrandRow = document.getElementById('customBrandRow');
            if (this.value === 'Other') {
                customBrandRow.style.display = 'block';
            } else {
                customBrandRow.style.display = 'none';
            }
        });
        
        document.getElementById('category').addEventListener('change', function() {
            const newCategoryRow = document.getElementById('newCategoryRow');
            if (this.value === 'New') {
                newCategoryRow.style.display = 'block';
            } else {
                newCategoryRow.style.display = 'none';
            }
        });
        
        // NEW: Google Drive image preview function
        function previewGdriveImage() {
            const url = document.getElementById('image_url').value;
            const preview = document.getElementById('imagePreview');
            
            if (!url) {
                alert('Please enter a Google Drive URL');
                return;
            }

            // Convert Google Drive URL to direct image URL
            let directUrl = url;
            
            // Handle different Google Drive URL formats
            if (url.includes('drive.google.com')) {
                const fileId = extractGoogleDriveId(url);
                if (fileId) {
                    directUrl = `https://drive.google.com/uc?export=view&id=${fileId}`;
                }
            }
            
            preview.src = directUrl;
        }

        // Extract Google Drive File ID from URL
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

        // Preview uploaded image
        document.getElementById('image')?.addEventListener('change', function(event) {
            const reader = new FileReader();
            const preview = document.getElementById('imagePreview');
            
            reader.onload = function() {
                preview.src = reader.result;
            }
            
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
                
                // Validate file size (2MB)
                const fileSize = event.target.files[0].size / 1024 / 1024;
                if (fileSize > 2) {
                    alert('File size exceeds 2MB. Please choose a smaller image.');
                    event.target.value = '';
                    preview.src = 'https://via.placeholder.com/300x200?text=No+Image+Selected';
                }
            }
        });

        // Auto-preview when Google Drive URL is pasted
        document.getElementById('image_url')?.addEventListener('paste', function() {
            setTimeout(previewGdriveImage, 100);
        });
    </script>
    @endpush
</body>
</html>
