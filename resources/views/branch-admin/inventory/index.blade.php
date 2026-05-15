@extends('layouts.branch-admin')

@section('content')
    <div class="container-fluid">
        <!-- Header with Branch Info -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Branch Inventory</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-shop me-1"></i> {{ Auth::user()->branch->name }} (Current Branch)
                </p>
            </div>
            <div>
                <a href="{{ route('branch-admin.inventory.low-stock') }}" class="btn btn-warning me-2">
                    <i class="bi bi-exclamation-triangle"></i> Low Stock
                </a>
                <a href="{{ route('branch-admin.inventory.add-product') }}" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> Add Stock
                </a>
                <a href="{{ route('branch-admin.inventory.transfer.form') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left-right"></i> Transfer Stock
                </a>
            </div>
        </div>

        <!-- Branch Selector Tabs -->
        <ul class="nav nav-tabs mb-4" id="inventoryTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="my-branch-tab" data-bs-toggle="tab" data-bs-target="#my-branch"
                    type="button" role="tab">
                    <i class="bi bi-shop me-1"></i> My Branch ({{ Auth::user()->branch->name }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="other-branches-tab" data-bs-toggle="tab" data-bs-target="#other-branches"
                    type="button" role="tab">
                    <i class="bi bi-building me-1"></i> Other Branches
                </button>
            </li>
        </ul>

        <div class="tab-content" id="inventoryTabsContent">
            <!-- My Branch Tab -->
            <div class="tab-pane fade show active" id="my-branch" role="tabpanel">
                <!-- Quick Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Total Items</h6>
                                        <h3 class="mb-0">{{ $inventories->total() }}</h3>
                                    </div>
                                    <i class="bi bi-box-seam fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">In Stock</h6>
                                        <h3 class="mb-0">
                                            {{ $inventories->filter(function ($item) {
                                                    return $item->available_quantity > $item->low_stock_threshold;
                                                })->count() }}
                                        </h3>
                                    </div>
                                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Low Stock</h6>
                                        <h3 class="mb-0">
                                            {{ $inventories->filter(function ($item) {
                                                    return $item->available_quantity > 0 && $item->available_quantity <= $item->low_stock_threshold;
                                                })->count() }}
                                        </h3>
                                    </div>
                                    <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Out of Stock</h6>
                                        <h3 class="mb-0">
                                            {{ $inventories->filter(function ($item) {
                                                    return $item->available_quantity <= 0;
                                                })->count() }}
                                        </h3>
                                    </div>
                                    <i class="bi bi-x-circle fs-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search Product</label>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search by product name..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Product</label>
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
                            <div class="col-md-4">
                                <label class="form-label">Stock Status</label>
                                <select name="stock_status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low
                                        Stock</option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of
                                        Stock</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel"></i> Apply Filters
                                </button>
                                <a href="{{ route('branch-admin.inventory.index') }}"
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
                                            $statusClass =
                                                $available <= 0
                                                    ? 'danger'
                                                    : ($available <= $inv->low_stock_threshold
                                                        ? 'warning'
                                                        : 'success');
                                            $expiry = $inv->expiration_date
                                                ? \Carbon\Carbon::parse($inv->expiration_date)
                                                : null;
                                        @endphp
                                        <tr>
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
                                                            {{ number_format($product->puff_count) }} puffs<br>
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
                                                <span class="fw-bold text-{{ $statusClass }}">{{ $available }}</span>
                                                @if ($inv->reserved_quantity > 0)
                                                    <br>
                                                    <small class="text-muted">({{ $inv->reserved_quantity }}
                                                        reserved)</small>
                                                @endif
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
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- View Details - Modal Trigger -->
                                                    <button type="button" class="btn btn-outline-info"
                                                        title="View Details" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal"
                                                        data-url="{{ route('branch-admin.inventory.show-modal', $inv) }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <!-- Edit - Modal Trigger -->
                                                    <button type="button" class="btn btn-outline-warning"
                                                        title="Edit Inventory" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal"
                                                        data-url="{{ route('branch-admin.inventory.edit-modal', $inv) }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <!-- Add Stock - Modal Trigger -->
                                                    <button type="button" class="btn btn-outline-success"
                                                        title="Add Stock" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal"
                                                        data-url="{{ route('branch-admin.inventory.add-stock-modal', $inv) }}">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </button>
                                                    <!-- Transfer - Modal Trigger -->
                                                    <button type="button" class="btn btn-outline-primary"
                                                        title="Transfer Stock" data-bs-toggle="modal"
                                                        data-bs-target="#dynamicModal"
                                                        data-url="{{ route('branch-admin.inventory.transfer-modal', ['inventory_id' => $inv->id]) }}">
                                                        <i class="bi bi-arrow-left-right"></i>
                                                    </button>
                                                    <!-- Delete - Modal Trigger -->
                                                    <button type="button" class="btn btn-outline-danger"
                                                        title="Delete Item" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $inv->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Hidden delete form -->
                                                <form id="delete-form-{{ $inv->id }}"
                                                    action="{{ route('branch-admin.inventory.destroy', $inv) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- DELETE CONFIRMATION MODAL -->
                                        <div class="modal fade" id="deleteModal{{ $inv->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"><i class="bi bi-trash3 me-2"></i>Delete
                                                            Inventory Item</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete
                                                            <strong>{{ $product->name }}</strong> from your inventory?</p>
                                                        <p class="text-danger">This action cannot be undone and the product
                                                            will be completely removed from your branch inventory.</p>
                                                        @if ($inv->quantity > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="bi bi-exclamation-triangle"></i> This item still
                                                                has <strong>{{ $inv->quantity }}</strong> units in stock.
                                                                You must adjust stock to zero before deletion.
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        @if ($inv->quantity == 0)
                                                            <button type="button" class="btn btn-danger"
                                                                onclick="document.getElementById('delete-form-{{ $inv->id }}').submit();">
                                                                Yes, Delete
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-danger" disabled>Cannot
                                                                Delete (Stock > 0)</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
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
                                {{ $inventories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Branches Tab - Working Implementation -->
            <div class="tab-pane fade" id="other-branches" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Other Branches Inventory</h5>
                    </div>
                    <div class="card-body">
                        <!-- Search Form for Other Branches -->
                        <form method="GET" action="{{ route('branch-admin.inventory.index') }}" class="mb-4">
                            <input type="hidden" name="tab" value="other-branches">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Search Product</label>
                                    <input type="text" name="search_other" class="form-control"
                                        placeholder="Search by product name..." value="{{ request('search_other') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Branch</label>
                                    <select name="other_branch_id" class="form-select">
                                        <option value="">All Branches</option>
                                        @php
                                            $otherBranchesList = App\Models\Branch::where(
                                                'id',
                                                '!=',
                                                Auth::user()->branch_id,
                                            )->get();
                                        @endphp
                                        @foreach ($otherBranchesList as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ request('other_branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Stock Status</label>
                                    <select name="stock_status_other" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="low"
                                            {{ request('stock_status_other') == 'low' ? 'selected' : '' }}>Low Stock
                                        </option>
                                        <option value="out"
                                            {{ request('stock_status_other') == 'out' ? 'selected' : '' }}>Out of Stock
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        @php
                            use App\Models\Branch;
                            use App\Models\BranchInventory;

                            $selectedBranchId = request('other_branch_id');
                            $searchTerm = request('search_other');
                            $stockStatus = request('stock_status_other');

                            $otherBranchesQuery = Branch::where('id', '!=', Auth::user()->branch_id);

                            if ($selectedBranchId) {
                                $otherBranchesQuery->where('id', $selectedBranchId);
                            }

                            $otherBranches = $otherBranchesQuery->get();
                        @endphp

                        @foreach ($otherBranches as $branch)
                            @php
                                $inventoryQuery = BranchInventory::with(['product', 'flavor'])
                                    ->where('branch_id', $branch->id)
                                    ->where('quantity', '>', 0);

                                if ($searchTerm) {
                                    $inventoryQuery->whereHas('product', function ($q) use ($searchTerm) {
                                        $q->where('name', 'like', '%' . $searchTerm . '%');
                                    });
                                }

                                if ($stockStatus == 'low') {
                                    $inventoryQuery->whereColumn('quantity', '<=', 'low_stock_threshold');
                                } elseif ($stockStatus == 'out') {
                                    $inventoryQuery->where('quantity', '<=', 0);
                                }

                                $branchInventory = $inventoryQuery->paginate(5, ['*'], 'page_' . $branch->id);
                            @endphp

                            <div class="card mb-3 border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-building me-2"></i>
                                        {{ $branch->name }}
                                        <small class="text-muted ms-2">{{ $branch->address }}</small>
                                        <span class="badge bg-secondary float-end">Total: {{ $branchInventory->total() }}
                                            items</span>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    @if ($branchInventory->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Flavor</th>
                                                        <th>Available</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($branchInventory as $inv)
                                                        <tr>
                                                            <td>{{ $inv->product->name }}</td>
                                                            <td>{{ $inv->flavor->name ?? 'N/A' }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-success">{{ $inv->available_quantity }}</span>
                                                            </td>
                                                            <td>
                                                                @if ($inv->available_quantity <= $inv->low_stock_threshold)
                                                                    <span class="badge bg-warning">Low Stock</span>
                                                                @else
                                                                    <span class="badge bg-success">In Stock</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-sm btn-outline-primary"
                                                                    data-bs-toggle="modal" data-bs-target="#dynamicModal"
                                                                    data-url="{{ route('branch-admin.inventory.transfer-modal', [
                                                                        'from_branch' => $branch->id,
                                                                        'product_id' => $inv->product_id,
                                                                        'flavor_id' => $inv->flavor_id,
                                                                    ]) }}">
                                                                    <i class="bi bi-arrow-left-right"></i> Request Transfer
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        @if ($branchInventory->hasPages())
                                            <div class="d-flex justify-content-center mt-2 mb-2">
                                                <nav aria-label="Page navigation for {{ $branch->name }}">
                                                    <ul class="pagination pagination-sm mb-0">
                                                        @if ($branchInventory->onFirstPage())
                                                            <li class="page-item disabled">
                                                                <span class="page-link">Previous</span>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="{{ $branchInventory->previousPageUrl() }}&tab=other-branches&other_branch_id={{ $selectedBranchId }}&search_other={{ $searchTerm }}&stock_status_other={{ $stockStatus }}">Previous</a>
                                                            </li>
                                                        @endif

                                                        @if ($branchInventory->hasMorePages())
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="{{ $branchInventory->nextPageUrl() }}&tab=other-branches&other_branch_id={{ $selectedBranchId }}&search_other={{ $searchTerm }}&stock_status_other={{ $stockStatus }}">Next</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item disabled">
                                                                <span class="page-link">Next</span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </nav>
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center text-muted py-3">
                                            No stock available in this branch
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if ($otherBranches->isEmpty())
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-building display-1"></i>
                                <p class="mt-3">No other branches found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic Modal Container (reused for View, Edit, Add Stock, Transfer) -->
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
        // Initialize Bootstrap tabs
        document.addEventListener('DOMContentLoaded', function() {
            const myBranchTab = document.getElementById('my-branch-tab');
            const otherBranchesTab = document.getElementById('other-branches-tab');
            const myBranchPane = document.getElementById('my-branch');
            const otherBranchesPane = document.getElementById('other-branches');

            if (myBranchTab) {
                myBranchTab.addEventListener('click', function() {
                    myBranchTab.classList.add('active');
                    otherBranchesTab.classList.remove('active');
                    myBranchPane.classList.add('show', 'active');
                    otherBranchesPane.classList.remove('show', 'active');
                });
            }

            if (otherBranchesTab) {
                otherBranchesTab.addEventListener('click', function() {
                    otherBranchesTab.classList.add('active');
                    myBranchTab.classList.remove('active');
                    otherBranchesPane.classList.add('show', 'active');
                    myBranchPane.classList.remove('show', 'active');
                });
            }

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'other-branches' && otherBranchesTab) {
                otherBranchesTab.click();
            }
        });

        // Auto-submit form when filters change
        document.querySelectorAll('select[name="product_id"], select[name="stock_status"]').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Dynamic modal loader
        const dynamicModal = document.getElementById('dynamicModal');
        if (dynamicModal) {
            dynamicModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const url = button.getAttribute('data-url');
                const modalContent = this.querySelector('.modal-content');

                // Show loading
                modalContent.innerHTML = `
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;

                // Fetch modal content
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        `;
                    });
            });
        }
    </script>
@endpush
