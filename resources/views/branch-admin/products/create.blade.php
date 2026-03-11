<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            border-radius: 10px;
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
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
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #6b46a0 100%);
        }
        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
        }
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        .list-group-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .badge-info {
            background-color: #e9ecef;
            color: #495057;
        }
        .flavor-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30" class="d-inline-block align-text-top me-2">
                Vape Expo - Create Product
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-grid me-2"></i> Branch Menu
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('branch-admin.dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('branch-admin.inventory.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-box-seam me-2"></i> Inventory Management
                        </a>
                        <a href="{{ route('branch-admin.products.index') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-plus-circle me-2"></i> Products
                        </a>
                        <a href="{{ route('branch-admin.inventory.low-stock') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-exclamation-triangle me-2"></i> Low Stock Alerts
                        </a>
                        <a href="{{ route('branch-admin.inventory.transfer.form') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-arrow-left-right me-2"></i> Transfer Stock
                        </a>
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
                
                <!-- Branch Info Card -->
                <div class="card mt-3">
                    <div class="card-header">
                        <i class="bi bi-info-circle me-2"></i> Branch Information
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ Auth::user()->branch->name ?? 'Your Branch' }}</strong></p>
                        <p class="mb-0 text-muted small">
                            <i class="bi bi-telephone me-1"></i> 0960 328 0432<br>
                            <i class="bi bi-clock me-1"></i> 9:00 AM - 10:00 PM
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1">Create New Product</h1>
                        <p class="text-muted mb-0">Add a new product to Vape Expo catalog</p>
                    </div>
                    <a href="{{ route('branch-admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Products
                    </a>
                </div>
                
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Product Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('branch-admin.products.store') }}" enctype="multipart/form-data">
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
                                    <label for="brand" class="form-label">Brand</label>
                                    <select class="form-select @error('brand') is-invalid @enderror" id="brand" name="brand">
                                        <option value="X-Vape" {{ old('brand') == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                                        <option value="Slimbar" {{ old('brand') == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                                        <option value="Relx" {{ old('brand') == 'Relx' ? 'selected' : '' }}>Relx</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">Product Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="pod-system" {{ old('type') == 'pod-system' ? 'selected' : '' }}>Pod System</option>
                                        <option value="disposable" {{ old('type') == 'disposable' ? 'selected' : '' }}>Disposable</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="nicotine_strength" class="form-label">Nicotine Strength</label>
                                    <input type="text" class="form-control @error('nicotine_strength') is-invalid @enderror" 
                                           id="nicotine_strength" name="nicotine_strength" value="{{ old('nicotine_strength', '10mg') }}" 
                                           placeholder="e.g., 10mg">
                                    @error('nicotine_strength')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- X-Vape Ultra Specific Fields -->
                            <div class="flavor-section" id="ultraFields">
                                <h6 class="mb-3"><i class="bi bi-lightning-charge me-2"></i> X-Vape Ultra Specifications</h6>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="puff_count" class="form-label">Puff Count</label>
                                        <input type="number" class="form-control" id="puff_count" name="puff_count" value="{{ old('puff_count', 10000) }}" placeholder="10000">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="battery_capacity" class="form-label">Battery (mAh)</label>
                                        <input type="number" class="form-control" id="battery_capacity" name="battery_capacity" value="{{ old('battery_capacity', 650) }}" placeholder="650">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="charging_type" class="form-label">Charging Type</label>
                                        <input type="text" class="form-control" id="charging_type" name="charging_type" value="{{ old('charging_type', 'Type-C') }}" placeholder="Type-C">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="liquid_capacity" class="form-label">Liquid Capacity (ml)</label>
                                        <input type="number" step="0.1" class="form-control" id="liquid_capacity" name="liquid_capacity" value="{{ old('liquid_capacity', 10) }}" placeholder="10">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="adjustable_airflow" name="adjustable_airflow" value="1" {{ old('adjustable_airflow') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="adjustable_airflow">
                                                Adjustable Airflow
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="smart_display" name="smart_display" value="1" {{ old('smart_display') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="smart_display">
                                                Smart Display
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price and Stock -->
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price (₱) *</label>
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
                            
                            <!-- Product Image -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <small class="text-muted">Upload product image (optional)</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show/hide Ultra fields based on category selection
        document.getElementById('category').addEventListener('change', function() {
            const ultraFields = document.getElementById('ultraFields');
            if (this.value === 'Ultra') {
                ultraFields.style.display = 'block';
            } else {
                ultraFields.style.display = 'none';
            }
        });
        
        // Trigger on page load
        document.addEventListener('DOMContentLoaded', function() {
            const category = document.getElementById('category').value;
            const ultraFields = document.getElementById('ultraFields');
            ultraFields.style.display = category === 'Ultra' ? 'block' : 'none';
        });
    </script>
</body>
</html>