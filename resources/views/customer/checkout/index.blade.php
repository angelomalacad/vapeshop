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
                                    <i class="bi bi-person"></i> <span class="d-none d-sm-inline">Use My Saved Address</span><span class="d-sm-none">Saved</span>
                                </label>

                                <input type="radio" class="btn-check" name="address_option" id="newAddressOption"
                                    value="new">
                                <label class="btn btn-outline-primary rounded-end-pill" for="newAddressOption">
                                    <i class="bi bi-plus-circle"></i> <span class="d-none d-sm-inline">Use Different Address</span><span class="d-sm-none">New</span>
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
                                        <div class="form-control bg-light text-muted" style="cursor: default;">Laguna</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>City/Municipality *</label>
                                        <select class="form-select" name="new_city" id="new_city">
                                            <option value="">Select City</option>
                                            <option value="Calamba City">Calamba City</option>
                                            <option value="Los Baños">Los Baños</option>
                                            <option value="Cabuyao">Cabuyao</option>
                                            <option value="Santa Rosa">Santa Rosa</option>
                                            <option value="Biñan">Biñan</option>
                                            <option value="San Pedro">San Pedro</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Barangay *</label>
                                        <select class="form-select" name="new_barangay" id="new_barangay">
                                            <option value="">Select Barangay</option>
                                            @foreach (['Canlubang', 'Majada In', 'Sirang Lupa', 'Burol', 'Palo alto', 'Laguerta', 'Paciano Rizal', 'Real', 'Halang', 'Banadero', 'Lingga', 'Parian', 'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6', 'Banlic', 'Barangay 7', 'Bucal', 'Pansol', 'Lecheria', 'Looc', 'Uwisan', 'Mayapa', 'Turbina', 'Batino', 'Lawa', 'Bubuyan', 'Hornalan', 'Sampiruhan', 'Milagrosa', 'Palingon', 'Saimsim', 'San Cristobal', 'Barandal', 'Makiling', 'La Mesa', 'Maunong', 'Pittland', 'Masili', 'Sucol', 'Ulango', 'Majada Labas', 'Kay-Anlog', 'Punta', 'Bagong Kalsada', 'Prinza', 'Mabato', 'Puting Lupa', 'Bunggo', 'Camaligan', 'Mabacan', 'San Jose', 'Majada Out'] as $barangayOption)
                                                <option value="{{ $barangayOption }}">{{ $barangayOption }}</option>
                                            @endforeach
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <!-- "Other" Barangay Input -->
                                    <div class="col-md-6 mb-3" id="otherBarangayContainer" style="display: none;">
                                        <label for="other_barangay" class="form-label">Specify Barangay</label>
                                        <input type="text" class="form-control" id="other_barangay"
                                            name="other_barangay" placeholder="Enter your barangay name">
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

                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill place-order-btn">
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
                        <!-- REMOVE TAX ROW - No tax -->
                        <!-- <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>₱{{ number_format($tax, 2) }}</span>
                        </div> -->
                        <div class="d-flex justify-content-between mb-2 summary-row">
                            <span>Delivery Fee</span>
                            <span id="deliveryFeeDisplay">₱0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 summary-total-row">
                            <span>Total Amount</span>
                            <span class="text-danger">₱{{ number_format($total, 2) }}</span>
                        </div>
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

            .checkout-card-body .row > [class*="col-"] {
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

            /* Place Order Button */
            .place-order-btn {
                padding: 0.85rem 1rem;
                font-size: 0.95rem;
                font-weight: 600;
                border-radius: 14px !important;
                margin-top: 0.5rem;
                box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
                transition: all 0.2s ease;
            }

            .place-order-btn:active {
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
            .order-item-img > div {
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

            .place-order-btn {
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

            // 1. Toggle Address Sections
            function toggleAddressSections() {
                let savedCity = '';
                @if (Auth::check() && Auth::user()->city)
                    savedCity = '{{ Auth::user()->city }}';
                @endif

                if (savedOption.checked) {
                    savedSection.style.display = 'block';
                    newSection.style.display = 'none';

                    // Disable new address inputs (no 'required' attribute set)
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

                    // Enable new address inputs (NO 'required' attribute is added)
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

            // 3. Delivery Method Alert
            function checkCityForDelivery(city) {
                const trimmedCity = city.trim().toLowerCase();
                if (trimmedCity === 'calamba city' || trimmedCity === 'calamba') {
                    deliveryAlert.className = 'alert alert-success mb-3';
                    deliveryAlertIcon.className = 'bi bi-bicycle me-2';
                    deliveryAlertTitle.innerText = 'Handled by our Branch Admin/Driver:';
                    deliveryAlertText.innerHTML =
                        'Your order will be delivered by our in-house team.<br>• Delivery hours: 9:00 AM - 8:00 PM daily<br>• Our rider will contact you before delivery<br>';
                    deliveryAlert.style.display = 'block';
                    deliveryFeeDisplay.innerHTML = '₱0.00';
                } else if (trimmedCity !== '') {
                    deliveryAlert.className = 'alert alert-primary mb-3';
                    deliveryAlertIcon.className = 'bi bi-truck me-2';
                    deliveryAlertTitle.innerText = 'Handled by Lalamove:';
                    deliveryAlertText.innerHTML =
                        'Your order will be fulfilled via <strong>Lalamove</strong> courier service.<br>• You will receive a tracking link by via clicking view details in my orders information<br>• Delivery fee is calculated and paid directly to the Lalamove driver';
                    deliveryAlert.style.display = 'block';
                    deliveryFeeDisplay.innerHTML = 'Calculated by Lalamove';
                } else {
                    deliveryAlert.style.display = 'none';
                    deliveryFeeDisplay.innerHTML = '₱0.00';
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
                    // Force the radio button value into the form
                    const selectedRadio = document.querySelector('input[name="address_option"]:checked');
                    if (selectedRadio) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'address_option';
                        hiddenInput.value = selectedRadio.value;
                        this.appendChild(hiddenInput);
                    }

                    // If "New Address" is selected, map the inputs
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

                        const deliveryAddressInput = document.createElement('input');
                        deliveryAddressInput.type = 'hidden';
                        deliveryAddressInput.name = 'delivery_address';
                        deliveryAddressInput.value = newAddress ? newAddress.value : '';

                        const cityInput = document.createElement('input');
                        cityInput.type = 'hidden';
                        cityInput.name = 'city';
                        cityInput.value = newCity ? newCity.value : '';

                        const barangayInput = document.createElement('input');
                        barangayInput.type = 'hidden';
                        barangayInput.name = 'barangay';
                        barangayInput.value = finalBarangay;

                        const otherBarangayInputHidden = document.createElement('input');
                        otherBarangayInputHidden.type = 'hidden';
                        otherBarangayInputHidden.name = 'other_barangay';
                        otherBarangayInputHidden.value = finalOtherBarangay;

                        const zipCodeInput = document.createElement('input');
                        zipCodeInput.type = 'hidden';
                        zipCodeInput.name = 'zip_code';
                        zipCodeInput.value = newZipCode ? newZipCode.value : '';

                        const landmarkInput = document.createElement('input');
                        landmarkInput.type = 'hidden';
                        landmarkInput.name = 'landmark';
                        landmarkInput.value = newLandmark ? newLandmark.value : '';

                        this.appendChild(deliveryAddressInput);
                        this.appendChild(cityInput);
                        this.appendChild(barangayInput);
                        this.appendChild(otherBarangayInputHidden);
                        this.appendChild(zipCodeInput);
                        this.appendChild(landmarkInput);

                        const provinceInput = document.createElement('input');
                        provinceInput.type = 'hidden';
                        provinceInput.name = 'province';
                        provinceInput.value = 'Laguna';
                        this.appendChild(provinceInput);
                    }
                });
            }
        });
    </script>
@endsection