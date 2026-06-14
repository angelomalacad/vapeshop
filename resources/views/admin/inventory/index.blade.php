@extends('layouts.admin')
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

    /* Individual icon colors */
    .col-md-3:first-child .stat-icon-wrapper {
        background: #eef4ff;
        color: #3b82f6;
    }

    .col-md-3:nth-child(2) .stat-icon-wrapper {
        background: #fef2f2;
        color: #ef4444;
    }

    .col-md-3:nth-child(3) .stat-icon-wrapper {
        background: #fef2f2;
        color: #dc2626;
    }

    .col-md-3:nth-child(4) .stat-icon-wrapper {
        background: #f3f4f6;
        color: #6b7280;
    }

    .col-md-3:nth-child(5) .stat-icon-wrapper {
        background: #e6f7e6;
        color: #10b981;
    }

    /* Mobile responsive */
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
@section('title', 'Inventory Overview - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
        <!-- Success and Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Inventory Overview</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-box-seam me-1"></i> Stock levels across all branches
                    </p>
                </div>
            </div>
            <div class="mt-2 mt-md-0 d-flex gap-2">
                <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-warning rounded-pill px-3">
                    <i class="bi bi-exclamation-triangle"></i> Low Stock
                </a>
                <a href="{{ route('admin.inventory.transfers') }}" class="btn btn-info text-white rounded-pill px-3">
                    <i class="bi bi-arrow-left-right"></i> Transfers
                </a>
                <a href="{{ route('admin.inventory.stock-history') }}" class="btn btn-secondary rounded-pill px-3">
                    <i class="bi bi-clock-history"></i> History
                </a>
            </div>
        </div>

        <!-- Stats Cards - Modern Minimalist (Inventory) -->
        <div class="row g-4 mb-4">
            @php
                $totalItems = \App\Models\BranchInventory::count();
                $lowStock = \App\Models\BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')
                    ->where('is_disposed', false)
                    ->count();
                $outOfStock = \App\Models\BranchInventory::where('quantity', '<=', 0)
                    ->where('is_disposed', false)
                    ->count();
                $totalValue = \App\Models\BranchInventory::with('product')
                    ->where('is_disposed', false)
                    ->get()
                    ->sum(fn($item) => $item->quantity * ($item->product->price ?? 0));
                $disposedCount = \App\Models\BranchInventory::where('is_disposed', true)->count();
            @endphp
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Items</span>
                        <h3 class="stat-value">{{ $totalItems }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Low Stock</span>
                        <h3 class="stat-value">{{ $lowStock }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Out of Stock</span>
                        <h3 class="stat-value">{{ $outOfStock }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-trash"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Disposed Items</span>
                        <h3 class="stat-value">{{ $disposedCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Value</span>
                        <h3 class="stat-value">₱{{ number_format($totalValue, 0) }}</h3>
                    </div>
                </div>
            </div>


            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Inventory</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3" id="filterForm">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Search Product</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by product name..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Branch</label>
                            <select name="branch_id" class="form-select">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Product</label>
                            <select name="product_id" class="form-select">
                                <option value="">All Products</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Stock Status</label>
                            <select name="stock_status" class="form-select">
                                <option value="">All Status</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock
                                </option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of
                                    Stock</option>
                                <option value="archived" {{ request('stock_status') == 'archived' ? 'selected' : '' }}>
                                    Archived</option>
                                <option value="disposed" {{ request('stock_status') == 'disposed' ? 'selected' : '' }}>
                                    Disposed</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-funnel me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary ms-2 px-4">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Image</th>
                                    <th>Branch</th>
                                    <th>Product</th>
                                    <th>Flavor</th>
                                    <th>In Stock</th>
                                    <th>Reserved</th>
                                    <th>Available</th>
                                    <th>Status</th>
                                    <th>Threshold</th>
                                    <th>Expiration Date</th>
                                    <th class="pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventories as $inv)
                                    @php
                                        $product = $inv->product;
                                        $available = $inv->quantity - $inv->reserved_quantity;
                                        $isArchived = $inv->is_archived ?? false;
                                        $isDisposed = $inv->is_disposed ?? false;
                                        $imageUrl = null;
                                        if ($product && $product->image) {
                                            if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                                $imageUrl = $product->image;
                                            } elseif (Storage::disk('public')->exists($product->image)) {
                                                $imageUrl = Storage::url($product->image);
                                            }
                                        }

                                        if ($isDisposed) {
                                            $statusClass = 'secondary';
                                            $statusText = 'Disposed';
                                        } elseif ($isArchived) {
                                            $statusClass = 'secondary';
                                            $statusText = 'Archived';
                                        } elseif ($available <= 0) {
                                            $statusClass = 'danger';
                                            $statusText = 'Out of Stock';
                                        } elseif ($available <= $inv->low_stock_threshold) {
                                            $statusClass = 'warning';
                                            $statusText = 'Low Stock';
                                        } else {
                                            $statusClass = 'success';
                                            $statusText = 'In Stock';
                                        }
                                        $expiry = $inv->expiration_date
                                            ? \Carbon\Carbon::parse($inv->expiration_date)
                                            : null;
                                    @endphp
                                    <tr>
                                        <td class="ps-4" style="width: 60px">
                                            @if ($imageUrl)
                                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <div
                                                    style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-box-seam text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $inv->branch->name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $inv->branch->manager_name }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $product->name ?? 'N/A' }}</span>
                                            <br>
                                            <small class="text-muted">{{ $product->brand ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $inv->flavor->name ?? 'N/A' }}</td>
                                        <td>{{ $inv->quantity }}</td>
                                        <td>{{ $inv->reserved_quantity }}</td>
                                        <td>
                                            <span class="fw-bold text-{{ $statusClass }}">{{ $available }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>{{ $inv->low_stock_threshold }}</td>
                                        <td>
                                            @if ($expiry && !$isDisposed)
                                                {{ $expiry->format('M d, Y') }}
                                                @if ($expiry->isPast())
                                                    <span class="badge bg-danger ms-1">Expired</span>
                                                @elseif($expiry->diffInDays(now()) <= 30)
                                                    <span class="badge bg-warning ms-1">Soon</span>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            <div class="btn-group btn-group-sm">
                                                @if ($isDisposed)
                                                    <a href="{{ route('admin.inventory.restore-disposed', $inv) }}"
                                                        class="btn btn-outline-success" title="Restore"
                                                        onclick="return confirm('Restore this item back to inventory?')">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </a>
                                                @else
                                                    <button type="button" class="btn btn-outline-info"
                                                        title="View Details"
                                                        onclick="openShowModal({{ $inv->id }})">
                                                        <i class="bi bi-eye"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-outline-warning" title="Edit"
                                                        onclick="openEditModal({{ $inv->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-outline-success"
                                                        title="Add Stock"
                                                        onclick="openAddStockModal({{ $inv->id }})">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>

                                                    @if ($isArchived)
                                                        <a href="{{ route('admin.inventory.unarchive', $inv) }}"
                                                            class="btn btn-outline-secondary" title="Restore"
                                                            onclick="return confirm('Restore this item?')">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.inventory.archive', $inv) }}"
                                                            class="btn btn-outline-secondary" title="Archive"
                                                            onclick="return confirm('Archive this item? It will be hidden from active inventory.')">
                                                            <i class="bi bi-archive"></i>
                                                        </a>
                                                    @endif

                                                    <button type="button" class="btn btn-outline-danger"
                                                        title="Dispose Item" data-bs-toggle="modal"
                                                        data-bs-target="#disposeModal" data-id="{{ $inv->id }}"
                                                        data-name="{{ $product->name ?? 'Item' }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <i class="bi bi-box-seam display-1 text-muted"></i>
                                            <p class="mt-3 text-muted">No inventory items found</p>
                                            <a href="{{ route('admin.inventory.create') }}"
                                                class="btn btn-primary rounded-pill px-4">
                                                <i class="bi bi-plus-circle me-1"></i> Add Your First Inventory Item
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
                            Showing {{ $inventories->firstItem() ?? 0 }} to {{ $inventories->lastItem() ?? 0 }} of
                            {{ $inventories->total() }} items
                        </div>
                        <div>
                            @if ($inventories->hasPages())
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm mb-0">
                                        @if ($inventories->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">Previous</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $inventories->previousPageUrl() }}"
                                                    rel="prev">Previous</a>
                                            </li>
                                        @endif

                                        @if ($inventories->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $inventories->nextPageUrl() }}"
                                                    rel="next">Next</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">Next</span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Containers -->
        <div class="modal fade" id="showInventoryModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content"></div>
            </div>
        </div>

        <div class="modal fade" id="editInventoryModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content"></div>
            </div>
        </div>

        <div class="modal fade" id="addStockModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </div>

        <!-- Dispose Modal -->
        <div class="modal fade" id="disposeModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-trash me-2"></i> Dispose Item
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="disposeForm" method="POST" action="">
                        @csrf
                        <div class="modal-body">
                            <p>Are you sure you want to dispose this item?</p>
                            <p class="fw-bold" id="disposeItemName"></p>
                            <div class="mb-3">
                                <label class="form-label">Reason for Disposal (Optional)</label>
                                <textarea name="dispose_reason" class="form-control" rows="3"
                                    placeholder="e.g., Expired, Damaged, Defective, etc."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i> Dispose
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('select[name="branch_id"], select[name="product_id"], select[name="stock_status"]')
                .forEach(function(select) {
                    select.addEventListener('change', function() {
                        document.getElementById('filterForm').submit();
                    });
                });

            function openShowModal(id) {
                const modalElement = document.getElementById('showInventoryModal');
                const modalContent = modalElement.querySelector('.modal-content');
                const url = '/admin/inventory/' + id + '/show-modal';

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
                        new bootstrap.Modal(modalElement).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading details</div>';
                        new bootstrap.Modal(modalElement).show();
                    });
            }

            function openEditModal(id) {
                const modalElement = document.getElementById('editInventoryModal');
                const modalContent = modalElement.querySelector('.modal-content');
                const url = '/admin/inventory/' + id + '/edit-modal';

                modalContent.innerHTML =
                    '<div class="text-center p-5"><div class="spinner-border text-warning" role="status"></div><p>Loading...</p></div>';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                        new bootstrap.Modal(modalElement).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading form</div>';
                        new bootstrap.Modal(modalElement).show();
                    });
            }

            function openAddStockModal(id) {
                const modalElement = document.getElementById('addStockModal');
                const modalContent = modalElement.querySelector('.modal-content');
                const url = '/admin/inventory/' + id + '/add-stock-modal';

                modalContent.innerHTML =
                    '<div class="text-center p-5"><div class="spinner-border text-success" role="status"></div><p>Loading...</p></div>';

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                        new bootstrap.Modal(modalElement).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading form</div>';
                        new bootstrap.Modal(modalElement).show();
                    });
            }

            const disposeModal = document.getElementById('disposeModal');
            if (disposeModal) {
                disposeModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const inventoryId = button.getAttribute('data-id');
                    const itemName = button.getAttribute('data-name');

                    document.getElementById('disposeItemName').textContent = itemName;
                    const disposeForm = document.getElementById('disposeForm');
                    disposeForm.action = '/admin/inventory/' + inventoryId + '/dispose';
                });
            }
        </script>
    @endsection
