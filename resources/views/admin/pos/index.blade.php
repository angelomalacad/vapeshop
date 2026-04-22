@extends('layouts.admin')

@section('title', 'Point of Sale - Vape Expo')

@section('content')
<style>
    /* Full screen adjustments */
    .container-fluid {
        padding: 0 15px !important;
        max-width: 100% !important;
    }
    
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
    .branch-selector {
        background: #e7f1ff;
        border-radius: 10px;
        padding: 10px 15px;
        margin-bottom: 20px;
    }
    
    /* Make product grid more compact for full screen */
    .pos-product-card .card-body {
        padding: 0.75rem;
    }
    
    .pos-product-card h6 {
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
    }
    
    /* Make cart sticky and full height */
    .sticky-top {
        top: 20px;
    }
    
    /* Responsive adjustments */
    @media (min-width: 1400px) {
        .col-md-3 {
            flex: 0 0 auto;
            width: 20%;
        }
    }
    
    @media (min-width: 1900px) {
        .col-md-3 {
            flex: 0 0 auto;
            width: 16.666%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Branch Selector -->
    <div class="branch-selector d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-shop me-2 text-primary"></i>
            <strong>Select Branch:</strong>
            <select id="branchSelect" class="form-select d-inline-block w-auto ms-2">
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <a href="{{ route('admin.pos.history') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-clock-history"></i> Sales History
            </a>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Left Side - Products Grid -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Product Catalog
                            @if($selectedBranchId)
                                <span class="badge bg-secondary ms-2">{{ $branches->where('id', $selectedBranchId)->first()->name ?? '' }}</span>
                            @endif
                        </h5>
                        <div class="input-group" style="width: 280px;">
                            <input type="text" id="productSearch" class="form-control" placeholder="Search products...">
                            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    @if($selectedBranchId)
                        @forelse($products as $category => $categoryProducts)
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3">
                                    <span class="category-badge">
                                        <i class="bi bi-tag me-1"></i>{{ $category ?: 'Uncategorized' }}
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
                                                 data-product-price="{{ $product->price }}">
                                                <div class="card-body text-center p-3">
                                                    <div class="product-image mb-2" style="height: 70px;">
                                                        @if($product->image)
                                                            <img src="{{ Storage::url($product->image) }}" 
                                                                 alt="{{ $product->name }}" class="img-fluid" style="max-height: 60px;">
                                                        @else
                                                            <i class="bi bi-box-seam text-muted" style="font-size: 1.8rem;"></i>
                                                        @endif
                                                    </div>
                                                    <h6 class="mb-0 small fw-semibold">{{ Str::limit($product->name, 20) }}</h6>
                                                    @if($item->flavor)
                                                        <small class="text-muted">{{ $item->flavor->name }}</small>
                                                    @endif
                                                    <div class="mt-2">
                                                        <span class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</span>
                                                        @if($available <= 5 && $available > 0)
                                                            <span class="badge bg-warning ms-1">Low</span>
                                                        @elseif($available <= 0)
                                                            <span class="badge bg-danger ms-1">Out</span>
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
                                <p class="mt-3 text-muted">No products in inventory for this branch.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-shop display-1 text-muted"></i>
                            <p class="mt-3 text-muted">Please select a branch to start selling</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Right Side - Shopping Cart -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm" style="position: sticky; top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-cart me-2 text-primary"></i>Shopping Cart
                        <span class="badge bg-secondary ms-2" id="cartCount">{{ count($cart) }}</span>
                    </h5>
                </div>
                <div class="card-body" style="max-height: calc(100vh - 280px); overflow-y: auto; min-height: 400px;">
                    <div id="cartItems">
                        @if(empty($cart))
                            <div class="text-center py-5">
                                <i class="bi bi-cart display-1 text-muted"></i>
                                <p class="mt-3 text-muted">Cart is empty</p>
                            </div>
                        @else
                            @foreach($cart as $index => $item)
                                <div class="pos-cart-item" data-index="{{ $index }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ Str::limit($item['product_name'], 30) }}</h6>
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
                                        <button class="btn btn-sm btn-outline-danger remove-item" data-index="{{ $index }}">
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
                            <span class="fs-5 fw-bold text-primary" id="cartTotal">₱{{ number_format($total, 2) }}</span>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-credit-card me-2"></i>Payment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="checkoutForm" action="{{ route('admin.pos.checkout') }}" method="POST">
                @csrf
                <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentTotal = {{ $total }};
        
        // Initialize total in modal
        const modalTotal = document.getElementById('modalTotal');
        if (modalTotal) modalTotal.value = currentTotal.toFixed(2);
        
        // Branch change handler
        const branchSelect = document.getElementById('branchSelect');
        if (branchSelect) {
            branchSelect.addEventListener('change', function() {
                const branchId = this.value;
                if (branchId) {
                    window.location.href = '{{ route("admin.pos.index") }}?branch_id=' + branchId;
                }
            });
        }
        
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
        const productCards = document.querySelectorAll('.pos-product-card');
        
        productCards.forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                const inventoryId = this.dataset.inventoryId;
                const productName = this.dataset.productName;
                const stockText = this.querySelector('.text-muted')?.innerText || '';
                const stockMatch = stockText.match(/\d+/);
                const maxStock = stockMatch ? parseInt(stockMatch[0]) : 999;
                
                const quantity = prompt(`Enter quantity for ${productName} (Max: ${maxStock}):`, 1);
                if (quantity && parseInt(quantity) > 0) {
                    if (parseInt(quantity) > maxStock) {
                        alert(`Only ${maxStock} units available!`);
                        return;
                    }
                    addToCart(inventoryId, parseInt(quantity));
                }
            });
        });
        
        function addToCart(inventoryId, quantity) {
            fetch('{{ route("admin.pos.add-to-cart") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    inventory_id: inventoryId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error adding to cart');
            });
        }
        
        // Clear cart
        const clearCartBtn = document.getElementById('clearCartBtn');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', function() {
                if (confirm('Clear entire cart?')) {
                    fetch('{{ route("admin.pos.clear-cart") }}', {
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
        }
        
        // Search functionality
        const searchBtn = document.getElementById('searchBtn');
        const productSearch = document.getElementById('productSearch');
        
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                const searchTerm = productSearch.value.toLowerCase();
                document.querySelectorAll('.pos-product-card').forEach(card => {
                    const productName = card.dataset.productName?.toLowerCase() || '';
                    if (productName.includes(searchTerm) || searchTerm === '') {
                        card.closest('.col-md-3').style.display = '';
                    } else {
                        card.closest('.col-md-3').style.display = 'none';
                    }
                });
            });
        }
        
        if (productSearch) {
            productSearch.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchBtn.click();
                }
            });
        }
    });
</script>
@endsection