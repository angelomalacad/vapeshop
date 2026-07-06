@extends('layouts.branch-admin')

@section('title', 'Payment Receipt - Vape Expo')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <!-- Receipt Header -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="60">
                            <h4 class="mt-2 mb-0">Vape Expo</h4>
                            <p class="text-muted small mb-0">{{ $receipt['branch_name'] }}</p>
                            <p class="text-muted small">{{ $receipt['date'] }}</p>
                        </div>

                        <!-- Receipt Info -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Order #:</span>
                                <span class="fw-semibold">{{ $receipt['order_number'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Cashier:</span>
                                <span>{{ $receipt['cashier'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Customer:</span>
                                <span>{{ $receipt['customer_name'] }}</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Items -->
                        <div class="mb-3">
                            @foreach ($receipt['items'] as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span>{{ $item['product_name'] }}</span>
                                        @if ($item['flavor_name'])
                                            <br><small class="text-muted">{{ $item['flavor_name'] }}</small>
                                        @endif
                                        <br><small>{{ $item['quantity'] }} x ₱{{ number_format($item['price'], 2) }}</small>
                                    </div>
                                    <span class="fw-semibold">₱{{ number_format($item['subtotal'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <!-- Totals -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>₱{{ number_format($receipt['subtotal'], 2) }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>TOTAL:</span>
                                <span>₱{{ number_format($receipt['total'], 2) }}</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Payment -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Payment Method:</span>
                                <span class="text-uppercase">{{ $receipt['payment_method'] }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Amount Paid:</span>
                                <span>₱{{ number_format($receipt['amount_paid'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-success">
                                <span>Change:</span>
                                <span>₱{{ number_format($receipt['change'], 2) }}</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Footer -->
                        <div class="text-center">
                            <p class="small text-muted mb-0">Thank you for shopping at Vape Expo!</p>
                            <p class="small text-muted">Business Hours: 9:00 AM - 10:00 PM</p>
                            <p class="small text-muted">Contact: 0960 328 0432</p>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button onclick="window.print()" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-printer"></i> Print Receipt
                            </button>
                            <a href="{{ route('branch-admin.pos.index') }}" class="btn btn-secondary flex-grow-1">
                                <i class="bi bi-cart"></i> New Sale
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .btn,
            .navbar,
            .sidebar,
            .top-navbar,
            .footer-info,
            .branch-info-card {
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
        }
    </style>
@endsection
