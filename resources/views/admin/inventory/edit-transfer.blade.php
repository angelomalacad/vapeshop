@extends('layouts.admin')

@section('title', 'Edit Transfer - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Edit Transfer</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-pencil me-1"></i> {{ $transfer->transfer_number }}
                </p>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    </div>

    <!-- Warning for non-pending transfers -->
    @if($transfer->status != 'pending')
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        This transfer is {{ $transfer->status }} and cannot be edited.
    </div>
    @endif

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.transfers.update', $transfer) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">From Branch *</label>
                        <select name="from_branch_id" class="form-select @error('from_branch_id') is-invalid @enderror" id="from_branch_id" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                            <option value="">Select Source Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('from_branch_id', $transfer->from_branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('from_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">To Branch *</label>
                        <select name="to_branch_id" class="form-select @error('to_branch_id') is-invalid @enderror" id="to_branch_id" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                            <option value="">Select Destination Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('to_branch_id', $transfer->to_branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('to_branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Product *</label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" id="product_id" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-has-flavors="{{ $product->flavors->count() > 0 ? 'true' : 'false' }}" {{ old('product_id', $transfer->product_id) == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->brand }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3" id="flavorRow" style="display: none;">
                        <label class="form-label fw-semibold">Flavor</label>
                        <select name="flavor_id" class="form-select @error('flavor_id') is-invalid @enderror" id="flavor_id" {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                            <option value="">No Flavor</option>
                        </select>
                        @error('flavor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Quantity *</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $transfer->quantity) }}" min="1" id="quantity" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted" id="availableStock"></small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Notes (Optional)</label>
                        <textarea name="notes" class="form-control" rows="2" {{ $transfer->status != 'pending' ? 'disabled' : '' }}>{{ old('notes', $transfer->notes) }}</textarea>
                    </div>
                </div>

                <div class="alert alert-info" id="stockCheck" style="display: none;">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <span id="stockMessage"></span>
                </div>

                <hr class="my-4">
                
                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.inventory.transfers.show', $transfer) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                        @if($transfer->status == 'pending')
                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            <i class="bi bi-check-circle me-1"></i> Update Transfer
                        </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Products data
    const products = @json($products);
    const currentFlavorId = {{ $transfer->flavor_id ?? 'null' }};

    // Check available stock when from branch and product are selected
    function checkAvailableStock() {
        const fromBranchId = document.getElementById('from_branch_id').value;
        const productId = document.getElementById('product_id').value;
        const flavorId = document.getElementById('flavor_id').value;
        const quantity = document.getElementById('quantity').value;
        const stockCheck = document.getElementById('stockCheck');
        const stockMessage = document.getElementById('stockMessage');
        const availableStock = document.getElementById('availableStock');

        if (!fromBranchId || !productId) {
            stockCheck.style.display = 'none';
            availableStock.innerHTML = '';
            return;
        }

        // Make AJAX call to check available stock
        fetch(`/api/branches/${fromBranchId}/products/${productId}/stock${flavorId ? '?flavor_id=' + flavorId : ''}`)
            .then(response => response.json())
            .then(data => {
                if (data.available !== undefined) {
                    availableStock.innerHTML = `Available: ${data.available} units`;
                    
                    if (quantity > data.available) {
                        stockCheck.style.display = 'block';
                        stockMessage.innerHTML = `Warning: Requested quantity (${quantity}) exceeds available stock (${data.available})`;
                        stockCheck.classList.remove('alert-info');
                        stockCheck.classList.add('alert-danger');
                    } else {
                        stockCheck.style.display = 'block';
                        stockMessage.innerHTML = `Available stock: ${data.available} units. You can transfer up to ${data.available} units.`;
                        stockCheck.classList.remove('alert-danger');
                        stockCheck.classList.add('alert-info');
                    }
                } else {
                    availableStock.innerHTML = 'No stock available';
                    stockCheck.style.display = 'block';
                    stockMessage.innerHTML = 'No stock available in source branch';
                    stockCheck.classList.remove('alert-info');
                    stockCheck.classList.add('alert-warning');
                }
            });
    }

    // Product change handler
    document.getElementById('product_id').addEventListener('change', function() {
        const productId = this.value;
        const flavorRow = document.getElementById('flavorRow');
        const flavorSelect = document.getElementById('flavor_id');
        
        if (!productId) {
            flavorRow.style.display = 'none';
            checkAvailableStock();
            return;
        }
        
        const selectedProduct = products.find(p => p.id == productId);
        
        if (selectedProduct && selectedProduct.flavors && selectedProduct.flavors.length > 0) {
            // Clear and populate flavors
            flavorSelect.innerHTML = '<option value="">Select Flavor</option>';
            selectedProduct.flavors.forEach(flavor => {
                const selected = flavor.id == currentFlavorId ? 'selected' : '';
                flavorSelect.innerHTML += `<option value="${flavor.id}" ${selected}>${flavor.name}</option>`;
            });
            flavorRow.style.display = 'block';
        } else {
            flavorSelect.innerHTML = '<option value="">No Flavor</option>';
            flavorRow.style.display = 'block';
        }
        
        checkAvailableStock();
    });

    // From branch change handler
    document.getElementById('from_branch_id').addEventListener('change', checkAvailableStock);
    document.getElementById('flavor_id').addEventListener('change', checkAvailableStock);
    document.getElementById('quantity').addEventListener('input', checkAvailableStock);

    // Trigger change on page load
    document.getElementById('product_id').dispatchEvent(new Event('change'));
</script>
@endpush