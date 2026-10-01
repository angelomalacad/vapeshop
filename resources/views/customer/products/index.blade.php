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
                                            <img src="{{ $bestVariant['image'] }}" class="product-img"
                                                alt="{{ $bestProduct->name }}"
                                                onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="product-img bg-light align-items-center justify-content-center"
                                                style="display: none;">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
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
                                <img src="{{ $firstVariant['image'] }}" class="product-img"
                                    alt="{{ $productName }}"
                                    onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="product-img bg-light align-items-center justify-content-center"
                                    style="display: none;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
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

    <!-- Product Selection Modal - Enhanced Mobile Version -->
    <div class="modal fade" id="itemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content mobile-modal">
                <!-- Mobile-friendly drag handle -->
                <div class="modal-drag-handle d-md-none"></div>

                <div class="modal-header-mobile">
                    <h5 class="modal-title-mobile"><i class="bi bi-box-seam"></i> Select Item</h5>
                    <button type="button" class="btn-close-mobile" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form action="{{ route('customer.cart.add') }}" method="POST" id="itemCartForm">
                    @csrf
                    <input type="hidden" name="inventory_id" id="itemInventoryId">

                    <div class="modal-body-mobile">
                        <!-- Product Image Section -->
                        <div class="product-image-section">
                            <div class="product-image-wrapper">
                                <img id="modalProductImage" src="" alt="Product Image" class="modal-product-img">
                                <div id="modalImagePlaceholder" class="modal-image-placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Product Info Section -->
                        <div class="product-info-section">
                            <h4 id="itemProductName" class="product-name-mobile">Product Name</h4>

                            <!-- Product Description -->
                            <div id="modalProductDescription" class="product-description-mobile">
                                <!-- Description will be loaded here -->
                            </div>

                            <!-- Product Details (Brand, Category, etc.) -->
                            <div id="modalProductDetails" class="product-details-mobile">
                                <!-- Details will be loaded here -->
                            </div>
                        </div>

                        <!-- Variant Selection -->
                        <div class="variant-section">
                            <label class="form-label-mobile">
                                <i class="bi bi-tag"></i> Variant / Flavor:
                            </label>
                            <select name="inventory_id" id="itemSelect" class="form-select form-select-mobile" required>
                                <option value="">Select variant...</option>
                            </select>
                        </div>

                        <!-- Quantity Section -->
                        <div class="quantity-section">
                            <label class="form-label-mobile">
                                <i class="bi bi-123"></i> Quantity
                            </label>
                            <div class="quantity-control">
                                <button type="button" class="quantity-btn" id="decreaseQty">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" name="quantity" id="itemQuantity" class="quantity-input" min="1"
                                    value="1">
                                <button type="button" class="quantity-btn" id="increaseQty">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <small class="stock-info-mobile" id="itemStockInfo"></small>
                        </div>

                        <!-- Price Display -->
                        <div class="price-section-mobile">
                            <div id="itemPriceDisplay" class="price-display-mobile"></div>
                            <div id="fulfilledBranchDisplay" class="branch-display-mobile"></div>
                        </div>
                    </div>

                    <div class="modal-footer-mobile">
                        <button type="button" class="btn-cancel-mobile" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-add-mobile">
                            <i class="bi bi-cart-plus me-1"></i> Add to Cart
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* === BASE STYLES === */
        .category-badge {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .product-img {
            height: 200px;
            width: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 16px 16px 0 0;
            display: block;           /* ✅ no inline gap */
            background: #f1f5f9;      /* ✅ soft bg behind transparent PNGs */
        }

        .btn-add-cart {
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add-cart:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
            transform: translateY(-1px);
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0d6efd;
        }

        /* === MOBILE MODAL STYLES === */
        .mobile-modal {
            border: none;
            border-radius: 24px 24px 0 0;
            overflow: hidden;
            max-height: 92vh;
        }

        .modal-drag-handle {
            width: 40px;
            height: 4px;
            background: #dee2e6;
            border-radius: 2px;
            margin: 12px auto 8px;
        }

        .modal-header-mobile {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #f0f0f0;
            background: #fff;
        }

        .modal-title-mobile {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
        }

        .modal-title-mobile i {
            color: #0d6efd;
            margin-right: 0.5rem;
        }

        .btn-close-mobile {
            background: #f1f5f9;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.2s ease;
        }

        .btn-close-mobile:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .modal-body-mobile {
            padding: 1rem 1.25rem;
            overflow-y: auto;
            max-height: calc(92vh - 180px);
            background: #f8fafc;
        }

        .modal-body-mobile::-webkit-scrollbar {
            width: 4px;
        }

        .modal-body-mobile::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        /* Product Image Section */
        .product-image-section {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .product-image-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .modal-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .modal-product-img.loaded {
            display: block;
        }

        .modal-image-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #94a3b8;
            font-size: 2.5rem;
        }

        /* Product Info Section */
        .product-info-section {
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .product-name-mobile {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .product-description-mobile {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 0.75rem;
            padding: 0 0.5rem;
        }

        .product-details-mobile {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .detail-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            color: #475569;
            font-weight: 500;
        }

        .detail-badge i {
            color: #0d6efd;
            font-size: 0.7rem;
        }

        /* Variant Section */
        .variant-section {
            margin-bottom: 1rem;
        }

        .form-label-mobile {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-label-mobile i {
            color: #0d6efd;
        }

        .form-select-mobile {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .form-select-mobile:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        /* Quantity Section */
        .quantity-section {
            margin-bottom: 1rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
        }

        .quantity-btn {
            width: 48px;
            height: 48px;
            border: none;
            background: #f8fafc;
            color: #0d6efd;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .quantity-btn:hover {
            background: #e2e8f0;
        }

        .quantity-btn:active {
            background: #cbd5e1;
            transform: scale(0.95);
        }

        .quantity-input {
            flex: 1;
            border: none;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a1a2e;
            padding: 0.75rem;
            background: #fff;
            -moz-appearance: textfield;
        }

        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .stock-info-mobile {
            display: block;
            text-align: center;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Price Section */
        .price-section-mobile {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
        }

        .price-display-mobile {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0d6efd;
        }

        .branch-display-mobile {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        /* Footer */
        .modal-footer-mobile {
            display: flex;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            padding-bottom: calc(1rem + env(safe-area-inset-bottom));
            background: #fff;
            border-top: 1px solid #f0f0f0;
        }

        .btn-cancel-mobile {
            flex: 1;
            padding: 0.875rem;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            color: #64748b;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .btn-cancel-mobile:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-add-mobile {
            flex: 2;
            padding: 0.875rem;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }

        .btn-add-mobile:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }

        .btn-add-mobile:active {
            transform: translateY(0);
        }

        /* === MOBILE RESPONSIVE === */
        @media (max-width: 767.98px) {
            .container {
                padding-left: 12px;
                padding-right: 12px;
            }

            /* ✅ Mobile product image — shorter height, cover fit, centered */
            .product-img {
                height: 130px;
                width: 100%;
                object-fit: cover;
                object-position: center;
                display: block;
                background: #f1f5f9;
            }

            /* ✅ Fallback placeholder fills the same area */
            .product-img.bg-light {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }

            .product-img.bg-light i {
                font-size: 2rem !important;
            }

            .product-card .card-body {
                padding: 0.75rem;
            }

            .product-card .card-title {
                font-size: 0.85rem;
                line-height: 1.3;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .product-price {
                font-size: 0.95rem;
            }

            .product-card .small {
                font-size: 0.7rem;
            }

            .btn-add-cart {
                padding: 0.5rem;
                font-size: 0.8rem;
            }

            .category-badge {
                font-size: 0.8rem;
            }

            .modal-dialog {
                margin: 0;
                align-items: flex-end;
                min-height: calc(100% - 20px);
            }

            .mobile-modal {
                border-radius: 24px 24px 0 0;
            }

            .modal-body-mobile {
                padding: 0.875rem 1rem;
            }

            .product-image-wrapper {
                width: 120px;
                height: 120px;
                border-radius: 16px;
            }

            .product-name-mobile {
                font-size: 1.05rem;
            }

            .quantity-btn {
                width: 44px;
                height: 44px;
            }

            .price-display-mobile {
                font-size: 1.25rem;
            }
        }

        @media (min-width: 768px) {
            .modal-dialog {
                max-width: 480px;
            }

            .mobile-modal {
                border-radius: 24px;
            }

            .modal-drag-handle {
                display: none;
            }

            .modal-body-mobile {
                max-height: calc(92vh - 200px);
            }
        }

        /* Safe area for iPhone notch */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .modal-footer-mobile {
                padding-bottom: calc(0.875rem + env(safe-area-inset-bottom));
            }
        }

        /* Smooth animations */
        .modal.fade .modal-dialog {
            transform: translateY(100%);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        @media (min-width: 768px) {
            .modal.fade .modal-dialog {
                transform: scale(0.95);
            }

            .modal.show .modal-dialog {
                transform: scale(1);
            }
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
                    const modalProductImage = document.getElementById('modalProductImage');
                    const modalImagePlaceholder = document.getElementById('modalImagePlaceholder');
                    const modalProductDescription = document.getElementById('modalProductDescription');
                    const modalProductDetails = document.getElementById('modalProductDetails');
                    const decreaseQty = document.getElementById('decreaseQty');
                    const increaseQty = document.getElementById('increaseQty');

                    // Quantity controls
                    if (decreaseQty) {
                        decreaseQty.addEventListener('click', function() {
                            let value = parseInt(itemQuantity.value) || 1;
                            if (value > 1) {
                                itemQuantity.value = value - 1;
                                itemQuantity.dispatchEvent(new Event('input'));
                            }
                        });
                    }

                    if (increaseQty) {
                        increaseQty.addEventListener('click', function() {
                            let value = parseInt(itemQuantity.value) || 1;
                            let maxStock = parseInt(itemQuantity.max) || 999;
                            if (value < maxStock) {
                                itemQuantity.value = value + 1;
                                itemQuantity.dispatchEvent(new Event('input'));
                            }
                        });
                    }

                    async function fetchProductVariants(productId, productName) {
                        try {
                            const response = await fetch(`/customer/products/${productId}/variants`);
                            const data = await response.json();
                            if (data.success && data.variants && data.variants.length > 0) {
                                return data;
                            } else {
                                alert('No items available for this product.');
                                return null;
                            }
                        } catch (error) {
                            console.error('Error fetching variants:', error);
                            alert('Error loading product items. Please try again.');
                            return null;
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

                            const data = await fetchProductVariants(productId, productName);
                            this.innerHTML = originalText;
                            this.disabled = false;

                            if (!data || !data.variants || data.variants.length === 0) return;

                            const variants = data.variants;
                            const productInfo = data.product_info || {};

                            // Set product name
                            document.getElementById('itemProductName').innerText = productName;

                            // Set product image
                            if (productInfo.image) {
                                modalProductImage.src = productInfo.image;
                                modalProductImage.onload = function() {
                                    modalProductImage.classList.add('loaded');
                                    modalImagePlaceholder.style.display = 'none';
                                };
                                modalProductImage.onerror = function() {
                                    modalProductImage.classList.remove('loaded');
                                    modalImagePlaceholder.style.display = 'flex';
                                };
                            } else {
                                modalProductImage.classList.remove('loaded');
                                modalImagePlaceholder.style.display = 'flex';
                            }

                            // Set product description
                            if (productInfo.description) {
                                modalProductDescription.innerHTML = productInfo.description;
                                modalProductDescription.style.display = 'block';
                            } else {
                                modalProductDescription.style.display = 'none';
                            }

                            // Set product details (brand, category, nicotine, etc.)
                            let detailsHtml = '';

                            if (productInfo.brand) {
                                detailsHtml += `<span class="detail-badge"><i class="bi bi-award"></i> ${productInfo.brand}</span>`;
                            }

                            if (productInfo.category) {
                                detailsHtml += `<span class="detail-badge"><i class="bi bi-tag"></i> ${productInfo.category}</span>`;
                            }

                            if (productInfo.nicotine_strength) {
                                detailsHtml += `<span class="detail-badge"><i class="bi bi-droplet"></i> ${productInfo.nicotine_strength}</span>`;
                            }

                            if (productInfo.puff_count) {
                                detailsHtml += `<span class="detail-badge"><i class="bi bi-cloud"></i> ${parseInt(productInfo.puff_count).toLocaleString()} puffs</span>`;
                            }

                            if (productInfo.battery_capacity) {
                                detailsHtml += `<span class="detail-badge"><i class="bi bi-battery-full"></i> ${productInfo.battery_capacity}mAh</span>`;
                            }

                            if (productInfo.liquid_capacity) {
                                detailsHtml += `<span class="detail-badge"><i class="bi bi-cup-straw"></i> ${productInfo.liquid_capacity}ml</span>`;
                            }

                            if (productInfo.type) {
                                const typeFormatted = productInfo.type.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                detailsHtml += `<span class="detail-badge"><i class="bi bi-grid"></i> ${typeFormatted}</span>`;
                            }

                            modalProductDetails.innerHTML = detailsHtml;

                            // Populate variants
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

                            // Reset quantity
                            itemQuantity.value = 1;
                            itemQuantity.disabled = true;
                            itemStockInfo.innerText = '';
                            itemPriceDisplay.innerText = '';
                            fulfilledBranchDisplay.innerText = '';

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
                            itemPriceDisplay.innerText = '₱' + price.toFixed(2);

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