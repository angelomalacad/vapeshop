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

        /* ✅ ADDED: Out of Stock styling */
        .pos-product-card.out-of-stock {
            opacity: 0.6;
            cursor: not-allowed;
            border-color: #dc2626 !important;
        }

        .pos-product-card.out-of-stock:hover {
            transform: none;
            box-shadow: none;
            border-color: #dc2626 !important;
        }

        .pos-product-card.out-of-stock .product-image,
        .pos-product-card.out-of-stock h6,
        .pos-product-card.out-of-stock small {
            filter: grayscale(100%);
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

        /* Modern Minimalist Modal Styles */
        .admin-modal-container {
            padding: 1.5rem;
            max-height: 85vh;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .admin-modal-container::-webkit-scrollbar {
            width: 6px;
        }

        .admin-modal-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .admin-modal-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Modal Header */
        .modal-header-minimal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eef2f6;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0;
        }

        .modal-title i {
            color: #3b82f6;
            margin-right: 0.5rem;
        }

        /* Cards */
        .info-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #eef2f6;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .card-header-minimal {
            padding: 0.875rem 1.25rem;
            background: white;
            border-bottom: 1px solid #eef2f6;
        }

        .card-header-minimal h6 {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0;
        }

        .card-header-minimal h6 i {
            margin-right: 0.5rem;
            color: #3b82f6;
        }

        .card-body-minimal {
            padding: 1rem 1.25rem;
        }

        /* Info Rows */
        .info-row {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .info-label {
            width: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .info-value {
            flex: 1;
            font-size: 0.8rem;
            color: #1a1a2e;
            font-weight: 500;
        }

        .info-value .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.65rem;
        }

        /* Form Styles */
        .form-label-minimal {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .form-control-minimal,
        .form-select-minimal {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            transition: all 0.2s;
            width: 100%;
        }

        .form-control-minimal:focus,
        .form-select-minimal:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Buttons */
        .btn-update {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .btn-secondary-minimal {
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary-minimal:hover {
            background: #e2e8f0;
        }

        /* Alert Styles */
        .alert-minimal {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.75rem;
            margin-bottom: 1rem;
        }

        .alert-danger-minimal {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #dc2626;
        }

        .alert-success-minimal {
            background: #ecfdf5;
            border: 1px solid #d1fae5;
            color: #059669;
        }

        .alert-info-minimal {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            color: #2563eb;
        }

        /* Gap utility */
        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        /* Quantity Modal Styles */
        .quantity-modal .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .quantity-modal .modal-header {
            border-bottom: 1px solid #eef2f6;
            padding: 1.25rem 1.5rem;
        }

        .quantity-modal .modal-header .modal-title {
            font-weight: 600;
            color: #1a1a2e;
        }

        .quantity-modal .modal-header .modal-title i {
            color: #3b82f6;
        }

        .quantity-modal .modal-body {
            padding: 1.5rem;
        }

        .product-info-card {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .product-info-card .product-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            background: white;
            padding: 8px;
            border: 1px solid #eef2f6;
            flex-shrink: 0;
        }

        .product-info-card .product-image-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: #eef2f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .product-info-card .product-details {
            flex: 1;
            min-width: 0;
        }

        .product-info-card .product-details h6 {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0.25rem;
        }

        .product-info-card .product-details .product-flavor {
            font-size: 0.8rem;
            color: #64748b;
        }

        .product-info-card .product-details .product-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #3b82f6;
            margin-top: 0.25rem;
        }

        .product-info-card .product-details .stock-info {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 0.25rem;
        }

        .stock-info .badge {
            font-size: 0.65rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .quantity-control .btn-qty {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #1a1a2e;
            font-size: 1.2rem;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-control .btn-qty:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .quantity-control .btn-qty:active {
            transform: scale(0.95);
        }

        .quantity-control .btn-qty:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .quantity-control .qty-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            min-width: 60px;
            text-align: center;
        }

        .quantity-control .qty-display input {
            width: 80px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.25rem 0.5rem;
        }

        .quantity-control .qty-display input:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .quantity-control .qty-display input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .quantity-control .qty-display input[type=number] {
            -moz-appearance: textfield;
        }

        .quantity-modal .modal-footer {
            border-top: 1px solid #eef2f6;
            padding: 1rem 1.5rem;
        }

        .btn-add-cart {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add-cart:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-add-cart:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-add-cart i {
            margin-right: 0.5rem;
        }

        .btn-secondary-cancel {
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary-cancel:hover {
            background: #e2e8f0;
        }

        /* GCash Upload Styles */
        .gcash-upload-area {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #f8f9fa;
        }

        .gcash-upload-area:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .gcash-upload-area.dragover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .gcash-upload-area .upload-icon {
            font-size: 2.5rem;
            color: #94a3b8;
            margin-bottom: 0.5rem;
        }

        .gcash-upload-area .upload-text {
            font-size: 0.85rem;
            color: #64748b;
        }

        .gcash-upload-area .upload-text strong {
            color: #3b82f6;
        }

        .gcash-preview {
            position: relative;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .gcash-preview img {
            max-height: 150px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .gcash-preview .remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gcash-preview .remove-image:hover {
            transform: scale(1.1);
            background: #dc2626;
        }

        .file-input-hidden {
            display: none;
        }

        .payment-proof-section {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #eef2f6;
        }

        .payment-proof-section .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        /* Camera Styles */
        .camera-container {
            position: relative;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            min-height: 200px;
        }

        .camera-container video {
            width: 100%;
            max-height: 300px;
            background: #000;
            object-fit: cover;
        }

        .camera-placeholder {
            padding: 2rem;
            text-align: center;
            color: #fff;
            background: #1a1a2e;
        }

        .camera-placeholder i {
            font-size: 3rem;
            display: block;
            margin-bottom: 1rem;
        }

        .camera-controls {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 0.5rem;
        }

        .camera-controls .btn {
            padding: 0.4rem 1.2rem;
            font-size: 0.85rem;
        }

        /* Toast Notification */
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

        /* Checkout Modal Styles */
        .checkout-modal .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .checkout-modal .modal-header {
            border-bottom: 1px solid #eef2f6;
            padding: 1.25rem 1.5rem;
            background: white !important;
        }

        .checkout-modal .modal-header .modal-title {
            font-weight: 600;
            color: #1a1a2e;
        }

        .checkout-modal .modal-header .modal-title i {
            color: #3b82f6;
        }

        .checkout-modal .modal-header .btn-close {
            color: #64748b;
        }

        .checkout-modal .modal-body {
            padding: 1.5rem;
            background: #f8f9fa;
        }

        .checkout-modal .modal-footer {
            border-top: 1px solid #eef2f6;
            padding: 1rem 1.5rem;
            background: white;
        }

        .checkout-modal .form-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .checkout-modal .form-control,
        .checkout-modal .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .checkout-modal .form-control:focus,
        .checkout-modal .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .checkout-modal .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1px solid #e2e8f0;
            background: #f8f9fa;
            font-size: 0.8rem;
        }

        .checkout-modal .btn-primary {
            background: #3b82f6;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .checkout-modal .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .checkout-modal .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .checkout-modal .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-outline-primary.active-option {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .btn-outline-success.active-option {
            background: #22c55e;
            color: white;
            border-color: #22c55e;
        }

        @media (max-width: 768px) {
            .checkout-modal .modal-body {
                padding: 1rem;
            }

            .checkout-modal .modal-header,
            .checkout-modal .modal-footer {
                padding: 1rem;
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
                                            // ✅ SKIP if item is archived or disposed
                                            if ($item->is_archived || $item->is_disposed) {
                                                continue;
                                            }

                                            $product = $item->product;
                                            $available = $item->available_quantity;
                                            $imageUrl = $product->image_url ?? ($product->image ?? '');
                                        @endphp
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <div class="card pos-product-card h-100 {{ $available <= 0 ? 'out-of-stock' : '' }}"
                                                data-inventory-id="{{ $item->id }}"
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-flavor-name="{{ $item->flavor->name ?? '' }}"
                                                data-flavor-id="{{ $item->flavor_id }}" data-available="{{ $available }}"
                                                data-image="{{ $imageUrl }}">
                                                <div class="card-body text-center p-3">
                                                    <div class="product-image mb-2"
                                                        style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                                        @if ($imageUrl)
                                                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}"
                                                                style="max-height: 70px; max-width: 100%; object-fit: contain;"
                                                                onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'bi bi-box-seam text-muted\' style=\'font-size: 2rem;\'></i>'">
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
                                                        @if ($available <= 0)
                                                            <span class="badge bg-danger ms-1">Out of Stock</span>
                                                        @elseif ($available <= 5)
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
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fs-5 fw-bold">Total:</span>
                                <span class="fs-5 fw-bold text-primary"
                                    id="cartTotal">₱{{ number_format($total, 2) }}</span>
                            </div>

                            <!-- BUTTONS -->
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

    <!-- Quantity Modal -->
    <div class="modal fade quantity-modal" id="quantityModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> Select Quantity
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Product Info -->
                    <div class="product-info-card">
                        <div id="modalImageContainer">
                            <img id="modalProductImage" src="" alt="Product" class="product-image"
                                style="display:none;">
                            <div id="modalImagePlaceholder" class="product-image-placeholder">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <div class="product-details">
                            <h6 id="modalProductName">Product Name</h6>
                            <div class="product-flavor" id="modalProductFlavor">Flavor</div>
                            <div class="product-price" id="modalProductPrice">₱0.00</div>
                            <div class="stock-info">
                                Available: <span id="modalStockAvailable">0</span> units
                                <span class="badge bg-danger ms-1" id="modalLowStock" style="display:none;">Out of
                                    Stock</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Control -->
                    <div class="quantity-control">
                        <button class="btn-qty" id="qtyDecrease" disabled>
                            <i class="bi bi-dash-lg"></i>
                        </button>
                        <div class="qty-display">
                            <input type="number" id="qtyInput" value="1" min="1">
                        </div>
                        <button class="btn-qty" id="qtyIncrease">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>

                    <!-- Total Price Preview -->
                    <div class="text-center">
                        <small class="text-muted">Total: </small>
                        <span class="fw-bold text-primary" id="modalTotalPrice">₱0.00</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-add-cart" id="addToCartBtn">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal - With Camera and Upload Options -->
    <div class="modal fade checkout-modal" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-credit-card"></i> Payment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="checkoutForm" action="{{ route('branch-admin.pos.checkout') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Name (Optional)</label>
                                    <input type="text" name="customer_name" class="form-control"
                                        placeholder="Walk-in Customer">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Customer Phone (Optional)</label>
                                    <input type="text" name="customer_phone" class="form-control"
                                        placeholder="09xxxxxxxxx">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="paymentMethod" class="form-select" required>
                                        <option value="cash">Cash</option>
                                        <option value="gcash">GCash</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="text" id="modalTotal" class="form-control bg-light" readonly
                                            value="{{ number_format($total, 2) }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount Paid <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="text" inputmode="decimal" name="amount_paid" id="amountPaid"
                                            class="form-control" required placeholder="0.00">
                                    </div>
                                    <!-- ✅ ADD THIS: Error Message -->
                                    <div id="amountPaidError" class="text-danger small mt-1" style="display: none;">
                                        <i class="bi bi-exclamation-circle me-1"></i> Amount paid is not enough. Please
                                        enter at least ₱{{ number_format($total, 2) }}.
                                    </div>
                                </div>
                                <div class="mb-3" id="changeDiv" style="display: none;">
                                    <label class="form-label text-success">Change</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="text" id="changeAmount" class="form-control bg-light" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- GCash Proof of Payment with Camera/Upload Options -->
                                <div id="gcashUploadSection" style="display: none;">
                                    <div class="payment-proof-section">
                                        <div class="section-title">
                                            <i class="bi bi-image me-1"></i> GCash Proof of Payment
                                        </div>

                                        <!-- Upload Options -->
                                        <div class="mb-3">
                                            <label class="form-label">Choose option:</label>
                                            <div class="d-flex gap-2">
                                                <button type="button"
                                                    class="btn btn-outline-primary btn-sm flex-fill active-option"
                                                    id="uploadOptionBtn">
                                                    <i class="bi bi-upload"></i> Upload
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm flex-fill"
                                                    id="cameraOptionBtn">
                                                    <i class="bi bi-camera"></i> Take Photo
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Upload Area -->
                                        <div id="uploadAreaContainer">
                                            <div class="gcash-upload-area" id="uploadArea">
                                                <div class="upload-icon">
                                                    <i class="bi bi-cloud-upload"></i>
                                                </div>
                                                <div class="upload-text">
                                                    Click or drag to upload proof of payment<br>
                                                    <small class="text-muted">Supported: JPG, PNG, PDF (Max 5MB)</small>
                                                </div>
                                                <input type="file" name="payment_proof" id="paymentProof"
                                                    class="file-input-hidden" accept="image/*,application/pdf">
                                            </div>
                                        </div>

                                        <!-- Camera Capture Area -->
                                        <div id="cameraAreaContainer" style="display: none;">
                                            <div class="camera-container">
                                                <video id="cameraVideo" autoplay playsinline
                                                    style="width: 100%; max-height: 300px; background: #000; display: none;"></video>
                                                <canvas id="cameraCanvas" style="display: none;"></canvas>
                                                <div id="cameraPlaceholder" class="camera-placeholder">
                                                    <i class="bi bi-camera"></i>
                                                    <p>Click "Start Camera" to take a photo</p>
                                                    <button type="button" class="btn btn-primary" id="startCameraBtn">
                                                        <i class="bi bi-play-circle"></i> Start Camera
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="camera-controls" style="display: none;" id="cameraControls">
                                                <button type="button" class="btn btn-success" id="capturePhotoBtn">
                                                    <i class="bi bi-camera"></i> Capture
                                                </button>
                                                <button type="button" class="btn btn-danger" id="stopCameraBtn">
                                                    <i class="bi bi-stop-circle"></i> Stop Camera
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Upload Preview -->
                                        <div id="uploadPreview" class="text-center" style="display: none;">
                                            <div class="gcash-preview">
                                                <img id="proofPreview" src="#" alt="Proof of Payment">
                                                <button type="button" class="remove-image" id="removeProof">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mt-2">Click X to remove</small>
                                        </div>

                                        <!-- Camera Preview -->
                                        <div id="cameraPreview" class="text-center" style="display: none;">
                                            <div class="gcash-preview">
                                                <img id="cameraPreviewImage" src="#" alt="Captured Photo">
                                                <button type="button" class="remove-image" id="removeCameraPhoto">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mt-2">Click X to retake</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes (Optional)</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Special instructions..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="completePaymentBtn">
                            <i class="bi bi-check-circle"></i> Complete Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Clear Cart Confirmation Modal -->
    <div class="modal fade" id="clearCartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden;">
                <div class="modal-header" style="border-bottom: 1px solid #eef2f6; background: #f8f9fa;">
                    <h5 class="modal-title">
                        <i class="bi bi-trash3 text-danger me-2"></i> Clear Cart
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 mb-2">Are you sure?</h5>
                    <p class="text-muted">This will remove all items from your cart. This action cannot be undone.</p>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eef2f6; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmClearCartBtn">
                        <i class="bi bi-trash me-1"></i> Yes, Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Toast Container for Notifications -->
    <div id="toastContainer"></div>
@endsection

@push('scripts')
    <script>
        // IMPORTANT: Get initial values from server as numbers
        let currentTotal = parseFloat({{ $total }}) || 0;
        let currentSubtotal = parseFloat({{ $subtotal }}) || 0;
        let currentTax = parseFloat({{ $tax }}) || 0;

        // Get button references
        const checkoutBtn = document.getElementById('checkoutBtn');
        const clearCartBtn = document.getElementById('clearCartBtn');
        const modalTotal = document.getElementById('modalTotal');

        // Quantity Modal Variables
        let selectedInventoryId = null;
        let selectedProductId = null;
        let selectedFlavorId = null;
        let maxAvailable = 0;
        let currentQty = 1;

        // ============================================
        // Camera Functionality
        // ============================================
        let cameraStream = null;
        let cameraActive = false;
        let capturedPhotoData = null;

        const cameraVideo = document.getElementById('cameraVideo');
        const cameraCanvas = document.getElementById('cameraCanvas');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');
        const cameraControls = document.getElementById('cameraControls');
        const startCameraBtn = document.getElementById('startCameraBtn');
        const capturePhotoBtn = document.getElementById('capturePhotoBtn');
        const stopCameraBtn = document.getElementById('stopCameraBtn');
        const cameraPreview = document.getElementById('cameraPreview');
        const cameraPreviewImage = document.getElementById('cameraPreviewImage');
        const removeCameraPhoto = document.getElementById('removeCameraPhoto');
        const cameraAreaContainer = document.getElementById('cameraAreaContainer');
        const uploadAreaContainer = document.getElementById('uploadAreaContainer');
        const uploadOptionBtn = document.getElementById('uploadOptionBtn');
        const cameraOptionBtn = document.getElementById('cameraOptionBtn');

        // Toggle between Upload and Camera options
        uploadOptionBtn.addEventListener('click', function() {
            this.classList.add('active-option');
            cameraOptionBtn.classList.remove('active-option');
            uploadAreaContainer.style.display = 'block';
            cameraAreaContainer.style.display = 'none';
            stopCamera();
            clearCameraPreview();
        });

        cameraOptionBtn.addEventListener('click', function() {
            this.classList.add('active-option');
            uploadOptionBtn.classList.remove('active-option');
            uploadAreaContainer.style.display = 'none';
            cameraAreaContainer.style.display = 'block';
        });

        // Start Camera
        startCameraBtn.addEventListener('click', async function() {
            try {
                if (cameraStream) {
                    stopCamera();
                }

                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment',
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    },
                    audio: false
                });

                cameraVideo.srcObject = cameraStream;
                cameraVideo.style.display = 'block';
                cameraPlaceholder.style.display = 'none';
                cameraControls.style.display = 'flex';
                cameraActive = true;

                this.textContent = 'Camera Started';
                this.disabled = true;

            } catch (err) {
                console.error('Error accessing camera:', err);
                showToast('error', 'Unable to access camera. Please check permissions and try again.');
            }
        });

        // Capture Photo
        capturePhotoBtn.addEventListener('click', function() {
            if (!cameraActive || !cameraStream) {
                showToast('error', 'Please start the camera first.');
                return;
            }

            const context = cameraCanvas.getContext('2d');
            cameraCanvas.width = cameraVideo.videoWidth || 1280;
            cameraCanvas.height = cameraVideo.videoHeight || 720;
            context.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);

            // Convert to data URL
            capturedPhotoData = cameraCanvas.toDataURL('image/jpeg', 0.9);

            // Show preview
            cameraPreviewImage.src = capturedPhotoData;
            cameraPreview.style.display = 'block';

            // Hide video and controls
            cameraVideo.style.display = 'none';
            cameraControls.style.display = 'none';

            // Stop camera
            stopCamera();

            showToast('success', 'Photo captured successfully!');
        });

        // Stop Camera
        stopCameraBtn.addEventListener('click', function() {
            stopCamera();
        });

        function stopCamera() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            cameraVideo.style.display = 'none';
            cameraVideo.srcObject = null;
            cameraActive = false;
            cameraControls.style.display = 'none';
            cameraPlaceholder.style.display = 'block';
            startCameraBtn.textContent = 'Start Camera';
            startCameraBtn.disabled = false;
        }

        // Remove Camera Photo (retake)
        removeCameraPhoto.addEventListener('click', function() {
            clearCameraPreview();
            // Show camera again
            cameraVideo.style.display = 'block';
            cameraControls.style.display = 'flex';
            cameraPreview.style.display = 'none';
            capturedPhotoData = null;
            // Restart camera
            startCameraBtn.click();
        });

        function clearCameraPreview() {
            cameraPreview.style.display = 'none';
            cameraPreviewImage.src = '#';
            capturedPhotoData = null;
        }

        // ============================================
        // GCash Upload Functionality
        // ============================================
        const paymentMethod = document.getElementById('paymentMethod');
        const gcashUploadSection = document.getElementById('gcashUploadSection');
        const uploadArea = document.getElementById('uploadArea');
        const paymentProof = document.getElementById('paymentProof');
        const uploadPreview = document.getElementById('uploadPreview');
        const proofPreview = document.getElementById('proofPreview');
        const removeProof = document.getElementById('removeProof');
        let uploadedFile = null;

        // Toggle GCash upload section
        if (paymentMethod) {
            paymentMethod.addEventListener('change', function() {
                if (this.value === 'gcash') {
                    gcashUploadSection.style.display = 'block';
                } else {
                    gcashUploadSection.style.display = 'none';
                    clearUploadedFile();
                    stopCamera();
                    clearCameraPreview();
                }
            });
        }

        // Click to upload
        if (uploadArea) {
            uploadArea.addEventListener('click', function() {
                paymentProof.click();
            });

            // Drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFile(files[0]);
                }
            });
        }

        // File input change
        if (paymentProof) {
            paymentProof.addEventListener('change', function() {
                if (this.files.length > 0) {
                    handleFile(this.files[0]);
                }
            });
        }

        function handleFile(file) {
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                showToast('error', 'File is too large. Maximum size is 5MB.');
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                showToast('error', 'Invalid file type. Please upload JPG, PNG, or PDF.');
                return;
            }

            uploadedFile = file;

            // Show preview for images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    proofPreview.src = e.target.result;
                    uploadPreview.style.display = 'block';
                    uploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                // For PDF, show a placeholder
                proofPreview.src = '{{ asset('images/pdf-icon.png') }}';
                uploadPreview.style.display = 'block';
                uploadArea.style.display = 'none';
                showToast('info', 'PDF file uploaded successfully');
            }

            // Clear camera preview if exists
            clearCameraPreview();
        }

        function clearUploadedFile() {
            uploadedFile = null;
            uploadPreview.style.display = 'none';
            uploadArea.style.display = 'block';
            if (paymentProof) paymentProof.value = '';
            if (proofPreview) proofPreview.src = '#';
        }

        if (removeProof) {
            removeProof.addEventListener('click', function() {
                clearUploadedFile();
            });
        }

        // ============================================
        // FIXED: Checkout Form Submission Handler
        // ============================================
        const checkoutForm = document.getElementById('checkoutForm');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(this);
                const method = document.getElementById('paymentMethod').value;

                // ✅ FIX: Strip commas from amount_paid before sending
                const amountPaidInput = document.getElementById('amountPaid');
                if (amountPaidInput) {
                    const rawAmount = amountPaidInput.value.replace(/,/g, '');
                    formData.set('amount_paid', rawAmount);
                }

                // Validate GCash
                if (method === 'gcash') {
                    if (capturedPhotoData) {
                        // Convert base64 to blob
                        const byteString = atob(capturedPhotoData.split(',')[1]);
                        const mimeString = capturedPhotoData.split(',')[0].split(':')[1].split(';')[0];
                        const ab = new ArrayBuffer(byteString.length);
                        const ia = new Uint8Array(ab);
                        for (let i = 0; i < byteString.length; i++) {
                            ia[i] = byteString.charCodeAt(i);
                        }
                        const blob = new Blob([ab], {
                            type: mimeString
                        });
                        const file = new File([blob], 'captured_photo_' + Date.now() + '.jpg', {
                            type: mimeString
                        });
                        formData.append('payment_proof', file);
                    } else {
                        const fileInput = document.getElementById('paymentProof');
                        if (!fileInput.files || fileInput.files.length === 0) {
                            showToast('error', 'Please upload a proof of payment or take a photo for GCash.');
                            return;
                        }
                    }
                }

                // Show loading
                const submitBtn = document.getElementById('completePaymentBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
                submitBtn.disabled = true;

                // Send request
                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.message || 'Server error');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            showToast('success', 'Payment successful!');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        } else {
                            showToast('error', data.message || 'Payment failed. Please try again.');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', error.message || 'Network error. Please check your connection.');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });
        }

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
        if (modalTotal) {
            modalTotal.value = currentTotal.toFixed(2);
        }

        // ============================================
        // AMOUNT PAID - FORMAT WITH COMMAS
        // ============================================
        const amountPaidInput = document.getElementById('amountPaid');
        const amountPaidError = document.getElementById('amountPaidError');
        const submitBtn = document.getElementById('completePaymentBtn');

        if (amountPaidInput) {
            // Format as user types with commas
            amountPaidInput.addEventListener('input', function() {
                // Get current raw value (remove commas first)
                let value = this.value.replace(/,/g, '');

                // Only keep numbers and decimal point
                value = value.replace(/[^0-9.]/g, '');

                // Split into integer and decimal parts
                let parts = value.split('.');
                let integerPart = parts[0];
                let decimalPart = parts.length > 1 ? '.' + parts[1] : '';

                // Add commas to integer part
                if (integerPart) {
                    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                // Set formatted value
                this.value = integerPart + decimalPart;

                // Check if amount is enough
                const rawValue = this.value.replace(/,/g, '');
                const amountPaid = parseFloat(rawValue) || 0;
                const change = amountPaid - currentTotal;
                const changeDiv = document.getElementById('changeDiv');
                const changeAmount = document.getElementById('changeAmount');

                if (amountPaid >= currentTotal) {
                    // ✅ Hide error and show change
                    if (amountPaidError) amountPaidError.style.display = 'none';
                    changeDiv.style.display = 'block';
                    changeAmount.value = change.toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    if (submitBtn) submitBtn.disabled = false;
                } else {
                    // ✅ Show error and hide change
                    if (amountPaidError) amountPaidError.style.display = 'block';
                    changeDiv.style.display = 'none';
                    if (submitBtn) submitBtn.disabled = true;
                }
            });
        }

        // Show Quantity Modal
        function showQuantityModal(productData) {
            selectedInventoryId = productData.inventoryId;
            selectedProductId = productData.productId;
            selectedFlavorId = productData.flavorId || null;
            maxAvailable = parseInt(productData.available) || 0;
            currentQty = 1;

            // Set product info
            document.getElementById('modalProductName').textContent = productData.name;
            document.getElementById('modalProductFlavor').textContent = productData.flavor || '';
            document.getElementById('modalProductPrice').textContent = `₱${parseFloat(productData.price).toFixed(2)}`;
            document.getElementById('modalStockAvailable').textContent = maxAvailable;

            // Set product image
            const img = document.getElementById('modalProductImage');
            const placeholder = document.getElementById('modalImagePlaceholder');

            if (productData.image && productData.image.trim() !== '') {
                img.src = productData.image;
                img.style.display = 'block';
                placeholder.style.display = 'none';
                img.onerror = function() {
                    this.style.display = 'none';
                    placeholder.style.display = 'flex';
                };
            } else {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }

            // ✅ FIXED: Show low stock or out of stock badge
            const lowStockBadge = document.getElementById('modalLowStock');
            if (lowStockBadge) {
                if (maxAvailable <= 0) {
                    lowStockBadge.textContent = 'Out of Stock';
                    lowStockBadge.className = 'badge bg-danger ms-1';
                    lowStockBadge.style.display = 'inline-block';
                } else if (maxAvailable <= 5) {
                    lowStockBadge.textContent = 'Low Stock';
                    lowStockBadge.className = 'badge bg-warning ms-1';
                    lowStockBadge.style.display = 'inline-block';
                } else {
                    lowStockBadge.style.display = 'none';
                }
            }

            // Reset quantity
            document.getElementById('qtyInput').value = 1;
            document.getElementById('qtyInput').max = maxAvailable;
            updateQuantityDisplay();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('quantityModal'));
            modal.show();
        }

        // Quantity Modal Controls
        document.getElementById('qtyInput')?.addEventListener('input', function() {
            let val = parseInt(this.value) || 1;
            if (val < 1) val = 1;
            if (val > maxAvailable) val = maxAvailable;
            currentQty = val;
            this.value = val;
            updateQuantityDisplay();
        });

        document.getElementById('qtyIncrease')?.addEventListener('click', function() {
            if (currentQty < maxAvailable) {
                currentQty++;
                document.getElementById('qtyInput').value = currentQty;
                updateQuantityDisplay();
            }
        });

        document.getElementById('qtyDecrease')?.addEventListener('click', function() {
            if (currentQty > 1) {
                currentQty--;
                document.getElementById('qtyInput').value = currentQty;
                updateQuantityDisplay();
            }
        });

        function updateQuantityDisplay() {
            const qty = currentQty;
            const priceText = document.getElementById('modalProductPrice').textContent;
            const price = parseFloat(priceText.replace('₱', '')) || 0;
            const total = qty * price;

            document.getElementById('modalTotalPrice').textContent = `₱${total.toFixed(2)}`;
            document.getElementById('qtyDecrease').disabled = qty <= 1;
            document.getElementById('qtyIncrease').disabled = qty >= maxAvailable;
            document.getElementById('addToCartBtn').disabled = qty <= 0 || qty > maxAvailable;
        }

        // Add to Cart from Modal
        document.getElementById('addToCartBtn')?.addEventListener('click', function() {
            const qty = parseInt(document.getElementById('qtyInput').value) || 1;

            if (qty > maxAvailable) {
                showToast('error', `Only ${maxAvailable} units available!`);
                return;
            }

            if (qty <= 0) {
                showToast('error', 'Quantity must be at least 1');
                return;
            }

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('quantityModal'));
            modal.hide();

            // Add to cart
            addToCart(selectedInventoryId, qty);
        });

        // Add product to cart - Modified to show modal
        function attachProductEvents() {
            document.querySelectorAll('.pos-product-card').forEach(card => {
                card.removeEventListener('click', handleProductClick);
                card.addEventListener('click', handleProductClick);
            });
        }

        function handleProductClick() {
            const productData = {
                inventoryId: this.dataset.inventoryId,
                productId: this.dataset.productId,
                name: this.dataset.productName,
                flavor: this.dataset.flavorName || '',
                flavorId: this.dataset.flavorId || null,
                price: this.dataset.productPrice,
                available: this.dataset.available,
                image: this.dataset.image || ''
            };

            showQuantityModal(productData);
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

        // Clear cart - with proper modal
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', function() {
                // Show the clear cart modal
                const clearCartModal = new bootstrap.Modal(document.getElementById('clearCartModal'));
                clearCartModal.show();
            });
        }

        // Confirm clear cart
        const confirmClearCartBtn = document.getElementById('confirmClearCartBtn');
        if (confirmClearCartBtn) {
            confirmClearCartBtn.addEventListener('click', function() {
                // Disable button while processing
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Clearing...';

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
                            // Hide modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'clearCartModal'));
                            if (modal) modal.hide();

                            showToast('success', 'Cart cleared successfully!');

                            // Reload after short delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showToast('error', data.message || 'Error clearing cart');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'Error clearing cart');
                    });
            });
        }

        function updateCartUI(data) {
            console.log('Updating cart UI with data:', data);

            // Update cart count badge
            const cartCountSpan = document.getElementById('cartCount');
            if (cartCountSpan) {
                cartCountSpan.textContent = data.cart_count || 0;
            }

            // Update subtotal
            const cartSubtotalSpan = document.getElementById('cartSubtotal');
            if (cartSubtotalSpan) {
                let subtotal = 0;
                if (typeof data.subtotal === 'string') {
                    subtotal = parseFloat(data.subtotal.replace(/,/g, '')) || 0;
                } else {
                    subtotal = parseFloat(data.subtotal) || 0;
                }
                cartSubtotalSpan.textContent = `₱${subtotal.toFixed(2)}`;
                currentSubtotal = subtotal;
            }

            // Update total
            const cartTotalSpan = document.getElementById('cartTotal');
            if (cartTotalSpan) {
                let total = 0;
                if (typeof data.total === 'string') {
                    total = parseFloat(data.total.replace(/,/g, '')) || 0;
                } else {
                    total = parseFloat(data.total) || 0;
                }
                cartTotalSpan.textContent = `₱${total.toFixed(2)}`;
                currentTotal = total;
            }

            // Update modal total
            if (modalTotal) {
                modalTotal.value = currentTotal.toFixed(2);
            }

            // Show/hide buttons
            if (checkoutBtn && clearCartBtn) {
                const cartCount = data.cart_count || 0;
                if (cartCount === 0) {
                    checkoutBtn.style.display = 'none';
                    clearCartBtn.style.display = 'none';
                } else {
                    checkoutBtn.style.display = 'block';
                    clearCartBtn.style.display = 'block';
                }
            }

            // Rebuild cart items
            rebuildCartItems(data);

            // Force update the change calculation if amount paid is already entered
            const amountPaid = document.getElementById('amountPaid');
            if (amountPaid && amountPaid.value) {
                const event = new Event('input');
                amountPaid.dispatchEvent(event);
            }
        }

        function rebuildCartItems(data) {
            const cartContainer = document.getElementById('cartItems');
            if (!cartContainer) return;

            const cartCount = data.cart_count || 0;

            if (cartCount === 0) {
                cartContainer.innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-cart display-1 text-muted"></i>
                        <p class="mt-3 text-muted">Cart is empty</p>
                    </div>
                `;
            } else {
                let html = '';
                for (const [id, item] of Object.entries(data.cart)) {
                    const price = parseFloat(item.price) || 0;
                    const quantity = parseInt(item.quantity) || 0;
                    const subtotal = price * quantity;

                    html += `
                        <div class="pos-cart-item" data-inventory-id="${item.inventory_id}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">${escapeHtml(item.product_name)}</h6>
                                    ${item.flavor_name ? `<small class="text-muted">${escapeHtml(item.flavor_name)}</small>` : ''}
                                    <div class="mt-2">
                                        <span class="text-muted">₱${price.toFixed(2)} x </span>
                                        <input type="number" class="form-control form-control-sm d-inline-block quantity-input"
                                               value="${quantity}" min="1" style="width: 60px;">
                                        <span class="fw-bold ms-2">₱${subtotal.toFixed(2)}</span>
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

            // Reattach event listeners
            attachCartItemEvents();
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showToast(type, message) {
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

        // Clean up camera when modal is closed
        document.getElementById('checkoutModal')?.addEventListener('hidden.bs.modal', function() {
            stopCamera();
            clearCameraPreview();
        });
    </script>
@endpush
