@extends('layouts.branch-admin')

@php
    use App\Helpers\GoogleDriveHelper;
@endphp

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
                <!-- Product Image -->
                <div class="card-img-top text-center p-3 bg-light" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                    @if($product->image_url)
                        <img src="{{ GoogleDriveHelper::getThumbnailUrl($product->image_url, 300) }}" 
                             alt="{{ $product->name }}"
                             style="max-height: 180px; max-width: 100%; object-fit: contain;"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Image+Error';">
                    @elseif($product->image)
                        <img src="{{ Storage::url($product->image) }}" 
                             alt="{{ $product->name }}"
                             style="max-height: 180px; max-width: 100%; object-fit: contain;"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Image+Error';">
                    @else
                        <div class="text-center">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted small mt-2">No image</p>
                        </div>
                    @endif
                </div>
                
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
                                <div class="mb-1">
                                    <span class="badge bg-success">In Stock: {{ $inventoryItem->quantity }}</span>
                                </div>
                            @else
                                <div class="mb-1">
                                    <span class="badge bg-secondary">Not in inventory</span>
                                </div>
                                <!-- REMOVED: Add to Inventory button -->
                            @endif
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#productModal{{ $product->id }}" title="View Details">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}" title="Edit Product">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include View Modal -->
        @include('branch-admin.products.modals.show', ['product' => $product])
        
        <!-- Include Edit Modal -->
        @include('branch-admin.products.modals.edit', ['product' => $product])

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

    <!-- Simple Pagination - Previous and Next Only -->
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($products->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">
                            <i class="bi bi-chevron-left"></i> Previous
                        </a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">
                            Next <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>
@endsection