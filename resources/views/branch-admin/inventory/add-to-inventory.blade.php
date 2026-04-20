@extends('layouts.branch-admin')

@section('page-title', 'Add Product to Inventory')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add Product to Inventory</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="alert alert-info mb-3">
                        <strong>Product:</strong> {{ $product->name }}<br>
                        <strong>Brand:</strong> {{ $product->brand }}<br>
                        @if($product->flavors->count() > 0)
                            <strong>Flavors Available:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($product->flavors as $flavor)
                                    <li>{{ $flavor->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('branch-admin.inventory.add-to-inventory.post') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        @if($product->flavors->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Select Flavor or Item <span class="text-danger">*</span></label>
                                <select name="flavor_id" class="form-select" required>
                                    <option value="">Select Flavor or Item</option>
                                    @foreach($product->flavors as $flavor)
                                        <option value="{{ $flavor->id }}">{{ $flavor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Initial Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control" min="0" value="0" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                            <input type="number" name="low_stock_threshold" class="form-control" min="1" value="10" required>
                            <small class="text-muted">Alert when stock reaches this level</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('branch-admin.products.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Add to Inventory
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection