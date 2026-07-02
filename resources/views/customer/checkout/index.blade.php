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
                                        <label>Province *</label>
                                        <input type="text" name="new_province" class="form-control"
                                            placeholder="e.g., Laguna">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>City/Municipality *</label>
                                        <input type="text" name="new_city" class="form-control"
                                            placeholder="e.g., Calamba City">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Barangay *</label>
                                        <input type="text" name="new_barangay" class="form-control"
                                            placeholder="e.g., Looc">
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

            <div class="col-lg-4" style="align-self: flex-start;">
                <div class="card shadow-sm border-0" style="position: sticky; top: 0; z-index: 1;">
                    <div class="card-header bg-white fw-bold">
                        <i class="bi bi-receipt"></i> Order Summary
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span>
                            <span>₱0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total Amount</span>
                            <span class="text-danger">₱{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="alert alert-secondary mt-3 mb-0">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Prices are final. No additional taxes or fees will be charged.
                            </small>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-truck"></i> <strong>Delivery Information:</strong>
                            </small>
                            <small class="text-muted">
                                • Delivery hours: 9:00 AM - 8:00 PM daily<br>
                                • Our rider will contact you before delivery<br>
                                • Please prepare exact change for COD payments
                            </small>
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

        function toggleAddressSections() {
            if (savedOption.checked) {
                savedSection.style.display = 'block';
                newSection.style.display = 'none';
                // Disable new address inputs
                document.querySelectorAll('#newAddressSection input').forEach(input => {
                    input.disabled = true;
                });
                // Enable saved address hidden inputs
                document.querySelectorAll('#savedAddressSection input').forEach(input => {
                    input.disabled = false;
                });
            } else {
                savedSection.style.display = 'none';
                newSection.style.display = 'block';
                // Enable new address inputs
                document.querySelectorAll('#newAddressSection input').forEach(input => {
                    input.disabled = false;
                });
                // Disable saved address hidden inputs
                document.querySelectorAll('#savedAddressSection input').forEach(input => {
                    input.disabled = true;
                });
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

        // Form submission - prepare address data based on selection
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            if (newOption.checked) {
                // Map new address fields to the expected field names
                const newAddress = document.querySelector('input[name="new_delivery_address"]');
                const newCity = document.querySelector('input[name="new_city"]');
                const newBarangay = document.querySelector('input[name="new_barangay"]');
                const newProvince = document.querySelector('input[name="new_province"]');
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

                const provinceInput = document.createElement('input');
                provinceInput.type = 'hidden';
                provinceInput.name = 'province';
                provinceInput.value = newProvince.value;

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
                this.appendChild(provinceInput);
                this.appendChild(zipCodeInput);
                this.appendChild(landmarkInput);
            }
        });
    </script>
@endsection
