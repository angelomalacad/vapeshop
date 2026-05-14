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
                <!-- Quick Stats Cards (unchanged) -->
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

                <!-- Filter Section (unchanged) -->
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
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
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
                                        <th>Expiration Date</th>   <!-- NEW COLUMN -->
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
                                            $statusClass = $available <= 0 ? 'danger' : ($available <= $inv->low_stock_threshold ? 'warning' : 'success');
                                            $expiry = $inv->expiration_date ? \Carbon\Carbon::parse($inv->expiration_date) : null;
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
                                                    <small class="text-muted">({{ $inv->reserved_quantity }} reserved)</small>
                                                @endif
                                            </td>
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
                                                    <!-- Delete button that opens modal -->
                                                    <button type="button" class="btn btn-outline-danger"
                                                        title="Delete Item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $inv->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Hidden delete form (still used by modal) -->
                                                <form id="delete-form-{{ $inv->id }}"
                                                    action="{{ route('branch-admin.inventory.destroy', $inv) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- DELETE CONFIRMATION MODAL (only modal added) -->
                                        <div class="modal fade" id="deleteModal{{ $inv->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"><i class="bi bi-trash3 me-2"></i>Delete Inventory Item</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete <strong>{{ $product->name }}</strong> from your inventory?</p>
                                                        <p class="text-danger">This action cannot be undone and the product will be completely removed from your branch inventory.</p>
                                                        @if($inv->quantity > 0)
                                                            <div class="alert alert-warning">
                                                                <i class="bi bi-exclamation-triangle"></i> This item still has <strong>{{ $inv->quantity }}</strong> units in stock.
                                                                You must adjust stock to zero before deletion.
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        @if($inv->quantity == 0)
                                                            <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-form-{{ $inv->id }}').submit();">
                                                                Yes, Delete
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-danger" disabled>Cannot Delete (Stock > 0)</button>
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
                                Showing {{ $inventories->firstItem() ?? 0 }} to {{ $inventories->lastItem() ?? 0 }} of {{ $inventories->total() }} items
                            </div>
                            <div>
                                {{ $inventories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Branches Tab (unchanged) -->
            <div class="tab-pane fade" id="other-branches" role="tabpanel">
                <!-- ... your existing "Other Branches" code ... -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize Bootstrap tabs (unchanged)
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

        // Auto-submit form when filters change (unchanged)
        document.querySelectorAll('select[name="product_id"], select[name="stock_status"]').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
@endpush