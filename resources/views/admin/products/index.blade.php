@extends('layouts.admin')

@section('title', 'Products Management - Vape Expo')

<style>
    .stat-card-modern {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        border: 1px solid #eef2f6;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .stat-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
        border-color: #e0e7ed;
    }

    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: all 0.3s ease;
    }

    .stat-card-modern:hover .stat-icon-wrapper {
        transform: scale(1.02);
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 600;
        color: #8b9cb0;
        display: block;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        line-height: 1.2;
    }

    .col-md-3:first-child .stat-icon-wrapper {
        background: #eef4ff;
        color: #3b82f6;
    }

    .col-md-3:nth-child(2) .stat-icon-wrapper {
        background: #e6f7e6;
        color: #10b981;
    }

    .col-md-3:nth-child(3) .stat-icon-wrapper {
        background: #fef2f2;
        color: #ef4444;
    }

    .col-md-3:nth-child(4) .stat-icon-wrapper {
        background: #fefce8;
        color: #f59e0b;
    }

    @media (max-width: 768px) {
        .stat-card-modern {
            padding: 1rem;
            gap: 0.75rem;
        }

        .stat-icon-wrapper {
            width: 44px;
            height: 44px;
            font-size: 1.3rem;
            border-radius: 14px;
        }

        .stat-value {
            font-size: 1.4rem;
        }

        .stat-label {
            font-size: 0.65rem;
        }
    }
</style>

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
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
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-3">
                    <i class="bi bi-plus-circle me-1"></i> Add New Product
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            @php
                $totalProducts = \App\Models\Product::count();
                $activeProducts = \App\Models\Product::where('is_active', true)->count();
                $inactiveProducts = \App\Models\Product::where('is_active', false)->count();
                $productsWithFlavors = \App\Models\Product::has('flavors')->count();
            @endphp
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Products</span>
                        <h3 class="stat-value">{{ $totalProducts }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Active Products</span>
                        <h3 class="stat-value">{{ $activeProducts }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Inactive Products</span>
                        <h3 class="stat-value">{{ $inactiveProducts }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">With Flavors</span>
                        <h3 class="stat-value">{{ $productsWithFlavors }}</h3>
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
                        <input type="text" name="search" class="form-control" placeholder="Product name..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Brand</label>
                        <select name="brand" class="form-select">
                            <option value="">All Brands</option>
                            @foreach ($brands as $brand)
                                @if ($brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}</option>
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
                                    <option value="{{ $cat }}"
                                        {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Type</label>
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach ($types as $type)
                                @if ($type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
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
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-box me-2 text-primary"></i>
                    Product List
                </h5>
                <span class="text-muted small">Total: {{ $products->total() }}</span>
            </div>
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
                                <th>Variant Count</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td class="ps-4">
                                        @if ($product->image_url)
                                            <img src="{{ \App\Helpers\GoogleDriveHelper::getThumbnailUrl($product->image_url, 50) }}"
                                                alt="{{ $product->name }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @elseif($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted fs-4"></i>
                                            </div>
                                        @endif
                </div>
                <td class="align-middle"><span class="fw-semibold">{{ $product->name }}</span>
            </div>
            <td class="align-middle">{{ $product->brand }}
        </div>
        <td class="align-middle">{{ $product->category }}
    </div>
    <td class="align-middle">₱{{ number_format($product->price, 2) }}</div>
    <td class="align-middle">
        @if ($product->flavors->count() > 0)
            <span class="badge bg-info ms-5 align-middle">{{ $product->flavors->count() }}</span>
        @else
            <span class="badge bg-secondary">No flavors</span>
        @endif
        </div>
    <td class="align-middle">
        @if ($product->is_active)
            <span class="badge bg-success px-3 py-2">
                <i class="bi bi-check-circle me-1"></i> Active
            </span>
        @else
            <span class="badge bg-secondary px-3 py-2">
                <i class="bi bi-x-circle me-1"></i> Inactive
            </span>
        @endif
        </div>
    <td class="pe-4 text-end align-middle">
        <div class="btn-group btn-group-sm">
            <!-- Add Stock button -->
            <button type="button" class="btn btn-outline-success" onclick="openAddStockModal(this)"
                data-product-id="{{ $product->id }}" title="Add Stock">
                <i class="bi bi-plus-circle"></i>
            </button>

            <!-- View Details button -->
            <button type="button" class="btn btn-outline-info" onclick="openViewModal({{ $product->id }})"
                title="View Product">
                <i class="bi bi-eye"></i>
            </button>

            <!-- Edit button -->
            <button type="button" class="btn btn-outline-warning" onclick="openEditModal({{ $product->id }})"
                title="Edit Product">
                <i class="bi bi-pencil"></i>
            </button>

            <!-- Toggle Status button (Pause/Play) -->
            <button type="button"
                class="btn btn-outline-{{ $product->is_active ? 'danger' : 'success' }} toggle-status-btn"
                data-url="{{ route('admin.products.toggle-status', $product) }}" data-id="{{ $product->id }}"
                title="{{ $product->is_active ? 'Deactivate' : 'Activate' }}">
                <i class="bi bi-{{ $product->is_active ? 'pause' : 'play' }}"></i>
            </button>

            <!-- Delete button - Using modal -->
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                data-bs-target="#deleteModal{{ $product->id }}" title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center py-5">
                <i class="bi bi-box display-1 text-muted"></i>
                <p class="mt-3 text-muted">No products found</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-circle me-1"></i> Add New Product
                </a>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>
        </div>

        @if ($products->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-end align-items-center">
                    <div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if ($products->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($products->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        @endif
        </div>
        </div>

        <!-- Edit Modal Container -->
        <div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading form...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Modal Container -->
        <div class="modal fade" id="viewModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="text-center p-5">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading product information...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Stock Modal Container -->
        <div class="modal fade" id="addStockModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content"><!-- loaded via AJAX --></div>
            </div>
        </div>

        <!-- Toggle Status Confirmation Modal -->
        <div class="modal fade" id="toggleStatusModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Confirm Status Change
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to <strong id="toggleActionText"></strong> this product?</p>
                        <p class="text-muted small mb-0">Product: <strong id="toggleProductName"></strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning" id="confirmToggleBtn">
                            <i class="bi bi-check-circle me-1"></i> Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modals -->
        @foreach ($products as $product)
            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Confirm Delete
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                            <p class="text-muted small mb-0">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger confirm-delete-btn"
                                data-url="{{ route('admin.products.destroy', $product) }}"
                                data-name="{{ $product->name }}">
                                <i class="bi bi-trash me-2"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <script>
            function openViewModal(productId) {
                const url = '/admin/products/' + productId + '/show-modal';
                const viewModal = document.getElementById('viewModal');
                const modalContent = viewModal.querySelector('.modal-content');

                modalContent.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading product information...</p>
            </div>
        `;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                    })
                    .catch(error => {
                        modalContent.innerHTML = `
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body"><p>Failed to load product information.</p></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
                `;
                    });

                new bootstrap.Modal(viewModal).show();
            }

            function openEditModal(productId) {
                const url = '/admin/products/' + productId + '/edit-modal';
                const editModal = document.getElementById('editModal');
                const modalContent = editModal.querySelector('.modal-content');

                modalContent.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading form...</p>
            </div>
        `;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        modalContent.innerHTML = html;

                        // Attach edit form submit handler for success notification
                        const editForm = modalContent.querySelector('#editForm');
                        if (editForm) {
                            editForm.addEventListener('submit', function(e) {
                                e.preventDefault();

                                const submitButton = editForm.querySelector('button[type="submit"]');
                                const originalText = submitButton.innerHTML;
                                submitButton.disabled = true;
                                submitButton.innerHTML =
                                    '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';

                                const formData = new FormData(editForm);

                                fetch(editForm.action, {
                                        method: 'POST',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        },
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            const bsModal = bootstrap.Modal.getInstance(editModal);
                                            bsModal.hide();
                                            showNotification(data.message, 'success');
                                            setTimeout(() => {
                                                location.reload();
                                            }, 1500);
                                        } else {
                                            submitButton.disabled = false;
                                            submitButton.innerHTML = originalText;
                                            showNotification(data.message || 'Update failed', 'danger');
                                        }
                                    })
                                    .catch(error => {
                                        submitButton.disabled = false;
                                        submitButton.innerHTML = originalText;
                                        showNotification('An error occurred', 'danger');
                                    });
                            });
                        }
                    })
                    .catch(error => {
                        modalContent.innerHTML = `
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body"><p>Failed to load form.</p></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
                `;
                    });

                new bootstrap.Modal(editModal).show();
            }

            window.openAddStockModal = function(button) {
                var productId = button.getAttribute('data-product-id');
                var modalElement = document.getElementById('addStockModal');
                var modalContent = modalElement.querySelector('.modal-content');
                var url = '/admin/products/' + productId + '/add-stock-modal';

                modalContent.innerHTML =
                    '<div class="text-center p-5"><div class="spinner-border text-success" role="status"></div><p class="mt-2 text-muted">Loading form...</p></div>';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
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
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
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
                                let errorHtml =
                                    '<i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Errors:</strong><ul>';
                                for (const [field, errors] of Object.entries(data.errors)) {
                                    errors.forEach(error => {
                                        errorHtml += `<li>${error}</li>`;
                                    });
                                }
                                errorHtml += '</ul>';
                                alertDiv.innerHTML = errorHtml;
                                alertDiv.style.display = 'block';
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        })
                        .catch(error => {
                            alertDiv.className = 'alert alert-danger';
                            alertDiv.innerHTML =
                                '<i class="bi bi-exclamation-triangle-fill me-2"></i> An error occurred. Please try again.';
                            alertDiv.style.display = 'block';
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        });
                });
            }

            // Toggle Status button handler - With Modal Confirmation and Success Notification
            let pendingToggleUrl = null;
            let pendingToggleButton = null;

            document.querySelectorAll('.toggle-status-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const url = this.getAttribute('data-url');
                    const isActive = this.classList.contains('btn-outline-danger');
                    const action = isActive ? 'deactivate' : 'activate';
                    const productName = this.closest('tr').querySelector('.fw-semibold').innerText;

                    // Set modal content
                    document.getElementById('toggleActionText').innerText = action;
                    document.getElementById('toggleProductName').innerText = productName;

                    // Store the URL and button reference
                    pendingToggleUrl = url;
                    pendingToggleButton = this;

                    // Show modal
                    const toggleModal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
                    toggleModal.show();
                });
            });

            // Handle confirm button click
            document.getElementById('confirmToggleBtn').addEventListener('click', function() {
                if (!pendingToggleUrl || !pendingToggleButton) return;

                const button = pendingToggleButton;
                const originalHtml = button.innerHTML;
                const url = pendingToggleUrl;

                // Close modal
                const toggleModal = bootstrap.Modal.getInstance(document.getElementById('toggleStatusModal'));
                toggleModal.hide();

                // Show loading state on the original button
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            button.disabled = false;
                            button.innerHTML = originalHtml;
                            showNotification(data.message || 'Action failed', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.disabled = false;
                        button.innerHTML = originalHtml;
                        showNotification('An error occurred. Please try again.', 'danger');
                    });

                pendingToggleUrl = null;
                pendingToggleButton = null;
            });

            // Delete confirmation handler with AJAX and success notification
            document.querySelectorAll('.confirm-delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const url = this.getAttribute('data-url');
                    const productName = this.getAttribute('data-name');
                    const modal = this.closest('.modal');

                    const originalHtml = this.innerHTML;
                    this.disabled = true;
                    this.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Deleting...';

                    fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const bsModal = bootstrap.Modal.getInstance(modal);
                                bsModal.hide();
                                showNotification(data.message, 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                this.disabled = false;
                                this.innerHTML = originalHtml;
                                showNotification(data.message || 'Delete failed', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.disabled = false;
                            this.innerHTML = originalHtml;
                            showNotification('An error occurred. Please try again.', 'danger');
                        });
                });
            });

            // Success/Error Notification Function
            function showNotification(message, type) {
                const existingAlerts = document.querySelectorAll('.notification-toast');
                existingAlerts.forEach(alert => alert.remove());

                let notificationContainer = document.querySelector('.notification-container');
                if (!notificationContainer) {
                    notificationContainer = document.createElement('div');
                    notificationContainer.className = 'notification-container';
                    notificationContainer.style.position = 'fixed';
                    notificationContainer.style.top = '20px';
                    notificationContainer.style.right = '20px';
                    notificationContainer.style.zIndex = '9999';
                    document.body.appendChild(notificationContainer);
                }

                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show shadow notification-toast`;
                alert.style.marginBottom = '10px';
                alert.style.minWidth = '300px';
                alert.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
                alert.style.color = type === 'success' ? '#155724' : '#721c24';
                alert.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
                alert.style.borderRadius = '8px';
                alert.style.padding = '12px 20px';
                alert.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2 fs-5"></i>
                <span class="flex-grow-1">${message}</span>
                <button type="button" class="btn-close ms-3" style="font-size: 0.75rem;" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

                notificationContainer.appendChild(alert);

                setTimeout(() => {
                    if (alert) {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => {
                            if (alert) alert.remove();
                        }, 500);
                    }
                }, 5000);
            }
        </script>
    @endsection
