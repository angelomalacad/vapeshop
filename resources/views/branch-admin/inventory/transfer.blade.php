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

                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle me-2"></i>
                                You are requesting stock FROM another branch or warehouse TO
                                <strong>{{ $currentBranch->name ?? Auth::user()->branch->name }}</strong> (your branch)
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">From Branch (Source) <span
                                            class="text-danger">*</span></label>
                                    <select name="from_branch_id" id="fromBranchSelect" class="form-select" required>
                                        <option value="">Select source...</option>
                                        <option value="0" {{ old('from_branch_id') == '0' ? 'selected' : '' }}>Main
                                            Warehouse</option>
                                        @foreach ($sourceBranches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select Main Warehouse or another branch that has the stock you
                                        need</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">To Branch (Destination) <span
                                            class="text-danger">*</span></label>
                                    <select name="to_branch_id" class="form-select" required>
                                        <option value="{{ Auth::user()->branch_id }}" selected>
                                            {{ Auth::user()->branch->name }} (Your Branch)
                                        </option>
                                    </select>
                                    <small class="text-muted">Stock will be transferred to your branch</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="productSelect" class="form-select" required>
                                    <option value="">Select product...</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Variant <span class="text-danger"
                                        id="flavorRequired">*</span></label>
                                <select name="flavor_id" id="flavorSelect" class="form-select">
                                    <option value="">-- Select variant --</option>
                                </select>
                                <small class="text-muted" id="flavorHelp">Select a specific variant for this product</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" class="form-control" min="1"
                                    required>
                                <small class="text-muted" id="availableStock">Select source and product to check
                                    availability</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Reason for transfer..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fromBranchSelect = document.getElementById('fromBranchSelect');
            const productSelect = document.getElementById('productSelect');
            const flavorSelect = document.getElementById('flavorSelect');
            const quantityInput = document.getElementById('quantity');
            const availableStockSpan = document.getElementById('availableStock');

            // When source changes, load available products
            fromBranchSelect.addEventListener('change', function() {
                const branchId = this.value;
                if (!branchId) {
                    productSelect.innerHTML = '<option value="">Select product...</option>';
                    flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                    availableStockSpan.innerHTML = 'Select source and product to check availability';
                    quantityInput.max = '';
                    return;
                }

                productSelect.innerHTML = '<option value="">Loading products...</option>';
                flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                availableStockSpan.innerHTML = 'Loading products...';

                fetch(`/branch-admin/api/source-products?branch_id=${branchId}`)
                    .then(response => response.json())
                    .then(products => {
                        productSelect.innerHTML = '<option value="">Select product...</option>';
                        if (products.length === 0) {
                            productSelect.innerHTML = '<option value="">No products available</option>';
                            availableStockSpan.innerHTML =
                                '<span class="text-warning">No products with stock in this source.</span>';
                        } else {
                            products.forEach(product => {
                                const option = document.createElement('option');
                                option.value = product.id;
                                option.textContent = product.name;
                                productSelect.appendChild(option);
                            });
                            availableStockSpan.innerHTML = 'Select a product to check availability';
                        }
                        quantityInput.max = '';
                    })
                    .catch(error => {
                        console.error('Error loading products:', error);
                        productSelect.innerHTML = '<option value="">Error loading products</option>';
                        availableStockSpan.innerHTML =
                            '<span class="text-danger">Error loading products. Check console.</span>';
                    });
            });

            // When product changes, load flavors with stock
            productSelect.addEventListener('change', function() {
                const productId = this.value;
                const fromBranch = fromBranchSelect.value;

                flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                availableStockSpan.innerHTML = 'Loading variants...';
                quantityInput.max = '';

                if (productId && fromBranch) {
                    fetch(`/branch-admin/api/products/${productId}/flavors?branch_id=${fromBranch}`)
                        .then(response => response.json())
                        .then(flavors => {
                            if (flavors.length > 0) {
                                flavorSelect.innerHTML = '';
                                flavors.forEach(flavor => {
                                    const option = document.createElement('option');
                                    option.value = flavor.id;
                                    option.textContent = flavor.name;
                                    flavorSelect.appendChild(option);
                                });
                                flavorSelect.value = flavors[0].id;
                                flavorSelect.disabled = false;
                                // Check availability for the first flavor
                                checkAvailability();
                            } else {
                                // No flavors with stock - show message and disable
                                flavorSelect.innerHTML =
                                    '<option value="">No available variants</option>';
                                flavorSelect.disabled = true;
                                availableStockSpan.innerHTML =
                                    '<span class="text-warning">This product has no available variants with stock.</span>';
                                quantityInput.max = 0;
                            }
                        })
                        .catch(error => {
                            console.error('Error loading flavors:', error);
                            availableStockSpan.innerHTML =
                                '<span class="text-danger">Error loading variants.</span>';
                        });
                }
            });

            // Flavor change triggers availability check
            flavorSelect.addEventListener('change', function() {
                const fromBranch = fromBranchSelect.value;
                const productId = productSelect.value;
                if (fromBranch && productId && !flavorSelect.disabled) {
                    checkAvailability();
                }
            });

            function checkAvailability() {
                const fromBranch = fromBranchSelect.value;
                const productId = productSelect.value;

                // If flavor dropdown is disabled, don't check
                if (flavorSelect.disabled) {
                    availableStockSpan.innerHTML =
                        '<span class="text-warning">No variants available for this product.</span>';
                    quantityInput.max = 0;
                    return;
                }

                const flavorId = flavorSelect.value;
                if (!flavorId) {
                    availableStockSpan.innerHTML = 'Please select a variant';
                    quantityInput.max = '';
                    return;
                }

                if (!fromBranch || !productId) {
                    availableStockSpan.innerHTML = 'Select source and product to check availability';
                    quantityInput.max = '';
                    return;
                }

                availableStockSpan.innerHTML = '<span class="text-info">Checking availability...</span>';

                let url;
                if (fromBranch === '0') {
                    url = `/branch-admin/api/warehouse/check?product_id=${productId}&flavor_id=${flavorId}`;
                } else {
                    url =
                        `/branch-admin/api/inventory/check?branch_id=${fromBranch}&product_id=${productId}&flavor_id=${flavorId}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const available = data.available || 0;
                            if (available > 0) {
                                availableStockSpan.innerHTML =
                                    `Max available: <strong class="text-primary">${available}</strong> units`;
                                quantityInput.max = available;
                                quantityInput.min = 1;
                                if (parseInt(quantityInput.value) > available) {
                                    quantityInput.value = available;
                                }
                            } else {
                                availableStockSpan.innerHTML =
                                    `<span class="text-danger">Out of stock (0 units available)</span>`;
                                quantityInput.max = 0;
                            }
                        } else {
                            availableStockSpan.innerHTML =
                                `<span class="text-danger">${data.message || 'Error checking availability'}</span>`;
                            quantityInput.max = 0;
                        }
                    })
                    .catch(error => {
                        console.error('Error checking availability:', error);
                        availableStockSpan.innerHTML =
                            '<span class="text-danger">Error checking availability.</span>';
                        quantityInput.max = 0;
                    });
            }

            // Initialize if pre-selected
            const initialBranch = fromBranchSelect.value;
            if (initialBranch) {
                fromBranchSelect.dispatchEvent(new Event('change'));
                const initialProduct = productSelect.value;
                if (initialProduct) {
                    setTimeout(() => {
                        productSelect.dispatchEvent(new Event('change'));
                    }, 500);
                }
            }
        });
    </script>
@endpush
