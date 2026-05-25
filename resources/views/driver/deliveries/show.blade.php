@extends('layouts.driver')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="bi bi-truck"></i> Delivery #{{ $delivery->tracking_number }}</h2>
            <p class="text-muted">Order #{{ $delivery->order->order_number }}</p>
        </div>
        <a href="{{ route('driver.deliveries') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Deliveries
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Delivery Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tracking Number:</strong> {{ $delivery->tracking_number }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $delivery->status == 'delivered' ? 'success' : ($delivery->status == 'in_transit' ? 'warning' : 'info') }}">
                            {{ ucfirst($delivery->status) }}
                        </span>
                    </p>
                    <p><strong>Assigned:</strong> {{ $delivery->assigned_at ? $delivery->assigned_at->format('M d, Y h:i A') : 'N/A' }}</p>
                    @if($delivery->picked_up_at)
                        <p><strong>Picked Up:</strong> {{ $delivery->picked_up_at->format('M d, Y h:i A') }}</p>
                    @endif
                    @if($delivery->delivered_at)
                        <p><strong>Delivered:</strong> {{ $delivery->delivered_at->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Customer Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $delivery->recipient_name }}</p>
                    <p><strong>Phone:</strong> {{ $delivery->recipient_phone }}</p>
                    <p><strong>Address:</strong> {{ $delivery->delivery_address }}</p>
                    @if($delivery->order->notes)
                        <p><strong>Order Notes:</strong> {{ $delivery->order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('driver.delivery.update', $delivery) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="picked_up" {{ $delivery->status == 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                <option value="in_transit" {{ $delivery->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="delivered" {{ $delivery->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="failed" {{ $delivery->status == 'failed' ? 'selected' : '' }}>Failed Delivery</option>
                            </select>
                        </div>

                        <div id="proofFields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Delivery Proof (Photo) *</label>
                                <input type="file" name="delivery_proof" class="form-control" accept="image/*">
                                <small class="text-muted">Take a photo of the delivered item at the customer's location</small>
                                @if($delivery->delivery_proof)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($delivery->delivery_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-image"></i> View Current Proof
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Payment Proof (Photo) *</label>
                                <input type="file" name="payment_proof" class="form-control" accept="image/*">
                                <small class="text-muted">Take a photo of the payment receipt or GCash transaction</small>
                                @if($delivery->payment_proof)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($delivery->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-receipt"></i> View Current Proof
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any issues or updates?">{{ $delivery->driver_notes }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Live Location</label>
                            <button type="button" class="btn btn-sm btn-secondary" id="getLocationBtn">
                                <i class="bi bi-geo-alt"></i> Share Current Location
                            </button>
                            <input type="hidden" name="driver_latitude" id="driverLatitude">
                            <input type="hidden" name="driver_longitude" id="driverLongitude">
                            <small class="text-muted d-block mt-1">Sharing your location helps customers track their delivery</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-check-circle"></i> Update Delivery Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const statusSelect = document.querySelector('select[name="status"]');
    const proofFields = document.getElementById('proofFields');
    
    function toggleProofFields() {
        proofFields.style.display = statusSelect.value === 'delivered' ? 'block' : 'none';
        const proofInputs = proofFields.querySelectorAll('input[type="file"]');
        proofInputs.forEach(input => {
            input.required = statusSelect.value === 'delivered';
        });
    }
    
    statusSelect.addEventListener('change', toggleProofFields);
    toggleProofFields();
    
    document.getElementById('getLocationBtn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('driverLatitude').value = position.coords.latitude;
                document.getElementById('driverLongitude').value = position.coords.longitude;
                alert('Location captured! This will be visible to the customer.');
            }, function() {
                alert('Unable to get location. Please check your GPS settings.');
            });
        } else {
            alert('Geolocation is not supported by your browser');
        }
    });
</script>
@endsection