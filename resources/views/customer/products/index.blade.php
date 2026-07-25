@extends('layouts.customer')

@section('content')
    <div class="container">
        <!-- Branch Filter & Search -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <label class="fw-semibold"><i class="bi bi-geo-alt"></i> Check Stock At:</label>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('customer.products.index', ['branch_id' => 'all']) }}"
                            class="btn btn-sm {{ $selectedBranchId == 'all' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
                            All Branches
                        </a>
                        @foreach ($branches as $branch)
                            <a href="{{ route('customer.products.index', ['branch_id' => $branch->id]) }}"
                                class="btn btn-sm {{ $selectedBranchId == $branch->id ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
                                {{ $branch->name }}
                            </a>
                        @endforeach
                    </div>
                    <span class="text-muted small">Showing stock availability from selected branch</span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="input-group w-100">
                    <input type="text" id="productSearch" class="form-control rounded-pill"
                        placeholder="Search products by name, brand, or flavor...">
                    <button class="btn btn-outline-secondary rounded-pill ms-2" id="searchButton">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Best Sellers Section -->
        @if (isset($bestSellers) && $bestSellers->count() > 0)
            <div class="mb-5">
                <h4 class="mb-3 fw-bold"><i class="bi bi-star-fill text-warning"></i> Best Sellers</h4>
                <div class="row g-4">
                    @foreach ($bestSellers as $product)
                        <div class="col-lg-2 col-md-3 col-4">
                            <div class="card product-card h-100">
                                <div class="position-relative">
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}" class="product-img"
                                            alt="{{ $product->name }}" style="height: 120px; object-fit: cover;">
                                    @else
                                        <div class="product-img bg-light d-flex align-items-center justify-content-center"
                                            style="height: 120px;">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger">Best Seller</span>
                                </div>
                                <div class="card-body p-2 text-center">
                                    <h6 class="card-title small fw-semibold mb-1">{{ Str::limit($product->name, 30) }}</h6>
                                    <div class="product-price small fw-bold text-danger">
                                        ₱{{ number_format($product->price, 2) }}
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 pb-2 text-center">
                                    <button class="btn btn-sm btn-add-cart w-100 select-item-btn"
                                        data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                        <i class="bi bi-cart-plus me-1"></i> Select Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products Grid - Shows ALL products from all branches -->
        <h4 class="mb-3 fw-bold"><i class="bi bi-grid-3x3-gap-fill"></i> All Products</h4>

        <div class="row g-4" id="productGrid">
            @php
                $currentCategory = '';
            @endphp

            @forelse($groupedProducts as $productName => $variants)
                
                @php
                    // Get the category of this product safely
                    $firstVariant = $variants->first();
                    $productCategory = $firstVariant['category'] ?? 'Other';
                @endphp

                <!-- ✅ Print Category Header only when category changes -->
                @if($productCategory !== $currentCategory)
                    @php $currentCategory = $productCategory; @endphp
                    <div class="col-12 mb-2 mt-2">
                        <h5 class="fw-semibold mb-3">
                            <span class="category-badge bg-light px-3 py-2 rounded-pill">
                                <i class="bi bi-tag me-1"></i> {{ $productCategory }}
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
                                $hasStock = $variants->contains(fn($v) => $v['available_quantity'] > 0);
                                $hasLowStock = $variants->contains(
                                    fn($v) => $v['available_quantity'] <= 5 && $v['available_quantity'] > 0,
                                );
                            @endphp
                            @if (!$hasStock)
                                <span class="position-absolute top-0 start-0 m-2 badge bg-danger">Out of Stock</span>
                            @elseif($hasLowStock)
                                <span class="position-absolute top-0 start-0 m-2 badge bg-warning">Low Stock</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-semibold">{{ $productName }}</h6>
                            <div class="product-price mt-2">₱{{ number_format($lowestPrice, 2) }}</div>
                            <div class="small text-muted mt-1">{{ $variants->count() }} variant(s) available</div>
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
                        <input type="hidden" name="product_id" id="itemProductId">
                        <div class="modal-body">
                            <p id="itemProductName" class="fw-bold mb-3"></p>
                            <label class="form-label fw-semibold">Choose Branch & Item:</label>
                            <select name="item_variant" id="itemSelect" class="form-select" required>
                                <option value="">Select branch and item...</option>
                            </select>
                            <div class="mt-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="itemQuantity" class="form-control"
                                    min="1" value="1">
                                <small class="text-muted" id="itemStockInfo"></small>
                            </div>
                            <div id="itemPriceDisplay" class="mt-2 text-end fw-bold text-danger"></div>
                            <div class="mt-2 small text-muted" id="branchNote"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .category-badge {
            font-size: 0.9rem;
            font-weight: 600;
        }
    </style>
@endsection

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

            if (searchButton) {
                searchButton.addEventListener('click', searchProducts);
            }
            if (searchInput) {
                searchInput.addEventListener('keyup', searchProducts);
            }

            // Item selection modal
            const itemModalElement = document.getElementById('itemModal');
            if (itemModalElement) {
                const itemModal = new bootstrap.Modal(itemModalElement);
                const itemSelect = document.getElementById('itemSelect');
                const itemInventoryId = document.getElementById('itemInventoryId');
                const itemProductId = document.getElementById('itemProductId');
                const itemQuantity = document.getElementById('itemQuantity');
                const itemStockInfo = document.getElementById('itemStockInfo');
                const itemPriceDisplay = document.getElementById('itemPriceDisplay');
                const branchNote = document.getElementById('branchNote');

                // Function to fetch product variants via AJAX
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

                // Handle "Select Item" buttons
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

                        // Show loading state on button
                        const originalText = this.innerHTML;
                        this.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
                        this.disabled = true;

                        // Fetch variants via AJAX
                        const variants = await fetchProductVariants(productId, productName);

                        // Reset button
                        this.innerHTML = originalText;
                        this.disabled = false;

                        if (!variants || variants.length === 0) {
                            return;
                        }

                        document.getElementById('itemProductName').innerText = productName;
                        if (itemProductId) {
                            itemProductId.value = productId;
                        }

                        // Populate item dropdown with branch info
                        itemSelect.innerHTML =
                            '<option value="">Select branch and item...</option>';

                        variants.forEach(function(variant) {
                            const option = document.createElement('option');
                            option.value = variant.inventory_id;
                            option.dataset.productId = variant.product_id;
                            option.dataset.price = variant.price;
                            option.dataset.maxStock = variant.available_quantity;
                            option.dataset.branchId = variant.branch_id;
                            option.dataset.branchName = variant.branch_name ||
                                'Unknown Branch';
                            option.dataset.itemName = variant.flavor || 'Standard';
                            option.textContent = '[' + (variant.branch_name ||
                                'Branch') + '] ' + (variant.flavor || 'Standard') +
                                ' - ₱' + parseFloat(variant.price).toFixed(2) +
                                ' (Stock: ' + variant.available_quantity + ')';
                            itemSelect.appendChild(option);
                        });

                        itemModal.show();
                    });
                });

                // Update stock and price when item changes
                if (itemSelect) {
                    itemSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        if (selectedOption && selectedOption.value) {
                            const inventoryId = selectedOption.value;
                            const productId = selectedOption.dataset.productId;
                            const price = parseFloat(selectedOption.dataset.price);
                            const maxStock = parseInt(selectedOption.dataset.maxStock);
                            const branchName = selectedOption.dataset.branchName;

                            itemInventoryId.value = inventoryId;
                            if (itemProductId && !itemProductId.value) {
                                itemProductId.value = productId;
                            }
                            itemQuantity.max = maxStock;
                            let currentQty = parseInt(itemQuantity.value) || 1;
                            itemQuantity.value = Math.min(currentQty, maxStock);
                            itemStockInfo.innerText = 'Max stock: ' + maxStock + ' units';
                            itemPriceDisplay.innerText = 'Price: ₱' + price.toFixed(2);
                            branchNote.innerHTML =
                                '<i class="bi bi-shop"></i> Will be fulfilled by: <strong>' + branchName +
                                '</strong>';
                        } else {
                            itemInventoryId.value = '';
                            itemStockInfo.innerText = '';
                            itemPriceDisplay.innerText = '';
                            branchNote.innerHTML = '';
                        }
                    });
                }

                // Validate quantity against max stock
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