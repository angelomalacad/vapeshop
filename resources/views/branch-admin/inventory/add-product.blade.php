@extends('layouts.branch-admin')

@section('page-title', 'Add Product to Inventory')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add Product to Branch Inventory</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('branch-admin.inventory.add-product.post') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Product</label>
                            <select name="product_id" id="productSelect" class="form-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Flavor (Optional)</label>
                            <select name="flavor_id" id="flavorSelect" class="form-select">
                                <option value="">No Flavor</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Initial Quantity</label>
                            <input type="number" name="quantity" class="form-control" min="0" value="0" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" class="form-control" min="1" value="5" required>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add to Inventory</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('productSelect').addEventListener('change', function() {
    const productId = this.value;
    const flavorSelect = document.getElementById('flavorSelect');
    
    flavorSelect.innerHTML = '<option value="">No Flavor</option>';
    
    if (productId) {
        fetch(`/api/products/${productId}/flavors`)
            .then(response => response.json())
            .then(flavors => {
                flavors.forEach(flavor => {
                    const option = document.createElement('option');
                    option.value = flavor.id;
                    option.textContent = flavor.name;
                    flavorSelect.appendChild(option);
                });
            });
    }
});
</script>
@endpush
@endsection