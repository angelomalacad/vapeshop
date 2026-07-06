@extends('layouts.branch-admin')

@section('title', 'POS Purchase History - Vape Expo')

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

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">POS Purchase History</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-clock-history me-1"></i> All walk-in customer transactions
            </p>
        </div>
        <div>
            <a href="{{ route('branch-admin.pos.index') }}" class="btn btn-primary">
                <i class="bi bi-cash-coin"></i> New Sale
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper" style="background: #dbeafe; color: #2563eb;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Today's Sales</span>
                <h3 class="stat-value">₱{{ number_format($todaySales, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper" style="background: #d1fae5; color: #059669;">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Total Sales</span>
                <h3 class="stat-value">₱{{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card-modern">
            <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
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
            <div class="stat-icon-wrapper" style="background: #fee2e2; color: #dc2626;">
                <i class="bi bi-calculator"></i>
            </div>
            <div class="stat-content">
                <span class="stat-label">Avg. Order Value</span>
                <h3 class="stat-value">₱{{ $totalOrders > 0 ? number_format($totalSales / $totalOrders, 2) : '0.00' }}</h3>
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
                    <label class="form-label">Customer Name</label>
                    <input type="text" name="customer" class="form-control" placeholder="Search customer..." value="{{ request('customer') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Filter
                    </button>
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
                            <td>{{ $order->customer_name }}</td>
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
                                        @endif
                                        @if(strlen($order->notes) > 30)
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
                                    <a href="{{ route('branch-admin.pos.order.show', $order) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View Receipt">
                                        <i class="bi bi-receipt"></i> Receipt
                                    </a>
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
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-receipt display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No purchase history found</p>
                                <a href="{{ route('branch-admin.pos.index') }}" class="btn btn-primary">
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

<!-- Notes Modal (Optional - for viewing full notes) -->
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
    document.addEventListener('DOMContentLoaded', function() {
        // Notes hover functionality - show full note on click
        document.querySelectorAll('.notes-cell').forEach(cell => {
            cell.addEventListener('click', function() {
                const fullNote = this.querySelector('.notes-full');
                if (fullNote) {
                    const orderNumber = this.closest('tr').querySelector('code')?.textContent || '';
                    document.getElementById('notesOrderNumber').textContent = 'Order: ' + orderNumber;
                    document.getElementById('notesContent').textContent = fullNote.textContent.trim();
                    const modal = new bootstrap.Modal(document.getElementById('notesModal'));
                    modal.show();
                }
            });
        });
    });
    
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed bottom-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
</script>
@endpush
@endsection