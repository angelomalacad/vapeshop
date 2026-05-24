@extends('layouts.admin')

@section('title', 'Low Stock Alert - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Logo and Navigation -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Low Stock Alert</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-exclamation-triangle me-1 text-danger"></i> Items that need immediate attention
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-box-seam me-1"></i> All Inventory
            </a>
            <a href="{{ route('admin.inventory.transfers') }}" class="btn btn-info text-white rounded-pill px-3">
                <i class="bi bi-arrow-left-right me-1"></i> Transfers
            </a>
        </div>
    </div>

    @php
        $lowStockItems = \App\Models\BranchInventory::with(['branch', 'product', 'flavor'])
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('branch_id')
            ->orderBy('quantity', 'asc')
            ->get()
            ->groupBy('branch.name');
    @endphp

    @forelse($lowStockItems as $branchName => $branchItems)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-danger bg-gradient text-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-shop me-2"></i>
                    {{ $branchName }}
                </h5>
                <span class="badge bg-white text-danger rounded-pill px-3 py-2">
                    {{ $branchItems->count() }} Item(s) Low
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Product</th>
                            <th>Flavor</th>
                            <th>Current Stock</th>
                            <th>Threshold</th>
                            <th>Status</th>
                            <th>Last Restocked</th>
                            <th class="pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branchItems as $item)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-semibold">{{ $item->product->name }}</span>
                                <br>
                                <small class="text-muted">{{ $item->product->brand }}</small>
                            </td>
                            <td>{{ $item->flavor->name ?? 'N/A' }}</td>
                            <td>
                                <span class="fw-bold text-danger">{{ $item->quantity }}</span>
                            </td>
                            <td>{{ $item->low_stock_threshold }}</td>
                            <td>
                                @if($item->quantity <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @else
                                    <span class="badge bg-warning">Low Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($item->last_restocked_at)
                                    {{ $item->last_restocked_at->format('M d, Y') }}
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="openAddStockModal({{ $item->id }})">
                                    <i class="bi bi-plus-circle me-1"></i> Add Stock
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                <i class="bi bi-check-circle-fill text-success display-4"></i>
            </div>
            <h4 class="fw-bold">All Stock Levels Normal</h4>
            <p class="text-muted">No low stock items found across any branch.</p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                </a>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-box-seam me-1"></i> View All Inventory
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

<!-- Add Stock Modal Container -->
<div class="modal fade" id="addStockModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content"><!-- loaded via AJAX --></div>
    </div>
</div>

<script>
    // Add Stock Modal
    function openAddStockModal(id) {
        const modalElement = document.getElementById('addStockModal');
        const modalContent = modalElement.querySelector('.modal-content');
        const url = '/admin/inventory/' + id + '/add-stock-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-success" role="status"></div><p>Loading...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                new bootstrap.Modal(modalElement).show();
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading form</div>';
                new bootstrap.Modal(modalElement).show();
            });
    }
</script>
@endsection