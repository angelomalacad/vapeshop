@extends('layouts.admin')

@section('title', 'Payment Receipt - Vape Expo')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-body p-0">
                        <!-- Receipt Header with Modern UI -->
                        <div class="text-center p-4" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="60"
                                style="filter: brightness(0) invert(1);">
                            <h4 class="mt-2 mb-0 text-white">Vape Expo</h4>
                            <p class="text-white-50 small mb-0">{{ $receipt['branch_name'] }}</p>
                            <p class="text-white-50 small">{{ $receipt['date'] }}</p>
                        </div>

                        <!-- Receipt Content -->
                        <div class="p-4">
                            <!-- Receipt Info -->
                            <div class="mb-3">
                                <div class="info-row">
                                    <span class="info-label">Order #</span>
                                    <span class="info-value fw-semibold">{{ $receipt['order_number'] }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Cashier</span>
                                    <span class="info-value">{{ $receipt['cashier'] }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Customer</span>
                                    <span class="info-value">{{ $receipt['customer_name'] }}</span>
                                </div>
                            </div>

                            <hr style="border-color: #eef2f6;">

                            <!-- Items -->
                            <div class="mb-3">
                                <h6 class="fw-semibold"
                                    style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.6px; color: #8b9cb0; margin-bottom: 1rem;">
                                    <i class="bi bi-box-seam me-2"></i>Items
                                </h6>
                                @foreach ($receipt['items'] as $item)
                                    <div class="d-flex justify-content-between mb-2"
                                        style="padding: 0.5rem 0; border-bottom: 1px dashed #eef2f6;">
                                        <div>
                                            <span
                                                style="font-weight: 500; font-size: 0.85rem;">{{ $item['product_name'] }}</span>
                                            @if ($item['flavor_name'])
                                                <br><small class="text-muted"
                                                    style="font-size: 0.7rem;">{{ $item['flavor_name'] }}</small>
                                            @endif
                                            <br><small class="text-muted" style="font-size: 0.7rem;">{{ $item['quantity'] }}
                                                x ₱{{ number_format($item['price'], 2) }}</small>
                                        </div>
                                        <span class="fw-semibold"
                                            style="font-size: 0.85rem;">₱{{ number_format($item['subtotal'], 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <hr style="border-color: #eef2f6;">

                            <!-- Totals -->
                            <div class="mb-3" style="background: #f8f9fa; border-radius: 12px; padding: 1rem;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted" style="font-size: 0.8rem;">Subtotal:</span>
                                    <span style="font-size: 0.8rem;">₱{{ number_format($receipt['subtotal'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted" style="font-size: 0.8rem;">Tax (12%):</span>
                                    <span style="font-size: 0.8rem;">₱{{ number_format($receipt['tax'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between fw-bold"
                                    style="font-size: 1.1rem; border-top: 2px solid #eef2f6; padding-top: 0.75rem; margin-top: 0.5rem;">
                                    <span>TOTAL:</span>
                                    <span style="color: #059669;">₱{{ number_format($receipt['total'], 2) }}</span>
                                </div>
                            </div>

                            <hr style="border-color: #eef2f6;">

                            <!-- Payment -->
                            <div class="mb-3" style="background: #eff6ff; border-radius: 12px; padding: 1rem;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted" style="font-size: 0.8rem;">Payment Method:</span>
                                    <span class="text-uppercase fw-semibold"
                                        style="font-size: 0.8rem; color: #2563eb;">{{ $receipt['payment_method'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted" style="font-size: 0.8rem;">Amount Paid:</span>
                                    <span
                                        style="font-size: 0.8rem;">₱{{ number_format($receipt['amount_paid'], 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted" style="font-size: 0.8rem;">Change:</span>
                                    <span class="fw-bold"
                                        style="color: #059669; font-size: 0.9rem;">₱{{ number_format($receipt['change'], 2) }}</span>
                                </div>
                            </div>

                            <hr style="border-color: #eef2f6;">

                            <!-- Footer -->
                            <div class="text-center">
                                <p class="small text-muted mb-0">Thank you for shopping at Vape Expo!</p>
                                <p class="small text-muted mb-0">Business Hours: 9:00 AM - 10:00 PM</p>
                                <p class="small text-muted">Contact: 0960 328 0432</p>
                            </div>

                            <!-- Buttons with Global UI -->
                            <div class="d-flex gap-2 mt-4">
                                <button onclick="window.print()" class="btn-update" style="width: auto; flex: 1;">
                                    <i class="bi bi-printer me-2"></i> Print Receipt
                                </button>
                                <a href="{{ route('admin.pos.index') }}" class="btn-secondary-minimal"
                                    style="display: flex; align-items: center; justify-content: center; flex: 1; text-decoration: none;">
                                    <i class="bi bi-cart me-2"></i> New Sale
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Global UI Styles */
        .info-row {
            display: flex;
            margin-bottom: 0.75rem;
        }

        .info-label {
            width: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .info-value {
            flex: 1;
            font-size: 0.8rem;
            color: #1a1a2e;
            font-weight: 500;
        }

        .btn-update {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-update:hover {
            background: #2563eb;
            transform: translateY(-1px);
            color: white;
        }

        .btn-secondary-minimal {
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 12px;
            padding: 0.6rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-secondary-minimal:hover {
            background: #e2e8f0;
            color: #475569;
        }

        @media print {

            .btn-update,
            .btn-secondary-minimal,
            .navbar,
            .sidebar,
            .top-navbar {
                display: none !important;
            }

            .main-content {
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }

            .card-body.p-0 {
                border-radius: 0 !important;
            }
        }

        @media (max-width: 768px) {
            .info-label {
                width: 80px;
            }

            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
    </style>
@endsection
