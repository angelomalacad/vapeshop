@extends('layouts.customer')

@section('content')
    <div class="container cart-container">
        <!-- Header with Title and Continue Shopping Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 cart-header">
            <h2 class="cart-title"><i class="bi bi-cart"></i> Shopping Cart</h2>
            <a href="{{ route('customer.products.index') }}"
                class="btn btn-outline-secondary rounded-pill continue-shopping-btn">
                <i class="bi bi-arrow-left"></i> <span class="d-none d-sm-inline">Continue Shopping</span><span
                    class="d-sm-none">Back</span>
            </a>
        </div>

        @if (count($items) > 0)
            <div class="card shadow-sm border-0 cart-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <form method="POST" action="{{ route('customer.cart.checkout-selected') }}"
                            id="checkoutSelectedForm">
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
                                                    <div class="bg-light rounded cart-product-img"
                                                        style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                        @if (isset($item['product_image']) && $item['product_image'])
                                                            <img src="{{ $item['product_image'] }}"
                                                                alt="{{ $item['product_name'] }}"
                                                                style="width: 100%; height: 100%; object-fit: cover; object-position: center; display: block;"
                                                                onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div
                                                                style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8;">
                                                                <i class="bi bi-box-seam" style="font-size: 1.5rem;"></i>
                                                            </div>
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
                <!-- ✅ DESKTOP ONLY FOOTER: Action buttons shown here on desktop -->
                <div class="card-footer bg-white border-0 py-3 cart-footer cart-footer-desktop">
                    <div class="row align-items-center g-3">
                        <!-- Left Side: Empty (Button moved to Header) -->
                        <div class="col-md-6 d-none d-md-block">
                            <!-- Empty -->
                        </div>

                        <!-- Right Side: Action Buttons -->
                        <div class="col-md-6 col-12 text-md-end">
                            <div class="d-flex flex-wrap justify-content-md-end align-items-center gap-3 cart-actions">
                                <h4 class="mb-0 selected-total-label">Selected Total: <span id="selectedTotal"
                                        class="text-danger">₱0.00</span>
                                </h4>

                                <!-- Checkout Selected -->
                                <button type="submit" form="checkoutSelectedForm" id="checkoutSelectedBtn"
                                    class="btn btn-primary rounded-pill px-4 checkout-selected-btn" style="display: none;">
                                    Checkout Selected <i class="bi bi-arrow-right"></i>
                                </button>

                                <!-- Checkout All -->
                                <a href="{{ route('customer.checkout.index') }}"
                                    class="btn btn-success rounded-pill px-4 checkout-all-btn">
                                    Checkout All <i class="bi bi-cart-check"></i>
                                </a>

                                <button type="button" id="clearCartBtn"
                                    class="btn btn-outline-danger rounded-pill clear-cart-btn" onclick="confirmClearCart()">
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

            <!-- ✅ MOBILE ONLY FOOTER: Action buttons moved to the bottom (below summary) -->
            <div class="cart-footer cart-footer-mobile">
                <div class="cart-actions-mobile">
                    <h4 class="mb-0 selected-total-label-mobile">Selected Total: <span id="selectedTotalMobile"
                            class="text-danger">₱0.00</span>
                    </h4>

                    <!-- Checkout Selected -->
                    <button type="submit" form="checkoutSelectedForm" id="checkoutSelectedBtnMobile"
                        class="btn btn-primary rounded-pill checkout-selected-btn-mobile" style="display: none;">
                        Checkout Selected <i class="bi bi-arrow-right"></i>
                    </button>

                    <!-- Checkout All -->
                    <a href="{{ route('customer.checkout.index') }}"
                        class="btn btn-success rounded-pill checkout-all-btn-mobile">
                        Checkout All <i class="bi bi-cart-check"></i>
                    </a>

                    <button type="button" id="clearCartBtnMobile"
                        class="btn btn-outline-danger rounded-pill clear-cart-btn-mobile" onclick="confirmClearCart()">
                        <i class="bi bi-trash3"></i> Clear Cart
                    </button>
                </div>
            </div>

            <!-- Delivery Information Note -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Delivery Information:</strong> Please ensure your address and contact details are correct before
                proceeding to checkout.
            </div>
        @else
            <div class="text-center py-5 bg-white rounded-4 shadow-sm empty-cart">
                <i class="bi bi-cart-x display-1 text-muted"></i>
                <h3 class="mt-3">Your cart is empty</h3>
                <p class="text-muted">Looks like you haven't added any items yet.</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-shop"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>

    <!-- ✅ NEW: Clear Cart Confirmation Modal (replaces browser confirm) -->
    <div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header" style="border-bottom: 1px solid #eef2f6; padding: 1.25rem 1.5rem;">
                    <h5 class="modal-title fw-bold" id="clearCartModalLabel" style="color: #dc3545;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Clear Cart
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <p class="mb-0">Are you sure you want to clear your entire cart?</p>
                    <p class="text-muted small mt-2 mb-0">This will remove all items from your cart. This action cannot be
                        undone.</p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" id="confirmClearCartBtn">
                        <i class="bi bi-trash3 me-1"></i>Yes, Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ NEW: Remove Specific Item Confirmation Modal -->
    <div class="modal fade" id="removeItemModal" tabindex="-1" aria-labelledby="removeItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header" style="border-bottom: 1px solid #eef2f6; padding: 1.25rem 1.5rem;">
                    <h5 class="modal-title fw-bold" id="removeItemModalLabel" style="color: #dc3545;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Remove Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <p class="mb-0">Are you sure you want to remove <strong id="removeItemName"></strong> from your
                        cart?</p>
                    <p class="text-muted small mt-2 mb-0">This will remove the item from your cart. This action cannot be
                        undone.</p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding: 1rem 1.5rem;">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill px-4" id="confirmRemoveItemBtn">
                        <i class="bi bi-trash3 me-1"></i>Yes, Remove
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ===== BASE STYLES ===== */
        .cart-table tbody tr {
            vertical-align: middle;
        }

        .cart-table td {
            padding: 1rem 0.75rem;
        }

        /* ✅ Base image wrapper — clean display everywhere */
        .cart-product-img {
            padding: 0 !important;
            overflow: hidden;
            background: #f1f5f9;
        }

        .cart-product-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
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

        .cart-table .form-check-input {
            width: 20px;
            height: 20px;
            cursor: pointer;
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

        /* ✅ NEW: Hide mobile footer on desktop, show desktop footer on desktop */
        .cart-footer-mobile {
            display: none;
        }

        /* ===== MOBILE STYLES ===== */
        @media (max-width: 767.98px) {
            .cart-container {
                padding-left: 14px;
                padding-right: 14px;
            }

            /* Header */
            .cart-header {
                margin-bottom: 1rem !important;
                gap: 0.75rem;
            }

            .cart-title {
                font-size: 1.15rem;
                margin-bottom: 0;
            }

            .cart-title i {
                color: #0d6efd;
            }

            .continue-shopping-btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.78rem;
                white-space: nowrap;
            }

            /* Card */
            .cart-card {
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04) !important;
            }

            /* ✅ Hide the desktop footer on mobile */
            .cart-footer-desktop {
                display: none !important;
            }

            /* ✅ Show the mobile footer on mobile */
            .cart-footer-mobile {
                display: block;
                margin-top: 1rem;
                padding: 1rem;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            }

            .cart-actions-mobile {
                display: flex;
                flex-direction: column;
                gap: 0.65rem;
            }

            .selected-total-label-mobile {
                font-size: 1rem;
                font-weight: 600;
                width: 100%;
                text-align: center;
                margin-bottom: 0.25rem !important;
                color: #1a1a2e;
            }

            .selected-total-label-mobile span {
                font-size: 1.15rem;
            }

            .checkout-selected-btn-mobile,
            .checkout-all-btn-mobile,
            .clear-cart-btn-mobile {
                width: 100%;
                padding: 0.7rem 1rem;
                font-size: 0.88rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
            }

            .checkout-selected-btn-mobile:active,
            .checkout-all-btn-mobile:active,
            .clear-cart-btn-mobile:active {
                transform: scale(0.98);
            }

            /* Hide table header */
            .cart-table thead {
                display: none;
            }

            /* Each row becomes a card */
            .cart-table tbody tr {
                display: block;
                padding: 0.85rem 1rem;
                margin-bottom: 0;
                border-bottom: 1px solid #eef2f6;
                position: relative;
                transition: background 0.2s ease;
            }

            .cart-table tbody tr:last-child {
                border-bottom: none;
            }

            .cart-table tbody tr:active {
                background: #f8fafc;
            }

            .cart-table tbody tr.selected-row {
                background: rgba(13, 110, 253, 0.05);
                border-left: 3px solid #0d6efd;
                padding-left: calc(1rem - 3px);
            }

            /* Cells */
            .cart-table td {
                display: block;
                padding: 0.3rem 0;
                border: none;
                text-align: left !important;
                font-size: 0.82rem;
                color: #475569;
            }

            /* Remove default data-label pseudo for cleaner look */
            .cart-table td:before {
                content: none;
            }

            /* Checkbox cell - top of card */
            .cart-table td[data-label="Select"] {
                position: absolute;
                top: 0.85rem;
                right: 1rem;
                padding: 0;
                width: auto;
                z-index: 2;
            }

            .cart-table td[data-label="Select"] .form-check-input {
                width: 22px;
                height: 22px;
                cursor: pointer;
                border: 2px solid #cbd5e1;
                transition: all 0.2s ease;
            }

            .cart-table td[data-label="Select"] .form-check-input:checked {
                border-color: #0d6efd;
                transform: scale(1.05);
            }

            /* Product cell */
            .cart-table td[data-label="Product"] {
                padding-bottom: 0.6rem;
                padding-right: 3.5rem;
                border-bottom: 1px solid #f1f5f9;
                margin-bottom: 0.5rem;
            }

            .cart-table td[data-label="Product"] strong {
                font-size: 0.92rem;
                color: #1a1a2e;
                display: block;
                line-height: 1.35;
                margin-bottom: 0.2rem;
            }

            /* ✅ Mobile image wrapper — perfect fit, no clipping */
            .cart-table td[data-label="Product"] .cart-product-img {
                width: 56px !important;
                height: 56px !important;
                border-radius: 10px !important;
                flex-shrink: 0;
                padding: 0 !important;
                overflow: hidden;
                background: #f1f5f9 !important;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 1px solid #eef2f6;
            }

            /* ✅ Image fills the entire box */
            .cart-table td[data-label="Product"] .cart-product-img img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover;
                object-position: center;
                display: block;
            }

            /* ✅ Fallback icon sizing */
            .cart-table td[data-label="Product"] .cart-product-img i {
                font-size: 1.5rem !important;
                color: #94a3b8;
            }

            .cart-table td[data-label="Product"] .d-flex {
                gap: 0.7rem !important;
            }

            .cart-table td[data-label="Product"] small {
                font-size: 0.7rem;
            }

            /* Info cells (Flavor, Price, Quantity, Total) - inline style */
            .cart-table td[data-label="Flavor"],
            .cart-table td[data-label="Price"],
            .cart-table td[data-label="Quantity"],
            .cart-table td[data-label="Total"] {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.4rem 0;
                font-size: 0.82rem;
            }

            .cart-table td[data-label="Flavor"]::before,
            .cart-table td[data-label="Price"]::before,
            .cart-table td[data-label="Quantity"]::before,
            .cart-table td[data-label="Total"]::before {
                content: attr(data-label);
                font-weight: 600;
                color: #64748b;
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .cart-table td[data-label="Total"] strong {
                color: #e74c3c;
                font-size: 0.95rem;
            }

            /* Quantity input mobile */
            .cart-table td[data-label="Quantity"] .d-flex {
                gap: 0.4rem !important;
            }

            .cart-table .quantity-input {
                width: 65px !important;
                height: 36px;
                font-size: 0.85rem;
                font-weight: 600;
                border-radius: 10px;
                padding: 0.25rem;
            }

            .cart-table .quantity-feedback small {
                font-size: 0.68rem;
            }

            /* Action cell - absolute position (top-right, below the checkbox) */
            .cart-table td[data-label="Action"] {
                position: absolute;
                top: 2.85rem;
                right: 1rem;
                padding: 0;
                width: auto;
                z-index: 2;
            }

            .cart-table .remove-item-btn {
                width: 34px;
                height: 34px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                border: 1.5px solid #fecaca;
                transition: all 0.2s ease;
            }

            .cart-table .remove-item-btn:active {
                background: #fee2e2;
                transform: scale(0.94);
            }

            /* Summary Card */
            #selectedSummaryCard {
                border-radius: 16px;
                margin-top: 1rem !important;
            }

            #selectedSummaryCard .card-header {
                padding: 0.85rem 1rem;
                font-size: 0.88rem;
                border-radius: 16px 16px 0 0;
            }

            #selectedSummaryCard .card-body {
                padding: 1rem;
            }

            #selectedSummaryCard .row {
                gap: 0;
            }

            #selectedSummaryCard .col-md-6 {
                text-align: left !important;
            }

            #selectedSummaryCard .col-md-6.text-end {
                text-align: left !important;
                margin-top: 0.5rem;
            }

            #selectedSummaryCard hr {
                margin: 0.4rem 0;
            }

            #selectedSummaryCard h5 {
                font-size: 1rem;
            }

            #selectedSummaryCard p {
                font-size: 0.85rem;
                margin-bottom: 0.25rem !important;
            }

            #selectedSummaryCard .alert {
                font-size: 0.72rem;
                padding: 0.6rem 0.75rem;
                border-radius: 10px;
            }

            /* Info alert */
            .cart-container>.alert-info {
                font-size: 0.78rem;
                padding: 0.7rem 0.85rem;
                border-radius: 12px;
                margin-top: 0.75rem;
            }

            /* Empty cart */
            .empty-cart {
                padding: 3rem 1.25rem !important;
                border-radius: 16px !important;
            }

            .empty-cart i.display-1 {
                font-size: 3rem !important;
            }

            .empty-cart h3 {
                font-size: 1.15rem;
            }

            .empty-cart p {
                font-size: 0.85rem;
            }

            .empty-cart .btn {
                padding: 0.6rem 1.35rem;
                font-size: 0.85rem;
            }
        }

        /* Extra small devices */
        @media (max-width: 380px) {
            .cart-title {
                font-size: 1.05rem;
            }

            .continue-shopping-btn {
                font-size: 0.72rem;
                padding: 0.35rem 0.65rem;
            }

            .cart-table td[data-label="Product"] strong {
                font-size: 0.85rem;
            }

            /* ✅ Smaller image wrapper for tiny phones */
            .cart-table td[data-label="Product"] .cart-product-img {
                width: 48px !important;
                height: 48px !important;
                padding: 0 !important;
                border-radius: 8px !important;
            }

            .selected-total-label-mobile {
                font-size: 0.92rem;
            }

            .selected-total-label-mobile span {
                font-size: 1.05rem;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .cart-table td {
                padding: 0.85rem 0.6rem;
                font-size: 0.85rem;
            }

            .cart-table th {
                font-size: 0.75rem;
                padding: 0.6rem;
            }

            .cart-product-img {
                width: 60px !important;
                height: 60px !important;
                padding: 0 !important;
                border-radius: 10px !important;
            }
        }

        /* Touch device: remove hover transforms */
        @media (hover: none) {
            .remove-item-btn:hover {
                background: transparent;
            }
        }
    </style>

    @push('scripts')
        <script>
            // Select All functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const checkoutSelectedBtn = document.getElementById('checkoutSelectedBtn');
            const checkoutSelectedBtnMobile = document.getElementById('checkoutSelectedBtnMobile');
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

                // Update display — desktop
                document.getElementById('selectedTotal').textContent = '₱' + selectedGrandTotal.toFixed(2);
                // ✅ Update display — mobile
                const selectedTotalMobileEl = document.getElementById('selectedTotalMobile');
                if (selectedTotalMobileEl) {
                    selectedTotalMobileEl.textContent = '₱' + selectedGrandTotal.toFixed(2);
                }

                document.getElementById('selectedSubtotal').textContent = '₱' + selectedSubtotal.toFixed(2);
                document.getElementById('selectedGrandTotal').textContent = '₱' + selectedGrandTotal.toFixed(2);

                // Show/hide checkout selected button based on selection (desktop)
                if (selectedCount > 0) {
                    checkoutSelectedBtn.style.display = 'inline-flex';
                } else {
                    checkoutSelectedBtn.style.display = 'none';
                }

                // ✅ Show/hide checkout selected button based on selection (mobile)
                if (checkoutSelectedBtnMobile) {
                    if (selectedCount > 0) {
                        checkoutSelectedBtnMobile.style.display = 'flex';
                    } else {
                        checkoutSelectedBtnMobile.style.display = 'none';
                    }
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

            // ✅ Remove item via AJAX (NO PAGE REFRESH) — now uses a modal
            document.querySelectorAll('.remove-item-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var inventoryId = this.dataset.inventoryId;
                    var productName = this.dataset.productName;

                    // Set the product name in the modal
                    document.getElementById('removeItemName').textContent = productName;

                    // Store the inventory ID on the confirm button
                    var confirmBtn = document.getElementById('confirmRemoveItemBtn');
                    confirmBtn.dataset.inventoryId = inventoryId;
                    confirmBtn.dataset.productName = productName;

                    // Show the modal
                    var modalElement = document.getElementById('removeItemModal');
                    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modal.show();
                });
            });

            // ✅ Handle the "Yes, Remove" button in the remove item modal
            document.addEventListener('DOMContentLoaded', function() {
                var confirmRemoveBtn = document.getElementById('confirmRemoveItemBtn');
                if (confirmRemoveBtn) {
                    confirmRemoveBtn.addEventListener('click', async function() {
                        var inventoryId = this.dataset.inventoryId;
                        var productName = this.dataset.productName;
                        var row = document.querySelector('tr:has(.remove-item-btn[data-inventory-id="' +
                            inventoryId + '"])');

                        if (!row) return;

                        var modalElement = document.getElementById('removeItemModal');
                        var modal = bootstrap.Modal.getOrCreateInstance(modalElement);

                        var originalBtnHtml = this.innerHTML;
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
                                modal.hide();
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
                                this.innerHTML = originalBtnHtml;
                                this.disabled = false;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error removing item');
                            this.innerHTML = originalBtnHtml;
                            this.disabled = false;
                        }
                    });
                }
            });

            // ✅ Clear cart — now opens a Bootstrap modal instead of browser confirm()
            window.confirmClearCart = function() {
                var modalElement = document.getElementById('clearCartModal');
                var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                modal.show();
            };

            // ✅ Handle the modal's "Yes, Clear Cart" button
            document.addEventListener('DOMContentLoaded', function() {
                var confirmBtn = document.getElementById('confirmClearCartBtn');
                if (confirmBtn) {
                    confirmBtn.addEventListener('click', async function() {
                        var modalElement = document.getElementById('clearCartModal');
                        var modal = bootstrap.Modal.getOrCreateInstance(modalElement);

                        var originalBtnHtml = this.innerHTML;
                        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
                        this.disabled = true;

                        try {
                            var response = await fetch('{{ route('customer.cart.clear') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            });

                            var data = await response.json();

                            if (data.success) {
                                modal.hide();
                                location.reload();
                            } else {
                                alert(data.message || 'Error clearing cart');
                                this.innerHTML = originalBtnHtml;
                                this.disabled = false;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error clearing cart');
                            this.innerHTML = originalBtnHtml;
                            this.disabled = false;
                        }
                    });
                }
            });

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
