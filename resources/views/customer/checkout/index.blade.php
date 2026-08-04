@extends('layouts.customer')

@section('content')
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="mb-4"><i class="bi bi-credit-card"></i> Delivery & Payment Method</h4>

                        <!-- Address Selection Toggle -->
                        <div class="mb-4">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="address_option" id="savedAddressOption"
                                    value="saved" checked autocomplete="off">
                                <label class="btn btn-outline-primary rounded-start-pill" for="savedAddressOption">
                                    <i class="bi bi-person"></i> Use My Saved Address
                                </label>

                                <input type="radio" class="btn-check" name="address_option" id="newAddressOption"
                                    value="new" autocomplete="off">
                                <label class="btn btn-outline-primary rounded-end-pill" for="newAddressOption">
                                    <i class="bi bi-plus-circle"></i> Use Different Address
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2 text-center">
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
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-house-heart me-2"></i>
                                    <strong>Your Saved Address:</strong><br>
                                    {{ Auth::user()->address ?? 'No address saved in profile' }}
                                    @if (Auth::user()->barangay)
                                        <br><strong>Barangay:</strong> {{ Auth::user()->barangay }}
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
                                <input type="hidden" name="province" id="saved_province"
                                    value="{{ Auth::user()->province }}">
                                <input type="hidden" name="zip_code" id="saved_zip_code"
                                    value="{{ Auth::user()->zip_code }}">
                                <input type="hidden" name="landmark" id="saved_landmark"
                                    value="{{ Auth::user()->landmark ?? '' }}">
                            </div>

                            <!-- New Address Section -->
                            <div id="newAddressSection" class="address-section" style="display: none;">
                                <div class="alert alert-secondary mb-3">
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
                                        <select class="form-select" name="new_city" id="new_city" required>
                                            <option value="">Select City</option>
                                            <option value="Calamba City">Calamba City</option>
                                            <option value="Los Baños">Los Baños</option>
                                            <option value="Cabuyao">Cabuyao</option>
                                            <option value="Santa Rosa">Santa Rosa</option>
                                            <option value="Biñan">Biñan</option>
                                            <option value="San Pedro">San Pedro</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Barangay *</label>
                                        <select class="form-select" name="new_barangay" required>
                                            <option value="">Select Barangay</option>
                                            @foreach([
                                                'Canlubang', 'Majada In', 'Sirang Lupa', 'Burol', 'Palo alto', 'Laguerta', 
                                                'Paciano Rizal', 'Real', 'Halang', 'Banadero', 'Lingga', 'Parian', 
                                                'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 
                                                'Barangay 6', 'Banlic', 'Barangay 7', 'Bucal', 'Pansol', 'Lecheria', 
                                                'Looc', 'Uwisan', 'Mayapa', 'Turbina', 'Batino', 'Lawa', 'Bubuyan', 
                                                'Hornalan', 'Sampiruhan', 'Milagrosa', 'Palingon', 'Saimsim', 
                                                'San Cristobal', 'Barandal', 'Makiling', 'La Mesa', 'Maunong', 
                                                'Pittland', 'Masili', 'Sucol', 'Ulango', 'Majada Labas', 'Kay-Anlog', 
                                                'Punta', 'Bagong Kalsada', 'Prinza', 'Mabato', 'Puting Lupa', 'Bunggo', 
                                                'Camaligan', 'Mabacan', 'San Jose', 'Majada Out'
                                            ] as $barangayOption)
                                                <option value="{{ $barangayOption }}">{{ $barangayOption }}</option>
                                            @endforeach
                                        </select>
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

                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">
                                <i class="bi bi-check-circle"></i> Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ORDER SUMMARY WITH IMAGES -->
            <div class="col-lg-4" style="align-self: flex-start;">
                <div class="card shadow-sm border-0" style="position: sticky; top: 0; z-index: 1;">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-receipt"></i> Order Summary
                    </div>
                    <div class="card-body">
                        <!-- List of Cart Items with Images -->
                        <div class="mb-3">
                            @foreach($cartItems as $item)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 me-3">
                                        @if($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['product_name'] }}" 
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        @else
                                            <div style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #adb5bd;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold small">{{ $item['product_name'] }}</div>
                                        <div class="small text-muted">Qty: {{ $item['quantity'] }}</div>
                                    </div>
                                    <div class="fw-bold small text-danger">
                                        ₱{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span>
                            <span id="deliveryFeeDisplay">₱0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
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
    </style>

    <script>
        // Toggle between saved and new address
        const savedOption = document.getElementById('savedAddressOption');
        const newOption = document.getElementById('newAddressOption');
        const savedSection = document.getElementById('savedAddressSection');
        const newSection = document.getElementById('newAddressSection');

        // Dynamic Delivery Alert Elements
        const deliveryAlert = document.getElementById('deliveryAlert');
        const deliveryAlertTitle = document.getElementById('deliveryAlertTitle');
        const deliveryAlertText = document.getElementById('deliveryAlertText');
        const deliveryAlertIcon = document.getElementById('deliveryAlertIcon');
        const deliveryFeeDisplay = document.getElementById('deliveryFeeDisplay');

        function toggleAddressSections() {
            if (savedOption.checked) {
                savedSection.style.display = 'block';
                newSection.style.display = 'none';
                // Disable new address inputs
                document.querySelectorAll('#newAddressSection select, #newAddressSection input').forEach(input => {
                    input.disabled = true;
                });
                // Enable saved address hidden inputs
                document.querySelectorAll('#savedAddressSection input').forEach(input => {
                    input.disabled = false;
                });
                // Check City from Saved Address
                checkCityForDelivery('{{ Auth::user()->city ?? "" }}');
            } else {
                savedSection.style.display = 'none';
                newSection.style.display = 'block';
                // Enable new address inputs
                document.querySelectorAll('#newAddressSection select, #newAddressSection input').forEach(input => {
                    input.disabled = false;
                });
                // Disable saved address hidden inputs
                document.querySelectorAll('#savedAddressSection input').forEach(input => {
                    input.disabled = true;
                });
                // Check City from New Address input
                const newCityInput = document.getElementById('new_city');
                checkCityForDelivery(newCityInput.value);
            }
        }

        savedOption.addEventListener('change', toggleAddressSections);
        newOption.addEventListener('change', toggleAddressSections);

        // Initialize
        toggleAddressSections();

        // Toggle GCash field
        const paymentMethod = document.getElementById('paymentMethod');
        const gcashField = document.getElementById('gcashField');

        function toggleGcashField() {
            if (paymentMethod.value === 'gcash') {
                gcashField.style.display = 'block';
            } else {
                gcashField.style.display = 'none';
            }
        }

        paymentMethod.addEventListener('change', toggleGcashField);
        toggleGcashField();

        // UPDATED: Unified Logic for Delivery Method (Branch Admin vs Lalamove)
        function checkCityForDelivery(city) {
            const trimmedCity = city.trim().toLowerCase();
            
            if (trimmedCity === 'calamba city' || trimmedCity === 'calamba') {
                // Branch Admin / Driver (Inside Calamba)
                deliveryAlert.className = 'alert alert-success mb-3';
                deliveryAlertIcon.className = 'bi bi-bicycle me-2';
                deliveryAlertTitle.innerText = 'Handled by our Branch Admin/Driver:';
                deliveryAlertText.innerHTML = 'Your order will be delivered by our in-house team.<br>• Delivery hours: 9:00 AM - 8:00 PM daily<br>• Our rider will contact you before delivery<br>';
                deliveryAlert.style.display = 'block';
                deliveryFeeDisplay.innerHTML = '₱0.00';
            } else if (trimmedCity !== '') {
                // Lalamove (Outside Calamba)
                deliveryAlert.className = 'alert alert-primary mb-3';
                deliveryAlertIcon.className = 'bi bi-truck me-2';
                deliveryAlertTitle.innerText = 'Handled by Lalamove:';
                deliveryAlertText.innerHTML = 'Your order will be fulfilled via <strong>Lalamove</strong> courier service.<br>• You will receive a tracking link via SMS/Email<br>• Delivery fee is calculated and paid directly to the Lalamove driver';
                deliveryAlert.style.display = 'block';
                deliveryFeeDisplay.innerHTML = 'Calculated by Lalamove';
            } else {
                deliveryAlert.style.display = 'none';
                deliveryFeeDisplay.innerHTML = '₱0.00';
            }
        }

        // Listener for New City Input
        document.getElementById('new_city').addEventListener('change', function() {
            if (newOption.checked) {
                checkCityForDelivery(this.value);
            }
        });

        // Form submission - prepare address data based on selection
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            if (newOption.checked) {
                // Map new address fields to the expected field names
                const newAddress = document.querySelector('input[name="new_delivery_address"]');
                const newCity = document.querySelector('select[name="new_city"]');
                const newBarangay = document.querySelector('select[name="new_barangay"]');
                const newZipCode = document.querySelector('input[name="new_zip_code"]');
                const newLandmark = document.querySelector('input[name="new_landmark"]');

                // Create hidden inputs for the form data
                const deliveryAddressInput = document.createElement('input');
                deliveryAddressInput.type = 'hidden';
                deliveryAddressInput.name = 'delivery_address';
                deliveryAddressInput.value = newAddress.value;

                const cityInput = document.createElement('input');
                cityInput.type = 'hidden';
                cityInput.name = 'city';
                cityInput.value = newCity.value;

                const barangayInput = document.createElement('input');
                barangayInput.type = 'hidden';
                barangayInput.name = 'barangay';
                barangayInput.value = newBarangay.value;

                const zipCodeInput = document.createElement('input');
                zipCodeInput.type = 'hidden';
                zipCodeInput.name = 'zip_code';
                zipCodeInput.value = newZipCode.value;

                const landmarkInput = document.createElement('input');
                landmarkInput.type = 'hidden';
                landmarkInput.name = 'landmark';
                landmarkInput.value = newLandmark.value;

                this.appendChild(deliveryAddressInput);
                this.appendChild(cityInput);
                this.appendChild(barangayInput);
                this.appendChild(zipCodeInput);
                this.appendChild(landmarkInput);
                
                // Hardcode province to Laguna
                const provinceInput = document.createElement('input');
                provinceInput.type = 'hidden';
                provinceInput.name = 'province';
                provinceInput.value = 'Laguna';
                this.appendChild(provinceInput);
            }
        });
    </script>
@endsection