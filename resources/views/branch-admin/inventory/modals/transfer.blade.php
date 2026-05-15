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
                You are requesting stock FROM another branch TO
                <strong>{{ $currentBranch->name ?? Auth::user()->branch->name }}</strong> (your branch)
            </div>

            <!-- Hidden fields for pre-selected product -->
            @if (isset($selectedProduct) && $selectedProduct)
                <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">
                @if (isset($selectedFlavor) && $selectedFlavor)
                    <input type="hidden" name="flavor_id" value="{{ $selectedFlavor->id }}">
                @endif
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">From Branch (Source) <span class="text-danger">*</span></label>
                    <select name="from_branch_id" id="fromBranchSelect" class="form-select" required>
                        <option value="">Select source branch...</option>
                        @foreach ($sourceBranches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ isset($preSelectedFromBranch) && $preSelectedFromBranch == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Select the branch that has the stock you need</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">To Branch (Destination) <span class="text-danger">*</span></label>
                    <select name="to_branch_id" class="form-select" required>
                        <option value="{{ Auth::user()->branch_id }}" selected>
                            {{ Auth::user()->branch->name }} (Your Branch)
                        </option>
                    </select>
                    <small class="text-muted">Stock will be transferred to your branch</small>
                </div>
            </div>

            <!-- Product selection - hidden if pre-selected, otherwise show dropdown -->
            @if (!isset($selectedProduct) || !$selectedProduct)
                <div class="mb-3">
                    <label class="form-label">Product <span class="text-danger">*</span></label>
                    <select name="product_id" id="productSelect" class="form-select" required>
                        <option value="">Select product...</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ isset($preSelectedProductId) && $preSelectedProductId == $product->id ? 'selected' : '' }}>
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
            @else
                <div class="mb-3">
                    <label class="form-label">Product</label>
                    <input type="text" class="form-control bg-light" value="{{ $selectedProduct->name }}" readonly>
                </div>
                @if ($selectedFlavor)
                    <div class="mb-3">
                        <label class="form-label">Flavor</label>
                        <input type="text" class="form-control bg-light" value="{{ $selectedFlavor->name }}"
                            readonly>
                    </div>
                @endif
            @endif

            <div class="mb-3">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1"
                    value="{{ isset($maxQuantity) && $maxQuantity > 0 ? 1 : 1 }}" required>
                <small class="text-muted" id="availableStock">
                    @if (isset($selectedProduct) && $selectedProduct)
                        Max available: <strong class="text-primary">{{ $maxQuantity }}</strong> units
                    @else
                        Select source branch and product to check availability
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
        <!-- Use type="submit" directly on the button inside the form -->
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
        const quantityInput = document.getElementById('quantity');
        const formError = document.getElementById('formError');
        const errorMessage = document.getElementById('errorMessage');

        let maxAvailable = {{ isset($maxQuantity) && $maxQuantity > 0 ? $maxQuantity : 0 }};

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

        // Form submit event - THIS IS THE KEY FIX
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                if (!validateForm()) {
                    e.preventDefault();
                    console.log('Validation failed');
                    return false;
                }
                console.log('Validation passed, submitting...');
                return true;
            });
        }

        // Initial validation
        validateForm();
    })();
</script>
