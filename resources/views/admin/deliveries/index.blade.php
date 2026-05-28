@extends('layouts.admin')

@section('title', 'Delivery Management - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">
                <i class="bi bi-truck me-2 text-primary"></i>Delivery Management
            </h1>
            <p class="text-muted mb-0">Real-time delivery status updated by drivers</p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.deliveries.export') }}" class="btn btn-success rounded-pill px-3">
                <i class="bi bi-download me-1"></i> Export Report
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Deliveries</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                        </div>
                        <i class="bi bi-truck fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Active Today</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['active_today'] }}</h2>
                        </div>
                        <i class="bi bi-arrow-repeat fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Completed</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['delivered'] }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Today's Driver</h6>
                            <h2 class="mb-0 fw-bold small">{{ $todayDriverName ?? 'Not assigned' }}</h2>
                        </div>
                        <i class="bi bi-person-badge fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Deliveries Today -->
    @if($stats['active_today'] > 0)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-truck me-2 text-primary"></i>
                Active Deliveries Today ({{ date('F d, Y') }})
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tracking #</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Driver</th>
                            <th>Last Update</th>
                            <th class="pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeToday as $delivery)
                        @php
                            $statusColors = [
                                'assigned' => 'info',
                                'picked_up' => 'primary',
                                'in_transit' => 'warning',
                                'delivered' => 'success',
                            ];
                        @endphp
                        <tr>
                            <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                            <td>{{ $delivery->order->order_number ?? 'N/A' }}</div></td>
                            <td>{{ $delivery->recipient_name }}</div></td>
                            <td>
                                <strong>{{ $delivery->delivery_address }}</strong><br>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> 
                                    {{ $delivery->order->city ?? 'N/A' }}, 
                                    {{ $delivery->order->barangay ?? 'N/A' }}
                                </small><br>
                                @if($delivery->order->landmark)
                                <small class="text-muted">
                                    <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
                                </small>
                                @endif
                            </div></td>
                            <td><span class="badge bg-{{ $statusColors[$delivery->status] ?? 'secondary' }}">{{ ucfirst($delivery->status) }}</span></div></td>
                            <td>{{ $delivery->driver->name ?? 'Waiting...' }}</div></td>
                            <td>{{ $delivery->updated_at->diffForHumans() }}</div></td>
                            <td class="pe-4">
                                <button type="button" class="btn btn-sm btn-info" onclick="loadDeliveryModal({{ $delivery->id }})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </div>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- All Deliveries Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2 text-primary"></i>All Deliveries</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tracking #</th>
                            <th>Date</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Driver</th>
                            <th>Proofs</th>
                            <th class="pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)
                        @php
                            $statusColors = [
                                'pending' => 'secondary',
                                'assigned' => 'info',
                                'picked_up' => 'primary',
                                'in_transit' => 'warning',
                                'delivered' => 'success',
                                'failed' => 'danger',
                            ];
                        @endphp
                        <tr>
                            <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                            <td>{{ $delivery->created_at->format('M d, Y') }}</div></td>
                            <td>{{ $delivery->order->order_number ?? 'N/A' }}</div></td>
                            <td>{{ $delivery->recipient_name }}</div></td>
                            <td>
                                <strong>{{ $delivery->delivery_address }}</strong><br>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt"></i> 
                                    {{ $delivery->order->city ?? 'N/A' }}, 
                                    {{ $delivery->order->barangay ?? 'N/A' }}
                                </small><br>
                                @if($delivery->order->landmark)
                                <small class="text-muted">
                                    <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
                                </small>
                                @endif
                            </div></td>
                            <td><span class="badge bg-{{ $statusColors[$delivery->status] ?? 'secondary' }}">{{ ucfirst($delivery->status) }}</span></div></td>
                            <td>{{ $delivery->driver->name ?? 'Unassigned' }}</div></td>
                            <td>
                                @if($delivery->delivery_proof)
                                <button type="button" class="btn btn-sm btn-outline-success me-1" title="Delivery Proof" onclick="viewProof('{{ Storage::url($delivery->delivery_proof) }}', 'Delivery Proof')">
                                    <i class="bi bi-camera"></i>
                                </button>
                                @endif
                                @if($delivery->payment_proof)
                                <button type="button" class="btn btn-sm btn-outline-primary" title="Payment Proof" onclick="viewProof('{{ Storage::url($delivery->payment_proof) }}', 'Payment Proof')">
                                    <i class="bi bi-receipt"></i>
                                </button>
                                @endif
                            </div> 
                            <td class="pe-4">
                                <button type="button" class="btn btn-sm btn-info" onclick="loadDeliveryModal({{ $delivery->id }})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </div>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-inbox display-1 text-muted"></i>
                                <p class="mt-3">No deliveries found</p>
                            </div>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $deliveries->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Container -->
<div id="deliveryModalContainer"></div>

<!-- Proof Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title" id="proofModalTitle">Proof</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="proofModalImage" src="" class="img-fluid" style="max-height: 80vh; width: auto;">
            </div>
            <div class="modal-footer bg-dark border-0">
                <a id="proofModalDownload" href="#" download class="btn btn-success">
                    <i class="bi bi-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewProof(imageUrl, title) {
        document.getElementById('proofModalTitle').textContent = title;
        document.getElementById('proofModalImage').src = imageUrl;
        document.getElementById('proofModalDownload').href = imageUrl;
        new bootstrap.Modal(document.getElementById('proofModal')).show();
    }

    function loadDeliveryModal(deliveryId) {
        const container = document.getElementById('deliveryModalContainer');
        container.innerHTML = '';
        
        fetch(`/admin/deliveries/${deliveryId}/modal`)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                const modal = new bootstrap.Modal(document.getElementById('deliveryModal'));
                modal.show();
                
                document.getElementById('deliveryModal').addEventListener('hidden.bs.modal', function() {
                    container.innerHTML = '';
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Could not load delivery details');
            });
    }
</script>
@endsection