@extends('layouts.branch-admin')

@section('title', 'Request Stock Transfer - Vape Expo')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Request Stock Transfer</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('branch-admin.inventory.transfer.request') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Transfer to Branch <span class="text-danger">*</span></label>
                            <select name="to_branch_id" class="form-select" required>
                                <option value="">Select branch...</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="productSelect" class="form-select" required>
                                <option value="">Select product...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $selectedProduct && $selectedProduct->id == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Flavor</label>
                            <select name="flavor_id" id="flavorSelect" class="form-select">
                                <option value="">All flavors</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                            <small class="text-muted" id="availableStock">Max available: <span id="maxQuantity">{{ $maxQuantity ?? 0 }}</span></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Reason for transfer..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Submit Request
                            </button>
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
    
    // Clear current options
    flavorSelect.innerHTML = '<option value="">All flavors</option>';
    
    if (productId) {
        // Fetch flavors for this product
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