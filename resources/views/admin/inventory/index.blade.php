@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Inventory Management</h1>
        <div>
            <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-warning me-2">
                <i class="bi bi-exclamation-triangle"></i> Low Stock
                @if($lowStockCount > 0)
                    <span class="badge bg-dark">{{ $lowStockCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.inventory.export') }}" class="btn btn-success">
                <i class="bi bi-download"></i> Export
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6>Total Branches</h6>
                    <h3>{{ $branches->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6>Total Products</h6>
                    <h3>{{ $products->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6>Low Stock</h6>
                    <h3>{{ $lowStockCount }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6>Out of Stock</h6>
                    <h3>{{ $outOfStockCount }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
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
                    <select name="stock_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Stock</th>
                            <th>Available</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $inv)
                        <tr>
                            <td>{{ $inv->branch->name }}</td>
                            <td>{{ $inv->product->name }}</td>
                            <td>{{ $inv->flavor->name ?? 'N/A' }}</td>
                            <td>{{ $inv->quantity }}</td>
                            <td>{{ $inv->available_quantity }}</td>
                            <td>
                                @if($inv->available_quantity <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($inv->available_quantity <= $inv->low_stock_threshold)
                                    <span class="badge bg-warning">Low Stock</span>
                                @else
                                    <span class="badge bg-success">In Stock</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No inventory found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $inventories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection