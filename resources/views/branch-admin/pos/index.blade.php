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
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    .payment-modal {
        backdrop-filter: blur(5px);
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
                                <input type="text" id="productSearch" class="form-control" placeholder="Search products...">
                                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-seam"></i> Inventory
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($products as $category => $categoryProducts)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3">
                                <span class="category-badge">
                                    <i class="bi bi-tag me-1"></i>{{ $category }}
                                </span>
                            </h6>
                            <div class="row g-3">
                                @foreach($categoryProducts as $item)
                                    @php
                                        $product = $item->product;
                                        $available = $item->available_quantity;
                                    @endphp
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <div class="card pos-product-card h-100" 
                                             data-inventory-id="{{ $item->id }}"
                                             data-product-name="{{ $product->name }}"
                                             data-product-price="{{ $product->price }}"
                                             data-available="{{ $available }}">
                                            <div class="card-body text-center p-3">
                                                <div class="product-image mb-2" style="height: 80px;">
                                                    @if($product->image_url)
                                                        <img src="{{ \App\Helpers\GoogleDriveHelper::getThumbnailUrl($product->image_url, 100) }}" 
                                                             alt="{{ $product->name }}" class="img-fluid" style="max-height: 70px;">
                                                    @elseif($product->image)
                                                        <img src="{{ Storage::url($product->image) }}" 
                                                             alt="{{ $product->name }}" class="img-fluid" style="max-height: 70px;">
                                                    @else
                                                        <i class="bi bi-box-seam text-muted" style="font-size: 2rem;"></i>
                                                    @endif
                                                </div>
                                                <h6 class="mb-0 small fw-semibold">{{ Str::limit($product->name, 25) }}</h6>
                                                @if($item->flavor)
                                                    <small class="text-muted">{{ $item->flavor->name }}</small>
                                                @endif
                                                <div class="mt-2">
                                                    <span class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</span>
                                                    @if($available <= 5)
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
                    
                    @if($products->isEmpty())
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
                        @if(empty($cart))
                            <div class="text-center py-5">
                                <i class="bi bi-cart display-1 text-muted"></i>
                                <p class="mt-3 text-muted">Cart is empty</p>
                            </div>
                        @else
                            @foreach($cart as $item)
                                <div class="pos-cart-item" data-inventory-id="{{ $item['inventory_id'] }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ $item['product_name'] }}</h6>
                                            @if($item['flavor_name'])
                                                <small class="text-muted">{{ $item['flavor_name'] }}</small>
                                            @endif
                                            <div class="mt-2">
                                                <span class="text-muted">₱{{ number_format($item['price'], 2) }} x </span>
                                                <input type="number" class="form-control form-control-sm d-inline-block quantity-input" 
                                                       value="{{ $item['quantity'] }}" min="1" style="width: 60px;">
                                                <span class="fw-bold ms-2">₱{{ number_format($item['subtotal'], 2) }}</span>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger remove-item" data-inventory-id="{{ $item['inventory_id'] }}">
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
                            <span class="fw-bold">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (12%):</span>
                            <span>₱{{ number_format($tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fs-5 fw-bold">Total:</span>
                            <span class="fs-5 fw-bold text-primary">₱{{ number_format($total, 2) }}</span>
                        </div>
                        
                        @if(!empty($cart))
                            <button class="btn btn-primary w-100" id="checkoutBtn" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                <i class="bi bi-credit-card"></i> Checkout
                            </button>
                            <button class="btn btn-outline-secondary w-100 mt-2" id="clearCartBtn">
                                <i class="bi bi-trash"></i> Clear Cart
                            </button>
                        @endif
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
                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required placeholder="Walk-in Customer">
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
                            <option value="paymaya">PayMaya</option>
                            <option value="card">Credit/Debit Card</option>
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
                            <input type="number" step="0.01" name="amount_paid" id="amountPaid" class="form-control" required>
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
@endsection

@push('scripts')
<script>
    let currentTotal = {{ $total }};
    
    // Initialize total in modal
    document.getElementById('modalTotal').value = currentTotal.toFixed(2);
    
    // Calculate change
    document.getElementById('amountPaid').addEventListener('input', function() {
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
    
    // Add product to cart
    document.querySelectorAll('.pos-product-card').forEach(card => {
        card.addEventListener('click', function() {
            const inventoryId = this.dataset.inventoryId;
            const productName = this.dataset.productName;
            
            const quantity = prompt(`Enter quantity for ${productName}:`, 1);
            if (quantity && parseInt(quantity) > 0) {
                addToCart(inventoryId, parseInt(quantity));
            }
        });
    });
    
    function addToCart(inventoryId, quantity) {
        fetch('{{ route("branch-admin.pos.add-to-cart") }}', {
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
            showToast('error', 'Error adding to cart');
        });
    }
    
    // Update quantity
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const cartItem = this.closest('.pos-cart-item');
            const inventoryId = cartItem.dataset.inventoryId;
            const quantity = parseInt(this.value);
            
            if (quantity > 0) {
                updateCartQuantity(inventoryId, quantity);
            }
        });
    });
    
    // Remove item
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const inventoryId = this.dataset.inventoryId;
            updateCartQuantity(inventoryId, 0);
        });
    });
    
    function updateCartQuantity(inventoryId, quantity) {
        fetch('{{ route("branch-admin.pos.update-cart") }}', {
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
        });
    }
    
    // Clear cart
    document.getElementById('clearCartBtn')?.addEventListener('click', function() {
        if (confirm('Clear entire cart?')) {
            fetch('{{ route("branch-admin.pos.clear-cart") }}', {
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
            });
        }
    });
    
    function updateCartUI(data) {
        // Update cart count
        document.getElementById('cartCount').textContent = data.cart_count;
        
        // Update totals in main view
        document.querySelector('.cart-total .fw-bold.text-primary').textContent = `₱${data.total}`;
        
        // Update modal total
        currentTotal = parseFloat(data.total);
        document.getElementById('modalTotal').value = currentTotal.toFixed(2);
        
        // Rebuild cart items
        const cartContainer = document.getElementById('cartItems');
        if (data.cart_count === 0) {
            cartContainer.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-cart display-1 text-muted"></i>
                    <p class="mt-3 text-muted">Cart is empty</p>
                </div>
            `;
            document.querySelector('.card-footer .btn-primary').style.display = 'none';
            document.querySelector('.card-footer .btn-outline-secondary').style.display = 'none';
        } else {
            let html = '';
            for (const [id, item] of Object.entries(data.cart)) {
                html += `
                    <div class="pos-cart-item" data-inventory-id="${item.inventory_id}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">${item.product_name}</h6>
                                ${item.flavor_name ? `<small class="text-muted">${item.flavor_name}</small>` : ''}
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
            
            // Reattach event listeners
            attachCartEvents();
        }
    }
    
    function attachCartEvents() {
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartItem = this.closest('.pos-cart-item');
                const inventoryId = cartItem.dataset.inventoryId;
                const quantity = parseInt(this.value);
                if (quantity > 0) {
                    updateCartQuantity(inventoryId, quantity);
                }
            });
        });
        
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const inventoryId = this.dataset.inventoryId;
                updateCartQuantity(inventoryId, 0);
            });
        });
    }
    
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed bottom-0 end-0 m-3`;
        toast.style.zIndex = 9999;
        toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill me-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    
    // Search functionality
    document.getElementById('searchBtn')?.addEventListener('click', function() {
        const searchTerm = document.getElementById('productSearch').value.toLowerCase();
        document.querySelectorAll('.pos-product-card').forEach(card => {
            const productName = card.dataset.productName?.toLowerCase() || '';
            if (productName.includes(searchTerm) || searchTerm === '') {
                card.closest('.col-md-3').style.display = '';
            } else {
                card.closest('.col-md-3').style.display = 'none';
            }
        });
    });
    
    document.getElementById('productSearch')?.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('searchBtn').click();
        }
    });
</script>
@endpush