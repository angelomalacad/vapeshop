@extends('layouts.branch-admin')

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

    /* === ADDED: MODERN MINIMALIST MODAL STYLES (from admin-modal.blade.php) === */
    .admin-modal-container {
        padding: 1.5rem;
        max-height: 85vh;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .admin-modal-container::-webkit-scrollbar {
        width: 6px;
    }

    .admin-modal-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .admin-modal-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Modal Header */
    .modal-header-minimal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eef2f6;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .modal-title i {
        color: #3b82f6;
        margin-right: 0.5rem;
    }

    /* Cards */
    .info-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .card-header-minimal {
        padding: 0.875rem 1.25rem;
        background: white;
        border-bottom: 1px solid #eef2f6;
    }

    .card-header-minimal h6 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .card-header-minimal h6 i {
        margin-right: 0.5rem;
        color: #3b82f6;
    }

    .card-body-minimal {
        padding: 1rem 1.25rem;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
    }

    .info-label {
        width: 100px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
    }

    .info-value {
        flex: 1;
        font-size: 0.8rem;
        color: #1a1a2e;
        font-weight: 500;
    }

    .info-value .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.65rem;
    }

    /* Status Badges */
    .badge-delivered {
        background: #d1fae5;
        color: #059669;
    }

    .badge-in_transit {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-picked_up {
        background: #dbeafe;
        color: #2563eb;
    }

    .badge-assigned {
        background: #f1f5f9;
        color: #475569;
    }

    /* Form Styles */
    .form-label-minimal {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .form-control-minimal,
    .form-select-minimal {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control-minimal:focus,
    .form-select-minimal:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    /* Proof Images */
    .proof-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .proof-image:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Buttons */
    .btn-update {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-secondary-minimal {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary-minimal:hover {
        background: #e2e8f0;
    }

    .btn-download {
        font-size: 0.7rem;
        padding: 0.25rem 0.75rem;
        border-radius: 30px;
    }

    /* Alert Styles */
    .alert-minimal {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .alert-danger-minimal {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        color: #dc2626;
    }

    .alert-success-minimal {
        background: #ecfdf5;
        border: 1px solid #d1fae5;
        color: #059669;
    }

    .alert-info-minimal {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
    }

    /* Image Preview Modal */
    .image-preview-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .image-preview-content {
        background: white;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        overflow: hidden;
    }

    .image-preview-header {
        padding: 1rem 1.25rem;
        background: white;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .image-preview-body {
        padding: 1.5rem;
        text-align: center;
    }

    .image-preview-body img {
        max-width: 100%;
        max-height: 400px;
        border-radius: 12px;
    }

    .image-preview-footer {
        padding: 1rem 1.25rem;
        background: #f8f9fa;
        border-top: 1px solid #eef2f6;
        text-align: right;
    }

    /* Gap utility */
    .gap-2 {
        gap: 0.5rem;
    }

    .gap-3 {
        gap: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-modal-container {
            padding: 1rem;
        }

        .info-label {
            width: 80px;
        }

        .card-header-minimal {
            padding: 0.75rem 1rem;
        }

        .card-body-minimal {
            padding: 0.75rem 1rem;
        }
    }

    /* NEW: Reservation display styles */
    .reserved-badge {
        background: #fef3c7;
        color: #d97706;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .reserved-badge i {
        font-size: 0.8rem;
    }

    .available-badge {
        background: #d1fae5;
        color: #059669;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .inventory-quantity {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .quantity-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-label {
        font-size: 0.7rem;
        color: #64748b;
        min-width: 60px;
    }

    .quantity-value {
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* NEW: Low stock row highlighting - Fixed with !important */
    .table > tbody > tr.table-low-stock > td {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
    }

    .table > tbody > tr.table-low-stock:hover > td {
        background-color: #fecaca !important;
    }

    /* Make text more visible */
    .table > tbody > tr.table-low-stock > td .fw-bold,
    .table > tbody > tr.table-low-stock > td .fw-semibold {
        color: #991b1b !important;
        font-weight: 700 !important;
    }

    .table > tbody > tr.table-low-stock > td .text-muted {
        color: #7f1d1d !important;
    }

    .table > tbody > tr.table-low-stock > td .quantity-value {
        color: #991b1b !important;
        font-weight: 700 !important;
    }

    .table > tbody > tr.table-low-stock > td .quantity-label {
        color: #7f1d1d !important;
    }

    /* Style badges inside low stock rows */
    .table > tbody > tr.table-low-stock > td .badge {
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.2);
    }

    .table > tbody > tr.table-low-stock > td .badge-low-stock {
        background: #b91c1c !important;
        color: white !important;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.4rem 0.7rem !important;
    }

    /* Update available badge in low stock rows */
    .table > tbody > tr.table-low-stock > td .available-badge {
        background: #dc2626 !important;
        color: white !important;
    }

    /* Update reserved badge in low stock rows */
    .table > tbody > tr.table-low-stock > td .reserved-badge {
        background: #dc2626 !important;
        color: white !important;
    }

    /* Style action buttons in low stock rows */
    .table > tbody > tr.table-low-stock > td .btn {
        border-color: #dc2626 !important;
    }

    .table > tbody > tr.table-low-stock > td .btn:hover {
        background: #dc2626 !important;
        color: white !important;
    }

    .table > tbody > tr.table-low-stock > td .btn-outline-info {
        color: #dc2626 !important;
        border-color: #dc2626 !important;
    }

    .table > tbody > tr.table-low-stock > td .btn-outline-warning {
        color: #dc2626 !important;
        border-color: #dc2626 !important;
    }

    .table > tbody > tr.table-low-stock > td .btn-outline-secondary {
        color: #dc2626 !important;
        border-color: #dc2626 !important;
    }

    .table > tbody > tr.table-low-stock > td .btn-outline-danger {
        color: #dc2626 !important;
        border-color: #dc2626 !important;
    }

    /* Make sure price text is visible */
    .table > tbody > tr.table-low-stock > td:last-child {
        font-weight: 700;
    }
</style>
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Branch Inventory</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-shop me-1"></i> {{ Auth::user()->branch->name }} (Current Branch)
                </p>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="inventoryTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="my-branch-tab" data-bs-toggle="tab" data-bs-target="#my-branch"
                    type="button" role="tab">
                    <i class="bi bi-shop me-1"></i> My Branch ({{ Auth::user()->branch->name }})
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="other-branches-tab" data-bs-toggle="tab" data-bs-target="#other-branches"
                    type="button" role="tab">
                    <i class="bi bi-building me-1"></i> Other Branches
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="disposed-tab" data-bs-toggle="tab" data-bs-target="#disposed" type="button"
                    role="tab">
                    <i class="bi bi-trash me-1"></i> Disposed Items
                </button>
            </li>
        </ul>

        <div class="tab-content" id="inventoryTabsContent">
            <!-- My Branch Tab -->
            <div class="tab-pane fade show active" id="my-branch" role="tabpanel">
                <!-- Quick Stats Cards -->
                <div class="row g-4 mb-4">
                    <!-- Total Items -->
                    <div class="col-md-3 col-6">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: #dbeafe; color: #2563eb;">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Total Items</span>
                                <h3 class="stat-value">{{ $inventories->total() }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- In Stock -->
                    <div class="col-md-3 col-6">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">In Stock</span>
                                <h3 class="stat-value">
                                    {{ $inventories->filter(function ($item) {
                                            return $item->available_quantity > $item->low_stock_threshold && !($item->is_archived ?? false);
                                        })->count() }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock -->
                    <div class="col-md-3 col-6">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Low Stock</span>
                                <h3 class="stat-value">{{ $lowStockCount ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Out of Stock -->
                    <div class="col-md-3 col-6">
                        <div class="stat-card-modern">
                            <div class="stat-icon-wrapper" style="background: #fee2e2; color: #dc2626;">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Out of Stock</span>
                                <h3 class="stat-value">
                                    {{ $inventories->filter(function ($item) {
                                            return $item->available_quantity <= 0 && !($item->is_archived ?? false);
                                        })->count() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3" id="myBranchForm"
                            action="{{ route('branch-admin.inventory.index') }}">
                            <input type="hidden" name="tab" value="my-branch">
                            <div class="col-md-4">
                                <label class="form-label">Search Product</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search by product name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product</label>
                                <select name="product_id" class="form-select" id="product_filter">
                                    <option value="">All Products</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Stock Status</label>
                                <select name="stock_status" class="form-select" id="stock_status_filter">
                                    <option value="">All Status</option>
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low
                                        Stock</option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of
                                        Stock</option>
                                    <option value="archived"
                                        {{ request('stock_status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Apply Filters
                                </button>
                                <a href="{{ route('branch-admin.inventory.index', ['tab' => 'my-branch']) }}"
                                    class="btn btn-outline-secondary ms-2">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Inventory List - {{ Auth::user()->branch->name }}</h5>
                            <span class="text-muted small">Total Value:
                                ₱{{ number_format(
                                    $inventories->sum(function ($item) {
                                        return $item->quantity * $item->product->price;
                                    }),
                                    2,
                                ) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <th>Brand</th>
                                        <th>Flavor</th>
                                        <th>Specs</th>
                                        <th>In Stock</th>
                                        <th>Available</th>
                                        <th>Reserved</th>
                                        <th>Expiration Date</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventories as $inv)
                                        @php
                                            $product = $inv->product;
                                            $available = $inv->available_quantity;
                                            $reserved = $inv->reserved_quantity ?? 0;
                                            $isArchived = $inv->is_archived ?? false;
                                            $isLowStock = $available <= $inv->low_stock_threshold && $available > 0;
                                            $expiry = $inv->expiration_date
                                                ? \Carbon\Carbon::parse($inv->expiration_date)
                                                : null;
                                            $imageUrl = null;
                                            if ($product && $product->image) {
                                                if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                                    $imageUrl = $product->image;
                                                } elseif (Storage::disk('public')->exists($product->image)) {
                                                    $imageUrl = Storage::url($product->image);
                                                }
                                            }
                                        @endphp
                                        <tr class="{{ $isLowStock ? 'table-low-stock' : '' }}">
                                            <td style="width: 60px">
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
                                                <span class="fw-semibold">{{ $product->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ $product->category }}</small>
                                            </td>
                                            <td>{{ $product->brand ?? 'N/A' }}</td>
                                            <td>{{ $inv->flavor->name ?? 'N/A' }}</td>
                                            <td>
                                                @if ($product->category == 'Ultra')
                                                    <small>
                                                        @if ($product->puff_count)
                                                            {{ number_format($product->puff_count) }} puffs
                                                        @endif
                                                        @if ($product->battery_capacity)
                                                            {{ $product->battery_capacity }}mAh
                                                        @endif
                                                    </small>
                                                @else
                                                    <small>Standard Pod</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="inventory-quantity">
                                                    <span class="quantity-value">{{ $inv->quantity }}</span>
                                                    <small class="text-muted">Alert: {{ $inv->low_stock_threshold }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="available-badge">
                                                    <i class="bi bi-check-circle"></i> {{ $available }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($reserved > 0)
                                                    <span class="reserved-badge">
                                                        <i class="bi bi-clock"></i> {{ $reserved }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($expiry)
                                                    {{ $expiry->format('M d, Y') }}
                                                    @if ($expiry->isPast())
                                                        <span class="badge bg-danger ms-1">Expired</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($isArchived)
                                                    <span class="badge bg-secondary">Archived</span>
                                                @elseif($available <= 0)
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($isLowStock)
                                                    <span class="badge badge-low-stock">Low Stock</span>
                                                @else
                                                    <span class="badge bg-success">In Stock</span>
                                                @endif
                                            </td>
                                            <td>₱{{ number_format($product->price, 2) }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-info"
                                                        title="View Details" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal"
                                                        data-url="{{ route('branch-admin.inventory.show-modal', $inv) }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning"
                                                        title="Edit Inventory" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal"
                                                        data-url="{{ route('branch-admin.inventory.edit-modal', $inv) }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>

                                                    @if ($isArchived)
                                                        <button type="button" class="btn btn-outline-success restore-archive-btn"
                                                            title="Restore Item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#restoreArchiveModal"
                                                            data-id="{{ $inv->id }}"
                                                            data-name="{{ $product->name }}">
                                                            <i class="bi bi-arrow-counterclockwise"></i>
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-outline-secondary archive-btn"
                                                            title="Archive Item" data-bs-toggle="modal"
                                                            data-bs-target="#archiveModal" data-id="{{ $inv->id }}"
                                                            data-name="{{ $product->name }}">
                                                            <i class="bi bi-archive"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger dispose-btn"
                                                            title="Dispose Item" data-bs-toggle="modal"
                                                            data-bs-target="#disposeModal" data-id="{{ $inv->id }}"
                                                            data-name="{{ $product->name }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-5">
                                                <i class="bi bi-box-seam display-1 text-muted"></i>
                                                <p class="mt-3 text-muted">No inventory items found</p>
                                                <a href="{{ route('branch-admin.inventory.add-product') }}"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-plus-circle"></i> Add Product to Inventory
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
                                @if ($inventories->onFirstPage())
                                    <span class="btn btn-outline-secondary disabled rounded-pill px-3 me-2">
                                        Previous
                                    </span>
                                @else
                                    <a href="{{ $inventories->previousPageUrl() }}&tab=my-branch"
                                        class="btn btn-outline-primary rounded-pill px-3 me-2">
                                        Previous
                                    </a>
                                @endif

                                @if ($inventories->hasMorePages())
                                    <a href="{{ $inventories->nextPageUrl() }}&tab=my-branch"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        Next
                                    </a>
                                @else
                                    <span class="btn btn-outline-secondary disabled rounded-pill px-3">
                                        Next
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Branches Tab -->
            <div class="tab-pane fade" id="other-branches" role="tabpanel">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Other Branches Inventory</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section for Other Branches -->
                        <form method="GET" class="row g-3 mb-4" id="otherBranchesForm"
                            action="{{ route('branch-admin.inventory.index') }}">
                            <input type="hidden" name="tab" value="other-branches">
                            <div class="col-md-4">
                                <label class="form-label">Search Product</label>
                                <input type="text" name="search_other" class="form-control"
                                    placeholder="Search by product name..." value="{{ request('search_other') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch</label>
                                <select name="branch_filter" class="form-select">
                                    <option value="">All Branches</option>
                                    @php
                                        $currentBranchId = Auth::user()->branch_id;
                                        $allOtherBranches = \App\Models\Branch::where(
                                            'id',
                                            '!=',
                                            $currentBranchId,
                                        )->get();
                                    @endphp
                                    @foreach ($allOtherBranches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ request('branch_filter') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product</label>
                                <select name="product_id_other" class="form-select">
                                    <option value="">All Products</option>
                                    @foreach ($allProducts as $product)
                                        <option value="{{ $product->id }}"
                                            {{ request('product_id_other') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Apply Filters
                                </button>
                                <a href="{{ route('branch-admin.inventory.index', ['tab' => 'other-branches']) }}"
                                    class="btn btn-outline-secondary ms-2">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    $currentBranchId = Auth::user()->branch_id;
                    $otherBranchesList = \App\Models\Branch::where('id', '!=', $currentBranchId)->get();

                    if (request('branch_filter')) {
                        $otherBranchesList = \App\Models\Branch::where('id', request('branch_filter'))->get();
                    }
                @endphp

                @foreach ($otherBranchesList as $branch)
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-building me-2"></i> {{ $branch->name }}
                                </h5>
                                <span class="text-muted small">
                                    Total Items: {{ $branchInventories[$branch->id]['total'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $branchItems = $branchInventories[$branch->id]['items'] ?? null;
                            @endphp

                            @if ($branchItems && $branchItems->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Image</th>
                                                <th>Product</th>
                                                <th>Brand</th>
                                                <th>Flavor</th>
                                                <th>Specs</th>
                                                <th>In Stock</th>
                                                <th>Available</th>
                                                <th>Reserved</th>
                                                <th>Expiration Date</th>
                                                <th>Status</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($branchItems as $inv)
                                                @php
                                                    $product = $inv->product;
                                                    $available = $inv->available_quantity;
                                                    $reserved = $inv->reserved_quantity ?? 0;
                                                    $isLowStock = $available <= $inv->low_stock_threshold && $available > 0;
                                                    $expiry = $inv->expiration_date
                                                        ? \Carbon\Carbon::parse($inv->expiration_date)
                                                        : null;
                                                    $imageUrl = null;
                                                    if ($product && $product->image) {
                                                        if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                                            $imageUrl = $product->image;
                                                        } elseif (Storage::disk('public')->exists($product->image)) {
                                                            $imageUrl = Storage::url($product->image);
                                                        }
                                                    }
                                                @endphp
                                                <tr class="{{ $isLowStock ? 'table-low-stock' : '' }}">
                                                    <td style="width: 60px">
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
                                                        <span class="fw-semibold">{{ $product->name }}</span>
                                                        <br>
                                                        <small class="text-muted">{{ $product->category }}</small>
                                                    </td>
                                                    <td>{{ $product->brand ?? 'N/A' }}</td>
                                                    <td>{{ $inv->flavor->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if ($product->category == 'Ultra')
                                                            <small>
                                                                @if ($product->puff_count)
                                                                    {{ number_format($product->puff_count) }} puffs
                                                                @endif
                                                                @if ($product->battery_capacity)
                                                                    {{ $product->battery_capacity }}mAh
                                                                @endif
                                                            </small>
                                                        @else
                                                            <small>Standard Pod</small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold">{{ $inv->quantity }}</span>
                                                        <br>
                                                        <small class="text-muted">Alert:
                                                            {{ $inv->low_stock_threshold }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="available-badge">
                                                            <i class="bi bi-check-circle"></i> {{ $available }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($reserved > 0)
                                                            <span class="reserved-badge">
                                                                <i class="bi bi-clock"></i> {{ $reserved }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">0</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($expiry)
                                                            {{ $expiry->format('M d, Y') }}
                                                            @if ($expiry->isPast())
                                                                <span class="badge bg-danger ms-1">Expired</span>
                                                            @endif
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($available <= 0)
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @elseif($isLowStock)
                                                            <span class="badge badge-low-stock">Low Stock</span>
                                                        @else
                                                            <span class="badge bg-success">In Stock</span>
                                                        @endif
                                                    </td>
                                                    <td>₱{{ number_format($product->price, 2) }}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-sm btn-primary request-transfer-btn"
                                                            data-inventory-id="{{ $inv->id }}"
                                                            data-from-branch="{{ $branch->id }}">
                                                            <i class="bi bi-arrow-right"></i> Request Transfer
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Fixed Pagination for this branch -->
                                <div class="d-flex justify-content-center mt-3">
                                    @if ($branchItems->onFirstPage())
                                        <span class="btn btn-outline-secondary disabled rounded-pill px-3 me-2">
                                            Previous
                                        </span>
                                    @else
                                        <a href="{{ route('branch-admin.inventory.index', array_merge(request()->all(), ['branch_page_' . $branch->id => $branchItems->currentPage() - 1, 'tab' => 'other-branches'])) }}"
                                            class="btn btn-outline-primary rounded-pill px-3 me-2">
                                            Previous
                                        </a>
                                    @endif

                                    @if ($branchItems->hasMorePages())
                                        <a href="{{ route('branch-admin.inventory.index', array_merge(request()->all(), ['branch_page_' . $branch->id => $branchItems->currentPage() + 1, 'tab' => 'other-branches'])) }}"
                                            class="btn btn-outline-primary rounded-pill px-3">
                                            Next
                                        </a>
                                    @else
                                        <span class="btn btn-outline-secondary disabled rounded-pill px-3">
                                            Next
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-box-seam display-1 text-muted"></i>
                                    <p class="mt-3 text-muted">No inventory items found in this branch</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Disposed Items Tab -->
            <div class="tab-pane fade" id="disposed" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Disposed Items</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $disposedItems = \App\Models\BranchInventory::where('branch_id', Auth::user()->branch_id)
                                ->where('is_disposed', true)
                                ->with(['product', 'flavor'])
                                ->orderBy('disposed_at', 'desc')
                                ->paginate(15);
                        @endphp

                        @if ($disposedItems->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Flavor</th>
                                            <th>Quantity</th>
                                            <th>Disposed Date</th>
                                            <th>Reason</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disposedItems as $item)
                                            @php
                                                $product = $item->product;
                                                $imageUrl = null;
                                                if ($product && $product->image) {
                                                    if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                                        $imageUrl = $product->image;
                                                    } elseif (Storage::disk('public')->exists($product->image)) {
                                                        $imageUrl = Storage::url($product->image);
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td style="width: 60px">
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
                                                    <span class="fw-semibold">{{ $product->name }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $product->category }}</small>
                                                </td>
                                                <td>{{ $item->flavor->name ?? 'N/A' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ $item->disposed_at ? $item->disposed_at->format('M d, Y h:i A') : $item->updated_at->format('M d, Y h:i A') }}
                                                </td>
                                                <td>{{ $item->dispose_reason ?? 'No reason provided' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-success restore-disposed-btn"
                                                        title="Restore Item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#restoreDisposedModal"
                                                        data-id="{{ $item->id }}"
                                                        data-name="{{ $product->name }}">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                @if ($disposedItems->onFirstPage())
                                    <span class="btn btn-outline-secondary disabled rounded-pill px-3 me-2">
                                        Previous
                                    </span>
                                @else
                                    <a href="{{ $disposedItems->previousPageUrl() }}&tab=disposed"
                                        class="btn btn-outline-primary rounded-pill px-3 me-2">
                                        Previous
                                    </a>
                                @endif

                                @if ($disposedItems->hasMorePages())
                                    <a href="{{ $disposedItems->nextPageUrl() }}&tab=disposed"
                                        class="btn btn-outline-primary rounded-pill px-3">
                                        Next
                                    </a>
                                @else
                                    <span class="btn btn-outline-secondary disabled rounded-pill px-3">
                                        Next
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-trash display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No disposed items found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Modal (Global UI) -->
    <div class="modal fade" id="archiveModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="admin-modal-container">
                    <div class="modal-header-minimal">
                        <h5 class="modal-title">
                            <i class="bi bi-archive"></i> Archive Item
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="archiveForm" method="POST" action="#">
                        @csrf
                        <div class="modal-body p-0">
                            <div class="alert alert-warning alert-minimal mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Warning:</strong> This action will archive the item.
                            </div>
                            <p>Are you sure you want to archive this item?</p>
                            <p class="fw-bold" id="archiveItemName"></p>
                            <p class="text-muted small">Archived items can be viewed by selecting "Archived" in the stock status filter.</p>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-update" style="width: auto; padding: 0.5rem 1.25rem;">
                                <i class="bi bi-archive me-1"></i> Archive Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Dispose Modal (Global UI) -->
    <div class="modal fade" id="disposeModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="admin-modal-container">
                    <div class="modal-header-minimal">
                        <h5 class="modal-title">
                            <i class="bi bi-trash"></i> Dispose Item
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="disposeForm" method="POST" action="#">
                        @csrf
                        <div class="modal-body p-0">
                            <div class="alert alert-danger alert-minimal mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Warning:</strong> This action will permanently remove the item from inventory.
                            </div>
                            <p>Are you sure you want to dispose this item?</p>
                            <p class="fw-bold" id="disposeItemName"></p>
                            <div class="mb-3">
                                <label class="form-label-minimal">Reason for Disposal (Optional)</label>
                                <textarea name="dispose_reason" class="form-control-minimal" rows="3"
                                    placeholder="e.g., Expired, Damaged, Defective, etc."></textarea>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-update" style="width: auto; padding: 0.5rem 1.25rem; background: #dc2626;">
                                <i class="bi bi-trash me-1"></i> Dispose Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Archive Modal (Global UI) -->
    <div class="modal fade" id="restoreArchiveModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="admin-modal-container">
                    <div class="modal-header-minimal">
                        <h5 class="modal-title">
                            <i class="bi bi-arrow-counterclockwise"></i> Restore Archived Item
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="restoreArchiveForm" method="POST" action="#">
                        @csrf
                        <div class="modal-body p-0">
                            <div class="alert alert-info alert-minimal mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Restore Item:</strong> This will bring the item back to active inventory.
                            </div>
                            <p>Are you sure you want to restore this archived item?</p>
                            <p class="fw-bold" id="restoreArchiveItemName"></p>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-update" style="width: auto; padding: 0.5rem 1.25rem; background: #10b981;">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Restore Disposed Modal (Global UI) -->
    <div class="modal fade" id="restoreDisposedModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="admin-modal-container">
                    <div class="modal-header-minimal">
                        <h5 class="modal-title">
                            <i class="bi bi-arrow-counterclockwise"></i> Restore Disposed Item
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="restoreDisposedForm" method="POST" action="#">
                        @csrf
                        <div class="modal-body p-0">
                            <div class="alert alert-info alert-minimal mb-4">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Restore Item:</strong> This will bring the item back to active inventory.
                            </div>
                            <p>Are you sure you want to restore this disposed item?</p>
                            <p class="fw-bold" id="restoreDisposedItemName"></p>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn-update" style="width: auto; padding: 0.5rem 1.25rem; background: #10b981;">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Modal (Global UI) - Loaded via AJAX -->
    <div class="modal fade" id="transferModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Modal Container (for View / Edit) -->
    <div class="modal fade" id="dynamicModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const myBranchTab = document.getElementById('my-branch-tab');
            const otherBranchesTab = document.getElementById('other-branches-tab');
            const disposedTab = document.getElementById('disposed-tab');
            const myBranchPane = document.getElementById('my-branch');
            const otherBranchesPane = document.getElementById('other-branches');
            const disposedPane = document.getElementById('disposed');

            function setActiveTab() {
                const urlParams = new URLSearchParams(window.location.search);
                const tabParam = urlParams.get('tab');

                if (tabParam === 'other-branches') {
                    if (otherBranchesTab) otherBranchesTab.click();
                } else if (tabParam === 'disposed') {
                    if (disposedTab) disposedTab.click();
                } else {
                    if (myBranchTab) myBranchTab.click();
                }
            }

            if (myBranchTab) {
                myBranchTab.addEventListener('click', function() {
                    myBranchTab.classList.add('active');
                    if (otherBranchesTab) otherBranchesTab.classList.remove('active');
                    if (disposedTab) disposedTab.classList.remove('active');
                    myBranchPane.classList.add('show', 'active');
                    otherBranchesPane.classList.remove('show', 'active');
                    if (disposedPane) disposedPane.classList.remove('show', 'active');

                    let url = new URL(window.location.href);
                    url.searchParams.set('tab', 'my-branch');
                    window.history.pushState({}, '', url);
                });
            }

            if (otherBranchesTab) {
                otherBranchesTab.addEventListener('click', function() {
                    otherBranchesTab.classList.add('active');
                    if (myBranchTab) myBranchTab.classList.remove('active');
                    if (disposedTab) disposedTab.classList.remove('active');
                    otherBranchesPane.classList.add('show', 'active');
                    myBranchPane.classList.remove('show', 'active');
                    if (disposedPane) disposedPane.classList.remove('show', 'active');

                    let url = new URL(window.location.href);
                    url.searchParams.set('tab', 'other-branches');
                    window.history.pushState({}, '', url);
                });
            }

            if (disposedTab) {
                disposedTab.addEventListener('click', function() {
                    disposedTab.classList.add('active');
                    if (myBranchTab) myBranchTab.classList.remove('active');
                    if (otherBranchesTab) otherBranchesTab.classList.remove('active');
                    disposedPane.classList.add('show', 'active');
                    myBranchPane.classList.remove('show', 'active');
                    otherBranchesPane.classList.remove('show', 'active');

                    let url = new URL(window.location.href);
                    url.searchParams.set('tab', 'disposed');
                    window.history.pushState({}, '', url);
                });
            }

            setActiveTab();

            const productFilter = document.getElementById('product_filter');
            const stockStatusFilter = document.getElementById('stock_status_filter');
            const myBranchForm = document.getElementById('myBranchForm');

            if (productFilter) {
                productFilter.addEventListener('change', function() {
                    myBranchForm.submit();
                });
            }

            if (stockStatusFilter) {
                stockStatusFilter.addEventListener('change', function() {
                    myBranchForm.submit();
                });
            }

            // ============================================================
            // ARCHIVE MODAL HANDLING
            // ============================================================
            const archiveModal = document.getElementById('archiveModal');
            if (archiveModal) {
                archiveModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const inventoryId = button.getAttribute('data-id');
                    const itemName = button.getAttribute('data-name');

                    document.getElementById('archiveItemName').textContent = itemName;
                    this.setAttribute('data-inventory-id', inventoryId);
                });
            }

            const archiveForm = document.getElementById('archiveForm');
            if (archiveForm) {
                const newForm = archiveForm.cloneNode(true);
                archiveForm.parentNode.replaceChild(newForm, archiveForm);
                
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const modal = document.getElementById('archiveModal');
                    const inventoryId = modal.getAttribute('data-inventory-id');
                    
                    if (!inventoryId) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Error: Inventory ID not found.', 'error');
                        }
                        return;
                    }
                    
                    this.action = '/branch-admin/inventory/' + inventoryId + '/archive';
                    
                    const formData = new FormData(this);
                    
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Archiving item...', 'info');
                    }
                    
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Item archived successfully!', 'success');
                            }
                            const modal = bootstrap.Modal.getInstance(document.getElementById('archiveModal'));
                            if (modal) modal.hide();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Failed to archive item.', 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Network error. Please try again.', 'error');
                        }
                    });
                });
            }

            // ============================================================
            // DISPOSE MODAL HANDLING
            // ============================================================
            const disposeModal = document.getElementById('disposeModal');
            if (disposeModal) {
                disposeModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const inventoryId = button.getAttribute('data-id');
                    const itemName = button.getAttribute('data-name');

                    document.getElementById('disposeItemName').textContent = itemName;
                    this.setAttribute('data-inventory-id', inventoryId);
                });
            }

            const disposeForm = document.getElementById('disposeForm');
            if (disposeForm) {
                const newForm = disposeForm.cloneNode(true);
                disposeForm.parentNode.replaceChild(newForm, disposeForm);
                
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const modal = document.getElementById('disposeModal');
                    const inventoryId = modal.getAttribute('data-inventory-id');
                    
                    if (!inventoryId) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Error: Inventory ID not found.', 'error');
                        }
                        return;
                    }
                    
                    this.action = '/branch-admin/inventory/' + inventoryId + '/dispose';
                    
                    const formData = new FormData(this);
                    
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Disposing item...', 'info');
                    }
                    
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Item disposed successfully!', 'success');
                            }
                            const modal = bootstrap.Modal.getInstance(document.getElementById('disposeModal'));
                            if (modal) modal.hide();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Failed to dispose item.', 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Network error. Please try again.', 'error');
                        }
                    });
                });
            }

            // ============================================================
            // RESTORE ARCHIVED ITEM MODAL HANDLING
            // ============================================================
            const restoreArchiveModal = document.getElementById('restoreArchiveModal');
            if (restoreArchiveModal) {
                restoreArchiveModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const inventoryId = button.getAttribute('data-id');
                    const itemName = button.getAttribute('data-name');

                    document.getElementById('restoreArchiveItemName').textContent = itemName;
                    this.setAttribute('data-inventory-id', inventoryId);
                });
            }

            const restoreArchiveForm = document.getElementById('restoreArchiveForm');
            if (restoreArchiveForm) {
                const newForm = restoreArchiveForm.cloneNode(true);
                restoreArchiveForm.parentNode.replaceChild(newForm, restoreArchiveForm);
                
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const modal = document.getElementById('restoreArchiveModal');
                    const inventoryId = modal.getAttribute('data-inventory-id');
                    
                    if (!inventoryId) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Error: Inventory ID not found.', 'error');
                        }
                        return;
                    }
                    
                    this.action = '/branch-admin/inventory/' + inventoryId + '/unarchive';
                    
                    const formData = new FormData(this);
                    
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Restoring archived item...', 'info');
                    }
                    
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Item restored successfully!', 'success');
                            }
                            const modal = bootstrap.Modal.getInstance(document.getElementById('restoreArchiveModal'));
                            if (modal) modal.hide();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Failed to restore item.', 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Network error. Please try again.', 'error');
                        }
                    });
                });
            }

            // ============================================================
            // RESTORE DISPOSED ITEM MODAL HANDLING
            // ============================================================
            const restoreDisposedModal = document.getElementById('restoreDisposedModal');
            if (restoreDisposedModal) {
                restoreDisposedModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const inventoryId = button.getAttribute('data-id');
                    const itemName = button.getAttribute('data-name');

                    document.getElementById('restoreDisposedItemName').textContent = itemName;
                    this.setAttribute('data-inventory-id', inventoryId);
                });
            }

            const restoreDisposedForm = document.getElementById('restoreDisposedForm');
            if (restoreDisposedForm) {
                const newForm = restoreDisposedForm.cloneNode(true);
                restoreDisposedForm.parentNode.replaceChild(newForm, restoreDisposedForm);
                
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const modal = document.getElementById('restoreDisposedModal');
                    const inventoryId = modal.getAttribute('data-inventory-id');
                    
                    if (!inventoryId) {
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Error: Inventory ID not found.', 'error');
                        }
                        return;
                    }
                    
                    this.action = '/branch-admin/inventory/' + inventoryId + '/restore-disposed';
                    
                    const formData = new FormData(this);
                    
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Restoring disposed item...', 'info');
                    }
                    
                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Item restored successfully!', 'success');
                            }
                            const modal = bootstrap.Modal.getInstance(document.getElementById('restoreDisposedModal'));
                            if (modal) modal.hide();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification(data.message || 'Failed to restore item.', 'error');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Network error. Please try again.', 'error');
                        }
                    });
                });
            }

            // ============================================================
            // HANDLE REQUEST TRANSFER BUTTONS (Other Branches Tab)
            // ============================================================
            const requestTransferBtns = document.querySelectorAll('.request-transfer-btn');

            requestTransferBtns.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const inventoryId = this.getAttribute('data-inventory-id');
                    const fromBranch = this.getAttribute('data-from-branch');
                    const url = '/branch-admin/inventory/transfer-modal?inventory_id=' +
                        inventoryId + '&from_branch=' + fromBranch;

                    const transferModal = document.getElementById('transferModal');
                    const modalContent = transferModal.querySelector('.modal-content');

                    modalContent.innerHTML = `
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            modalContent.innerHTML = html;
                            const bsModal = new bootstrap.Modal(transferModal);
                            bsModal.show();
                            
                            // After the modal is shown, attach the submit handler
                            transferModal.addEventListener('shown.bs.modal', function() {
                                const transferForm = document.getElementById('transferForm');
                                if (transferForm) {
                                    // Remove any existing listeners
                                    const newTransferForm = transferForm.cloneNode(true);
                                    transferForm.parentNode.replaceChild(newTransferForm, transferForm);
                                    
                                    newTransferForm.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        
                                        const formData = new FormData(this);
                                        
                                        if (typeof window.showNotification === 'function') {
                                            window.showNotification('Submitting transfer request...', 'info');
                                        }
                                        
                                        fetch(this.action, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'Accept': 'application/json',
                                            },
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                if (typeof window.showNotification === 'function') {
                                                    window.showNotification(data.message || 'Transfer request submitted successfully!', 'success');
                                                }
                                                // Close modal
                                                const modal = bootstrap.Modal.getInstance(document.getElementById('transferModal'));
                                                if (modal) modal.hide();
                                                // Reload page after 1.5 seconds
                                                setTimeout(() => {
                                                    window.location.reload();
                                                }, 1500);
                                            } else {
                                                if (typeof window.showNotification === 'function') {
                                                    window.showNotification(data.message || 'Failed to submit transfer request.', 'error');
                                                }
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            if (typeof window.showNotification === 'function') {
                                                window.showNotification('Network error. Please try again.', 'error');
                                            }
                                        });
                                    });
                                }
                            }, { once: true });
                        })
                        .catch(error => {
                            modalContent.innerHTML = `
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Error</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Failed to load content. Please try again.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            `;
                            const bsModal = new bootstrap.Modal(transferModal);
                            bsModal.show();
                        });
                });
            });

            // ============================================================
            // DYNAMIC MODAL FOR VIEW / EDIT
            // ============================================================
            const dynamicModal = document.getElementById('dynamicModal');
            if (dynamicModal) {
                dynamicModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    if (button && button.classList && button.classList.contains('request-transfer-btn')) {
                        event.preventDefault();
                        return;
                    }

                    const url = button ? button.getAttribute('data-url') : null;
                    if (!url) return;

                    const modalContent = this.querySelector('.modal-content');

                    modalContent.innerHTML = `
                        <div class="text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;

                    fetch(url)
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
                                <div class="modal-body">
                                    <p>Failed to load content. Please try again.</p>
                                    <p class="text-muted small">URL: ${url}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            `;
                        });
                });
            }
        });
    </script>
@endpush