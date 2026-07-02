@extends('layouts.admin')

@section('title', 'Warehouse Management - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
        <!-- Success Message Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4 d-none" role="alert"
                id="flashSuccess">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                    <div>
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Message Alert -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-4 d-none" role="alert"
                id="flashError">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-danger"></i>
                    <div>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 fw-bold">
                    <i class="bi bi-building me-2 text-primary"></i>Warehouse Management
                </h1>
                <p class="text-muted small mb-0">Manage central stock and distribute to branches</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.warehouse.pending') }}" class="btn btn-warning rounded-pill px-4">
                    <i class="bi bi-clock-history me-2"></i>Pending Requests
                    @php $pendingCount = \App\Models\StockTransfer::where('transfer_type', 'warehouse_to_branch')->where('status', 'pending')->count(); @endphp
                    @if ($pendingCount > 0)
                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                    @endif
                </a>
                <button type="button" class="btn btn-primary rounded-pill px-4" onclick="openAddStockModal()">
                    <i class="bi bi-plus-circle me-2"></i>Add Stock to Warehouse
                </button>
            </div>
        </div>

        <!-- Stats Cards - Global UI -->
        <div class="row g-4 mb-4">
            <!-- Total Products -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #dbeafe; color: #2563eb;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Products</span>
                        <h3 class="stat-value">{{ $inventory->total() }}</h3>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Low Stock</span>
                        <h3 class="stat-value">{{ $lowStockCount }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669;">
                        <span class="bi">₱</span>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Value</span>
                        <h3 class="stat-value">₱{{ number_format($totalValue, 0) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="col-md-3 col-6">
                <a href="{{ route('admin.warehouse.pending') }}" class="text-decoration-none">
                    <div class="stat-card-modern" style="cursor: pointer;">
                        <div class="stat-icon-wrapper" style="background: #fee2e2; color: #dc2626;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Pending Requests</span>
                            <h3 class="stat-value">{{ $pendingCount }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="alert alert-info border-0 rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-3 me-3 text-primary"></i>
                <div>
                    <strong>Warehouse Management Guide:</strong><br>
                    <small>• Use <strong>"Add Stock"</strong> to increase warehouse inventory with new products or restock
                        existing ones.<br>
                        • Use <strong>"Distribute"</strong> to send stock directly to any branch. This will be immediately
                        available for the branch to receive.<br>
                        • Products with <strong class="text-warning">expiration dates</strong> will show warnings when
                        nearing expiry.</small>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Warehouse Inventory
                    </h5>
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.warehouse.index') }}" class="d-flex" id="searchForm"
                        style="min-width: 250px;">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search product, brand, category..." value="{{ request('search') }}"
                                id="searchInput">
                            <button class="btn btn-outline-primary" type="submit" id="searchBtn">
                                <i class="bi bi-search"></i>
                            </button>
                            @if (request('search'))
                                <a href="{{ route('admin.warehouse.index') }}" class="btn btn-outline-secondary"
                                    id="clearSearch">
                                    <i class="bi bi-x"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Flavor</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                                <th>Low Stock Alert</th>
                                <th>Last Purchase Price</th>
                                <th>Total Value</th>
                                <th>Last Restocked</th>
                                <th>Expiration Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory as $item)
                                @php
                                    $product = $item->product;
                                    $isLowStock = $item->quantity <= $item->low_stock_threshold;
                                    $totalItemValue = $item->quantity * ($item->last_purchase_price ?? 0);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if ($product && $product->image)
                                                <img src="{{ Storage::url($product->image) }}"
                                                    alt="{{ $product->name }}" class="rounded me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $product->name ?? 'N/A' }}</strong>
                                                @if ($product && $product->sku)
                                                    <br><small class="text-muted">SKU: {{ $product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->flavor ? $item->flavor->name : 'N/A' }}</td>
                                    <td>{{ $product->category ?? 'Uncategorized' }}</td>
                                    <td>{{ $product->brand ?? 'N/A' }}</td>
                                    <td>
                                        <span class="fw-bold {{ $isLowStock ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($item->quantity) }}
                                        </span>
                                        @if ($item->quantity <= 0)
                                            <span class="badge bg-danger ms-1">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($isLowStock)
                                            <span class="badge bg-warning">Below {{ $item->low_stock_threshold }}</span>
                                        @else
                                            <span class="text-muted">≥ {{ $item->low_stock_threshold }}</span>
                                        @endif
                                    </td>
                                    <td>₱{{ number_format($item->last_purchase_price ?? 0, 2) }}</td>
                                    <td>₱{{ number_format($totalItemValue, 2) }}</td>
                                    <td>{{ $item->last_restocked_at ? $item->last_restocked_at->format('M d, Y') : 'Never' }}
                                    </td>
                                    <td>
                                        @if ($item->expiration_date)
                                            {{ \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') }}
                                            @if (\Carbon\Carbon::parse($item->expiration_date)->isPast())
                                                <span class="badge bg-danger ms-1">Expired</span>
                                            @elseif(\Carbon\Carbon::parse($item->expiration_date)->diffInDays(now()) <= 30)
                                                <span class="badge bg-warning ms-1">Soon</span>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-warning rounded-pill me-1"
                                                onclick="openEditModal({{ $item->id }})" title="Edit Stock">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-outline-primary rounded-pill"
                                                onclick="openDistributeModal({{ $item->id }})">
                                                <i class="bi bi-send"></i> Distribute
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No products in warehouse inventory</p>
                                        <button class="btn btn-primary" onclick="openAddStockModal()">
                                            Add First Stock
                                        </button>
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
                        Showing {{ $inventory->firstItem() ?? 0 }} to {{ $inventory->lastItem() ?? 0 }} of
                        {{ $inventory->total() }} results
                    </div>
                    <div>
                        @if ($inventory->hasPages())
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end mb-0">
                                    @if ($inventory->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $inventory->previousPageUrl() }}" rel="prev">Previous</a>
                                        </li>
                                    @endif

                                    @if ($inventory->hasMorePages())
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $inventory->nextPageUrl() }}" rel="next">Next</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal Container -->
    <div class="modal fade" id="editModalContainer" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"></div>
        </div>
    </div>

    <!-- Distribute Modal Container -->
    <div class="modal fade" id="distributeModalContainer" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"></div>
        </div>
    </div>

    <!-- Add Stock Modal Container -->
    <div class="modal fade" id="addStockModalContainer" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"></div>
        </div>
    </div>

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

    <script>
        // ============ CONVERT SESSION FLASHES TO GLOBAL NOTIFICATIONS ============
        document.addEventListener('DOMContentLoaded', function() {
            const flashSuccess = document.getElementById('flashSuccess');
            const flashError = document.getElementById('flashError');

            if (flashSuccess && flashSuccess.textContent.trim()) {
                const message = flashSuccess.textContent.trim();
                const cleanMessage = message.replace(/Success!/g, '').trim();
                if (typeof window.showNotification === 'function') {
                    window.showNotification(cleanMessage, 'success');
                }
                flashSuccess.remove();
            }

            if (flashError && flashError.textContent.trim()) {
                const message = flashError.textContent.trim();
                const cleanMessage = message.replace(/Error!/g, '').trim();
                if (typeof window.showNotification === 'function') {
                    window.showNotification(cleanMessage, 'error');
                }
                flashError.remove();
            }
        });

        // ============ OPEN MODAL FUNCTIONS ============
        function openEditModal(id) {
            const modalElement = document.getElementById('editModalContainer');
            const modalContent = modalElement.querySelector('.modal-content');
            const url = '/admin/warehouse/' + id + '/edit-modal';

            modalContent.innerHTML =
                '<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div><p>Loading...</p></div>';

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading form</div>';
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                });
        }

        function openDistributeModal(id) {
            const modalElement = document.getElementById('distributeModalContainer');
            const modalContent = modalElement.querySelector('.modal-content');
            const url = '/admin/warehouse/' + id + '/distribute-modal';

            modalContent.innerHTML =
                '<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div><p>Loading...</p></div>';

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading form</div>';
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                });
        }

        function openAddStockModal() {
            const modalElement = document.getElementById('addStockModalContainer');
            const modalContent = modalElement.querySelector('.modal-content');
            const url = '/admin/warehouse/add-stock-modal';

            modalContent.innerHTML =
                '<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div><p>Loading...</p></div>';

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading form</div>';
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                });
        }

        // ============ SEARCH FUNCTIONALITY ============
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            const searchBtn = document.getElementById('searchBtn');
            const clearSearch = document.getElementById('clearSearch');

            // Search on Enter key
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (searchForm) {
                            searchForm.submit();
                        }
                    }
                });
            }

            // Search on button click
            if (searchBtn) {
                searchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (searchForm) {
                        searchForm.submit();
                    }
                });
            }

            // Clear search
            if (clearSearch) {
                clearSearch.addEventListener('click', function(e) {
                    // The link already clears the search via the route
                    // This is just a fallback
                });
            }
        });

        // ============ FLAVOR LOADING FOR MODALS ============
        // This function loads flavors when the edit modal is opened
        function loadEditFlavors() {
            var productSelect = document.querySelector('.product-select-edit');
            var flavorSelect = document.querySelector('.flavor-select-edit');

            if (!productSelect || !flavorSelect) {
                return;
            }

            var productId = productSelect.value;
            var currentFlavorId = flavorSelect.getAttribute('data-current-flavor-id') || '';

            if (!productId) {
                flavorSelect.disabled = true;
                flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                return;
            }

            flavorSelect.disabled = true;
            flavorSelect.innerHTML = '<option value="">Loading flavors...</option>';

            fetch('/admin/api/products/' + productId + '/flavors')
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
                    if (data.length > 0) {
                        data.forEach(function(flavor) {
                            var option = document.createElement('option');
                            option.value = flavor.id;
                            option.textContent = flavor.name;
                            flavorSelect.appendChild(option);
                        });
                        flavorSelect.disabled = false;
                        if (currentFlavorId) {
                            flavorSelect.value = currentFlavorId;
                        }
                    } else {
                        flavorSelect.innerHTML = '<option value="">No flavors available</option>';
                        flavorSelect.disabled = true;
                    }
                })
                .catch(function(error) {
                    console.error('Error loading flavors:', error);
                    flavorSelect.innerHTML = '<option value="">Error loading flavors</option>';
                    flavorSelect.disabled = true;
                });
        }

        // This function loads flavors when the add stock modal is opened
        function loadAddFlavors() {
            var productSelect = document.getElementById('productSelectAdd');
            var flavorSelect = document.getElementById('flavorSelectAdd');

            if (!productSelect || !flavorSelect) {
                return;
            }

            var productId = productSelect.value;

            if (!productId) {
                flavorSelect.disabled = true;
                flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                return;
            }

            flavorSelect.disabled = true;
            flavorSelect.innerHTML = '<option value="">Loading flavors...</option>';

            fetch('/admin/api/products/' + productId + '/flavors')
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
                    if (data.length > 0) {
                        data.forEach(function(flavor) {
                            var option = document.createElement('option');
                            option.value = flavor.id;
                            option.textContent = flavor.name;
                            flavorSelect.appendChild(option);
                        });
                        flavorSelect.disabled = false;
                    } else {
                        flavorSelect.innerHTML = '<option value="">No flavors available</option>';
                        flavorSelect.disabled = true;
                    }
                })
                .catch(function(error) {
                    console.error('Error loading flavors:', error);
                    flavorSelect.innerHTML = '<option value="">Error loading flavors</option>';
                    flavorSelect.disabled = true;
                });
        }

        // Listen for modal shown events to load flavors
        document.addEventListener('shown.bs.modal', function(e) {
            var target = e.target;

            // Edit modal shown
            if (target.id === 'editModalContainer') {
                setTimeout(loadEditFlavors, 300);

                // Also attach change event to product select
                var productSelect = target.querySelector('.product-select-edit');
                if (productSelect) {
                    productSelect.addEventListener('change', function() {
                        loadEditFlavors();
                    });
                }
            }

            // Add stock modal shown
            if (target.id === 'addStockModalContainer') {
                setTimeout(loadAddFlavors, 300);

                // Also attach change event to product select
                var productSelect = target.querySelector('#productSelectAdd');
                if (productSelect) {
                    productSelect.addEventListener('change', function() {
                        loadAddFlavors();
                    });
                }
            }
        });
    </script>
@endsection
