@extends('layouts.branch-admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Edit Product: {{ $product->name }}</h1>
        <a href="{{ route('branch-admin.products.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('branch-admin.products.update', $product) }}">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand" class="form-select">
                            <option value="X-Vape" {{ $product->brand == 'X-Vape' ? 'selected' : '' }}>X-Vape</option>
                            <option value="Slimbar" {{ $product->brand == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                            <option value="Relx" {{ $product->brand == 'Relx' ? 'selected' : '' }}>Relx</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="Ultra" {{ $product->category == 'Ultra' ? 'selected' : '' }}>Ultra</option>
                            <option value="Slimbar" {{ $product->category == 'Slimbar' ? 'selected' : '' }}>Slimbar</option>
                            <option value="Relx" {{ $product->category == 'Relx' ? 'selected' : '' }}>Relx</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="pod-system" {{ $product->type == 'pod-system' ? 'selected' : '' }}>Pod System</option>
                            <option value="disposable" {{ $product->type == 'disposable' ? 'selected' : '' }}>Disposable</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price (₱)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Puff Count</label>
                        <input type="number" name="puff_count" class="form-control" value="{{ $product->puff_count }}">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Battery (mAh)</label>
                        <input type="number" name="battery_capacity" class="form-control" value="{{ $product->battery_capacity }}">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nicotine Strength</label>
                        <input type="text" name="nicotine_strength" class="form-control" value="{{ $product->nicotine_strength }}">
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('branch-admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection