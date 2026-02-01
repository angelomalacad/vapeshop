<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product - Branch Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">VapeShop</a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('branch-admin.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('branch-admin.products.index') }}" class="nav-link">Back to Products</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Branch Menu</div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('branch-admin.dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('branch-admin.inventory.index') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-box-seam me-2"></i> Inventory
                        </a>
                        <a href="{{ route('branch-admin.products.index') }}" class="list-group-item list-group-item-action active">
                            <i class="bi bi-tags me-2"></i> Products
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-cart me-2"></i> Orders
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-graph-up me-2"></i> Reports
                        </a>
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-house me-2"></i> Back to Home
                        </a>
                    </div>
                </div>
                
                <!-- Quick Actions Card -->
                <div class="card mt-3">
                    <div class="card-header">Quick Actions</div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('branch-admin.inventory.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> Add to Inventory
                            </a>
                            <a href="{{ route('branch-admin.products.create') }}" class="btn btn-success btn-sm active">
                                <i class="bi bi-plus-circle"></i> Create New Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Create New Product</h1>
                    <a href="{{ route('branch-admin.products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Products
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Product Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('branch-admin.products.store') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="sku" class="form-label">SKU (Stock Keeping Unit) *</label>
                                <input type="text" class="form-control" id="sku" name="sku" required>
                                <small class="text-muted">Unique identifier for this product</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Category *</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label">Product Type *</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="disposable">Disposable</option>
                                        <option value="pod">Pod</option>
                                        <option value="mod">Mod</option>
                                        <option value="liquid">Liquid</option>
                                        <option value="coil">Coil</option>
                                        <option value="accessory">Accessory</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Price *</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="stock_quantity" class="form-label">Initial Stock Quantity *</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="0" required>
                                    <div class="form-text">This will automatically add to your inventory</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('branch-admin.products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Create Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>