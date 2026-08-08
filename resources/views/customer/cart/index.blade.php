@extends('layouts.customer')

@section('content')
    <div class="container">
        <!-- ADDED: Header with Title and Continue Shopping Button (Just like Back button) -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-cart"></i> Shopping Cart</h2>
            <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary rounded-pill">
                <i class="bi bi-arrow-left"></i> Continue Shopping
            </a>
        </div>

        @if (count($items) > 0)
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <form method="POST" action="{{ route('customer.cart.checkout-selected') }}" id="checkoutSelectedForm">
                            @csrf
                            <table class="table cart-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Product</th>
                                        <th>Variant</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $index => $item)
                                        <tr>
                                            <td data-label="Select" style="width: 40px;">
                                                <input type="checkbox" name="selected_items[]"
                                                    value="{{ $item['inventory_id'] }}"
                                                    class="form-check-input item-checkbox" data-price="{{ $item['price'] }}"
                                                    data-quantity="{{ $item['quantity'] }}">
                                            </td>
                                            <td data-label="Product">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-light rounded p-1"
                                                        style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                        @if (isset($item['product_image']) && $item['product_image'])
                                                            <img src="{{ $item['product_image'] }}"
                                                                alt="{{ $item['product_name'] }}"
                                                                style="width: 100%; height: 100%; object-fit: cover;"
                                                                onerror="this.onerror=null; this.src='https://via.placeholder.com/70x70?text=No+Image';">
                                                        @else
                                                            <i class="bi bi-box-seam fs-2 text-muted"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <strong>{{ $item['product_name'] }}</strong>
                                                        @if (isset($item['branch_name']))
                                                            <br><small class="text-muted">{{ $item['branch_name'] }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Flavor">{{ $item['flavor_name'] ?? '—' }}</td>
                                            <td data-label="Price">₱{{ number_format($item['price'], 2) }}</td>
                                            <td data-label="Quantity">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="number" value="{{ $item['quantity'] }}" min="1"
                                                        max="{{ $item['max_quantity'] ?? 999 }}"
                                                        class="form-control quantity-input" style="width: 80px;"
                                                        data-inventory-id="{{ $item['inventory_id'] }}"
                                                        data-price="{{ $item['price'] }}"
                                                        id="qty_{{ $item['inventory_id'] }}">
                                                    <div class="quantity-feedback"
                                                        id="feedback_{{ $item['inventory_id'] }}" style="display: none;">
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Total" class="item-total">
                                                <strong
                                                    id="total_{{ $item['inventory_id'] }}">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                                            </td>
                                            <td data-label="Action">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-circle remove-item-btn"
                                                    data-inventory-id="{{ $item['inventory_id'] }}"
                                                    data-product-name="{{ $item['product_name'] }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <!-- Left Side: Empty (Button moved to Header) -->
                        <div class="col-md-6">
                            <!-- Empty -->
                        </div>
                        
                        <!-- Right Side: Action Buttons -->
                        <div class="col-md-6 text-md-end">
                            <div class="d-flex flex-wrap justify-content-md-end align-items-center gap-3">
                                <h4 class="mb-0">Selected Total: <span id="selectedTotal" class="text-danger">₱0.00</span>
                                </h4>
                                
                                <!-- Checkout Selected -->
                                <button type="submit" form="checkoutSelectedForm" id="checkoutSelectedBtn"
                                    class="btn btn-primary rounded-pill px-4" style="display: none;">
                                    Checkout Selected <i class="bi bi-arrow-right"></i>
                                </button>

                                <!-- Checkout All -->
                                <a href="{{ route('customer.checkout.index') }}" class="btn btn-success rounded-pill px-4">
                                    Checkout All <i class="bi bi-cart-check"></i>
                                </a>
                                
                                <button type="button" id="clearCartBtn" class="btn btn-outline-danger rounded-pill"
                                    onclick="confirmClearCart()">
                                    <i class="bi bi-trash3"></i> Clear Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected Items Summary Card -->
            <div class="card shadow-sm border-0 mt-4" id="selectedSummaryCard" style="display: none;">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-check-circle-fill text-success me-2"></i> Selected Items Summary
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 fw-semibold">Subtotal:</p>
                            <hr>
                            <h5 class="mb-0">Total Amount:</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-1 fw-semibold" id="selectedSubtotal">₱0.00</p>
                            <hr>
                            <h5 class="mb-0 text-danger" id="selectedGrandTotal">₱0.00</h5>
                        </div>
                    </div>
                    <div class="alert alert-secondary mt-3 mb-0">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Prices are final. No additional taxes or fees will be charged.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Delivery Information Note -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Delivery Information:</strong> Please ensure your address and contact details are correct before
                proceeding to checkout.
            </div>
        @else
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-3">Your cart is empty</h3>
                <p class="text-muted">Looks like you haven't added any items yet.</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-shop"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>

    <style>
        .cart-table tbody tr {
            vertical-align: middle;
        }

        .cart-table td {
            padding: 1rem 0.75rem;
        }

        .quantity-input {
            text-align: center;
            transition: all 0.2s;
        }

        .quantity-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .quantity-input.updating {
            background-color: #fff3cd;
        }

        .cart-table .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        tr.selected-row {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .quantity-feedback {
            display: inline-block;
            margin-left: 5px;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }

        .fade-out {
            animation: fadeOut 1s ease-out;
        }

        @media (max-width: 768px) {
            .cart-table thead {
                display: none;
            }

            .cart-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border-bottom: 1px solid #dee2e6;
            }

            .cart-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                border: none;
            }

            .cart-table td:before {
                content: attr(data-label);
                font-weight: 600;
                width: 40%;
            }

            .cart-table td:first-child {
                display: block;
            }

            .cart-table td:first-child:before {
                display: none;
            }

            .cart-table td:first-child .d-flex {
                justify-content: space-between;
            }
        }
    </style>

    @push('scripts')
        <script>
            // Select All functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const checkoutSelectedBtn = document.getElementById('checkoutSelectedBtn');
            const selectedSummaryCard = document.getElementById('selectedSummaryCard');

            // Update selected total and summary
            function updateSelectedTotal() {
                let selectedSubtotal = 0;
                let selectedCount = 0;

                itemCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const price = parseFloat(checkbox.dataset.price);
                        const quantity = parseInt(checkbox.dataset.quantity);
                        selectedSubtotal += price * quantity;
                        selectedCount++;
                        checkbox.closest('tr').classList.add('selected-row');
                    } else {
                        checkbox.closest('tr').classList.remove('selected-row');
                    }
                });

                const selectedGrandTotal = selectedSubtotal;

                // Update display
                document.getElementById('selectedTotal').textContent = '₱' + selectedGrandTotal.toFixed(2);
                document.getElementById('selectedSubtotal').textContent = '₱' + selectedSubtotal.toFixed(2);
                document.getElementById('selectedGrandTotal').textContent = '₱' + selectedGrandTotal.toFixed(2);

                // Show/hide checkout selected button based on selection
                if (selectedCount > 0) {
                    checkoutSelectedBtn.style.display = 'inline-flex';
                } else {
                    checkoutSelectedBtn.style.display = 'none';
                }

                // Show/hide selected summary card
                selectedSummaryCard.style.display = selectedCount > 0 ? 'block' : 'none';
            }

            // Function to update quantity via AJAX
            async function updateQuantity(inventoryId, newQuantity, price, inputElement) {
                if (newQuantity < 1) {
                    alert('Quantity must be at least 1');
                    inputElement.value = 1;
                    return;
                }

                const max = parseInt(inputElement.getAttribute('max'));
                if (newQuantity > max) {
                    alert('Maximum quantity available is ' + max);
                    inputElement.value = max;
                    return;
                }

                // Show updating state
                inputElement.classList.add('updating');
                const originalValue = inputElement.value;

                // Show feedback indicator
                const feedbackDiv = document.getElementById('feedback_' + inventoryId);
                if (feedbackDiv) {
                    feedbackDiv.style.display = 'inline-block';
                    feedbackDiv.innerHTML =
                        '<small class="text-success"><i class="bi bi-check-circle-fill"></i> Updating...</small>';
                }

                try {
                    const response = await fetch('/customer/cart/update/' + inventoryId, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            quantity: newQuantity
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Update total for this row
                        const newTotal = price * newQuantity;
                        const totalElement = document.getElementById('total_' + inventoryId);
                        totalElement.textContent = '₱' + newTotal.toFixed(2);
                        totalElement.style.color = '#28a745';
                        setTimeout(function() {
                            totalElement.style.color = '';
                        }, 500);

                        // Update checkbox data-quantity
                        const checkbox = document.querySelector('input[name="selected_items[]"][value="' + inventoryId +
                            '"]');
                        if (checkbox) {
                            checkbox.dataset.quantity = newQuantity;
                            if (checkbox.checked) {
                                updateSelectedTotal();
                            }
                        }

                        // Update all item totals and refresh selected total
                        updateSelectedTotal();

                        // Show success feedback
                        if (feedbackDiv) {
                            feedbackDiv.innerHTML =
                                '<small class="text-success"><i class="bi bi-check-circle-fill"></i> Saved</small>';
                            setTimeout(function() {
                                feedbackDiv.style.display = 'none';
                            }, 1500);
                        }
                    } else {
                        alert(data.message || 'Error updating quantity');
                        inputElement.value = originalValue;
                        if (feedbackDiv) {
                            feedbackDiv.innerHTML =
                                '<small class="text-danger"><i class="bi bi-x-circle-fill"></i> Failed</small>';
                            setTimeout(function() {
                                feedbackDiv.style.display = 'none';
                            }, 1500);
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error updating quantity. Please try again.');
                    inputElement.value = originalValue;
                    if (feedbackDiv) {
                        feedbackDiv.innerHTML =
                            '<small class="text-danger"><i class="bi bi-x-circle-fill"></i> Error</small>';
                        setTimeout(function() {
                            feedbackDiv.style.display = 'none';
                        }, 1500);
                    }
                } finally {
                    // Remove updating state
                    inputElement.classList.remove('updating');
                }
            }

            // Auto-update quantity on change (no button needed)
            document.querySelectorAll('.quantity-input').forEach(function(input) {
                // Handle manual input changes
                input.addEventListener('change', function() {
                    var inventoryId = this.dataset.inventoryId;
                    var newQuantity = parseInt(this.value);
                    var price = parseFloat(this.dataset.price);

                    updateQuantity(inventoryId, newQuantity, price, this);
                });

                // Optional: Update on Enter key
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.blur();
                    }
                });
            });

            // Remove item via AJAX (NO PAGE REFRESH)
            document.querySelectorAll('.remove-item-btn').forEach(function(btn) {
                btn.addEventListener('click', async function() {
                    var inventoryId = this.dataset.inventoryId;
                    var productName = this.dataset.productName;
                    var row = this.closest('tr');

                    if (confirm('Remove ' + productName + ' from cart?')) {
                        var originalHtml = this.innerHTML;
                        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                        this.disabled = true;

                        try {
                            var response = await fetch('/customer/cart/remove/' + inventoryId, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            });

                            var data = await response.json();

                            if (data.success) {
                                row.style.transition = 'all 0.3s ease';
                                row.style.opacity = '0';
                                setTimeout(function() {
                                    row.remove();
                                    updateSelectedTotal();
                                    if (document.querySelectorAll('.item-checkbox').length === 0) {
                                        location.reload();
                                    }
                                }, 300);
                            } else {
                                alert(data.message || 'Error removing item');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error removing item');
                        } finally {
                            this.innerHTML = originalHtml;
                            this.disabled = false;
                        }
                    }
                });
            });

            // Clear cart with confirmation
            window.confirmClearCart = async function() {
                if (confirm('Are you sure you want to clear your entire cart?')) {
                    var clearBtn = document.getElementById('clearCartBtn');
                    var originalBtnHtml = clearBtn.innerHTML;

                    clearBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                    clearBtn.disabled = true;

                    try {
                        var response = await fetch('{{ route('customer.cart.clear') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        var data = await response.json();

                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Error clearing cart');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error clearing cart');
                    } finally {
                        clearBtn.innerHTML = originalBtnHtml;
                        clearBtn.disabled = false;
                    }
                }
            };

            // Select All functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    itemCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    updateSelectedTotal();
                });
            }

            // Individual checkbox changes
            itemCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    updateSelectedTotal();
                    if (selectAllCheckbox) {
                        var allChecked = Array.from(itemCheckboxes).every(function(cb) {
                            return cb.checked;
                        });
                        selectAllCheckbox.checked = allChecked;
                    }
                });
            });

            // Initial calculation
            updateSelectedTotal();
        </script>
    @endpush
@endsection