@extends('layouts.driver')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-truck"></i> My Deliveries</h2>
        <span class="badge bg-primary">{{ $totalDeliveries ?? 0 }} total deliveries</span>
    </div>
    
    <!-- Active Deliveries Section -->
    @if($activeDeliveries->count() > 0)
    <div class="mb-4">
        <h4 class="mb-3"><i class="bi bi-play-circle-fill text-warning"></i> Active Deliveries</h4>
        <div class="row">
            @foreach($activeDeliveries as $delivery)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-header bg-{{ $delivery->status == 'in_transit' ? 'warning' : ($delivery->status == 'picked_up' ? 'info' : 'secondary') }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-receipt"></i> Order #{{ $delivery->order->order_number ?? 'N/A' }}</strong>
                            <span class="badge bg-light text-dark">{{ ucfirst($delivery->status) }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-person text-muted"></i> <strong>{{ $delivery->recipient_name }}</strong>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-geo-alt text-muted"></i> 
                            {{ $delivery->delivery_address }}<br>
                            @if($delivery->order)
                            <small class="text-muted">
                                <i class="bi bi-building"></i> {{ $delivery->order->city ?? 'N/A' }}, {{ $delivery->order->barangay ?? 'N/A' }}
                            </small><br>
                            @if($delivery->order->landmark)
                            <small class="text-muted">
                                <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
                            </small>
                            @endif
                            @endif
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-telephone text-muted"></i> {{ $delivery->recipient_phone }}
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-clock text-muted"></i> Assigned: {{ $delivery->assigned_at ? $delivery->assigned_at->diffForHumans() : 'N/A' }}
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button type="button" class="btn btn-primary w-100" onclick="openDeliveryModal({{ $delivery->id }})">
                            <i class="bi bi-eye"></i> Update Delivery
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Completed Deliveries Section -->
    @if($completedDeliveries->count() > 0)
    <div class="mt-4">
        <h4 class="mb-3"><i class="bi bi-check-circle-fill text-success"></i> Completed Deliveries</h4>
        <div class="row">
            @foreach($completedDeliveries as $delivery)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong><i class="bi bi-receipt"></i> Order #{{ $delivery->order->order_number ?? 'N/A' }}</strong>
                            <span class="badge bg-light text-dark">Delivered</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-person text-muted"></i> <strong>{{ $delivery->recipient_name }}</strong>
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-geo-alt text-muted"></i> 
                            {{ $delivery->delivery_address }}<br>
                            @if($delivery->order)
                            <small class="text-muted">
                                <i class="bi bi-building"></i> {{ $delivery->order->city ?? 'N/A' }}, {{ $delivery->order->barangay ?? 'N/A' }}
                            </small><br>
                            @if($delivery->order->landmark)
                            <small class="text-muted">
                                <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
                            </small>
                            @endif
                            @endif
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-clock text-muted"></i> Delivered: {{ $delivery->delivered_at ? $delivery->delivered_at->format('M d, Y') : 'N/A' }}
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="openDeliveryModal({{ $delivery->id }})">
                            <i class="bi bi-eye"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- No Deliveries Message -->
    @if($activeDeliveries->count() == 0 && $completedDeliveries->count() == 0)
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-truck display-1 text-muted"></i>
            <h4 class="mt-3">No Deliveries Assigned</h4>
            <p class="text-muted">You don't have any deliveries assigned yet.</p>
        </div>
    </div>
    @endif

    <!-- Pagination for Completed Deliveries -->
    @if(method_exists($completedDeliveries, 'links') && $completedDeliveries->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $completedDeliveries->links() }}
    </div>
    @endif
</div>

<!-- Modal Container -->
<div id="modalContainer"></div>

<script>
    function openDeliveryModal(deliveryId) {
        const container = document.getElementById('modalContainer');
        
        container.innerHTML = `
            <div class="modal fade" id="deliveryModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center p-5">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2">Loading delivery details...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('deliveryModal'));
        modal.show();
        
        fetch(`/driver/deliveries/${deliveryId}`)
            .then(response => response.text())
            .then(html => {
                const modalContent = document.querySelector('#deliveryModal .modal-content');
                if (modalContent) {
                    modalContent.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const modalContent = document.querySelector('#deliveryModal .modal-content');
                if (modalContent) {
                    modalContent.innerHTML = `
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Error</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger">
                                Failed to load delivery details. Please try again.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    `;
                }
            });
    }
</script>
@endsection