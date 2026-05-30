@extends('layouts.driver')

@section('content')
<style>
    /* Modern Minimalist Styles */
    .page-header {
        margin-bottom: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .stats-container {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .stat-badge {
        padding: 0.5rem 1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .stat-badge-active {
        background: #eff6ff;
        color: #2563eb;
    }
    
    .stat-badge-completed {
        background: #ecfdf5;
        color: #059669;
    }
    
    .stat-badge-total {
        background: #f8f9fa;
        color: #1a1a2e;
    }
    
    /* Section Headers */
    .section-header {
        margin-bottom: 1.25rem;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .section-title i {
        margin-right: 0.5rem;
    }
    
    /* Delivery Cards */
    .delivery-card {
        border: none;
        border-radius: 16px;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }
    
    .delivery-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    
    .card-header-active {
        background: #eef2ff;
        padding: 0.875rem 1.25rem;
        border-bottom: none;
    }
    
    .card-header-completed {
        background: #ecfdf5;
        padding: 0.875rem 1.25rem;
        border-bottom: none;
    }
    
    .card-header-active .order-number,
    .card-header-completed .order-number {
        font-weight: 600;
        font-size: 0.875rem;
        color: #1a1a2e;
        margin-bottom: 0;
    }
    
    .status-badge-active {
        background: #dbeafe;
        color: #2563eb;
        padding: 0.25rem 0.65rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .status-badge-completed {
        background: #d1fae5;
        color: #059669;
        padding: 0.25rem 0.65rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    /* Card Body */
    .delivery-info {
        padding: 1rem 1.25rem;
    }
    
    .info-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .info-icon {
        width: 28px;
        color: #64748b;
        font-size: 0.9rem;
        margin-top: 2px;
    }
    
    .info-content {
        flex: 1;
    }
    
    .info-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-size: 0.85rem;
        color: #1a1a2e;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    .info-subvalue {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
    
    /* Card Footer */
    .card-footer-custom {
        background: white;
        padding: 0.875rem 1.25rem;
        border-top: 1px solid #eef2f6;
    }
    
    /* Buttons - Blue Color */
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
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }
    
    .btn-view {
        background: #eff6ff;
        color: #3b82f6;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .btn-view:hover {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
        transform: translateY(-1px);
    }
    
    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 16px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    .empty-state h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0;
    }
    
    /* Pagination */
    .pagination {
        margin-bottom: 0;
        margin-top: 1rem;
    }
    
    .pagination .page-link {
        border: none;
        color: #1a1a2e;
        border-radius: 30px;
        margin: 0 2px;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .pagination .page-link:hover {
        background: #f1f5f9;
        color: #1a1a2e;
    }
    
    .pagination .active .page-link {
        background: #3b82f6;
        color: white;
    }
    
    /* Section Count Badge */
    .count-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 0.25rem 0.65rem;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
        margin-left: 0.5rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.25rem;
        }
        
        .delivery-card {
            margin-bottom: 1rem;
        }
        
        .empty-state {
            padding: 2rem;
        }
        
        .stats-container {
            margin-top: 0.5rem;
        }
    }
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="page-title"><i class="bi bi-truck me-2 text-primary"></i> My Deliveries</h2>
        </div>
        <div class="stats-container">
            <span class="stat-badge stat-badge-active">
                <i class="bi bi-play-circle-fill me-1"></i> {{ $activeCount ?? 0 }} Active
            </span>
            <span class="stat-badge stat-badge-completed">
                <i class="bi bi-check-circle-fill me-1"></i> {{ $completedCount ?? 0 }} Completed
            </span>
            <span class="stat-badge stat-badge-total">
                <i class="bi bi-receipt me-1"></i> {{ $totalDeliveries ?? 0 }} Total
            </span>
        </div>
    </div>
    
    <!-- Active Deliveries Section -->
    @if($activeDeliveries->count() > 0)
    <div class="mb-4">
        <div class="section-header">
            <h4 class="section-title">
                <i class="bi bi-play-circle-fill text-warning"></i> Active Deliveries
                <span class="count-badge">{{ $activeCount ?? 0 }} active</span>
            </h4>
        </div>
        <div class="row g-4">
            @foreach($activeDeliveries as $delivery)
            <div class="col-md-6">
                <div class="delivery-card">
                    <div class="card-header-active d-flex justify-content-between align-items-center">
                        <div class="order-number">
                            <i class="bi bi-receipt me-1"></i> Order #{{ $delivery->order->order_number ?? 'N/A' }}
                        </div>
                        <span class="status-badge-active">
                            <i class="bi bi-{{ $delivery->status == 'in_transit' ? 'truck' : ($delivery->status == 'picked_up' ? 'box-seam' : 'clock') }} me-1"></i>
                            {{ ucfirst($delivery->status) }}
                        </span>
                    </div>
                    <div class="delivery-info">
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Customer</div>
                                <div class="info-value">{{ $delivery->recipient_name }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Delivery Address</div>
                                <div class="info-value">{{ Str::limit($delivery->delivery_address, 50) }}</div>
                                @if($delivery->order)
                                <div class="info-subvalue">
                                    <i class="bi bi-building me-1"></i> {{ $delivery->order->city ?? 'N/A' }}, {{ $delivery->order->barangay ?? 'N/A' }}
                                </div>
                                @endif
                                @if($delivery->order && $delivery->order->landmark)
                                <div class="info-subvalue">
                                    <i class="bi bi-pin-map me-1"></i> Landmark: {{ $delivery->order->landmark }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Contact</div>
                                <div class="info-value">{{ $delivery->recipient_phone }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Assigned</div>
                                <div class="info-value">{{ $delivery->assigned_at ? $delivery->assigned_at->diffForHumans() : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer-custom">
                        <button type="button" class="btn-update" onclick="openDeliveryModal({{ $delivery->id }})">
                            <i class="bi bi-truck me-2"></i> Update Delivery
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination for Active Deliveries -->
        @if($activeDeliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $activeDeliveries->links() }}
        </div>
        @endif
    </div>
    @endif

    <!-- Completed Deliveries Section -->
    @if($completedDeliveries->count() > 0)
    <div class="mt-4">
        <div class="section-header">
            <h4 class="section-title">
                <i class="bi bi-check-circle-fill text-success"></i> Completed Deliveries
                <span class="count-badge">{{ $completedCount ?? 0 }} completed</span>
            </h4>
        </div>
        <div class="row g-4">
            @foreach($completedDeliveries as $delivery)
            <div class="col-md-6">
                <div class="delivery-card">
                    <div class="card-header-completed d-flex justify-content-between align-items-center">
                        <div class="order-number">
                            <i class="bi bi-receipt me-1"></i> Order #{{ $delivery->order->order_number ?? 'N/A' }}
                        </div>
                        <span class="status-badge-completed">
                            <i class="bi bi-check-circle-fill me-1"></i> Delivered
                        </span>
                    </div>
                    <div class="delivery-info">
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Customer</div>
                                <div class="info-value">{{ $delivery->recipient_name }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Delivery Address</div>
                                <div class="info-value">{{ Str::limit($delivery->delivery_address, 50) }}</div>
                                @if($delivery->order)
                                <div class="info-subvalue">
                                    <i class="bi bi-building me-1"></i> {{ $delivery->order->city ?? 'N/A' }}, {{ $delivery->order->barangay ?? 'N/A' }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Contact</div>
                                <div class="info-value">{{ $delivery->recipient_phone }}</div>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Delivered On</div>
                                <div class="info-value">{{ $delivery->delivered_at ? $delivery->delivered_at->format('M d, Y h:i A') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer-custom">
                        <button type="button" class="btn-view" onclick="openDeliveryModal({{ $delivery->id }})">
                            <i class="bi bi-eye me-2"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination for Completed Deliveries -->
        @if($completedDeliveries->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $completedDeliveries->links() }}
        </div>
        @endif
    </div>
    @endif

    <!-- No Deliveries Message -->
    @if($activeDeliveries->count() == 0 && $completedDeliveries->count() == 0)
    <div class="empty-state">
        <i class="bi bi-truck"></i>
        <h5>No Deliveries Assigned</h5>
        <p>You don't have any deliveries assigned yet.</p>
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
                            <p class="mt-2 text-muted">Loading delivery details...</p>
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
                        <div class="modal-header" style="border-bottom: 1px solid #eef2f6;">
                            <h5 class="modal-title">Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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