@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="mb-4"><i class="bi bi-credit-card"></i> Delivery & Payment</h4>
                    <form method="POST" action="{{ route('customer.checkout.store') }}" id="checkoutForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Full Name *</label>
                                <input type="text" name="customer_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone Number *</label>
                                <input type="text" name="customer_phone" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Email (optional)</label>
                            <input type="email" name="customer_email" class="form-control">
                        </div>

                        <!-- Hidden delivery type - always delivery -->
                        <input type="hidden" name="delivery_type" value="delivery">

                        <!-- Delivery Address Fields -->
                        <div class="mb-3">
                            <label>Delivery Address *</label>
                            <input type="text" name="delivery_address" class="form-control" placeholder="House/Unit #, Street, Subdivision" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>City/Municipality *</label>
                                <input type="text" name="city" class="form-control" placeholder="e.g., Calamba City" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Barangay *</label>
                                <input type="text" name="barangay" class="form-control" placeholder="e.g., Looc" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Landmark (optional)</label>
                            <input type="text" name="landmark" class="form-control" placeholder="Near 7-Eleven, Church, etc.">
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

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-receipt"></i> Order Summary
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (12%)</span>
                        <span>₱{{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery Fee</span>
                        <span>₱0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total</span>
                        <span class="text-danger">₱{{ number_format($total, 2) }}</span>
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
@endsection