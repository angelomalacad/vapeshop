<!-- Modal content only - no layout -->
<div class="modal-content">
    <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
            <i class="bi bi-arrow-left-right me-2"></i>Request Stock Transfer
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <form method="POST" action="{{ route('branch-admin.inventory.transfer.request') }}" id="transferForm">
            @csrf

            <div class="alert alert-info mb-4">
                <i class="bi bi-info-circle me-2"></i>
                You are requesting stock FROM 
                <strong>
                    @if(isset($preSelectedFromBranch) && $preSelectedFromBranch == '0')
                        Main Warehouse
                    @elseif(isset($preSelectedFromBranch) && $preSelectedFromBranch != '0')
                        @php
                            $sourceBranch = \App\Models\Branch::find($preSelectedFromBranch);
                            echo $sourceBranch ? $sourceBranch->name : 'Another Branch';
                        @endphp
                    @elseif(isset($selectedProduct) && $selectedProduct && isset($selectedProduct->branch))
                        {{ $selectedProduct->branch->name ?? 'Another Branch' }}
                    @else
                        Another branch
                    @endif
                </strong>
                TO <strong>{{ $currentBranch->name ?? Auth::user()->branch->name }}</strong> (your branch)
            </div>

            <!-- Hidden fields for pre-selected data -->
            @if(isset($preSelectedFromBranch))
                <input type="hidden" name="from_branch_id" value="{{ $preSelectedFromBranch }}">
            @endif

            @if (isset($selectedProduct) && $selectedProduct)
                <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">
                @if (isset($selectedFlavor) && $selectedFlavor)
                    <input type="hidden" name="flavor_id" value="{{ $selectedFlavor->id }}">
                @endif
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">From Branch (Source) <span class="text-danger">*</span></label>
                    @if(isset($preSelectedFromBranch))
                        @php
                            $sourceBranchName = 'Main Warehouse';
                            if($preSelectedFromBranch != '0') {
                                $sourceBranch = \App\Models\Branch::find($preSelectedFromBranch);
                                $sourceBranchName = $sourceBranch ? $sourceBranch->name : 'Branch';
                            }
                        @endphp
                        <input type="text" class="form-control bg-light" value="{{ $sourceBranchName }}" readonly>
                        <small class="text-muted">Source branch is pre-selected</small>
                    @else
                        <select name="from_branch_id" id="fromBranchSelect" class="form-select" required>
                            <option value="">Select source branch...</option>
                            <option value="0" {{ old('from_branch_id') == '0' ? 'selected' : '' }}>Main Warehouse</option>
                            @foreach ($sourceBranches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('from_branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select Main Warehouse or a branch that has the stock you need</small>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">To Branch (Destination) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control bg-light" 
                           value="{{ Auth::user()->branch->name }} (Your Branch)" readonly>
                    <input type="hidden" name="to_branch_id" value="{{ Auth::user()->branch_id }}">
                    <small class="text-muted">Stock will be transferred to your branch</small>
                </div>
            </div>

            <!-- Product selection - hidden if pre-selected, otherwise show dropdown -->
            @if (!isset($selectedProduct) || !$selectedProduct)
                <div class="mb-3">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="productSelect" class="form-select" required>
                        <option value="">Select product...</option>
                    </select>
                </div>

                <div class="mb-3" id="flavorContainer">
                    <label class="form-label">Variant <span class="text-danger" id="flavorRequired">*</span></label>
                    <select name="flavor_id" id="flavorSelect" class="form-select">
                        <option value="">-- Select variant --</option>
                    </select>
                    <small class="text-muted" id="flavorHelp">Select a specific variant for this product</small>
                </div>
            @else
                <div class="mb-3">
                    <label class="form-label">Product</label>
                    <input type="text" class="form-control bg-light" value="{{ $selectedProduct->name }}" readonly>
                </div>
                @if (isset($selectedFlavor) && $selectedFlavor)
                    <div class="mb-3">
                        <label class="form-label">Variant</label>
                        <input type="text" class="form-control bg-light" value="{{ $selectedFlavor->name }}" readonly>
                    </div>
                @else
                    <div class="mb-3" id="flavorContainer">
                        <label class="form-label">Variant <span class="text-danger" id="flavorRequired">*</span></label>
                        <select name="flavor_id" id="flavorSelect" class="form-select">
                            <option value="">-- Select variant --</option>
                        </select>
                        <small class="text-muted" id="flavorHelp">Select a specific variant for this product</small>
                    </div>
                @endif
            @endif

            <div class="mb-3">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1"
                    value="{{ isset($maxQuantity) && $maxQuantity > 0 ? 1 : 1 }}" required>
                <small class="text-muted" id="availableStock">
                    @if (isset($selectedProduct) && $selectedProduct && isset($maxQuantity))
                        Max available: <strong class="text-primary">{{ $maxQuantity }}</strong> units
                    @else
                        Select source and product to check availability
                    @endif
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (Optional)</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Reason for transfer..."></textarea>
            </div>

            <div id="formError" class="alert alert-danger mt-3" style="display: none;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span id="errorMessage"></span>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="transferForm" class="btn btn-primary" id="submitBtn">
            <i class="bi bi-send"></i> Submit Request
        </button>
    </div>
</div>

<script>
    (function() {
        const form = document.getElementById('transferForm');
        const fromBranchSelect = document.getElementById('fromBranchSelect');
        const productSelect = document.getElementById('productSelect');
        const flavorSelect = document.getElementById('flavorSelect');
        const quantityInput = document.getElementById('quantity');
        const availableStockSpan = document.getElementById('availableStock');
        const flavorContainer = document.getElementById('flavorContainer');
        const formError = document.getElementById('formError');
        const errorMessage = document.getElementById('errorMessage');

        let maxAvailable = {{ isset($maxQuantity) && $maxQuantity > 0 ? $maxQuantity : 0 }};
        let isPreSelected = {{ isset($selectedProduct) && $selectedProduct ? 'true' : 'false' }};

        function showError(msg) {
            if (formError && errorMessage) {
                errorMessage.innerText = msg;
                formError.style.display = 'block';
            }
            return false;
        }

        function hideError() {
            if (formError) {
                formError.style.display = 'none';
            }
        }

        function validateForm() {
            const fromBranch = fromBranchSelect ? fromBranchSelect.value : '';
            const productId = productSelect ? productSelect.value : '';
            const quantity = quantityInput ? parseInt(quantityInput.value) : 0;

            if (!fromBranch) {
                showError('Please select a source branch.');
                return false;
            }

            if (!productId) {
                showError('Please select a product.');
                return false;
            }

            if (isNaN(quantity) || quantity < 1) {
                showError('Please enter a valid quantity (minimum 1).');
                return false;
            }

            if (maxAvailable > 0 && quantity > maxAvailable) {
                showError(`Maximum available quantity is ${maxAvailable} units.`);
                return false;
            }

            hideError();
            return true;
        }

        // Function to check availability
        function checkAvailability(productId, fromBranch, flavorId) {
            if (!productId || !fromBranch) {
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
                        maxAvailable = data.available || 0;
                        
                        if (maxAvailable > 0) {
                            availableStockSpan.innerHTML =
                                `Max available: <strong class="text-primary">${maxAvailable}</strong> units`;
                            quantityInput.max = maxAvailable;
                            quantityInput.min = 1;
                            quantityInput.disabled = false;
                            if (parseInt(quantityInput.value) > maxAvailable) {
                                quantityInput.value = maxAvailable;
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
                    validateForm();
                })
                .catch(error => {
                    console.error('Error checking availability:', error);
                    availableStockSpan.innerHTML = '<span class="text-danger">Error checking availability. Please refresh.</span>';
                    quantityInput.max = 0;
                    quantityInput.value = '';
                    quantityInput.disabled = true;
                });
        }

        // Only initialize dynamic behavior if not pre-selected
        if (!isPreSelected) {
            // When source changes, load available products
            if (fromBranchSelect) {
                fromBranchSelect.addEventListener('change', function() {
                    const branchId = this.value;
                    
                    if (!branchId) {
                        if (productSelect) {
                            productSelect.innerHTML = '<option value="">Select product...</option>';
                        }
                        if (flavorSelect) {
                            flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                        }
                        if (flavorContainer) {
                            flavorContainer.style.display = 'block';
                        }
                        if (availableStockSpan) {
                            availableStockSpan.innerHTML = 'Select source and product to check availability';
                        }
                        if (quantityInput) {
                            quantityInput.max = '';
                            quantityInput.value = '';
                        }
                        return;
                    }

                    if (productSelect) {
                        productSelect.innerHTML = '<option value="">Loading products...</option>';
                    }
                    if (flavorSelect) {
                        flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                    }
                    if (flavorContainer) {
                        flavorContainer.style.display = 'block';
                    }
                    if (availableStockSpan) {
                        availableStockSpan.innerHTML = 'Loading products...';
                    }

                    const url = `/branch-admin/api/source-products?branch_id=${branchId}`;

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(products => {
                            if (productSelect) {
                                productSelect.innerHTML = '<option value="">Select product...</option>';
                                if (products.length === 0) {
                                    productSelect.innerHTML = '<option value="">No products available</option>';
                                    if (availableStockSpan) {
                                        availableStockSpan.innerHTML = '<span class="text-warning">No products with stock in this source.</span>';
                                    }
                                } else {
                                    products.forEach(product => {
                                        const option = document.createElement('option');
                                        option.value = product.id;
                                        option.textContent = product.name;
                                        productSelect.appendChild(option);
                                    });
                                    if (availableStockSpan) {
                                        availableStockSpan.innerHTML = 'Select a product to check availability';
                                    }
                                }
                            }
                            if (quantityInput) {
                                quantityInput.max = '';
                                quantityInput.value = '';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading products:', error);
                            if (productSelect) {
                                productSelect.innerHTML = '<option value="">Error loading products</option>';
                            }
                            if (availableStockSpan) {
                                availableStockSpan.innerHTML = '<span class="text-danger">Error loading products. Please refresh.</span>';
                            }
                        });
                });
            }

            // When product changes, load flavors with stock
            if (productSelect) {
                productSelect.addEventListener('change', function() {
                    const productId = this.value;
                    const fromBranch = fromBranchSelect ? fromBranchSelect.value : '';

                    if (flavorSelect) {
                        flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                    }
                    if (availableStockSpan) {
                        availableStockSpan.innerHTML = 'Loading variants...';
                    }
                    if (quantityInput) {
                        quantityInput.max = '';
                        quantityInput.value = '';
                    }
                    if (flavorContainer) {
                        flavorContainer.style.display = 'block';
                    }

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
                                    // No flavors - hide flavor selector
                                    if (flavorContainer) {
                                        flavorContainer.style.display = 'none';
                                    }
                                    if (flavorSelect) {
                                        flavorSelect.innerHTML = '';
                                        const option = document.createElement('option');
                                        option.value = '';
                                        option.textContent = 'No variants';
                                        flavorSelect.appendChild(option);
                                        flavorSelect.value = '';
                                    }
                                    if (availableStockSpan) {
                                        availableStockSpan.innerHTML = 'Checking availability...';
                                    }
                                    checkAvailability(productId, fromBranch, '');
                                    return;
                                }
                                
                                // Populate flavors
                                if (flavorSelect) {
                                    flavorSelect.innerHTML = '';
                                    flavors.forEach(flavor => {
                                        const option = document.createElement('option');
                                        option.value = flavor.id;
                                        option.textContent = flavor.name;
                                        flavorSelect.appendChild(option);
                                    });
                                }
                                
                                if (flavorContainer) {
                                    flavorContainer.style.display = 'block';
                                }
                                if (flavorSelect) {
                                    flavorSelect.disabled = false;
                                }
                                
                                // Auto-select first flavor
                                if (flavors.length > 0 && flavorSelect) {
                                    flavorSelect.value = flavors[0].id;
                                    checkAvailability(productId, fromBranch, flavors[0].id);
                                }
                            })
                            .catch(error => {
                                console.error('Error loading flavors:', error);
                                if (flavorSelect) {
                                    flavorSelect.innerHTML = '<option value="">Error loading variants</option>';
                                    flavorSelect.disabled = true;
                                }
                                if (availableStockSpan) {
                                    availableStockSpan.innerHTML = '<span class="text-danger">Error loading variants. Please refresh.</span>';
                                }
                            });
                    } else {
                        if (!productId && flavorSelect) {
                            flavorSelect.innerHTML = '<option value="">-- Select variant --</option>';
                            if (availableStockSpan) {
                                availableStockSpan.innerHTML = 'Select a product to check availability';
                            }
                        } else if (!fromBranch && availableStockSpan) {
                            availableStockSpan.innerHTML = 'Select a source to check availability';
                        }
                    }
                });
            }

            // Flavor change triggers availability check
            if (flavorSelect) {
                flavorSelect.addEventListener('change', function() {
                    const fromBranch = fromBranchSelect ? fromBranchSelect.value : '';
                    const productId = productSelect ? productSelect.value : '';
                    const flavorId = this.value;
                    
                    if (fromBranch && productId) {
                        checkAvailability(productId, fromBranch, flavorId);
                    }
                });
            }

            // Validate on input change
            if (fromBranchSelect) {
                fromBranchSelect.addEventListener('change', validateForm);
            }

            if (productSelect) {
                productSelect.addEventListener('change', validateForm);
            }

            if (quantityInput) {
                quantityInput.addEventListener('input', validateForm);
            }

            // Initialize if pre-selected
            if (fromBranchSelect) {
                const initialBranch = fromBranchSelect.value;
                if (initialBranch) {
                    fromBranchSelect.dispatchEvent(new Event('change'));
                    if (productSelect) {
                        const initialProduct = productSelect.value;
                        if (initialProduct) {
                            setTimeout(() => {
                                productSelect.dispatchEvent(new Event('change'));
                            }, 500);
                        }
                    }
                }
            }
        } else {
            // Pre-selected product - just check availability if flavor is selected
            const fromBranch = document.querySelector('input[name="from_branch_id"]')?.value || 
                              fromBranchSelect?.value || 
                              {{ isset($preSelectedFromBranch) ? $preSelectedFromBranch : 'null' }};
            
            const productId = {{ isset($selectedProduct) ? $selectedProduct->id : 'null' }};
            const flavorId = {{ isset($selectedFlavor) ? $selectedFlavor->id : 'null' }};
            
            if (fromBranch && productId) {
                setTimeout(() => {
                    checkAvailability(productId, fromBranch, flavorId);
                }, 300);
            }
        }

        // Form submit event
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        }

        // Initial validation
        validateForm();
    })();
</script>