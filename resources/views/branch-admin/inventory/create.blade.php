<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product to Inventory - Branch Admin</title>
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
                        <a href="{{ route('branch-admin.products.index') }}" class="list-group-item list-group-item-action">
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
                            <a href="{{ route('branch-admin.products.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle"></i> Create New Product
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Add Product to Inventory</h1>
                    <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Inventory
                    </a>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Select Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('branch-admin.inventory.store') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Select Product *</label>
                                <select name="product_id" class="form-select" required>
                                    <option value="">Choose a product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} 
                                            ({{ $product->sku }}) 
                                            - ₱{{ number_format($product->price, 2) }}
                                            @if($product->flavor)
                                                - {{ $product->flavor }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">
                                    Can't find the product? 
                                    <a href="{{ route('branch-admin.products.create') }}">Create a new product</a>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Initial Quantity *</label>
                                    <input type="number" name="quantity" class="form-control" 
                                           min="0" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Low Stock Threshold *</label>
                                    <input type="number" name="low_stock_threshold" class="form-control" 
                                           min="0" value="5" required>
                                    <div class="form-text">Alert when stock reaches this level</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Optimal Stock Level *</label>
                                    <input type="number" name="optimal_stock_level" class="form-control" 
                                           min="1" value="20" required>
                                    <div class="form-text">Target stock level for restocking</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Add to Inventory
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Available Products Preview -->
                @if($products->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-grid me-2"></i>Available Products</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($products->take(6) as $product)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" 
                                             class="card-img-top" 
                                             style="height: 150px; object-fit: cover;" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 150px;">
                                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $product->name }}</h6>
                                        <p class="card-text small text-muted mb-1">
                                            <i class="bi bi-tag"></i> {{ $product->category->name ?? 'Uncategorized' }}
                                        </p>
                                        <p class="card-text mb-1">
                                            <strong>₱{{ number_format($product->price, 2) }}</strong>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($product->flavor)
                                                <span class="badge bg-info">{{ $product->flavor }}</span>
                                            @endif
                                            <small class="text-muted">{{ $product->sku }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($products->count() > 6)
                        <div class="text-center mt-3">
                            <a href="{{ route('branch-admin.products.index') }}" class="btn btn-outline-primary btn-sm">
                                View All Products ({{ $products->count() }})
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>