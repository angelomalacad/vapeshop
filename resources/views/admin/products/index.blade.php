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

    /* Delete Modal Minimalist Styles - Global UI */
    .delete-modal-header {
        background: #dc2626;
        color: white;
        padding: 1rem 1.25rem;
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .delete-modal-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .delete-modal-body {
        padding: 1.25rem;
    }

    .delete-modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #eef2f6;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .btn-delete {
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
        background: transparent;
        border: none;
        font-size: 1rem;
        cursor: pointer;
    }

    /* Toggle Status Modal Minimalist Styles */
    .toggle-modal-header {
        background: #f59e0b;
        color: white;
        padding: 1rem 1.25rem;
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .toggle-modal-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .toggle-modal-body {
        padding: 1.25rem;
    }

    .toggle-modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #eef2f6;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-confirm {
        background: #f59e0b;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-confirm:hover {
        background: #d97706;
        transform: translateY(-1px);
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

            <!-- Delete button - Using modal with global UI -->
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

        <!-- Toggle Status Confirmation Modal - Updated with Global UI -->
        <div class="modal fade" id="toggleStatusModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="toggle-modal-header">
                        <h5>
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Confirm Status Change
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="toggle-modal-body">
                        <p>Are you sure you want to <strong id="toggleActionText"></strong> this product?</p>
                        <p class="text-muted small mb-0">Product: <strong id="toggleProductName"></strong></p>
                    </div>
                    <div class="toggle-modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn-confirm" id="confirmToggleBtn">
                            <i class="bi bi-check-circle me-1"></i> Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modals - Updated with Global UI -->
        @foreach ($products as $product)
            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="delete-modal-header">
                            <h5>
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                Confirm Delete
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="delete-modal-body">
                            <p>Are you sure you want to delete <strong>{{ $product->name }}</strong>?</p>
                            <p class="text-muted small mb-0">This action cannot be undone.</p>
                        </div>
                        <div class="delete-modal-footer">
                            <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn-delete confirm-delete-btn"
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
            // ========== GLOBAL NOTIFICATION FUNCTION ==========
            window.showNotification = function(message, type) {
                const existingAlerts = document.querySelectorAll('.notification-toast');
                existingAlerts.forEach(alert => alert.remove());

                let notificationContainer = document.querySelector('.notification-container');
                if (!notificationContainer) {
                    notificationContainer = document.createElement('div');
                    notificationContainer.className = 'notification-container';
                    notificationContainer.style.position = 'fixed';
                    notificationContainer.style.top = '20px';
                    notificationContainer.style.right = '20px';
                    notificationContainer.style.zIndex = '99999';
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
            };

            // ========== FUNCTIONS ==========
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
                                            window.showNotification(data.message, 'success');
                                            setTimeout(() => {
                                                location.reload();
                                            }, 1500);
                                        } else {
                                            submitButton.disabled = false;
                                            submitButton.innerHTML = originalText;
                                            window.showNotification(data.message || 'Update failed', 'danger');
                                        }
                                    })
                                    .catch(error => {
                                        submitButton.disabled = false;
                                        submitButton.innerHTML = originalText;
                                        window.showNotification('An error occurred', 'danger');
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
                // Your existing openAddStockModal function - keep as is
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

                        // Get references to elements
                        const submitBtn = modalContent.querySelector('#submitStockBtn');
                        const addStockForm = modalContent.querySelector('#addStockForm');
                        const alertDiv = modalContent.querySelector('#formAlert');
                        const branchSelect = modalContent.querySelector('#branchSelect');
                        const quantityInput = modalContent.querySelector('#quantityInput');
                        const expirationDate = modalContent.querySelector('#expirationDate');
                        const flavorSelect = modalContent.querySelector('select[name="flavor_id"]');
                        const purchasePrice = modalContent.querySelector('input[name="purchase_price"]');
                        const notes = modalContent.querySelector('textarea[name="notes"]');
                        const warehouseStockInfo = modalContent.querySelector('#warehouseStockInfo');
                        const warehouseStockQty = modalContent.querySelector('#warehouseStockQty');
                        const lowStockWarning = modalContent.querySelector('#lowStockWarning');
                        const lowStockQty = modalContent.querySelector('#lowStockQty');
                        const quantityHelp = modalContent.querySelector('#quantityHelp');
                        const expiryHelp = modalContent.querySelector('#expiryHelp');
                        const helpTextSpan = modalContent.querySelector('#helpText');

                        let currentWarehouseStock = 0;

                        // Function to check warehouse stock
                        async function checkWarehouseStock() {
                            const branchId = branchSelect ? branchSelect.value : '';
                            const flavorId = flavorSelect ? flavorSelect.value : '';

                            if (branchId && branchId !== 'warehouse' && warehouseStockInfo) {
                                warehouseStockInfo.style.display = 'block';
                                warehouseStockQty.innerHTML =
                                    '<span class="spinner-border spinner-border-sm"></span> Loading...';
                                if (lowStockWarning) lowStockWarning.style.display = 'none';

                                const apiUrl = `/admin/api/warehouse-stock/${productId}?flavor_id=${flavorId}`;

                                try {
                                    const response = await fetch(apiUrl, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    });

                                    if (!response.ok) {
                                        throw new Error('HTTP ' + response.status);
                                    }

                                    const data = await response.json();

                                    if (data.success) {
                                        currentWarehouseStock = data.quantity || 0;
                                        warehouseStockQty.innerHTML =
                                            `<strong class="text-primary">${currentWarehouseStock}</strong>`;

                                        if (currentWarehouseStock === 0) {
                                            warehouseStockQty.innerHTML = `<strong class="text-danger">0</strong>`;
                                            if (quantityHelp) quantityHelp.innerHTML =
                                                '<span class="text-danger">⚠️ No stock available in warehouse.</span>';
                                            if (quantityInput) quantityInput.max = 0;
                                        } else if (currentWarehouseStock < 10) {
                                            if (lowStockWarning) lowStockWarning.style.display = 'block';
                                            if (lowStockQty) lowStockQty.innerHTML = currentWarehouseStock;
                                            if (quantityHelp) quantityHelp.innerHTML =
                                                `<span class="text-warning">⚠️ Only ${currentWarehouseStock} units available.</span>`;
                                            if (quantityInput) quantityInput.max = currentWarehouseStock;
                                        } else {
                                            if (quantityHelp) quantityHelp.innerHTML =
                                                `<span class="text-success">✓ ${currentWarehouseStock} units available</span>`;
                                            if (quantityInput) quantityInput.max = currentWarehouseStock;
                                        }
                                    } else {
                                        warehouseStockQty.innerHTML = '<span class="text-danger">Error</span>';
                                    }
                                } catch (error) {
                                    console.error('Error checking warehouse stock:', error);
                                    warehouseStockQty.innerHTML =
                                        '<span class="text-danger">Connection error</span>';
                                }
                            } else if (branchId === 'warehouse') {
                                if (warehouseStockInfo) warehouseStockInfo.style.display = 'none';
                                if (quantityHelp) quantityHelp.innerHTML = '';
                                if (quantityInput) quantityInput.max = '';
                            } else {
                                if (warehouseStockInfo) warehouseStockInfo.style.display = 'none';
                                if (quantityHelp) quantityHelp.innerHTML = '';
                                if (quantityInput) quantityInput.max = '';
                            }
                        }

                        // Update UI based on destination
                        function updateUI() {
                            const branchId = branchSelect ? branchSelect.value : '';

                            if (branchId === 'warehouse') {
                                if (helpTextSpan) helpTextSpan.innerHTML = 'Adding new stock to Main Warehouse.';
                                if (expiryHelp) expiryHelp.innerHTML = '<span class="text-danger">* Required</span>';
                                if (quantityHelp) quantityHelp.innerHTML = 'Enter quantity to add';
                                if (quantityInput) quantityInput.max = '';
                                if (warehouseStockInfo) warehouseStockInfo.style.display = 'none';
                                if (expirationDate) expirationDate.required = true;
                                if (expirationDate) expirationDate.style.borderColor = '#dc2626';
                            } else if (branchId && branchId !== '') {
                                if (helpTextSpan) helpTextSpan.innerHTML =
                                    'Transferring stock from warehouse to branch.';
                                if (expiryHelp) expiryHelp.innerHTML = '<span class="text-danger">* Required</span>';
                                if (expirationDate) expirationDate.required = true;
                                if (expirationDate) expirationDate.style.borderColor = '#dc2626';
                                checkWarehouseStock();
                            } else {
                                if (helpTextSpan) helpTextSpan.innerHTML = 'Select destination';
                                if (expiryHelp) expiryHelp.innerHTML = '<span class="text-danger">* Required</span>';
                                if (warehouseStockInfo) warehouseStockInfo.style.display = 'none';
                                if (quantityHelp) quantityHelp.innerHTML = '';
                                if (expirationDate) expirationDate.required = true;
                            }
                        }

                        // Attach event listeners
                        if (branchSelect) {
                            branchSelect.addEventListener('change', updateUI);
                        }

                        if (flavorSelect) {
                            flavorSelect.addEventListener('change', function() {
                                if (branchSelect && branchSelect.value && branchSelect.value !== 'warehouse') {
                                    checkWarehouseStock();
                                }
                            });
                        }

                        if (quantityInput) {
                            quantityInput.addEventListener('input', function() {
                                const branchId = branchSelect ? branchSelect.value : '';
                                const quantity = parseInt(this.value);

                                if (branchId && branchId !== 'warehouse') {
                                    if (quantity > currentWarehouseStock) {
                                        this.value = currentWarehouseStock;
                                        if (quantityHelp) quantityHelp.innerHTML =
                                            `<span class="text-danger">⚠️ Max available: ${currentWarehouseStock} units</span>`;
                                    } else if (quantity <= 0) {
                                        this.value = 1;
                                    } else {
                                        if (quantityHelp) quantityHelp.innerHTML =
                                            `<span class="text-success">✓ Transferring ${quantity} units</span>`;
                                    }
                                }
                            });
                        }

                        // Submit button handler
                        if (submitBtn && addStockForm) {
                            submitBtn.onclick = function(e) {
                                e.preventDefault();

                                if (alertDiv) alertDiv.style.display = 'none';

                                if (!branchSelect || !branchSelect.value) {
                                    if (alertDiv) {
                                        alertDiv.className = 'alert-minimal alert-danger-minimal';
                                        alertDiv.innerHTML = 'Please select a destination.';
                                        alertDiv.style.display = 'block';
                                    }
                                    return;
                                }

                                const branchId = branchSelect.value;
                                const quantity = parseInt(quantityInput ? quantityInput.value : 0);

                                // Validate expiration date - REQUIRED FOR BOTH
                                if (!expirationDate || !expirationDate.value) {
                                    if (alertDiv) {
                                        alertDiv.className = 'alert-minimal alert-danger-minimal';
                                        alertDiv.innerHTML = 'Expiration Date is required.';
                                        alertDiv.style.display = 'block';
                                        if (expirationDate) expirationDate.focus();
                                    }
                                    return;
                                }

                                if (isNaN(quantity) || quantity <= 0) {
                                    if (alertDiv) {
                                        alertDiv.className = 'alert-minimal alert-danger-minimal';
                                        alertDiv.innerHTML = 'Please enter a valid quantity.';
                                        alertDiv.style.display = 'block';
                                    }
                                    return;
                                }

                                if (branchId && branchId !== 'warehouse') {
                                    if (quantity > currentWarehouseStock) {
                                        if (alertDiv) {
                                            alertDiv.className = 'alert-minimal alert-danger-minimal';
                                            alertDiv.innerHTML =
                                                `Insufficient warehouse stock. Available: ${currentWarehouseStock} units.`;
                                            alertDiv.style.display = 'block';
                                        }
                                        return;
                                    }
                                }

                                const originalText = submitBtn.innerHTML;
                                submitBtn.disabled = true;
                                submitBtn.innerHTML =
                                    '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

                                const formData = new FormData();
                                formData.append('branch_id', branchId);
                                formData.append('quantity', quantity);
                                formData.append('expiration_date', expirationDate.value);
                                if (flavorSelect && flavorSelect.value) formData.append('flavor_id', flavorSelect
                                    .value);
                                if (purchasePrice && purchasePrice.value) formData.append('purchase_price',
                                    purchasePrice.value);
                                if (notes && notes.value) formData.append('notes', notes.value);
                                formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'));

                                fetch(addStockForm.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            const modal = bootstrap.Modal.getInstance(modalElement);
                                            modal.hide();
                                            localStorage.setItem('notification_message', data.message);
                                            localStorage.setItem('notification_type', 'success');
                                            window.location.reload();
                                        } else {
                                            submitBtn.disabled = false;
                                            submitBtn.innerHTML = originalText;
                                            if (alertDiv) {
                                                alertDiv.className = 'alert-minimal alert-danger-minimal';
                                                alertDiv.innerHTML = data.message || 'Error occurred';
                                                alertDiv.style.display = 'block';
                                            }
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        submitBtn.disabled = false;
                                        submitBtn.innerHTML = originalText;
                                        if (alertDiv) {
                                            alertDiv.className = 'alert-minimal alert-danger-minimal';
                                            alertDiv.innerHTML = 'Network error. Please try again.';
                                            alertDiv.style.display = 'block';
                                        }
                                    });
                            };
                        }

                        // Initialize UI
                        updateUI();

                        new bootstrap.Modal(modalElement).show();
                    })
                    .catch(error => {
                        console.error('Error loading modal:', error);
                        modalContent.innerHTML = '<div class="alert alert-danger">Error loading form</div>';
                        new bootstrap.Modal(modalElement).show();
                    });
            };

            // Toggle Status button handler
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

                    document.getElementById('toggleActionText').innerText = action;
                    document.getElementById('toggleProductName').innerText = productName;

                    pendingToggleUrl = url;
                    pendingToggleButton = this;

                    const toggleModal = new bootstrap.Modal(document.getElementById('toggleStatusModal'));
                    toggleModal.show();
                });
            });

            document.getElementById('confirmToggleBtn').addEventListener('click', function() {
                if (!pendingToggleUrl || !pendingToggleButton) return;

                const button = pendingToggleButton;
                const originalHtml = button.innerHTML;
                const url = pendingToggleUrl;

                const toggleModal = bootstrap.Modal.getInstance(document.getElementById('toggleStatusModal'));
                toggleModal.hide();

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
                            window.showNotification(data.message, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            button.disabled = false;
                            button.innerHTML = originalHtml;
                            window.showNotification(data.message || 'Action failed', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.disabled = false;
                        button.innerHTML = originalHtml;
                        window.showNotification('An error occurred. Please try again.', 'danger');
                    });

                pendingToggleUrl = null;
                pendingToggleButton = null;
            });

            // Delete confirmation handler
            document.querySelectorAll('.confirm-delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const url = this.getAttribute('data-url');
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
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const bsModal = bootstrap.Modal.getInstance(modal);
                                bsModal.hide();
                                window.showNotification(data.message, 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1500);
                            } else {
                                this.disabled = false;
                                this.innerHTML = originalHtml;
                                window.showNotification(data.message || 'Delete failed', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.disabled = false;
                            this.innerHTML = originalHtml;
                            window.showNotification('An error occurred. Please try again.', 'danger');
                        });
                });
            });

            // Check for stored notification on page load (for add-stock modal)
            document.addEventListener('DOMContentLoaded', function() {
                const message = localStorage.getItem('notification_message');
                const type = localStorage.getItem('notification_type');

                if (message) {
                    localStorage.removeItem('notification_message');
                    localStorage.removeItem('notification_type');

                    setTimeout(function() {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification(message, type);
                        }
                    }, 100);
                }
            });
        </script>
    @endsection
