@extends('layouts.customer')

@section('content')
    <div class="container">
        <!-- Branch Filter & Search -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <label class="fw-semibold"><i class="bi bi-geo-alt"></i> Delivering to:</label>
                    <div class="d-flex gap-2 flex-wrap">
                        @if (Auth::check() && Auth::user()->barangay)
                            @php
                                // Determine which barangay to use for branch lookup
                                $displayBarangay = Auth::user()->barangay;
                                if ($displayBarangay === 'Other' && Auth::user()->other_barangay) {
                                    $displayBarangay = Auth::user()->other_barangay;
                                }

                                $userBranch = DB::table('branch_barangay')
                                    ->where('barangay_name', Auth::user()->barangay)
                                    ->join('branches', 'branch_barangay.branch_id', '=', 'branches.id')
                                    ->select('branches.name')
                                    ->first();
                            @endphp

                            <span class="btn btn-sm btn-success rounded-pill">
                                <i class="bi bi-check-circle"></i>
                                {{ Auth::user()->city ?? '' }} -
                                {{-- FORCE DISPLAY: If barangay is 'Other', force the display of other_barangay --}}
                                @if (Auth::user()->barangay === 'Other')
                                    {{ Auth::user()->other_barangay ?: 'Unknown Area' }}
                                @else
                                    {{ Auth::user()->barangay }}
                                @endif
                            </span>
                        @endif
                    </div>
                    <span class="text-muted small">Showing stock availability across all branches</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="productSearch" class="form-control border-start-0"
                        placeholder="Search products...">
                    <button class="btn btn-primary" id="searchButton"><i class="bi bi-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <!-- Best Sellers Section (Using EXACT same card structure) -->
        @if ($bestSellers && $bestSellers->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3 fw-bold"><i class="bi bi-star-fill text-warning"></i> Best Sellers</h4>
                <div class="row g-4" id="bestSellerGrid">
                    @foreach ($bestSellers as $bestProduct)
                        @php
                            $bestVariant = $groupedProducts[$bestProduct->name]->first() ?? null;
                        @endphp
                        @if ($bestVariant)
                            <div class="col-lg-3 col-md-4 col-6 product-item"
                                data-name="{{ strtolower($bestProduct->name) }}">
                                <div class="card product-card h-100">
                                    <div class="position-relative">
                                        @if ($bestVariant['image'])
                                            <img src="{{ Storage::url($bestVariant['image']) }}" class="product-img"
                                                alt="{{ $bestProduct->name }}">
                                        @else
                                            <div
                                                class="product-img bg-light d-flex align-items-center justify-content-center">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif

                                        @php
                                            // Check if this specific product is globally out of stock across all branches
                                            $globalOutOfStock = $groupedProducts[$bestProduct->name]->every(
                                                fn($v) => $v['available_quantity'] <= 0,
                                            );
                                        @endphp

                                        @if ($globalOutOfStock)
                                            <span class="position-absolute top-0 start-0 m-2 badge bg-danger">Out of
                                                Stock</span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title fw-semibold">{{ $bestProduct->name }}</h6>
                                        <div class="product-price mt-2">₱{{ number_format($bestVariant['price'], 2) }}</div>
                                        <div class="small text-muted mt-1">
                                            {{ $groupedProducts[$bestProduct->name]->pluck('flavor')->unique()->count() }}
                                            variant(s) available</div>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 pb-3">
                                        <button class="btn btn-add-cart w-100 text-white select-item-btn"
                                            data-product-name="{{ $bestProduct->name }}"
                                            data-product-id="{{ $bestProduct->id }}">
                                            <i class="bi bi-cart-plus me-1"></i> Select Item
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products Grid (All Products) -->
        <h4 class="mb-3 fw-bold"><i class="bi bi-grid-3x3-gap-fill"></i> All Products</h4>

        <div class="row g-4" id="productGrid">
            @php $currentCategory = ''; @endphp

            @forelse($groupedProducts as $productName => $variants)
                @php
                    $firstVariant = $variants->first();
                    $productCategory = $firstVariant['category'] ?? 'Other';
                @endphp

                @if ($productCategory !== $currentCategory)
                    @php $currentCategory = $productCategory; @endphp
                    <div class="col-12 mb-2 mt-2">
                        <h5 class="fw-semibold mb-3">
                            <span class="category-badge bg-light px-3 py-2 rounded-pill">
                                <i class="bi bi-tag me-1"></i>{{ $productCategory }}
                            </span>
                        </h5>
                    </div>
                @endif

                <div class="col-lg-3 col-md-4 col-6 product-item" data-name="{{ strtolower($productName) }}">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            @php $firstVariant = $variants->first(); @endphp

                            @if ($firstVariant['image'])
                                <img src="{{ Storage::url($firstVariant['image']) }}" class="product-img"
                                    alt="{{ $productName }}">
                            @else
                                <div class="product-img bg-light d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif

                            @php
                                $lowestPrice = $variants->min('price');
                                $uniqueFlavorCount = $variants->pluck('flavor')->unique()->count();
                                $globalOutOfStock = $variants->every(fn($v) => $v['available_quantity'] <= 0);
                            @endphp

                            @if ($globalOutOfStock)
                                <span class="position-absolute top-0 start-0 m-2 badge bg-danger">Out of Stock</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">{{ $productName }}</h6>
                            <div class="product-price mt-2">₱{{ number_format($lowestPrice, 2) }}</div>
                            <div class="small text-muted mt-1">{{ $variants->pluck('flavor')->unique()->count() }}
                                variant(s) available</div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pb-3">
                            <button class="btn btn-add-cart w-100 text-white select-item-btn"
                                data-product-name="{{ $productName }}"
                                data-product-id="{{ $firstVariant['product_id'] }}">
                                <i class="bi bi-cart-plus me-1"></i> Select Item
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <p class="mt-3">No products available.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-box-seam"></i> Select Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('customer.cart.add') }}" method="POST" id="itemCartForm">
                    @csrf
                    <input type="hidden" name="inventory_id" id="itemInventoryId">
                    <div class="modal-body">
                        <h6 id="itemProductName" class="fw-bold mb-3">Product Name</h6>

                        <div class="mb-3">
                            <label class="form-label">Variant / Flavor:</label>
                            <select name="inventory_id" id="itemSelect" class="form-select" required>
                                <option value="">Select variant...</option>
                            </select>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="itemQuantity" class="form-control" min="1"
                                value="1">
                            <small class="text-muted" id="itemStockInfo"></small>
                        </div>

                        <div id="itemPriceDisplay" class="mt-2 text-end fw-bold text-danger"></div>
                        <div id="fulfilledBranchDisplay" class="mt-1 small text-muted text-end"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .category-badge {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .product-card {
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 16px 16px 0 0;
        }

        .btn-add-cart {
            background: #0d6efd;
            border: none;
        }

        .btn-add-cart:hover {
            background: #0b5ed7;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Search functionality
                const searchInput = document.getElementById('productSearch');
                const searchButton = document.getElementById('searchButton');

                function searchProducts() {
                    let term = searchInput.value.toLowerCase();
                    document.querySelectorAll('.product-item').forEach(item => {
                        let name = item.dataset.name;
                        if (name && name.includes(term)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                }

                if (searchButton) searchButton.addEventListener('click', searchProducts);
                if (searchInput) searchInput.addEventListener('keyup', searchProducts);

                // Item selection modal
                const itemModalElement = document.getElementById('itemModal');
                if (itemModalElement) {
                    const itemModal = new bootstrap.Modal(itemModalElement);
                    const itemSelect = document.getElementById('itemSelect');
                    const itemInventoryId = document.getElementById('itemInventoryId');
                    const itemQuantity = document.getElementById('itemQuantity');
                    const itemStockInfo = document.getElementById('itemStockInfo');
                    const itemPriceDisplay = document.getElementById('itemPriceDisplay');
                    const fulfilledBranchDisplay = document.getElementById('fulfilledBranchDisplay');

                    async function fetchProductVariants(productId, productName) {
                        try {
                            const response = await fetch(`/customer/products/${productId}/variants`);
                            const data = await response.json();
                            if (data.success && data.variants && data.variants.length > 0) {
                                return data.variants;
                            } else {
                                alert('No items available for this product.');
                                return [];
                            }
                        } catch (error) {
                            console.error('Error fetching variants:', error);
                            alert('Error loading product items. Please try again.');
                            return [];
                        }
                    }

                    const itemButtons = document.querySelectorAll('.select-item-btn');
                    itemButtons.forEach(btn => {
                        btn.addEventListener('click', async function(e) {
                            e.preventDefault();
                            const productName = this.dataset.productName;
                            const productId = this.dataset.productId;

                            if (!productId) {
                                alert('Invalid product. Please try again.');
                                return;
                            }

                            const originalText = this.innerHTML;
                            this.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
                            this.disabled = true;

                            const variants = await fetchProductVariants(productId, productName);
                            this.innerHTML = originalText;
                            this.disabled = false;

                            if (!variants || variants.length === 0) return;

                            document.getElementById('itemProductName').innerText = productName;

                            itemSelect.innerHTML = '<option value="">Select variant...</option>';
                            variants.forEach(function(variant) {
                                const option = document.createElement('option');
                                option.value = variant.inventory_id;
                                option.dataset.maxStock = variant.available_quantity;
                                option.dataset.price = variant.price;
                                option.dataset.branchName = variant.branch_name;
                                option.dataset.isUserBranch = variant.branch_id === variant
                                    .user_branch_id ? 'true' : 'false';

                                let displayText = (variant.flavor || 'Standard') + ' - ₱' +
                                    parseFloat(variant.price).toFixed(2);
                                if (variant.available_quantity <= 0) {
                                    displayText += ' (Currently Out of Stock)';
                                } else {
                                    displayText += ' (Stock: ' + variant
                                        .available_quantity + ')';
                                }
                                option.textContent = displayText;
                                itemSelect.appendChild(option);
                            });

                            itemModal.show();
                        });
                    });

                    itemSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption && selectedOption.value) {
                            const maxStock = parseInt(selectedOption.dataset.maxStock);
                            const price = parseFloat(selectedOption.dataset.price);
                            const branchName = selectedOption.dataset.branchName;
                            const isUserBranch = selectedOption.dataset.isUserBranch === 'true';

                            itemInventoryId.value = selectedOption.value;
                            itemQuantity.max = Math.max(maxStock, 1);
                            itemQuantity.value = 1;
                            itemStockInfo.innerText = maxStock > 0 ? 'Max stock: ' + maxStock :
                                'Currently Out of Stock';
                            itemPriceDisplay.innerText = 'Price: ₱' + price.toFixed(2);

                            if (maxStock <= 0) {
                                fulfilledBranchDisplay.innerText = '';
                                itemQuantity.disabled = true;
                            } else {
                                itemQuantity.disabled = false;
                                if (isUserBranch) {
                                    fulfilledBranchDisplay.innerText = '✓ Fulfilled by your assigned branch: ' +
                                        branchName;
                                    fulfilledBranchDisplay.style.color = '#198754';
                                } else {
                                    fulfilledBranchDisplay.innerText = '↗ Fulfilled by: ' + branchName;
                                    fulfilledBranchDisplay.style.color = '#6c757d';
                                }
                            }
                        } else {
                            itemInventoryId.value = '';
                            itemStockInfo.innerText = '';
                            itemPriceDisplay.innerText = '';
                            fulfilledBranchDisplay.innerText = '';
                            itemQuantity.disabled = true;
                        }
                    });

                    if (itemQuantity) {
                        itemQuantity.addEventListener('input', function() {
                            const selectedOption = itemSelect.options[itemSelect.selectedIndex];
                            if (selectedOption && selectedOption.value) {
                                const maxStock = parseInt(selectedOption.dataset.maxStock);
                                let value = parseInt(this.value);
                                if (isNaN(value)) value = 1;
                                if (value > maxStock) this.value = maxStock;
                                if (value < 1) this.value = 1;
                            }
                        });
                    }
                }
            });
        </script>
    @endpush

@endsection
