@extends('layouts.customer')

@section('content')

    <div class="container checkout-container">
        <!-- Back Button Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 checkout-header">
            <h4 class="checkout-title"><i class="bi bi-credit-card"></i> Delivery & Payment</h4>
            <a href="{{ route('customer.cart.index') }}" class="btn btn-outline-secondary rounded-pill back-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 checkout-card">
                    <div class="card-body checkout-card-body">
                        <!-- Address Selection Toggle -->
                        <div class="mb-4 address-toggle-wrapper">
                            <div class="btn-group w-100 address-toggle" role="group">
                                <input type="radio" class="btn-check" name="address_option" id="savedAddressOption"
                                    value="saved" checked>
                                <label class="btn btn-outline-primary rounded-start-pill" for="savedAddressOption">
                                    <i class="bi bi-person"></i> <span class="d-none d-sm-inline">Use My Saved
                                        Address</span><span class="d-sm-none">Saved</span>
                                </label>

                                <input type="radio" class="btn-check" name="address_option" id="newAddressOption"
                                    value="new">
                                <label class="btn btn-outline-primary rounded-end-pill" for="newAddressOption">
                                    <i class="bi bi-plus-circle"></i> <span class="d-none d-sm-inline">Use Different
                                        Address</span><span class="d-sm-none">New</span>
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2 text-center address-hint">
                                <i class="bi bi-info-circle"></i> Choose to use your saved profile address or enter a new
                                one
                            </small>
                        </div>

                        <form method="POST" action="{{ route('customer.checkout.store') }}" id="checkoutForm">
                            @csrf

                            <!-- Customer Information -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name *</label>
                                    <input type="text" name="customer_name" class="form-control"
                                        value="{{ old('customer_name', Auth::user()->name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number *</label>
                                    <input type="text" name="customer_phone" class="form-control"
                                        value="{{ old('customer_phone', Auth::user()->phone) }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Email (optional)</label>
                                <input type="email" name="customer_email" class="form-control"
                                    value="{{ old('customer_email', Auth::user()->email) }}">
                            </div>

                            <!-- Hidden delivery type - always delivery -->
                            <input type="hidden" name="delivery_type" value="delivery">

                            <!-- Saved Address Section -->
                            <div id="savedAddressSection" class="address-section">
                                <div class="alert alert-info mb-3 saved-address-alert">
                                    <i class="bi bi-house-heart me-2"></i>
                                    <strong>Your Saved Address:</strong><br>
                                    {{ Auth::user()->address ?? 'No address saved in profile' }}

                                    @if (Auth::user()->barangay)
                                        <br><strong>Barangay:</strong>
                                        @if (Auth::user()->barangay === 'Other' && Auth::user()->other_barangay)
                                            {{ Auth::user()->other_barangay }}
                                        @else
                                            {{ Auth::user()->barangay }}
                                        @endif
                                    @endif

                                    @if (Auth::user()->city || Auth::user()->province)
                                        <br>{{ Auth::user()->city ?? '' }}, {{ Auth::user()->province ?? '' }}
                                    @endif
                                    @if (Auth::user()->zip_code)
                                        <br><strong>ZIP Code:</strong> {{ Auth::user()->zip_code }}
                                    @endif
                                    @if (Auth::user()->landmark)
                                        <br><strong>Landmark:</strong> {{ Auth::user()->landmark }}
                                    @endif
                                </div>
                                <input type="hidden" name="delivery_address" id="saved_delivery_address"
                                    value="{{ Auth::user()->address }}">
                                <input type="hidden" name="city" id="saved_city" value="{{ Auth::user()->city }}">
                                <input type="hidden" name="barangay" id="saved_barangay"
                                    value="{{ Auth::user()->barangay ?? '' }}">
                                <input type="hidden" name="other_barangay" id="saved_other_barangay"
                                    value="{{ Auth::user()->other_barangay ?? '' }}">
                                <input type="hidden" name="province" id="saved_province"
                                    value="{{ Auth::user()->province }}">
                                <input type="hidden" name="zip_code" id="saved_zip_code"
                                    value="{{ Auth::user()->zip_code }}">
                                <input type="hidden" name="landmark" id="saved_landmark"
                                    value="{{ Auth::user()->landmark ?? '' }}">
                            </div>

                            <!-- New Address Section -->
                            <div id="newAddressSection" class="address-section" style="display: none;">
                                <div class="alert alert-secondary mb-3 new-address-alert">
                                    <i class="bi bi-pencil-square me-2"></i>
                                    <strong>Enter New Delivery Address:</strong>
                                </div>

                                <!-- Delivery Address Fields -->
                                <div class="mb-3">
                                    <label>Delivery Address *</label>
                                    <input type="text" name="new_delivery_address" class="form-control"
                                        placeholder="House/Unit #, Street, Subdivision">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Province</label>
                                        <!-- Displayed as static text, not editable -->
                                        <div class="form-control bg-light text-muted" style="cursor: default;">Laguna
                                        </div>
                                        <!-- Sent to the controller (it validates new_province when using a new address) -->
                                        <input type="hidden" name="new_province" value="Laguna">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>City/Municipalities *</label>
                                        <select class="form-select" name="new_city" id="new_city">
                                            <option value="">Select City/Municipalities</option>
                                            <option value="Alaminos">Alaminos</option>
                                            <option value="Bay">Bay</option>
                                            <option value="Biñan">Biñan</option>
                                            <option value="Cabuyao">Cabuyao</option>
                                            <option value="Calamba City">Calamba City</option>
                                            <option value="Calauan">Calauan</option>
                                            <option value="Cavinti">Cavinti</option>
                                            <option value="Famy">Famy</option>
                                            <option value="Kalayaan">Kalayaan</option>
                                            <option value="Liliw">Liliw</option>
                                            <option value="Los Baños">Los Baños</option>
                                            <option value="Luisiana">Luisiana</option>
                                            <option value="Lumban">Lumban</option>
                                            <option value="Mabitac">Mabitac</option>
                                            <option value="Magdalena">Magdalena</option>
                                            <option value="Majayjay">Majayjay</option>
                                            <option value="Nagcarlan">Nagcarlan</option>
                                            <option value="Paete">Paete</option>
                                            <option value="Pagsanjan">Pagsanjan</option>
                                            <option value="Pakil">Pakil</option>
                                            <option value="Pangil">Pangil</option>
                                            <option value="Pila">Pila</option>
                                            <option value="Rizal">Rizal</option>
                                            <option value="San Pablo">San Pablo</option>
                                            <option value="San Pedro">San Pedro</option>
                                            <option value="Santa Cruz">Santa Cruz</option>
                                            <option value="Santa Maria">Santa Maria</option>
                                            <option value="Santa Rosa">Santa Rosa</option>
                                            <option value="Siniloan">Siniloan</option>
                                            <option value="Victoria">Victoria</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Barangay *</label>
                                        <select class="form-select" name="new_barangay" id="new_barangay">
                                            <option value="">Select Barangay</option>
                                            @php
                                                $calambaBarangays = [
                                                    'Canlubang',
                                                    'Majada In',
                                                    'Sirang Lupa',
                                                    'Burol',
                                                    'Palo alto',
                                                    'Laguerta',
                                                    'Paciano Rizal',
                                                    'Real',
                                                    'Halang',
                                                    'Banadero',
                                                    'Lingga',
                                                    'Parian',
                                                    'Barangay 1',
                                                    'Barangay 2',
                                                    'Barangay 3',
                                                    'Barangay 4',
                                                    'Barangay 5',
                                                    'Barangay 6',
                                                    'Banlic',
                                                    'Barangay 7',
                                                    'Bucal',
                                                    'Pansol',
                                                    'Lecheria',
                                                    'Looc',
                                                    'Uwisan',
                                                    'Mayapa',
                                                    'Turbina',
                                                    'Batino',
                                                    'Lawa',
                                                    'Bubuyan',
                                                    'Hornalan',
                                                    'Sampiruhan',
                                                    'Milagrosa',
                                                    'Palingon',
                                                    'Saimsim',
                                                    'San Cristobal',
                                                    'Barandal',
                                                    'Makiling',
                                                    'La Mesa',
                                                    'Maunong',
                                                    'Pittland',
                                                    'Masili',
                                                    'Sucol',
                                                    'Ulango',
                                                    'Majada Labas',
                                                    'Kay-Anlog',
                                                    'Punta',
                                                    'Bagong Kalsada',
                                                    'Prinza',
                                                    'Mabato',
                                                    'Puting Lupa',
                                                    'Bunggo',
                                                    'Camaligan',
                                                    'Mabacan',
                                                    'San Jose',
                                                    'Majada Out',
                                                ];
                                                natcasesort($calambaBarangays); // alphabetical, "Barangay 2" before "Barangay 10"
                                            @endphp
                                            @foreach ($calambaBarangays as $barangayOption)
                                                <option value="{{ $barangayOption }}">{{ $barangayOption }}</option>
                                            @endforeach
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <!-- "Other" Barangay Input -->
                                    <div class="col-md-6 mb-3" id="otherBarangayContainer" style="display: none;">
                                        <label for="other_barangay" class="form-label">Specify Barangay</label>
                                        <input type="text" class="form-control" id="other_barangay"
                                            name="new_other_barangay" placeholder="Enter your barangay name">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>ZIP Code *</label>
                                        <input type="text" name="new_zip_code" class="form-control"
                                            placeholder="e.g., 4027">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Landmark (optional)</label>
                                    <input type="text" name="new_landmark" class="form-control"
                                        placeholder="Near 7-Eleven, Church, etc.">
                                </div>
                            </div>

                            <!-- Dynamic Delivery Information Alert -->
                            <div id="deliveryAlert" class="alert mb-3" style="display: none;">
                                <i class="bi bi-truck me-2" id="deliveryAlertIcon"></i>
                                <strong id="deliveryAlertTitle">Delivery Method:</strong><br>
                                <span id="deliveryAlertText">Loading delivery details...</span>
                            </div>

                            <!-- Hidden branch selection (system will assign nearest branch) -->
                            <input type="hidden" name="branch_id" value="{{ $branch->id }}">

                            <div class="mb-3">
                                <label>Payment Method *</label>
                                <select name="payment_method" id="paymentMethod" class="form-select">
                                    <option value="cod">Cash on Delivery (COD)</option>
                                    <option value="gcash">GCash</option>
                                </select>
                                <small class="text-muted text-secondary d-block mt-1">
                                    <i class="bi bi-info-circle"></i> Payment will be collected upon delivery.
                                </small>
                            </div>

                            <!-- GCash Reference Field (shown when GCash is selected) -->
                            <div id="gcashField" class="mb-3" style="display: none;">
                                <label>GCash Reference Number *</label>
                                <input type="text" name="gcash_reference" class="form-control"
                                    placeholder="Enter GCash reference number">
                                <small class="text-muted">Please send payment to GCash number: 09123456789</small>
                            </div>

                            <div class="mb-3">
                                <label>Order Notes (optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Special delivery instructions..."></textarea>
                            </div>

                            <!-- ✅ DESKTOP Place Order button (inside form, hidden on mobile via CSS) -->
                            <button type="submit"
                                class="btn btn-primary btn-lg w-100 rounded-pill place-order-btn place-order-btn-desktop">
                                <i class="bi bi-check-circle"></i> Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ORDER SUMMARY WITH IMAGES -->
            <div class="col-lg-4 order-summary-col" style="align-self: flex-start;">
                <div class="card shadow-sm border-0 order-summary-card" style="position: sticky; top: 0; z-index: 1;">
                    <div class="card-header bg-white fw-bold order-summary-header">
                        <i class="bi bi-receipt"></i> Order Summary
                    </div>
                    <div class="card-body order-summary-body">
                        <!-- List of Cart Items with Images -->
                        <div class="mb-3 order-items-list">
                            @foreach ($cartItems as $item)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom order-item">
                                    <div class="flex-shrink-0 me-3 order-item-img">
                                        @if ($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}"
                                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div
                                                style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 order-item-info">
                                        <div class="fw-semibold small">{{ $item['product_name'] }}</div>

                                        {{-- Variant display correctly placed below the name --}}
                                        @if (isset($item['flavor_name']) && $item['flavor_name'])
                                            <div class="small text-muted">Variant: {{ $item['flavor_name'] }}</div>
                                        @endif

                                        <div class="small text-muted">Qty: {{ $item['quantity'] }}</div>
                                    </div>
                                    <div class="fw-bold small text-danger order-item-price">
                                        ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mb-2 summary-row">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 summary-row">
                            <span>Delivery Fee</span>
                            <span id="deliveryFeeDisplay">₱{{ number_format($deliveryFee ?? 0, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 summary-total-row">
                            <span>Total Amount</span>
                            <span class="text-danger" id="grandTotalDisplay">₱{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <!-- ✅ MOBILE ONLY: Place Order button at the bottom of Order Summary -->
                    <div class="card-footer bg-white border-0 order-summary-footer-mobile">
                        <button type="button" id="mobilePlaceOrderBtn"
                            class="btn btn-primary btn-lg w-100 rounded-pill place-order-btn place-order-btn-mobile">
                            <i class="bi bi-check-circle"></i> Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .address-section {
            transition: all 0.3s ease;
        }

        .btn-group .btn-check:checked+.btn {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        /* ✅ Desktop button visible by default */
        .place-order-btn-desktop {
            display: block;
        }

        /* ✅ Mobile footer hidden by default (desktop view) */
        .order-summary-footer-mobile {
            display: none;
        }

        /* ✅ Mobile button hidden by default */
        .place-order-btn-mobile {
            display: none;
        }

        /* ===== MOBILE APP-LIKE STYLES ===== */
        @media (max-width: 767.98px) {
            .checkout-container {
                padding-left: 14px;
                padding-right: 14px;
            }

            /* Header */
            .checkout-header {
                margin-bottom: 1rem !important;
                gap: 0.75rem;
            }

            .checkout-title {
                font-size: 1.1rem;
                margin-bottom: 0;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .checkout-title i {
                color: #0d6efd;
                font-size: 1.15rem;
            }

            .back-btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.78rem;
                white-space: nowrap;
            }

            /* Main Card */
            .checkout-card {
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04) !important;
            }

            .checkout-card-body {
                padding: 1rem;
            }

            /* Address Toggle */
            .address-toggle {
                border-radius: 14px !important;
                overflow: hidden;
            }

            .address-toggle .btn {
                font-size: 0.78rem;
                padding: 0.6rem 0.4rem;
                border-width: 1.5px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.3rem;
                transition: all 0.2s ease;
            }

            .address-toggle .btn i {
                font-size: 0.85rem;
            }

            .address-toggle .btn:active {
                transform: scale(0.98);
            }

            .address-hint {
                font-size: 0.72rem;
                line-height: 1.4;
                padding: 0 0.5rem;
            }

            /* Form controls */
            .checkout-card-body .form-control,
            .checkout-card-body .form-select {
                border-radius: 12px;
                padding: 0.7rem 0.9rem;
                font-size: 0.88rem;
                border-width: 1.5px;
                border-color: #e2e8f0;
                transition: all 0.2s ease;
            }

            .checkout-card-body .form-control:focus,
            .checkout-card-body .form-select:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.08);
            }

            .checkout-card-body label {
                font-size: 0.82rem;
                font-weight: 500;
                color: #374151;
                margin-bottom: 0.35rem;
            }

            .checkout-card-body .row>[class*="col-"] {
                margin-bottom: 0.85rem !important;
            }

            .checkout-card-body .mb-3 {
                margin-bottom: 0.85rem !important;
            }

            .checkout-card-body small.text-muted {
                font-size: 0.72rem;
                line-height: 1.4;
            }

            /* Alerts */
            .saved-address-alert,
            .new-address-alert {
                font-size: 0.78rem;
                padding: 0.75rem 0.9rem;
                border-radius: 12px;
                line-height: 1.5;
            }

            #deliveryAlert {
                font-size: 0.78rem;
                padding: 0.75rem 0.9rem;
                border-radius: 12px;
                line-height: 1.5;
            }

            #deliveryAlert strong {
                font-size: 0.82rem;
            }

            /* Payment */
            .checkout-card-body select {
                font-size: 0.88rem;
            }

            /* ✅ HIDE desktop Place Order button on mobile */
            .place-order-btn-desktop {
                display: none !important;
            }

            /* ✅ SHOW mobile Place Order footer + button on mobile */
            .order-summary-footer-mobile {
                display: block !important;
                padding: 1rem;
                border-top: 1px solid #eef2f6;
                background: #fff;
                border-radius: 0 0 16px 16px;
            }

            .place-order-btn-mobile {
                display: block !important;
                padding: 0.85rem 1rem;
                font-size: 0.95rem;
                font-weight: 600;
                border-radius: 14px !important;
                box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
                transition: all 0.2s ease;
            }

            .place-order-btn-mobile:active {
                transform: scale(0.98);
                box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
            }

            /* Order Summary - Sticky behavior removed on mobile */
            .order-summary-col {
                margin-top: 0.5rem;
            }

            .order-summary-card {
                border-radius: 16px !important;
                overflow: hidden;
                position: relative !important;
                top: auto !important;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04) !important;
            }

            .order-summary-header {
                padding: 0.85rem 1rem;
                font-size: 0.92rem;
                border-radius: 16px 16px 0 0;
            }

            .order-summary-body {
                padding: 1rem;
            }

            /* Order items */
            .order-items-list {
                margin-bottom: 0.75rem !important;
            }

            .order-item {
                margin-bottom: 0.75rem !important;
                padding-bottom: 0.75rem !important;
                gap: 0.5rem;
            }

            .order-item:last-child {
                margin-bottom: 0 !important;
                padding-bottom: 0 !important;
                border-bottom: none !important;
            }

            .order-item-img img,
            .order-item-img>div {
                width: 50px !important;
                height: 50px !important;
                border-radius: 10px !important;
            }

            .order-item-info .fw-semibold {
                font-size: 0.82rem !important;
                line-height: 1.3;
                margin-bottom: 0.15rem;
            }

            .order-item-info .small {
                font-size: 0.7rem !important;
                line-height: 1.35;
            }

            .order-item-price {
                font-size: 0.85rem !important;
                white-space: nowrap;
                margin-left: 0.4rem;
            }

            /* Summary rows */
            .summary-row {
                font-size: 0.85rem;
                margin-bottom: 0.5rem !important;
            }

            .summary-total-row {
                font-size: 1rem !important;
            }

            .summary-total-row span:last-child {
                font-size: 1.15rem;
            }

            .order-summary-body hr {
                margin: 0.65rem 0;
            }
        }

        /* Extra small devices */
        @media (max-width: 380px) {
            .checkout-title {
                font-size: 1rem;
            }

            .back-btn {
                font-size: 0.72rem;
                padding: 0.35rem 0.6rem;
            }

            .address-toggle .btn {
                font-size: 0.72rem;
                padding: 0.55rem 0.35rem;
            }

            .checkout-card-body .form-control,
            .checkout-card-body .form-select {
                font-size: 0.82rem;
                padding: 0.65rem 0.8rem;
            }

            .place-order-btn-mobile {
                font-size: 0.88rem;
            }
        }

        /* Tablet */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .checkout-card-body {
                padding: 1.25rem;
            }

            .order-summary-card {
                position: sticky;
                top: 1rem;
            }
        }

        /* Touch device: remove hover transforms */
        @media (hover: none) {
            .address-toggle .btn:hover {
                background: transparent;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const savedOption = document.getElementById('savedAddressOption');
            const newOption = document.getElementById('newAddressOption');
            const savedSection = document.getElementById('savedAddressSection');
            const newSection = document.getElementById('newAddressSection');
            const deliveryAlert = document.getElementById('deliveryAlert');
            const deliveryAlertTitle = document.getElementById('deliveryAlertTitle');
            const deliveryAlertText = document.getElementById('deliveryAlertText');
            const deliveryAlertIcon = document.getElementById('deliveryAlertIcon');
            const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');
            const paymentMethod = document.getElementById('paymentMethod');
            const gcashField = document.getElementById('gcashField');
            const newCityInput = document.getElementById('new_city');
            const barangaySelect = document.getElementById('new_barangay');
            const otherBarangayContainer = document.getElementById('otherBarangayContainer');
            const otherBarangayInput = document.getElementById('other_barangay');
            const checkoutForm = document.getElementById('checkoutForm');
            const mobilePlaceOrderBtn = document.getElementById('mobilePlaceOrderBtn');
            const grandTotalDisplay = document.getElementById('grandTotalDisplay');
            const SUBTOTAL = {{ (float) ($subtotal ?? 0) }};
            const CALAMBA_FEE = {{ (float) ($deliveryFee ?? 50) }};

            // 1. Toggle Address Sections
            function toggleAddressSections() {
                let savedCity = '';
                @if (Auth::check() && Auth::user()->city)
                    savedCity = '{{ Auth::user()->city }}';
                @endif

                if (savedOption.checked) {
                    savedSection.style.display = 'block';
                    newSection.style.display = 'none';

                    document.querySelectorAll('#newAddressSection select, #newAddressSection input').forEach(
                        input => {
                            input.disabled = true;
                        });

                    document.querySelectorAll('#savedAddressSection input').forEach(input => {
                        input.disabled = false;
                    });

                    checkCityForDelivery(savedCity);
                } else {
                    savedSection.style.display = 'none';
                    newSection.style.display = 'block';

                    document.querySelectorAll('#newAddressSection select, #newAddressSection input').forEach(
                        input => {
                            input.disabled = false;
                        });

                    document.querySelectorAll('#savedAddressSection input').forEach(input => {
                        input.disabled = true;
                    });

                    checkCityForDelivery(newCityInput.value);
                }
            }

            savedOption.addEventListener('change', toggleAddressSections);
            newOption.addEventListener('change', toggleAddressSections);
            toggleAddressSections();

            // 2. Toggle GCash Field
            function toggleGcashField() {
                gcashField.style.display = paymentMethod.value === 'gcash' ? 'block' : 'none';
            }
            paymentMethod.addEventListener('change', toggleGcashField);
            toggleGcashField();

            // 3. Delivery Method Alert + live fee/total update
            function checkCityForDelivery(city) {
                const trimmedCity = city.trim().toLowerCase();
                if (trimmedCity === 'calamba city' || trimmedCity === 'calamba') {
                    deliveryAlert.className = 'alert alert-success mb-3';
                    deliveryAlertIcon.className = 'bi bi-bicycle me-2';
                    deliveryAlertTitle.innerText = 'Handled by our Branch Admin/Driver:';
                    deliveryAlertText.innerHTML =
                        'Your order will be delivered by our in-house team.<br>• Delivery hours: 9:00 AM - 8:00 PM daily<br>• Our rider will contact you before delivery<br>• A flat ₱50 delivery fee applies within Calamba City';
                    deliveryAlert.style.display = 'block';

                    deliveryFeeDisplay.innerHTML = '₱' + CALAMBA_FEE.toFixed(2);
                    if (grandTotalDisplay) {
                        grandTotalDisplay.textContent = '₱' + (SUBTOTAL + CALAMBA_FEE).toFixed(2);
                    }
                } else if (trimmedCity !== '') {
                    deliveryAlert.className = 'alert alert-primary mb-3';
                    deliveryAlertIcon.className = 'bi bi-truck me-2';
                    deliveryAlertTitle.innerText = 'Handled by Lalamove:';
                    deliveryAlertText.innerHTML =
                        'Your order will be fulfilled via <strong>Lalamove</strong> courier service.<br>• You will receive a tracking link by via clicking view details in my orders information<br>• Delivery fee is calculated and paid directly to the Lalamove driver';
                    deliveryAlert.style.display = 'block';

                    deliveryFeeDisplay.innerHTML = 'Calculated by Lalamove';
                    if (grandTotalDisplay) {
                        grandTotalDisplay.textContent = '₱' + SUBTOTAL.toFixed(2);
                    }
                } else {
                    deliveryAlert.style.display = 'none';

                    deliveryFeeDisplay.innerHTML = '₱0.00';
                    if (grandTotalDisplay) {
                        grandTotalDisplay.textContent = '₱' + SUBTOTAL.toFixed(2);
                    }
                }
            }

            newCityInput.addEventListener('change', function() {
                if (newOption.checked) checkCityForDelivery(this.value);
            });

            // 4. Toggle "Other" Barangay
            function toggleOtherBarangay() {
                if (barangaySelect.value === 'Other') {
                    otherBarangayContainer.style.display = 'block';
                    otherBarangayInput.setAttribute('required', 'required');
                } else {
                    otherBarangayContainer.style.display = 'none';
                    otherBarangayInput.removeAttribute('required');
                    otherBarangayInput.value = '';
                }
            }
            if (barangaySelect) {
                toggleOtherBarangay();
                barangaySelect.addEventListener('change', toggleOtherBarangay);
            }

            // 5. FORM SUBMISSION
            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    // 🧹 Remove any previously appended dynamic inputs
                    this.querySelectorAll('input[data-dynamic="true"]').forEach(el => el.remove());

                    // ✅ Force the selected address_option into the form
                    const selectedRadio = document.querySelector('input[name="address_option"]:checked');
                    if (selectedRadio) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'address_option';
                        hiddenInput.value = selectedRadio.value;
                        hiddenInput.dataset.dynamic = 'true';
                        this.appendChild(hiddenInput);
                    }

                    // ✅ If "New Address" is selected, map the inputs
                    if (newOption.checked) {
                        let finalBarangay = '';
                        let finalOtherBarangay = '';

                        if (barangaySelect.value === 'Other') {
                            finalBarangay = 'Other';
                            finalOtherBarangay = otherBarangayInput.value;
                        } else {
                            finalBarangay = barangaySelect.value;
                            finalOtherBarangay = '';
                        }

                        const newAddress = document.querySelector('input[name="new_delivery_address"]');
                        const newCity = document.querySelector('select[name="new_city"]');
                        const newZipCode = document.querySelector('input[name="new_zip_code"]');
                        const newLandmark = document.querySelector('input[name="new_landmark"]');

                        const fields = {
                            delivery_address: newAddress ? newAddress.value : '',
                            city: newCity ? newCity.value : '',
                            barangay: finalBarangay,
                            other_barangay: finalOtherBarangay,
                            zip_code: newZipCode ? newZipCode.value : '',
                            landmark: newLandmark ? newLandmark.value : '',
                            province: 'Laguna',
                        };

                        for (const [name, value] of Object.entries(fields)) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = name;
                            input.value = value;
                            input.dataset.dynamic = 'true';
                            this.appendChild(input);
                        }
                    }
                });
            }

            // ✅ Mobile button triggers the form's submit event so our handler runs
            if (mobilePlaceOrderBtn && checkoutForm) {
                mobilePlaceOrderBtn.addEventListener('click', function() {
                    if (typeof checkoutForm.requestSubmit === 'function') {
                        checkoutForm.requestSubmit();
                    } else {
                        checkoutForm.dispatchEvent(new Event('submit', {
                            cancelable: true,
                            bubbles: true
                        }));
                    }
                });
            }
        });
    </script>

    {{-- ✅ UPDATED: Switch the barangay list depending on the selected city --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const citySelect = document.getElementById('new_city');
            const barangaySelect = document.getElementById('new_barangay');
            if (!citySelect || !barangaySelect) return;

            // 1. Store the default Calamba list (rendered by PHP)
            const calambaOptionsHtml = barangaySelect.innerHTML;

            // 2. Complete database of barangays for all Laguna cities
            const lagunaBarangays = {
                "Alaminos": ["Barangay I (Pob.)", "Barangay II (Pob.)", "Barangay III (Pob.)", "Barangay IV (Pob.)", "Del Carmen", "San Agustin", "San Andres", "San Benito", "San Gregorio", "San Ildefonso", "San Juan", "San Miguel", "San Roque", "Santa Rosa"],
                "Bay": ["Bitin", "Calo", "Dila", "Maitim", "Masaya", "Paciano Rizal", "Puypuy", "San Agustin (Pob.)", "San Antonio", "San Isidro", "San Nicolas (Pob.)", "San Roque", "Santa Cruz", "Santo Domingo", "Tagumpay", "Tranca"],
                "Biñan": ["Biñan", "Bungahan", "Canlalay", "Casile", "De La Paz", "Ganado", "Langkiwa", "Loma", "Malaban", "Malamig", "Mampalasan", "Platero", "Poblacion", "San Antonio", "San Francisco", "San Jose", "San Vicente", "Santo Domingo", "Santo Niño", "Soro-soro", "Timbao", "Tubigan", "Zapote"],
                "Cabuyao": ["Baclaran", "Banay-Banay", "Banlic", "Bigaa", "Butong", "Casile", "Diezmo", "Gulod", "Mamatid", "Marinig", "Niugan", "Pittland", "Pulo", "Sala", "San Isidro", "Barangay I Poblacion", "Barangay II Poblacion", "Barangay III Poblacion"],
                "Calauan": ["Balayhangin", "Bangyas", "Dayap", "Hanggan", "Imok", "Kanluran (Pob.)", "Lamot 1", "Lamot 2", "Masiit", "Prinza", "Santo Tomas", "Silangan (Pob.)"],
                "Cavinti": ["Anglas", "Bukal", "Bulajo", "Cansuso", "Duhat", "Inao-Awan", "Kanluran (Pob.)", "Layasin", "Layugan", "Mahipon", "Paowin", "Poblacion", "Silangan (Pob.)", "Sisilmin", "Sumucab", "Tibatib", "Udia"],
                "Famy": ["Asana (Pob.)", "Bacong-Sigsigan", "Bagong Pag-Asa (Pob.)", "Balitoc", "Banaba (Pob.)", "Batuhan", "Bulihan", "Caballero (Pob.)", "Calumpang", "Kapatalan", "Magdapio", "Mayatba", "Minayutan", "Salangbato", "San Juan", "San Roque", "Tunhac"],
                "Kalayaan": ["Longos", "San Antonio", "San Juan (Pob.)", "San Miguel", "San Pedro"],
                "Liliw": ["Bagong Anyo (Pob.)", "Bayate", "Bongkol", "Bubukal", "Cabuyew", "Calumpang", "Culoy", "Dagatan", "Daniw", "Dita", "Ibabang Palina", "Ibabang San Roque", "Ibabang Taykin", "Ilayang Palina", "Ilayang San Roque", "Ilayang Taykin", "Kanlurang Bukal", "Laguan", "Luquin", "Malabo-Kalantukan", "Masikap (Pob.)", "Maslun (Pob.)", "Mojon", "Novaliches", "Oples", "Pag-Asa (Pob.)", "Palayan", "Rizal (Pob.)", "San Isidro", "Silangang Bukal", "Tuy-Baanan"],
                "Los Baños": ["Anos", "Bagong Silang", "Bambang", "Batong Malake", "Baybayin (Pob.)", "Bayog", "Lalakay", "Maahas", "Malinta", "Mayondon", "Putho Tuntungin", "San Antonio", "Tadlac", "Timugan (Pob.)"],
                "Luisiana": ["De La Paz", "Barangay I (Pob.)", "Barangay II (Pob.)", "Barangay III (Pob.)", "Barangay IV (Pob.)", "Barangay V (Pob.)", "Barangay VI (Pob.)", "Barangay VII (Pob.)", "Barangay VIII (Pob.)", "San Antonio", "San Buenaventura", "San Diego", "San Isidro", "San Jose", "San Juan", "San Luis", "San Pablo", "San Pedro", "San Rafael", "San Roque", "San Salvador", "Santo Domingo", "Santo Tomas"],
                "Lumban": ["Bagong Silang", "Balimbingan (Pob.)", "Balubad", "Caliraya", "Concepcion", "Lewin", "Maracta (Pob.)", "Maytalang I", "Maytalang II", "Primera Parang (Pob.)", "Primera Pulo (Pob.)", "Salac (Pob.)", "Santo Niño (Pob.)", "Segunda Parang (Pob.)", "Segunda Pulo (Pob.)", "Wawa (Pob.)"],
                "Mabitac": ["Amuyong", "Bayanihan (Pob.)", "Lambac (Pob.)", "Libis ng Nayon (Pob.)", "Lucong", "Maligaya (Pob.)", "Masikap (Pob.)", "Matalatala", "Nanguma", "Numero", "Paagahan", "Pag-Asa (Pob.)", "San Antonio", "San Miguel", "Sinagtala (Pob.)"],
                "Magdalena": ["Alipit", "Baanan", "Balanac", "Bucal", "Buenavista", "Bungkol", "Buo", "Burlungan", "Cigaras", "Halayhayin", "Ibabang Atingay", "Ibabang Butnong", "Ilayang Atingay", "Ilayang Butnong", "Ilog", "Malaking Ambling", "Malinao", "Maravilla", "Munting Ambling", "Poblacion", "Sabang", "Salasad", "Tanawan", "Tipunan"],
                "Majayjay": ["Amonoy", "Bakia", "Balanac", "Balayong", "Banilad", "Banti", "Bitaoy", "Botocan", "Bukal", "Burgos", "Burol", "Coralao", "Gagalot", "Ibabang Banga", "Ibabang Bayucain", "Ilayang Banga", "Ilayang Bayucain", "Isabang", "Malinao", "May-it", "Munting Kawayan", "Olla", "Oobi", "Origuel (Pob.)", "Panalaban", "Pangil", "Panglan", "Piit", "Pook", "Rizal", "San Francisco (Pob.)", "San Isidro", "San Miguel (Pob.)", "San Roque", "Santa Catalina (Pob.)", "Suba", "Talortor", "Tanawan", "Taytay", "Villa Nogales"],
                "Nagcarlan": ["Abo", "Alibungbungan", "Alumbrado", "Balayong", "Balimbing", "Balinacon", "Bambang", "Banago", "Banca-banca", "Bangcuro", "Banilad", "Bayaquitos", "Buboy", "Buenavista", "Buhanginan", "Bukal", "Bunga", "Cabuyew", "Calumpang", "Kanluran Kabubuhayan", "Kanluran Lazaan", "Labangan", "Lagulo", "Lawaguin", "Maiit", "Malaya", "Malinao", "Manaol", "Maravilla", "Nagcalbang", "Oples", "Palayan", "Palina", "Poblacion I (Pob.)", "Poblacion II (Pob.)", "Poblacion III (Pob.)", "Sabang", "San Francisco", "Santa Lucia", "Sibulan", "Silangan Ilaya", "Silangan Kabubuhayan", "Silangan Lazaan", "Silangan Napapatid", "Sinipian", "Sulsuguin", "Talahib", "Talangan", "Taytay", "Tipacan", "Wakat", "Yukos"],
                "Paete": ["Bagumbayan (Pob.)", "Bangkusay (Pob.)", "Ermita (Pob.)", "Ibaba del Norte (Pob.)", "Ibaba del Sur (Pob.)", "Ilaya del Norte (Pob.)", "Ilaya del Sur (Pob.)", "Maytoong (Pob.)", "Quinale (Pob.)"],
                "Pagsanjan": ["Biñan", "Buboy", "Cabanbanan", "Calusiche", "Dingin", "Lambac", "Layugan", "Magdapio", "Maulawin", "Pinagsanjan", "Barangay I (Pob.)", "Barangay II (Pob.)", "Sabang", "Sampaloc", "San Isidro"],
                "Pakil": ["Baño", "Banilan", "Burgos (Pob.)", "Casa Real", "Casinsin", "Dorado", "Gonzales (Pob.)", "Kabulusan", "Matikiw", "Rizal (Pob.)", "Saray", "Taft (Pob.)", "Tavera (Pob.)"],
                "Pangil": ["Balian", "Dambo", "Galalan", "Isla (Pob.)", "Mabato-Azufre", "Natividad (Pob.)", "San Jose (Pob.)", "Sulib"],
                "Pila": ["Aplaya", "Bagong Pook", "Bukal", "Bulilan Norte (Pob.)", "Bulilan Sur (Pob.)", "Concepcion", "Labuin", "Linga", "Masico", "Mojon", "Pansol", "Pinagbayanan", "San Antonio", "San Miguel", "Santa Clara Norte (Pob.)", "Santa Clara Sur (Pob.)", "Tubuan"],
                "Rizal": ["Antipolo", "East Poblacion", "Paul 1", "Paul 2", "Pook", "Tala", "Talaga", "Tuy", "West Poblacion"],
                "San Pablo": ["Bagong Bayan II-A (Pob.)", "Bagong Pook VI-C (Pob.)", "Barangay I-A (Pob.)", "Barangay I-B (Pob.)", "Barangay II-A (Pob.)", "Barangay II-B (Pob.)", "Barangay II-C (Pob.)", "Barangay II-D (Pob.)", "Barangay II-E (Pob.)", "Barangay II-F (Pob.)", "Barangay III-A (Pob.)", "Barangay III-B (Pob.)", "Barangay III-C (Pob.)", "Barangay III-D (Pob.)", "Barangay III-E (Pob.)", "Barangay III-F (Pob.)", "Barangay IV-A (Pob.)", "Barangay IV-B (Pob.)", "Barangay IV-C (Pob.)", "Barangay V-A (Pob.)", "Barangay V-B (Pob.)", "Barangay V-C (Pob.)", "Barangay V-D (Pob.)", "Barangay VI-A (Pob.)", "Barangay VI-B (Pob.)", "Barangay VI-D (Pob.)", "Barangay VI-E (Pob.)", "Barangay VII-A (Pob.)", "Barangay VII-B (Pob.)", "Barangay VII-C (Pob.)", "Barangay VII-D (Pob.)", "Barangay VII-E (Pob.)", "Bautista", "Concepcion", "Del Remedio", "Dolores", "San Antonio 1", "San Antonio 2", "San Bartolome", "San Buenaventura", "San Crispin", "San Cristobal", "San Diego", "San Francisco", "San Gabriel", "San Gregorio", "San Ignacio", "San Isidro", "San Joaquin", "San Jose", "San Juan", "San Lorenzo", "San Lucas 1", "San Lucas 2", "San Marcos", "San Mateo", "San Miguel", "San Nicolas", "San Pedro", "San Rafael", "San Roque", "San Vicente", "Santa Ana", "Santa Catalina", "Santa Cruz", "Santa Elena", "Santa Felomina", "Santa Isabel", "Santa Maria", "Santa Maria Magdalena", "Santa Monica", "Santa Veronica", "Santiago I", "Santiago II", "Santisimo Rosario", "Santo Angel", "Santo Cristo", "Santo Niño", "Soledad"],
                "San Pedro": ["Bagong Silang", "Calendola", "Chrysanthemum", "Cuyab", "Estrella", "Fatima", "Gsis", "Landayan", "Langgam", "Laram", "Magsaysay", "Maharlika", "Narra", "Nueva", "Pacita 1", "Pacita 2", "Poblacion", "Riverside", "Rosario", "Sampaguita Village", "San Antonio", "San Lorenzo Ruiz", "San Roque", "San Vicente", "Santo Niño", "United Bayanihan", "United Better Living"],
                "Santa Cruz": ["Alipit", "Bagumbayan", "Bubukal", "Calios", "Duhat", "Gatid", "Jasaan", "Labuin", "Malinao", "Oogong", "Pagsawitan", "Palasan", "Patimbao", "Barangay I (Pob.)", "Barangay II (Pob.)", "Barangay III (Pob.)", "Barangay IV (Pob.)", "Barangay V (Pob.)", "San Jose", "San Juan", "San Pablo Norte", "San Pablo Sur", "Santisima Cruz", "Santo Angel Central", "Santo Angel Norte", "Santo Angel Sur"],
                "Santa Maria": ["Adia", "Bagong Pook", "Bagumbayan", "Barangay I (Pob.)", "Barangay II (Pob.)", "Barangay III (Pob.)", "Barangay IV (Pob.)", "Bubukal", "Cabooan", "Calangay", "Cambuja", "Coralan", "Cueva", "Inayapan", "Jose Laurel, Sr.", "Kayhakat", "Macasipac", "Masinao", "Mataling-Ting", "Pao-o", "Parang Ng Buho", "Santiago", "Talangka", "Tungkod"],
                "Santa Rosa": ["Aplaya", "Balibago", "Caingin", "Dila", "Dita", "Don Jose", "Ibaba", "Labas", "Macabling", "Malitlit", "Malusak (Pob.)", "Market Area (Pob.)", "Kanluran (Pob.)", "Pook", "Pulong Santa Cruz", "Santo Domingo", "Sinalhan", "Tagapo"],
                "Siniloan": ["Acevida", "Bagong Pag-Asa (Pob.)", "Bagumbarangay (Pob.)", "Buhay", "G. Redor (Pob.)", "Gen. Luna", "Halayhayin", "J. Rizal (Pob.)", "Kapatalan", "Laguio", "Liyang", "Llavac", "Magsaysay", "Mendiola (Pob.)", "Pandeno", "Salubungan", "Wawa"],
                "Victoria": ["Banca-banca", "Daniw", "Masapang", "Nanhaya (Pob.)", "Pagalangan", "San Benito", "San Felix", "San Francisco", "San Roque (Pob.)"]
            };

            function syncBarangayOptions() {
                const city = citySelect.value.trim();
                
                if (city === 'Calamba City' || city === 'Calamba') {
                    // Restore Calamba's default list
                    barangaySelect.innerHTML = calambaOptionsHtml;
                    barangaySelect.value = '';
                    barangaySelect.dataset.mode = 'calamba';
                } else if (city && lagunaBarangays[city]) {
                    // Build list for the selected city
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    
                    const sorted = lagunaBarangays[city].sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
                    sorted.forEach(name => {
                        const opt = document.createElement('option');
                        opt.value = name;
                        opt.textContent = name;
                        barangaySelect.appendChild(opt);
                    });
                    
                    // Add "Other" just in case
                    const otherOpt = document.createElement('option');
                    otherOpt.value = 'Other';
                    otherOpt.textContent = 'Other';
                    barangaySelect.appendChild(otherOpt);
                    
                    barangaySelect.dataset.mode = 'dynamic';
                } else {
                    // No city selected
                    barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                    barangaySelect.dataset.mode = 'empty';
                }

                // Trigger change so the "Other" input logic runs
                barangaySelect.dispatchEvent(new Event('change'));
            }

            citySelect.addEventListener('change', syncBarangayOptions);
        });
    </script>
@endsection