@extends('layouts.branch-admin')

@section('title', 'Request Stock Transfer - Vape Expo')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Request Stock Transfer</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-arrow-left-right me-1"></i> Transfer stock to your branch
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-arrow-left-right me-2"></i>Request Stock Transfer
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('branch-admin.inventory.transfer.request') }}" id="transferForm">
                    @csrf

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        You are requesting stock FROM another branch or warehouse TO
                        <strong>{{ $currentBranch->name ?? Auth::user()->branch->name }}</strong> (your branch)
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">From Branch (Source) <span class="text-danger">*</span></label>
                            <select name="from_branch_id" id="fromBranchSelect" class="form-select" required>
                                <option value="">Select source...</option>
                                <option value="0" {{ old('from_branch_id') == '0' ? 'selected' : '' }}>Main Warehouse</option>
                                @foreach ($sourceBranches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Select Main Warehouse or another branch that has the stock you need</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">To Branch (Destination) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   value="{{ Auth::user()->branch->name }} (Your Branch)" 
                                   disabled
                                   style="background-color: #e9ecef !important; cursor: not-allowed; opacity: 1;">
                            <input type="hidden" name="to_branch_id" value="{{ Auth::user()->branch_id }}">
                            <small class="text-muted">Stock will be transferred to your branch</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select name="product_id" id="productSelect" class="form-select" required>
                                <option value="">Select product...</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3" id="flavorContainer">
                            <label class="form-label">Variant <span class="text-danger" id="flavorRequired">*</span></label>
                            <select name="flavor_id" id="flavorSelect" class="form-select">
                                <option value="">-- Select variant --</option>
                            </select>
                            <small class="text-muted" id="flavorHelp">Select a specific variant for this product</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                            <small class="text-muted" id="availableStock">Select source and product to check availability</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="1" placeholder="Reason for transfer..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-send"></i> Submit Request
                        </button>
                    </div>
                </form>
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
    const flavorContainer = document.getElementById('flavorContainer');

    // When source changes, load available products
    fromBranchSelect.addEventListener('change', function() {
        const branchId = this.value;
        
        if (!branchId) {
            productSelect.innerHTML = '<option value="">Select product...</option>';
            flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
            flavorContainer.style.display = 'block';
            availableStockSpan.innerHTML = 'Select source and product to check availability';
            quantityInput.max = '';
            quantityInput.value = '';
            return;
        }

        productSelect.innerHTML = '<option value="">Loading products...</option>';
        flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
        flavorContainer.style.display = 'block';
        availableStockSpan.innerHTML = 'Loading products...';

        const url = `/branch-admin/api/source-products?branch_id=${branchId}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(products => {
                productSelect.innerHTML = '<option value="">Select product...</option>';
                if (products.length === 0) {
                    productSelect.innerHTML = '<option value="">No products available</option>';
                    availableStockSpan.innerHTML = '<span class="text-warning">No products with stock in this source.</span>';
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
                quantityInput.value = '';
            })
            .catch(error => {
                console.error('Error loading products:', error);
                productSelect.innerHTML = '<option value="">Error loading products</option>';
                availableStockSpan.innerHTML = '<span class="text-danger">Error loading products. Please refresh.</span>';
            });
    });

    // When product changes, load flavors with stock
    productSelect.addEventListener('change', function() {
        const productId = this.value;
        const fromBranch = fromBranchSelect.value;

        flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
        availableStockSpan.innerHTML = 'Loading variants...';
        quantityInput.max = '';
        quantityInput.value = '';
        flavorContainer.style.display = 'block';

        if (productId && fromBranch) {
            const url = `/branch-admin/api/products/${productId}/flavors?branch_id=${fromBranch}`;
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(flavors => {
                    if (flavors.length === 0) {
                        flavorContainer.style.display = 'none';
                        flavorSelect.innerHTML = '';
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No variants';
                        flavorSelect.appendChild(option);
                        flavorSelect.value = '';
                        availableStockSpan.innerHTML = 'Checking availability...';
                        checkAvailability(productId, fromBranch, '');
                        return;
                    }
                    
                    flavorSelect.innerHTML = '';
                    flavors.forEach(flavor => {
                        const option = document.createElement('option');
                        option.value = flavor.id;
                        option.textContent = flavor.name;
                        flavorSelect.appendChild(option);
                    });
                    
                    flavorContainer.style.display = 'block';
                    flavorSelect.disabled = false;
                    
                    if (flavors.length > 0) {
                        flavorSelect.value = flavors[0].id;
                        checkAvailability(productId, fromBranch, flavors[0].id);
                    }
                })
                .catch(error => {
                    console.error('Error loading flavors:', error);
                    flavorSelect.innerHTML = '<option value="">Error loading variants</option>';
                    flavorSelect.disabled = true;
                    availableStockSpan.innerHTML = '<span class="text-danger">Error loading variants. Please refresh.</span>';
                });
        } else {
            if (!productId) {
                flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                availableStockSpan.innerHTML = 'Select a product to check availability';
            } else if (!fromBranch) {
                availableStockSpan.innerHTML = 'Select a source to check availability';
            }
        }
    });

    flavorSelect.addEventListener('change', function() {
        const fromBranch = fromBranchSelect.value;
        const productId = productSelect.value;
        const flavorId = this.value;
        
        if (fromBranch && productId) {
            checkAvailability(productId, fromBranch, flavorId);
        }
    });

    function checkAvailability(productId, fromBranch, flavorId) {
        if (!productId) {
            availableStockSpan.innerHTML = 'Select a product to check availability';
            quantityInput.max = '';
            quantityInput.value = '';
            return;
        }

        if (!fromBranch) {
            availableStockSpan.innerHTML = 'Select a source to check availability';
            quantityInput.max = '';
            quantityInput.value = '';
            return;
        }

        availableStockSpan.innerHTML = '<span class="text-info">Checking availability...</span>';

        let url;
        if (fromBranch === '0') {
            url = `/branch-admin/api/warehouse/check?product_id=${productId}&flavor_id=${flavorId || ''}`;
        } else {
            url = `/branch-admin/api/inventory/check?branch_id=${fromBranch}&product_id=${productId}&flavor_id=${flavorId || ''}`;
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const available = data.available || 0;
                    
                    if (available > 0) {
                        availableStockSpan.innerHTML =
                            `Max available: <strong class="text-primary">${available}</strong> units`;
                        quantityInput.max = available;
                        quantityInput.min = 1;
                        quantityInput.disabled = false;
                        if (parseInt(quantityInput.value) > available) {
                            quantityInput.value = available;
                        }
                    } else {
                        availableStockSpan.innerHTML = `<span class="text-danger">Out of stock (0 units available)</span>`;
                        quantityInput.max = 0;
                        quantityInput.value = '';
                        quantityInput.disabled = true;
                    }
                } else {
                    availableStockSpan.innerHTML = `<span class="text-danger">${data.message || 'Error checking availability'}</span>`;
                    quantityInput.max = 0;
                    quantityInput.value = '';
                    quantityInput.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error checking availability:', error);
                availableStockSpan.innerHTML = '<span class="text-danger">Error checking availability. Please refresh.</span>';
                quantityInput.max = 0;
                quantityInput.value = '';
                quantityInput.disabled = true;
            });
    }

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

    // ============================================================
    // ADDED: AJAX FORM SUBMISSION WITH GLOBAL NOTIFICATION
    // ============================================================

    const transferForm = document.getElementById('transferForm');
    if (transferForm) {
        transferForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get the submit button
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Disable button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting...';

            // Collect form data
            const formData = new FormData(this);

            // Show processing notification
            if (typeof window.showNotification === 'function') {
                window.showNotification('Submitting transfer request...', 'info');
            }

            // Send AJAX request
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                if (data.success) {
                    // Show success notification
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.message || 'Transfer request submitted successfully!', 'success');
                    }

                    // Redirect to transfers page after 1.5 seconds
                    setTimeout(() => {
                        window.location.href = "{{ route('branch-admin.inventory.transfers') }}";
                    }, 1500);
                } else {
                    // Show error notification
                    if (typeof window.showNotification === 'function') {
                        window.showNotification(data.message || 'Failed to submit transfer request.', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Network error. Please try again.', 'error');
                }
            });
        });
    }
});
</script>
@endpush