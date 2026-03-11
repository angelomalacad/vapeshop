@extends('layouts.branch-admin')

@section('content')
<div class="container-fluid">
    <!-- Header with Branch Info -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Branch Inventory</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-shop me-1"></i> {{ Auth::user()->branch->name }}
                <span class="mx-2">|</span>
                <i class="bi bi-telephone me-1"></i> 0960 328 0432
                <span class="mx-2">|</span>
                <i class="bi bi-clock me-1"></i> 9:00 AM - 10:00 PM
            </p>
        </div>
        <div>
            <a href="{{ route('branch-admin.inventory.low-stock') }}" class="btn btn-warning me-2">
                <i class="bi bi-exclamation-triangle"></i> Low Stock
                @if(isset($lowStockCount) && $lowStockCount > 0)
                    <span class="badge bg-dark">{{ $lowStockCount }}</span>
                @endif
            </a>
            <a href="{{ route('branch-admin.inventory.add-product') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle"></i> Add Product
            </a>
            <a href="{{ route('branch-admin.inventory.transfer.form') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left-right"></i> Transfer
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
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
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">In Stock</h6>
                            <h3 class="mb-0">{{ $inventories->filter(function($item) { 
                                return $item->available_quantity > $item->low_stock_threshold; 
                            })->count() }}</h3>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Low Stock</h6>
                            <h3 class="mb-0">{{ $inventories->filter(function($item) { 
                                return $item->available_quantity > 0 && 
                                       $item->available_quantity <= $item->low_stock_threshold; 
                            })->count() }}</h3>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Out of Stock</h6>
                            <h3 class="mb-0">{{ $inventories->filter(function($item) { 
                                return $item->available_quantity <= 0; 
                            })->count() }}</h3>
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
                <div class="col-md-3">
                    <label class="form-label">Product</label>
                    <select name="product_id" class="form-select">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Brand</label>
                    <select name="brand" class="form-select">
                        <option value="">All Brands</option>
                        <option value="X-Vape" {{ request('brand') == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                        <option value="Slimbar" {{ request('brand') == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                        <option value="Relx" {{ request('brand') == 'Relx' ? 'selected' : '' }}>Relx</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <option value="Ultra" {{ request('category') == 'Ultra' ? 'selected' : '' }}>X-Vape Ultra</option>
                        <option value="Slimbar" {{ request('category') == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                        <option value="Relx" {{ request('category') == 'Relx' ? 'selected' : '' }}>Relx</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                    <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-outline-secondary ms-2">
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
                <h5 class="mb-0">Inventory List</h5>
                <span class="text-muted small">Total Value: ₱{{ number_format($inventories->sum(function($item) { 
                    return $item->quantity * $item->product->price; 
                }), 2) }}</span>
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
                                @if($inv->product->category == 'Ultra')
                                    <small>
                                        @if($inv->product->puff_count) {{ $inv->product->puff_count }} puffs<br>@endif
                                        @if($inv->product->battery_capacity) {{ $inv->product->battery_capacity }}mAh @endif
                                    </small>
                                @else
                                    <small>Standard</small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold">{{ $inv->quantity }}</span>
                                <br>
                                <small class="text-muted">Threshold: {{ $inv->low_stock_threshold }}</small>
                            </td>
                            <td>
                                @php
                                    $available = $inv->available_quantity;
                                    $statusClass = $available <= 0 ? 'danger' : ($available <= $inv->low_stock_threshold ? 'warning' : 'success');
                                @endphp
                                <span class="fw-bold text-{{ $statusClass }}">
                                    {{ $available }}
                                </span>
                                @if($inv->reserved_quantity > 0)
                                    <br>
                                    <small class="text-muted">({{ $inv->reserved_quantity }} reserved)</small>
                                @endif
                            </td>
                            <td>
                                @if($available <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($available <= $inv->low_stock_threshold)
                                    <span class="badge bg-warning">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                            <td>₱{{ number_format($inv->product->price, 2) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('branch-admin.inventory.show', $inv) }}" class="btn btn-outline-info" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('branch-admin.inventory.add-stock', $inv) }}" class="btn btn-outline-success" title="Add Stock">
                                        <i class="bi bi-plus-circle"></i>
                                    </a>
                                    <a href="{{ route('branch-admin.inventory.transfer.form', ['inventory_id' => $inv->id]) }}" class="btn btn-outline-primary" title="Transfer">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-box-seam display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No inventory items found</p>
                                <a href="{{ route('branch-admin.inventory.add-product') }}" class="btn btn-primary">
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
@endsection

@push('scripts')
<script>
    // Auto-submit form when filters change (optional)
    document.querySelectorAll('select[name="product_id"], select[name="brand"], select[name="category"], select[name="stock_status"]').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush