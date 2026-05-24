@extends('layouts.admin')

@section('title', 'Inventory Overview - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
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
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
                <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary rounded-pill px-3">
                    <i class="bi bi-plus-circle me-1"></i> Add Inventory
                </a>
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

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            @php
                $totalItems = \App\Models\BranchInventory::count();
                $lowStock = \App\Models\BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count();
                $outOfStock = \App\Models\BranchInventory::where('quantity', '<=', 0)->count();
                $totalValue = \App\Models\BranchInventory::with('product')->get()->sum(fn($item) => $item->quantity * ($item->product->price ?? 0));
            @endphp
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Items</h6>
                                <h2 class="mb-0 fw-bold">{{ $totalItems }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-box-seam fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger bg-gradient text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Low Stock</h6>
                                <h2 class="mb-0 fw-bold">{{ $lowStock }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-exclamation-triangle fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-dark text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Out of Stock</h6>
                                <h2 class="mb-0 fw-bold">{{ $outOfStock }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Total Value</h6>
                                <h2 class="mb-0 fw-bold">₱{{ number_format($totalValue, 0) }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                <i class="bi bi-currency-dollar fs-4"></i>
                            </div>
                        </div>
                    </div>
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
                        <input type="text" name="search" class="form-control" placeholder="Search by product name..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Branch</label>
                        <select name="branch_id" class="form-select">
                            <option value="">All Branches</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Stock Status</label>
                        <select name="stock_status" class="form-select">
                            <option value="">All Status</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="archived" {{ request('stock_status') == 'archived' ? 'selected' : '' }}>Archived</option>
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
                                <th class="ps-4">Branch</th>
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
                                    $available = $inv->quantity - $inv->reserved_quantity;
                                    $isArchived = $inv->is_archived ?? false;
                                    $statusClass = $isArchived ? 'secondary' : ($available <= 0 ? 'danger' : ($available <= $inv->low_stock_threshold ? 'warning' : 'success'));
                                    $statusText = $isArchived ? 'Archived' : ($available <= 0 ? 'Out of Stock' : ($available <= $inv->low_stock_threshold ? 'Low Stock' : 'In Stock'));
                                    $expiry = $inv->expiration_date ? \Carbon\Carbon::parse($inv->expiration_date) : null;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-semibold">{{ $inv->branch->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $inv->branch->manager_name }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $inv->product->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $inv->product->brand ?? 'N/A' }}</small>
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
                                        @if($expiry)
                                            {{ $expiry->format('M d, Y') }}
                                            @if($expiry->isPast())
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
                                            <a href="{{ route('admin.inventory.show', $inv) }}" class="btn btn-outline-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.inventory.edit', $inv) }}" class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.inventory.add-stock', $inv) }}" class="btn btn-outline-success" title="Add Stock">
                                                <i class="bi bi-plus-circle"></i>
                                            </a>
                                            
                                            @if($inv->is_archived)
                                                <a href="{{ route('admin.inventory.unarchive', $inv) }}" 
                                                   class="btn btn-outline-secondary" 
                                                   title="Restore"
                                                   onclick="return confirm('Restore this item?')">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.inventory.archive', $inv) }}" 
                                                   class="btn btn-outline-secondary" 
                                                   title="Archive"
                                                   onclick="return confirm('Archive this item? It will be hidden from active inventory.')">
                                                    <i class="bi bi-archive"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="bi bi-box-seam display-1 text-muted"></i>
                                        <p class="mt-3 text-muted">No inventory items found</p>
                                        <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary rounded-pill px-4">
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
            Showing {{ $inventories->firstItem() ?? 0 }} to {{ $inventories->lastItem() ?? 0 }} of {{ $inventories->total() }} items
        </div>
        <div>
            @if ($inventories->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($inventories->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $inventories->previousPageUrl() }}" rel="prev">Previous</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($inventories->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $inventories->nextPageUrl() }}" rel="next">Next</a>
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
    
    <script>
        // Auto-submit form when dropdowns change
        document.querySelectorAll('select[name="branch_id"], select[name="product_id"], select[name="stock_status"]').forEach(function(select) {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    </script>
@endsection