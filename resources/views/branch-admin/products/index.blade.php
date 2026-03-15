@extends('layouts.branch-admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Product Catalog</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-tags me-1"></i> Products available in {{ Auth::user()->branch->name }}
            </p>
        </div>
        <div>
            <a href="{{ route('branch-admin.inventory.add-product') }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Add to Inventory
            </a>
            <a href="{{ route('branch-admin.products.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Create New Product
            </a>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0">{{ $product->name }}</h5>
                        @if($product->brand)
                            <span class="badge bg-primary">{{ $product->brand }}</span>
                        @endif
                    </div>
                    
                    <p class="text-muted small mb-2">
                        <i class="bi bi-grid"></i> {{ $product->category }}
                    </p>
                    
                    <p class="card-text small">{{ Str::limit($product->description, 100) }}</p>
                    
                    <div class="mb-2">
                        <strong>Price:</strong> ₱{{ number_format($product->price, 2) }}
                    </div>
                    
                    @if($product->puff_count || $product->battery_capacity)
                    <div class="small text-muted mb-3">
                        @if($product->puff_count)
                            <span class="me-2"><i class="bi bi-lightning"></i> {{ number_format($product->puff_count) }} puffs</span>
                        @endif
                        @if($product->battery_capacity)
                            <span><i class="bi bi-battery"></i> {{ $product->battery_capacity }}mAh</span>
                        @endif
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            @php
                                $inventoryItem = $product->branchInventories
                                    ->where('branch_id', Auth::user()->branch_id)
                                    ->first();
                            @endphp
                            @if($inventoryItem)
                                <span class="badge bg-success">In Stock: {{ $inventoryItem->quantity }}</span>
                            @else
                                <span class="badge bg-secondary">Not in inventory</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('branch-admin.products.show', $product) }}" class="btn btn-sm btn-outline-info" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('branch-admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning" title="Edit Product">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <h4 class="mt-3">No Products Found</h4>
                    <p class="text-muted">There are no products available in your branch inventory.</p>
                    <a href="{{ route('branch-admin.inventory.add-product') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Products to Inventory
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection