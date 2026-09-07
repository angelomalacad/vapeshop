@extends('layouts.branch-admin')

@section('title', 'Low Stock Alert - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Success and Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header with Logo and Back Button -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Low Stock Alert</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-exclamation-triangle me-1 text-danger"></i> Items in {{ Auth::user()->branch->name }} that need attention
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    @php
        // STRICTLY FILTER BY THE CURRENT BRANCH ONLY
        $lowStockItems = \App\Models\BranchInventory::with(['branch', 'product', 'flavor'])
            ->where('branch_id', Auth::user()->branch_id)
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->where('is_disposed', false)
            ->where('is_archived', false)
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
                            <th>Flavor or Item</th>
                            <th>Current Stock</th>
                            <th>Threshold</th>
                            <th>Status</th>
                            <th>Last Restocked</th>
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
            <p class="text-muted">No low stock items found in {{ Auth::user()->branch->name }}.</p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>
@endsection