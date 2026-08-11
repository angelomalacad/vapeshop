@extends('layouts.admin')

@section('title', 'POS Purchase History - Admin')

@section('content')
<style>
    .badge-gcash {
        background: #0073ba;
        color: white;
    }
    
    .badge-cash {
        background: #22c55e;
        color: white;
    }
    
    .btn-group-action .btn {
        margin-right: 6px;
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .btn-group-action .btn:last-child {
        margin-right: 0;
    }
    
    .btn-group-action .btn i {
        margin-right: 4px;
    }

    .notes-cell {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
    }

    .notes-cell:hover {
        white-space: normal;
        overflow: visible;
        background: #f8f9fa;
        position: relative;
        z-index: 10;
    }

    .notes-cell .notes-text {
        display: inline-block;
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .notes-cell .notes-full {
        display: none;
        position: absolute;
        background: white;
        padding: 8px 12px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: 1px solid #e2e8f0;
        z-index: 100;
        min-width: 200px;
        max-width: 400px;
        white-space: normal;
        word-wrap: break-word;
        top: -10px;
        left: 0;
    }

    .notes-cell:hover .notes-full {
        display: block;
    }

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

    /* Proof Thumbnail Styles */
    .proof-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid #e2e8f0;
        transition: all 0.2s;
        background: #f8f9fa;
    }

    .proof-thumbnail:hover {
        transform: scale(1.1);
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .proof-thumbnail-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-preview-modal .modal-body {
        text-align: center;
        padding: 20px;
    }

    .image-preview-modal .modal-body img {
        max-height: 70vh;
        max-width: 100%;
        border-radius: 8px;
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
        .proof-thumbnail {
            width: 40px;
            height: 40px;
        }
    }

    @media print {
        .sidebar, .top-navbar, .btn, .modal-footer, .card-header .btn, .pagination, .filter-section {
            display: none !important;
        }
        .modal-content {
            box-shadow: none !important;
            border: none !important;
        }
        .modal-dialog {
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">POS Sales History</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-clock-history me-1"></i> All POS transactions across all branches
                </p>
            </div>
        </div>
    </div>

   <!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper" style="background: #dbeafe; color: #2563eb;">
                <i class>₱</i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Sales</span>
                <h3 class="stat-value">₱{{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669;">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Orders</span>
                <h3 class="stat-value">{{ $totalOrders }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
                <i class="bi bi-calendar3"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Today's Sales</span>
                <h3 class="stat-value">₱{{ number_format($todaySales, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper" style="background: #fee2e2; color: #dc2626;">
                <i class="bi bi-cart-check"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Today's Orders</span>
                <h3 class="stat-value">{{ $todayOrders }}</h3>
            </div>
        </div>
    </div>
</div>
    <!-- Sales by Branch & Payment Method -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-shop me-2 text-primary"></i>Sales by Branch</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salesByBranch as $branch)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $branch->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $branch->orders_count ?? 0 }}</span>
                                        </td>
                                        <td class="text-end">
                                            ₱{{ number_format($branch->orders_sum_total_amount ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th class="fw-bold">Total</th>
                                    <th class="text-center fw-bold">{{ $totalOrders }}</th>
                                    <th class="text-end fw-bold">₱{{ number_format($totalSales, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-credit-card me-2 text-primary"></i>Sales by Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Payment Method</th>
                                    <th class="text-center">Orders</th>
                                    <th class="text-end">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salesByPayment as $payment)
                                    <tr>
                                        <td>
                                            <span class="badge badge-{{ $payment->payment_method == 'cash' ? 'cash' : 'gcash' }} text-uppercase">
                                                {{ $payment->payment_method == 'cash' ? 'Cash' : 'GCash' }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $payment->total_orders }}</td>
                                        <td class="text-end">₱{{ number_format($payment->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Orders</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer" class="form-control" placeholder="Search customer..." value="{{ request('customer') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="">All</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.pos.history') }}" class="btn btn-outline-secondary ms-2 px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Date & Time</th>
                            <th>Branch</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Cashier</th>
                            <th>Notes</th>
                            <th class="pe-4 text-center">Action</th>
                            <th class="pe-4 text-center">Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4"><code>{{ $order->order_number }}</code></td>
                            <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $order->branch->name }}</span>
                            </td>
                            <td>{{ $order->customer_name ?? 'Walk-in Customer' }}</td>
                            <td>{{ $order->items->count() }} items</td>
                            <td class="fw-bold">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $order->payment_method === 'gcash' ? 'gcash' : 'cash' }} text-uppercase">
                                    <i class="bi bi-{{ $order->payment_method === 'gcash' ? 'phone' : 'cash' }} me-1"></i>
                                    {{ $order->payment_method }}
                                </span>
                            </td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                @if($order->notes)
                                    <div class="notes-cell" title="Click to view full note">
                                        <span class="notes-text">{{ Str::limit($order->notes, 30) }}</span>
                                        @if(strlen($order->notes) > 30)
                                            <span class="notes-full">{{ $order->notes }}</span>
                                            <i class="bi bi-info-circle text-muted ms-1" title="Hover to see full note"></i>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="pe-4 text-center">
                                <div class="btn-group-action d-flex justify-content-center">
                                    <!-- Receipt Button -->
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#receiptModal{{ $order->id }}">
                                        <i class="bi bi-receipt"></i> Receipt
                                    </button>
                                </div>
                            </td>
                            <td class="pe-4 text-center">
                                @if($order->payment_method === 'gcash' && $order->payment_proof)
                                    @php
                                        $proofUrl = asset('storage/' . $order->payment_proof);
                                    @endphp
                                    <div class="proof-thumbnail-wrapper">
                                        <img src="{{ $proofUrl }}" 
                                             alt="Payment Proof" 
                                             class="proof-thumbnail"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#proofModal{{ $order->id }}"
                                             data-proof="{{ $proofUrl }}"
                                             data-order="{{ $order->order_number }}"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-muted\' style=\'font-size:0.7rem;\'>No image</span>'"
                                             title="Click to view full image">
                                    </div>
                                    
                                    <!-- Proof Modal per order -->
                                    <div class="modal fade image-preview-modal" id="proofModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-image me-2"></i> Payment Proof
                                                        <small class="text-muted d-block">Order: {{ $order->order_number }}</small>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ $proofUrl }}" alt="Payment Proof" class="img-fluid">
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ $proofUrl }}" download class="btn btn-primary">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($order->payment_method === 'gcash')
                                    <span class="text-muted" style="font-size:0.7rem;">No proof</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center py-5">
                                <i class="bi bi-receipt display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No purchase history found</p>
                                <a href="{{ route('admin.pos.index') }}" class="btn btn-primary">
                                    <i class="bi bi-cash-coin"></i> Make First Sale
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modals -->
@foreach($orders as $order)
<div class="modal fade" id="receiptModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-receipt me-2"></i>Order Receipt</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Vape Expo" height="50">
                    <h5 class="mt-2 mb-0">Vape Expo</h5>
                    <p class="text-muted small mb-0">{{ $order->branch->name }}</p>
                    <p class="text-muted small">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="mb-2">
                    <div class="d-flex justify-content-between"><span class="text-muted">Order #:</span><span class="fw-semibold">{{ $order->order_number }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Cashier:</span><span>{{ $order->user->name }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Customer:</span><span>{{ $order->customer_name ?? 'Walk-in Customer' }}</span></div>
                    @if ($order->notes)<div class="d-flex justify-content-between"><span class="text-muted">Notes:</span><span class="text-muted">{{ $order->notes }}</span></div>@endif
                </div>
                <hr>
                @foreach ($order->items as $item)
                    <div class="d-flex justify-content-between mb-1">
                        <div>{{ $item->product->name }}<br><small class="text-muted">{{ $item->quantity }} x ₱{{ number_format($item->price, 2) }}</small></div>
                        <span>₱{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5"><span>TOTAL:</span><span>₱{{ number_format($order->total_amount, 2) }}</span></div>
                <hr>
                <div class="d-flex justify-content-between"><span>Payment:</span><span class="text-uppercase">{{ $order->payment_method == 'cash' ? 'Cash' : 'GCash' }}</span></div>
                <div class="text-center mt-3"><small class="text-muted">Thank you for shopping at Vape Expo!</small></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button onclick="printReceipt({{ $order->id }})" class="btn btn-primary"><i class="bi bi-printer"></i> Print</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-sticky-fill me-2"></i> Order Notes
                    <small class="text-muted d-block" id="notesOrderNumber"></small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="notesContent" class="p-3 bg-light rounded" style="white-space: pre-wrap; word-wrap: break-word;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printReceipt(orderId) {
    var modalContent = document.querySelector('#receiptModal' + orderId + ' .modal-body');
    if (!modalContent) return;
    var clone = modalContent.cloneNode(true);
    var printWindow = window.open('', '_blank');
    if (printWindow) {
        printWindow.document.write('<html><head><title>Receipt</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><style>body{padding:20px}@media print{body{padding:0}}</style></head><body>' + clone.outerHTML + '<script>window.print();window.close();<\/script></body></html>');
        printWindow.document.close();
    }
}

// Notes click
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.notes-cell').forEach(function(cell) {
        cell.addEventListener('click', function() {
            var fullNote = this.querySelector('.notes-full');
            if (fullNote) {
                var orderNumber = this.closest('tr').querySelector('code')?.textContent || '';
                document.getElementById('notesOrderNumber').textContent = 'Order: ' + orderNumber;
                document.getElementById('notesContent').textContent = fullNote.textContent.trim();
                var modal = new bootstrap.Modal(document.getElementById('notesModal'));
                modal.show();
            }
        });
    });
});
</script>
@endpush
@endsection