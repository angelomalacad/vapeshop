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
                                            $isArchived = $inv->is_archived ?? false;
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
                                                <small class="text-muted">Alert: {{ $inv->low_stock_threshold }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">{{ $available }}</span>
                                            </td>
                                            <td>
                                                @if ($expiry)
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
                                            <td>
                                                @if ($isArchived)
                                                    <span class="badge bg-secondary">Archived</span>
                                                @elseif($available <= 0)
                                                    <span class="badge bg-danger">Out of Stock</span>
                                                @elseif($available <= $inv->low_stock_threshold)
                                                    <span class="badge bg-warning">Low Stock</span>
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
                                                    <button type="button" class="btn btn-outline-primary"
                                                        title="Transfer Stock" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal"
                                                        data-url="{{ route('branch-admin.inventory.transfer-modal', ['inventory_id' => $inv->id]) }}">
                                                        <i class="bi bi-arrow-left-right"></i>
                                                    </button>

                                                    @if ($isArchived)
                                                        <form
                                                            action="{{ route('branch-admin.inventory.unarchive', $inv) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success"
                                                                title="Restore Item"
                                                                onclick="return confirm('Restore this item back to inventory?')">
                                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                            </button>
                                                        </form>
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
                                            <td colspan="11" class="text-center py-5">
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
                                                        <span class="fw-bold text-success">{{ $available }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($expiry)
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
                                                    <td>
                                                        @if ($available <= 0)
                                                            <span class="badge bg-danger">Out of Stock</span>
                                                        @elseif($available <= $inv->low_stock_threshold)
                                                            <span class="badge bg-warning">Low Stock</span>
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
                                                    <form
                                                        action="{{ route('branch-admin.inventory.restore-disposed', $item) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                            onclick="return confirm('Restore this item back to inventory?')">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                        </button>
                                                    </form>
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

    <!-- Archive Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="bi bi-archive me-2"></i> Archive Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="archiveForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Are you sure you want to archive this item?</p>
                        <p class="fw-bold" id="archiveItemName"></p>
                        <p class="text-muted small">Archived items can be viewed by selecting "Archived" in the stock
                            status filter.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-archive me-1"></i> Archive
                        </button>
                    </div>
                </form>
            </div>
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

    <!-- Dynamic Modal Container -->
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

            // Archive modal handling
            const archiveModal = document.getElementById('archiveModal');
            if (archiveModal) {
                archiveModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const inventoryId = button.getAttribute('data-id');
                    const itemName = button.getAttribute('data-name');

                    document.getElementById('archiveItemName').textContent = itemName;
                    const archiveForm = document.getElementById('archiveForm');
                    archiveForm.action = '/branch-admin/inventory/' + inventoryId + '/archive';
                });
            }

            // Dispose modal handling
            const disposeModal = document.getElementById('disposeModal');
            if (disposeModal) {
                disposeModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const inventoryId = button.getAttribute('data-id');
                    const itemName = button.getAttribute('data-name');

                    document.getElementById('disposeItemName').textContent = itemName;
                    const disposeForm = document.getElementById('disposeForm');
                    disposeForm.action = '/branch-admin/inventory/' + inventoryId + '/dispose';
                });
            }

            // Handle Request Transfer buttons for Other Branches
            const requestTransferBtns = document.querySelectorAll('.request-transfer-btn');

            requestTransferBtns.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const inventoryId = this.getAttribute('data-inventory-id');
                    const fromBranch = this.getAttribute('data-from-branch');
                    const url = '/branch-admin/inventory/transfer-modal?inventory_id=' +
                        inventoryId + '&from_branch=' + fromBranch;

                    const dynamicModal = document.getElementById('dynamicModal');
                    const modalContent = dynamicModal.querySelector('.modal-content');

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
                            const bsModal = new bootstrap.Modal(dynamicModal);
                            bsModal.show();
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
                            const bsModal = new bootstrap.Modal(dynamicModal);
                            bsModal.show();
                        });
                });
            });

            // Dynamic Modal for regular buttons (View, Edit, Transfer from My Branch)
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
