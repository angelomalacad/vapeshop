@extends('layouts.admin')

@section('title', 'Delivery Management - Vape Expo')
<style>
    .stat-card-modern {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        border: 1px solid #eef2f6;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .stat-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
        border-color: #e0e7ed;
    }

    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: all 0.3s ease;
    }

    .stat-card-modern:hover .stat-icon-wrapper {
        transform: scale(1.02);
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 600;
        color: #8b9cb0;
        display: block;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        line-height: 1.2;
    }

    @media (max-width: 768px) {
        .stat-card-modern {
            padding: 1rem;
            gap: 0.75rem;
        }

        .stat-icon-wrapper {
            width: 44px;
            height: 44px;
            font-size: 1.3rem;
            border-radius: 14px;
        }

        .stat-value {
            font-size: 1.4rem;
        }

        .stat-label {
            font-size: 0.65rem;
        }
    }
</style>
@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold">
                    <i class="bi bi-truck me-2 text-primary"></i>Delivery History
                </h1>
                <p class="text-muted mb-0">Delivery status updated by drivers</p>
            </div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('admin.deliveries.export') }}" class="btn btn-success rounded-pill px-3">
                    <i class="bi bi-download me-1"></i> Export Report
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Deliveries -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #dbeafe; color: #2563eb;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Deliveries</span>
                        <h3 class="stat-value">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Active Deliveries -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Active Deliveries</span>
                        <h3 class="stat-value">{{ $stats['active_today'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Completed -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Completed</span>
                        <h3 class="stat-value">{{ $stats['delivered'] }}</h3>
                    </div>
                </div>
            </div>

            <!-- Today's Driver -->
            <div class="col-md-3 col-6">
                <div class="stat-card-modern">
                    <div class="stat-icon-wrapper" style="background: #ede9fe; color: #7c3aed;">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Today's Driver</span>
                        <h3 class="stat-value" style="font-size: 1.2rem; font-weight: 600;">
                            {{ $todayDriverName ?? 'Not assigned' }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Deliveries Section -->
        @if ($activeToday->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-play-circle-fill text-warning me-2"></i>
                        Active Deliveries
                        <span class="badge bg-warning ms-2">{{ $activeToday->count() }}</span>
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
                                @foreach ($activeToday as $delivery)
                                    @php
                                        $statusColors = [
                                            'assigned' => 'info',
                                            'picked_up' => 'primary',
                                            'in_transit' => 'warning',
                                        ];
                                    @endphp
                                    <tr>
                                        <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                                        <td>{{ $delivery->order->order_number ?? 'N/A' }}
                    </div>
                    </td>
                    <td>{{ $delivery->recipient_name }}
                </div>
                </td>
                <td>
                    <strong>{{ $delivery->delivery_address }}</strong><br>
                    <small class="text-muted">
                        <i class="bi bi-geo-alt"></i>
                        {{ $delivery->order->city ?? 'N/A' }},
                        {{ $delivery->order->barangay ?? 'N/A' }}
                    </small><br>
                    @if ($delivery->order->landmark)
                        <small class="text-muted">
                            <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
                        </small>
                    @endif
            </div>
            </td>
            <td><span
                    class="badge bg-{{ $statusColors[$delivery->status] ?? 'secondary' }}">{{ ucfirst($delivery->status) }}</span>
    </div>
    </td>
    <td>{{ $delivery->driver->name ?? 'Waiting...' }}</div>
    </td>
    <td>{{ $delivery->updated_at->diffForHumans() }}</div>
    </td>
    <td class="pe-4">
        <button type="button" class="btn btn-sm btn-info rounded-pill px-3"
            onclick="loadDeliveryModal({{ $delivery->id }})">
            <i class="bi bi-eye me-1"></i> View
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

        <!-- Completed Deliveries Section -->
        @php
            $completedDeliveries = $deliveries->filter(function ($delivery) {
                return in_array($delivery->status, ['delivered', 'failed']);
            });
        @endphp

        @if ($completedDeliveries->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        Delivery History
                        <span class="badge bg-success ms-2">{{ $completedDeliveries->count() }}</span>
                    </h5>
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
                                @foreach ($completedDeliveries as $delivery)
                                    @php
                                        $statusColors = [
                                            'delivered' => 'success',
                                            'failed' => 'danger',
                                        ];
                                    @endphp
                                    <tr>
                                        <td class="ps-4"><code>{{ $delivery->tracking_number }}</code></td>
                                        <td>{{ $delivery->created_at->format('M d, Y') }}
                    </div>
    </td>
    <td>{{ $delivery->order->order_number ?? 'N/A' }}</div>
    </td>
    <td>{{ $delivery->recipient_name }}</div>
    </td>
    <td>
        <strong>{{ $delivery->delivery_address }}</strong><br>
        <small class="text-muted">
            <i class="bi bi-geo-alt"></i>
            {{ $delivery->order->city ?? 'N/A' }},
            {{ $delivery->order->barangay ?? 'N/A' }}
        </small><br>
        @if ($delivery->order->landmark)
            <small class="text-muted">
                <i class="bi bi-pin-map"></i> Landmark: {{ $delivery->order->landmark }}
            </small>
        @endif
        </div>
    </td>
    <td><span
            class="badge bg-{{ $statusColors[$delivery->status] ?? 'secondary' }}">{{ ucfirst($delivery->status) }}</span>
        </div>
    </td>
    <td>{{ $delivery->driver->name ?? 'Unassigned' }}</div>
    </td>
    <td>
        @if ($delivery->delivery_proof)
            <button type="button" class="btn btn-sm btn-outline-success me-1 rounded-pill" title="Delivery Proof"
                onclick="viewProof('{{ Storage::url($delivery->delivery_proof) }}', 'Delivery Proof')">
                <i class="bi bi-camera"></i>
            </button>
        @endif
        @if ($delivery->payment_proof)
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" title="Payment Proof"
                onclick="viewProof('{{ Storage::url($delivery->payment_proof) }}', 'Payment Proof')">
                <i class="bi bi-receipt"></i>
            </button>
        @endif
        </div>
    <td class="pe-4">
        <button type="button" class="btn btn-sm btn-info rounded-pill px-3"
            onclick="loadDeliveryModal({{ $delivery->id }})">
            <i class="bi bi-eye me-1"></i> View
        </button>
        </div>
        </tr>
        @endforeach
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
        @endif

        <!-- No Deliveries Message -->
        @if ($deliveries->count() == 0)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="mt-3">No deliveries found</p>
                </div>
            </div>
        @endif
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
                        <img id="proofModalImage" src="" class="img-fluid"
                            style="max-height: 80vh; width: auto;">
                    </div>
                    <div class="modal-footer bg-dark border-0">
                        <a id="proofModalDownload" href="#" download class="btn btn-success rounded-pill">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <button type="button" class="btn btn-secondary rounded-pill"
                            data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Rounded buttons */
            .btn-sm {
                border-radius: 30px !important;
            }

            /* Table hover effect */
            .table tbody tr:hover {
                background-color: rgba(13, 110, 253, 0.04);
                cursor: pointer;
            }

            /* Badge styles */
            .badge {
                font-weight: 500;
                padding: 0.35rem 0.65rem;
                border-radius: 30px;
            }
        </style>

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

                // Remove any existing backdrops
                const existingBackdrops = document.querySelectorAll('.modal-backdrop');
                existingBackdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');

                const modalHtml = `
            <div class="modal fade" id="deliveryModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body text-center p-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading delivery details...</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

                container.innerHTML = modalHtml;

                const modalElement = document.getElementById('deliveryModal');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();

                fetch(`/admin/deliveries/${deliveryId}/modal`)
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
