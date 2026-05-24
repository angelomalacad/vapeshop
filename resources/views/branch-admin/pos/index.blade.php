@extends('layouts.branch-admin')

@section('title', 'Point of Sale - Vape Expo')

@section('content')
    <style>
        .pos-product-card {
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid #e9ecef;
        }

        .pos-product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: #0d6efd;
        }

        .pos-cart-item {
            border-bottom: 1px solid #e9ecef;
            padding: 10px 0;
        }

        .pos-cart-item:last-child {
            border-bottom: none;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
        }

        .category-badge {
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .cart-total {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }

        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <!-- Left Side - Products Grid -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Product Catalog
                            </h5>
                            <div class="d-flex gap-2">
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" id="productSearch" class="form-control"
                                        placeholder="Search products...">
                                    <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <a href="{{ route('branch-admin.inventory.index') }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-box-seam"></i> Inventory
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($products as $category => $categoryProducts)
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">
                                    <span class="category-badge">
                                        <i class="bi bi-tag me-1"></i>{{ $category }}
                                    </span>
                                </h6>
                                <div class="row g-3">
                                    @foreach ($categoryProducts as $item)
                                        @php
                                            $product = $item->product;
                                            $available = $item->available_quantity;
                                        @endphp
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <div class="card pos-product-card h-100" data-inventory-id="{{ $item->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-available="{{ $available }}">
                                                <div class="card-body text-center p-3">
                                                    <div class="product-image mb-2" style="height: 80px;">
                                                        @if ($product->image_url)
                                                            <img src="{{ \App\Helpers\GoogleDriveHelper::getThumbnailUrl($product->image_url, 100) }}"
                                                                alt="{{ $product->name }}" class="img-fluid"
                                                                style="max-height: 70px;">
                                                        @elseif($product->image)
                                                            <img src="{{ Storage::url($product->image) }}"
                                                                alt="{{ $product->name }}" class="img-fluid"
                                                                style="max-height: 70px;">
                                                        @else
                                                            <i class="bi bi-box-seam text-muted"
                                                                style="font-size: 2rem;"></i>
                                                        @endif
                                                    </div>
                                                    <h6 class="mb-0 small fw-semibold">{{ Str::limit($product->name, 25) }}
                                                    </h6>
                                                    @if ($item->flavor)
                                                        <small class="text-muted">{{ $item->flavor->name }}</small>
                                                    @endif
                                                    <div class="mt-2">
                                                        <span
                                                            class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</span>
                                                        @if ($available <= 5)
                                                            <span class="badge bg-warning ms-1">Low Stock</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">Stock: {{ $available }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @if ($products->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-box-seam display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No products in inventory. Please add stock first.</p>
                                <a href="{{ route('branch-admin.inventory.add-product') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Add Stock
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side - Shopping Cart -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-cart me-2 text-primary"></i>Shopping Cart
                            <span class="badge bg-secondary ms-2" id="cartCount">{{ count($cart) }}</span>
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <div id="cartItems">
                            @if (empty($cart))
                                <div class="text-center py-5">
                                    <i class="bi bi-cart display-1 text-muted"></i>
                                    <p class="mt-3 text-muted">Cart is empty</p>
                                </div>
                            @else
                                @foreach ($cart as $item)
                                    <div class="pos-cart-item" data-inventory-id="{{ $item['inventory_id'] }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $item['product_name'] }}</h6>
                                                @if ($item['flavor_name'])
                                                    <small class="text-muted">{{ $item['flavor_name'] }}</small>
                                                @endif
                                                <div class="mt-2">
                                                    <span class="text-muted">₱{{ number_format($item['price'], 2) }} x
                                                    </span>
                                                    <input type="number"
                                                        class="form-control form-control-sm d-inline-block quantity-input"
                                                        value="{{ $item['quantity'] }}" min="1"
                                                        style="width: 60px;">
                                                    <span
                                                        class="fw-bold ms-2">₱{{ number_format($item['subtotal'], 2) }}</span>
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger remove-item"
                                                data-inventory-id="{{ $item['inventory_id'] }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="cart-total">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span class="fw-bold" id="cartSubtotal">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (12%):</span>
                                <span id="cartTax">₱{{ number_format($tax, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fs-5 fw-bold">Total:</span>
                                <span class="fs-5 fw-bold text-primary"
                                    id="cartTotal">₱{{ number_format($total, 2) }}</span>
                            </div>

                            <!-- BUTTONS - Always visible in HTML, JavaScript will hide when cart empty -->
                            <button class="btn btn-primary w-100" id="checkoutBtn" data-bs-toggle="modal"
                                data-bs-target="#checkoutModal">
                                <i class="bi bi-credit-card"></i> Checkout
                            </button>
                            <button class="btn btn-outline-secondary w-100 mt-2" id="clearCartBtn">
                                <i class="bi bi-trash"></i> Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-credit-card me-2"></i>Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="checkoutForm" action="{{ route('branch-admin.pos.checkout') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control"
                                placeholder="Walk-in Customer (optional)">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Customer Phone (Optional)</label>
                            <input type="text" name="customer_phone" class="form-control" placeholder="09xxxxxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="text" id="modalTotal" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount Paid <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="amount_paid" id="amountPaid"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3" id="changeDiv" style="display: none;">
                            <label class="form-label text-success">Change</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="text" id="changeAmount" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Special instructions..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Complete Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Container for Notifications -->
    <div id="toastContainer"></div>
@endsection

@push('scripts')
    <script>
        let currentTotal = {{ $total }};
        let currentSubtotal = {{ $subtotal }};
        let currentTax = {{ $tax }};

        // Get button references
        const checkoutBtn = document.getElementById('checkoutBtn');
        const clearCartBtn = document.getElementById('clearCartBtn');
        const modalTotal = document.getElementById('modalTotal');

        // Initialize button visibility based on cart
        function initButtonVisibility() {
            const cartCount = parseInt(document.getElementById('cartCount')?.textContent || '0');
            if (checkoutBtn && clearCartBtn) {
                if (cartCount === 0) {
                    checkoutBtn.style.display = 'none';
                    clearCartBtn.style.display = 'none';
                } else {
                    checkoutBtn.style.display = 'block';
                    clearCartBtn.style.display = 'block';
                }
            }
        }

        // Initialize total in modal
        if (modalTotal) modalTotal.value = currentTotal.toFixed(2);

        // Calculate change
        const amountPaidInput = document.getElementById('amountPaid');
        if (amountPaidInput) {
            amountPaidInput.addEventListener('input', function() {
                const amountPaid = parseFloat(this.value) || 0;
                const change = amountPaid - currentTotal;
                const changeDiv = document.getElementById('changeDiv');
                const changeAmount = document.getElementById('changeAmount');

                if (amountPaid >= currentTotal) {
                    changeDiv.style.display = 'block';
                    changeAmount.value = change.toFixed(2);
                } else {
                    changeDiv.style.display = 'none';
                }
            });
        }

        // Add product to cart
        function attachProductEvents() {
            document.querySelectorAll('.pos-product-card').forEach(card => {
                card.removeEventListener('click', handleProductClick);
                card.addEventListener('click', handleProductClick);
            });
        }

        function handleProductClick() {
            const inventoryId = this.dataset.inventoryId;
            const productName = this.dataset.productName;
            const available = parseInt(this.dataset.available);

            const quantity = prompt(`Enter quantity for ${productName} (Max: ${available}):`, 1);
            if (quantity && parseInt(quantity) > 0) {
                const qty = parseInt(quantity);
                if (qty > available) {
                    showToast('error', `Only ${available} units available!`);
                    return;
                }
                addToCart(inventoryId, qty);
            }
        }

        function addToCart(inventoryId, quantity) {
            showToast('info', 'Adding to cart...');

            fetch('{{ route('branch-admin.pos.add-to-cart') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        inventory_id: inventoryId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartUI(data);
                        showToast('success', data.message);
                    } else {
                        showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error adding to cart');
                });
        }

        // Update quantity
        function attachCartItemEvents() {
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.removeEventListener('change', handleQuantityChange);
                input.addEventListener('change', handleQuantityChange);
            });

            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.removeEventListener('click', handleRemoveItem);
                btn.addEventListener('click', handleRemoveItem);
            });
        }

        function handleQuantityChange(e) {
            const cartItem = this.closest('.pos-cart-item');
            const inventoryId = cartItem.dataset.inventoryId;
            const quantity = parseInt(this.value);

            if (quantity > 0) {
                updateCartQuantity(inventoryId, quantity);
            }
        }

        function handleRemoveItem(e) {
            const inventoryId = this.dataset.inventoryId;
            updateCartQuantity(inventoryId, 0);
        }

        function updateCartQuantity(inventoryId, quantity) {
            fetch('{{ route('branch-admin.pos.update-cart') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        inventory_id: inventoryId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartUI(data);
                    } else {
                        showToast('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'Error updating cart');
                });
        }

        // Clear cart
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', function() {
                if (confirm('Clear entire cart?')) {
                    fetch('{{ route('branch-admin.pos.clear-cart') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('error', 'Error clearing cart');
                        });
                }
            });
        }

        function updateCartUI(data) {
            // Update cart count badge
            const cartCountSpan = document.getElementById('cartCount');
            if (cartCountSpan) cartCountSpan.textContent = data.cart_count;

            // Update subtotal in main view
            const cartSubtotalSpan = document.getElementById('cartSubtotal');
            if (cartSubtotalSpan) cartSubtotalSpan.textContent = `₱${data.subtotal}`;

            // Update tax in main view
            const cartTaxSpan = document.getElementById('cartTax');
            if (cartTaxSpan) cartTaxSpan.textContent = `₱${data.tax}`;

            // Update total in main view
            const cartTotalSpan = document.getElementById('cartTotal');
            if (cartTotalSpan) cartTotalSpan.textContent = `₱${data.total}`;

            // Update modal total
            currentTotal = parseFloat(data.total);
            if (modalTotal) modalTotal.value = currentTotal.toFixed(2);

            // Show or hide buttons based on cart count
            if (checkoutBtn && clearCartBtn) {
                if (data.cart_count === 0) {
                    checkoutBtn.style.display = 'none';
                    clearCartBtn.style.display = 'none';
                } else {
                    checkoutBtn.style.display = 'block';
                    clearCartBtn.style.display = 'block';
                }
            }

            // Rebuild cart items HTML
            const cartContainer = document.getElementById('cartItems');
            if (!cartContainer) return;

            if (data.cart_count === 0) {
                cartContainer.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-cart display-1 text-muted"></i>
                    <p class="mt-3 text-muted">Cart is empty</p>
                </div>
            `;
            } else {
                let html = '';
                for (const [id, item] of Object.entries(data.cart)) {
                    html += `
                    <div class="pos-cart-item" data-inventory-id="${item.inventory_id}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${escapeHtml(item.product_name)}</h6>
                                ${item.flavor_name ? `<small class="text-muted">${escapeHtml(item.flavor_name)}</small>` : ''}
                                <div class="mt-2">
                                    <span class="text-muted">₱${parseFloat(item.price).toFixed(2)} x </span>
                                    <input type="number" class="form-control form-control-sm d-inline-block quantity-input"
                                           value="${item.quantity}" min="1" style="width: 60px;">
                                    <span class="fw-bold ms-2">₱${parseFloat(item.subtotal).toFixed(2)}</span>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger remove-item" data-inventory-id="${item.inventory_id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                }
                cartContainer.innerHTML = html;
            }

            // Reattach event listeners to new cart items
            attachCartItemEvents();
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showToast(type, message) {
            // Create toast container if it doesn't exist
            let toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toastContainer';
                toastContainer.style.position = 'fixed';
                toastContainer.style.bottom = '20px';
                toastContainer.style.right = '20px';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }

            const toast = document.createElement('div');
            toast.className = `alert alert-${type === 'success' ? 'success' : (type === 'info' ? 'info' : 'danger')}`;
            toast.style.background = type === 'success' ? '#d4edda' : (type === 'info' ? '#cce5ff' : '#f8d7da');
            toast.style.color = type === 'success' ? '#155724' : (type === 'info' ? '#004085' : '#721c24');
            toast.style.border = 'none';
            toast.style.borderRadius = '12px';
            toast.style.padding = '12px 20px';
            toast.style.marginTop = '10px';
            toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            toast.style.animation = 'slideIn 0.3s ease';
            toast.innerHTML =
                `<i class="bi bi-${type === 'success' ? 'check-circle' : (type === 'info' ? 'info-circle' : 'exclamation-triangle')}-fill me-2"></i>${escapeHtml(message)}`;

            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Add CSS animation for toast
        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
        document.head.appendChild(style);

        // Search functionality
        const searchBtn = document.getElementById('searchBtn');
        const productSearch = document.getElementById('productSearch');

        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                const searchTerm = productSearch.value.toLowerCase();
                document.querySelectorAll('.pos-product-card').forEach(card => {
                    const productName = card.dataset.productName?.toLowerCase() || '';
                    const cardContainer = card.closest('.col-md-3');
                    if (cardContainer) {
                        if (productName.includes(searchTerm) || searchTerm === '') {
                            cardContainer.style.display = '';
                        } else {
                            cardContainer.style.display = 'none';
                        }
                    }
                });
            });
        }

        if (productSearch) {
            productSearch.addEventListener('keyup', function(e) {
                if (e.key === 'Enter' && searchBtn) {
                    searchBtn.click();
                }
            });
        }

        // Initialize all events on page load
        document.addEventListener('DOMContentLoaded', function() {
            attachProductEvents();
            attachCartItemEvents();
            initButtonVisibility();
        });
    </script>
@endpush
