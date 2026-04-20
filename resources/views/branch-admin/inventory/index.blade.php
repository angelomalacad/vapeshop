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
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventories as $inv)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">{{ $inv->product->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ $inv->product->category }}</small>
                                            </td>
                                            <td>{{ $inv->product->brand ?? 'N/A' }}</td>
                                            <td>{{ $inv->flavor->name ?? 'N/A' }}</td>
                                            <td>
                                                @if ($inv->product->category == 'Ultra')
                                                    <small>
                                                        @if ($inv->product->puff_count)
                                                            {{ number_format($inv->product->puff_count) }} puffs<br>
                                                        @endif
                                                        @if ($inv->product->battery_capacity)
                                                            {{ $inv->product->battery_capacity }}mAh
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
                                                @php
                                                    $available = $inv->available_quantity;
                                                    $statusClass =
                                                        $available <= 0
                                                            ? 'danger'
                                                            : ($available <= $inv->low_stock_threshold
                                                                ? 'warning'
                                                                : 'success');
                                                @endphp
                                                <span class="fw-bold text-{{ $statusClass }}">
                                                    {{ $available }}
                                                </span>
                                                @if ($inv->reserved_quantity > 0)
                                                    <br>
                                                    <small class="text-muted">({{ $inv->reserved_quantity }}
                                                        reserved)</small>
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
                                            <td>₱{{ number_format($inv->product->price, 2) }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('branch-admin.inventory.show', $inv) }}"
                                                        class="btn btn-outline-info" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('branch-admin.inventory.edit', $inv) }}"
                                                        class="btn btn-outline-warning" title="Edit Inventory">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('branch-admin.inventory.add-stock', $inv) }}"
                                                        class="btn btn-outline-success" title="Add Stock">
                                                        <i class="bi bi-plus-circle"></i>
                                                    </a>
                                                    <a href="{{ route('branch-admin.inventory.transfer.form', ['inventory_id' => $inv->id]) }}"
                                                        class="btn btn-outline-primary" title="Transfer Stock">
                                                        <i class="bi bi-arrow-left-right"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        title="Delete Item"
                                                        onclick="confirmDelete({{ $inv->id }}, '{{ $inv->product->name }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                                <form id="delete-form-{{ $inv->id }}"
                                                    action="{{ route('branch-admin.inventory.destroy', $inv) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
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

            <!-- Other Branches Tab -->
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
                                            $otherBranchesList = App\Models\Branch::where('id', '!=', Auth::user()->branch_id)->get();
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
                                        <option value="low" {{ request('stock_status_other') == 'low' ? 'selected' : '' }}>Low Stock</option>
                                        <option value="out" {{ request('stock_status_other') == 'out' ? 'selected' : '' }}>Out of Stock</option>
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
                                    $inventoryQuery->whereHas('product', function($q) use ($searchTerm) {
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
                                        <span class="badge bg-secondary float-end">Total: {{ $branchInventory->total() }} items</span>
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
                                                                <span class="badge bg-success">{{ $inv->available_quantity }}</span>
                                                            </td>
                                                            <td>
                                                                @if ($inv->available_quantity <= $inv->low_stock_threshold)
                                                                    <span class="badge bg-warning">Low Stock</span>
                                                                @else
                                                                    <span class="badge bg-success">In Stock</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('branch-admin.inventory.transfer.form', [
                                                                    'from_branch' => $branch->id,
                                                                    'product_id' => $inv->product_id,
                                                                    'flavor_id' => $inv->flavor_id,
                                                                ]) }}"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i class="bi bi-arrow-left-right"></i> Request Transfer
                                                                </a>
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
                                                                <a class="page-link" href="{{ $branchInventory->previousPageUrl() }}&tab=other-branches&other_branch_id={{ $selectedBranchId }}&search_other={{ $searchTerm }}&stock_status_other={{ $stockStatus }}">Previous</a>
                                                            </li>
                                                        @endif
                                                        
                                                        @if ($branchInventory->hasMorePages())
                                                            <li class="page-item">
                                                                <a class="page-link" href="{{ $branchInventory->nextPageUrl() }}&tab=other-branches&other_branch_id={{ $selectedBranchId }}&search_other={{ $searchTerm }}&stock_status_other={{ $stockStatus }}">Next</a>
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
                        
                        @if($otherBranches->isEmpty())
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
@endsection

@push('scripts')
    <script>
        // Initialize Bootstrap tabs
        document.addEventListener('DOMContentLoaded', function() {
            // Get all tab buttons
            const myBranchTab = document.getElementById('my-branch-tab');
            const otherBranchesTab = document.getElementById('other-branches-tab');
            const myBranchPane = document.getElementById('my-branch');
            const otherBranchesPane = document.getElementById('other-branches');
            
            // My Branch tab click handler
            if (myBranchTab) {
                myBranchTab.addEventListener('click', function() {
                    // Remove active class from both tabs
                    myBranchTab.classList.remove('active');
                    otherBranchesTab.classList.remove('active');
                    myBranchPane.classList.remove('show', 'active');
                    otherBranchesPane.classList.remove('show', 'active');
                    
                    // Add active class to this tab
                    myBranchTab.classList.add('active');
                    myBranchPane.classList.add('show', 'active');
                });
            }
            
            // Other Branches tab click handler
            if (otherBranchesTab) {
                otherBranchesTab.addEventListener('click', function() {
                    // Remove active class from both tabs
                    myBranchTab.classList.remove('active');
                    otherBranchesTab.classList.remove('active');
                    myBranchPane.classList.remove('show', 'active');
                    otherBranchesPane.classList.remove('show', 'active');
                    
                    // Add active class to this tab
                    otherBranchesTab.classList.add('active');
                    otherBranchesPane.classList.add('show', 'active');
                });
            }
            
            // Check URL parameter to see which tab should be active
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab');
            
            if (activeTab === 'other-branches') {
                // Trigger other branches tab click
                if (otherBranchesTab) {
                    otherBranchesTab.click();
                }
            }
        });

        // Auto-submit form when filters change
        document.querySelectorAll('select[name="product_id"], select[name="stock_status"]').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Delete confirmation function
        function confirmDelete(inventoryId, productName) {
            if (confirm(`Are you sure you want to delete ${productName} from your inventory? This action cannot be undone.`)) {
                document.getElementById(`delete-form-${inventoryId}`).submit();
            }
        }
    </script>
@endpush