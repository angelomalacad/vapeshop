@extends('layouts.admin')

@section('title', 'Products Management - Vape Expo')

<style>
    .table .btn-group {
        display: inline-flex;
        gap: 4px;
        flex-wrap: nowrap;
        align-items: center;
    }
    .table .btn-group form,
    .table .btn-group form button {
        margin: 0;
        padding: 0;
        display: inline-flex;
    }
    .table .btn-group .btn,
    .table .btn-group form button {
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }
    @media (max-width: 768px) {
        .table .btn-group {
            flex-wrap: wrap;
        }
    }
</style>

@section('content')
    <div class="container-fluid px-4">
        <!-- Header with Dashboard Button -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Products Management</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-box me-1"></i> Manage all products across branches
                    </p>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-1"></i> Add New Product
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            @php
                $totalProducts = \App\Models\Product::count();
                $activeProducts = \App\Models\Product::where('is_active', true)->count();
                $withFlavors = \App\Models\Product::has('flavors')->count();
            @endphp
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Products</h6>
                                <h2 class="mb-0 fw-bold">{{ $totalProducts }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-box fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success bg-gradient text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Active Products</h6>
                                <h2 class="mb-0 fw-bold">{{ $activeProducts }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">With Flavors</h6>
                                <h2 class="mb-0 fw-bold">{{ $withFlavors }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-droplet fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">X Ultra / Slimbar</h6>
                                <h2 class="mb-0 fw-bold">
                                    {{ \App\Models\Product::whereIn('brand', ['X-Vape', 'Slimbar', 'Relx'])->count() }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-star fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Products</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Product name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Brand</label>
                        <select name="brand" class="form-select">
                            <option value="">All Brands</option>
                            @foreach ($brands as $brand)
                                @if ($brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $cat)
                                @if ($cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach ($types as $type)
                                @if ($type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-funnel me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary ms-2 px-4">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Image</th>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Flavors or Items</th>
                                <th>Status</th>
                                <th class="pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        @if ($product->image_url)
                                            <img src="{{ \App\Helpers\GoogleDriveHelper::getThumbnailUrl($product->image_url, 50) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @elseif($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><span class="fw-semibold">{{ $product->name }}</span></td>
                                    <td>{{ $product->brand }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>₱{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if ($product->flavors->count() > 0)
                                            <span class="badge bg-info">{{ $product->flavors->count() }} flavors or items</span>
                                        @else
                                            <span class="badge bg-secondary">No flavors</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <!-- Add Stock button (existing, works) -->
                                            <button type="button" class="btn btn-outline-success" 
                                                onclick="openAddStockModal(this)"
                                                data-product-id="{{ $product->id }}"
                                                title="Add Stock to Branch">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>

                                            <!-- NEW: View Details button (modal) -->
                                            <button type="button" class="btn btn-outline-info" 
                                                onclick="openShowModal({{ $product->id }})"
                                                title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <!-- NEW: Edit button (modal) -->
                                            <button type="button" class="btn btn-outline-warning" 
                                                onclick="openEditModal({{ $product->id }})"
                                                title="Edit Product">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Toggle Status form (unchanged) -->
                                            <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-{{ $product->is_active ? 'danger' : 'success' }}" title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-{{ $product->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>

                                            <!-- Delete button (unchanged) -->
                                            <button type="button" class="btn btn-outline-danger" onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </td>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-box display-1 text-muted"></i>
                                        <p class="mt-3 text-muted">No products found</p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
                                            <i class="bi bi-plus-circle me-1"></i> Add New Product
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                    <div>{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL CONTAINERS ===== -->

    <!-- Add Stock Modal Container (already working) -->
    <div class="modal fade" id="addStockModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content"><!-- loaded via AJAX --></div>
        </div>
    </div>

    <!-- Show Product Modal Container -->
    <div class="modal fade" id="showProductModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"><!-- loaded via AJAX --></div>
        </div>
    </div>

    <!-- Edit Product Modal Container -->
    <div class="modal fade" id="editProductModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"><!-- loaded via AJAX --></div>
        </div>
    </div>

@endsection

<script>
    // ====== ADD STOCK MODAL (existing, unchanged) ======
    window.openAddStockModal = function(button) {
        var productId = button.getAttribute('data-product-id');
        var modalElement = document.getElementById('addStockModal');
        var modalContent = modalElement.querySelector('.modal-content');
        var url = '/admin/products/' + productId + '/add-stock-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-success" role="status"></div><p class="mt-2 text-muted">Loading form...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                attachStockSubmitHandler(modalElement);
                new bootstrap.Modal(modalElement).show();
            })
            .catch(error => {
                modalContent.innerHTML = '<div class="alert alert-danger">Error loading form</div>';
                new bootstrap.Modal(modalElement).show();
            });
    };

    function attachStockSubmitHandler(modalElement) {
        const submitBtn = modalElement.querySelector('#submitStockBtn');
        const form = modalElement.querySelector('#addStockForm');
        if (!submitBtn || !form) return;
        const newBtn = submitBtn.cloneNode(true);
        submitBtn.parentNode.replaceChild(newBtn, submitBtn);
        newBtn.addEventListener('click', function() {
            const formData = new FormData(form);
            const alertDiv = modalElement.querySelector('#formAlert');
            const submitBtn = this;
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertDiv.className = 'alert alert-success';
                    alertDiv.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> ' + data.message;
                    alertDiv.style.display = 'block';
                    setTimeout(() => {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        modal.hide();
                        location.reload();
                    }, 1500);
                } else {
                    alertDiv.className = 'alert alert-danger';
                    let errorHtml = '<i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Errors:</strong><ul>';
                    for (const [field, errors] of Object.entries(data.errors)) {
                        errors.forEach(error => { errorHtml += `<li>${error}</li>`; });
                    }
                    errorHtml += '</ul>';
                    alertDiv.innerHTML = errorHtml;
                    alertDiv.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> An error occurred. Please try again.';
                alertDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // ====== SHOW PRODUCT MODAL ======
    window.openShowModal = function(productId) {
        var modalElement = document.getElementById('showProductModal');
        var modalContent = modalElement.querySelector('.modal-content');
        var url = '/admin/products/' + productId + '/show-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div><p class="mt-2">Loading product details...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                new bootstrap.Modal(modalElement).show();
            })
            .catch(error => {
                modalContent.innerHTML = '<div class="alert alert-danger">Error loading details</div>';
                new bootstrap.Modal(modalElement).show();
            });
    };

    // ====== EDIT PRODUCT MODAL ======
    window.openEditModal = function(productId) {
        var modalElement = document.getElementById('editProductModal');
        var modalContent = modalElement.querySelector('.modal-content');
        var url = '/admin/products/' + productId + '/edit-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-warning" role="status"></div><p class="mt-2">Loading edit form...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                new bootstrap.Modal(modalElement).show();
            })
            .catch(error => {
                modalContent.innerHTML = '<div class="alert alert-danger">Error loading form</div>';
                new bootstrap.Modal(modalElement).show();
            });
    };

    // ====== DELETE CONFIRMATION (unchanged) ======
    window.confirmDelete = function(productId, productName) {
        if (confirm('Are you sure you want to delete "' + productName + '"? This action cannot be undone.')) {
            document.getElementById('delete-form-' + productId).submit();
        }
    };
</script>