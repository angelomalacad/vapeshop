@extends('layouts.admin')

@section('title', 'Stock Transfers - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Logo and Navigation -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Stock Transfers</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-arrow-left-right me-1"></i> Manage all transfer requests
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.inventory.create-transfer') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> New Transfer
            </a>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-box-seam me-1"></i> All Inventory
            </a>
            <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-warning rounded-pill px-3">
                <i class="bi bi-exclamation-triangle me-1"></i> Low Stock
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        @php
            $pendingCount = \App\Models\StockTransfer::where('status', 'pending')->count();
            $approvedCount = \App\Models\StockTransfer::where('status', 'approved')->count();
            $completedCount = \App\Models\StockTransfer::where('status', 'completed')->count();
            $cancelledCount = \App\Models\StockTransfer::where('status', 'cancelled')->count();
        @endphp
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Pending</h6>
                            <h2 class="mb-0 fw-bold">{{ $pendingCount }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-hourglass fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Approved</h6>
                            <h2 class="mb-0 fw-bold">{{ $approvedCount }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
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
                            <h2 class="mb-0 fw-bold">{{ $completedCount }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Cancelled</h6>
                            <h2 class="mb-0 fw-bold">{{ $cancelledCount }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-x-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Transfers</h5>
            <span class="text-muted small">Total: {{ $transfers->total() }} transfers</span>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">From Branch</label>
                    <select name="from_branch" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('from_branch') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">To Branch</label>
                    <select name="to_branch" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('to_branch') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.inventory.transfers') }}" class="btn btn-outline-secondary ms-2 px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Transfer #</th>
                            <th>Date</th>
                            <th>From Branch</th>
                            <th>To Branch</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'approved' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusIcons = [
                                'pending' => 'bi-hourglass',
                                'approved' => 'bi-check-circle',
                                'completed' => 'bi-check-circle-fill',
                                'cancelled' => 'bi-x-circle'
                            ];
                        @endphp
                        <tr>
                            <td class="ps-4"><code class="fw-semibold">{{ $transfer->transfer_number }}</code></td>
                            <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                            
                            <!-- From Branch with null check -->
                            <td>
                                @if($transfer->fromBranch)
                                    {{ $transfer->fromBranch->name }}
                                    @if($transfer->transfer_type == 'warehouse_to_branch')
                                        <span class="badge bg-primary ms-1">Warehouse</span>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                                @if($transfer->from_branch_id == Auth::user()->branch_id ?? false)
                                    <span class="badge bg-info ms-1">Your Branch</span>
                                @endif
                            </td>
                            
                            <!-- To Branch with null check -->
                            <td>
                                @if($transfer->toBranch)
                                    {{ $transfer->toBranch->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                                @if(isset(Auth::user()->branch_id) && $transfer->to_branch_id == Auth::user()->branch_id)
                                    <span class="badge bg-info ms-1">Your Branch</span>
                                @endif
                            </td>
                            
                            <!-- Product with null check -->
                            <td>
                                @if($transfer->product)
                                    {{ $transfer->product->name }}
                                @else
                                    <span class="text-muted">Product Deleted</span>
                                @endif
                            </td>
                            
                            <!-- Flavor with null check -->
                            <td>
                                @if($transfer->flavor)
                                    {{ $transfer->flavor->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            
                            <td><span class="fw-bold">{{ $transfer->quantity }}</span></td>
                            
                            <!-- Status -->
                            <td>
                                <span class="badge bg-{{ $statusColors[$transfer->status] }} px-3 py-2">
                                    <i class="bi {{ $statusIcons[$transfer->status] }} me-1"></i>
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </td>
                            
                            <!-- Requested By with null check -->
                            <td>
                                @if($transfer->requestedBy)
                                    {{ $transfer->requestedBy->name }}
                                @else
                                    <span class="text-muted">Unknown</span>
                                @endif
                            </td>
                            
                            <td class="pe-4">
                                <div class="btn-group btn-group-sm">
                                    <!-- View Details -->
                                    <button type="button" class="btn btn-outline-info" title="View Details" onclick="openTransferModal({{ $transfer->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <!-- Edit - Only show for pending transfers -->
                                    @if($transfer->status == 'pending')
                                        <button type="button" class="btn btn-outline-warning" title="Edit" onclick="openEditTransferModal({{ $transfer->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary" title="Cannot edit {{ $transfer->status }} transfers" disabled>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif

                                    <!-- Approve (only pending) -->
                                    @if($transfer->status == 'pending')
                                        <form action="{{ route('admin.inventory.transfers.approve', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Approve" onclick="return confirm('Approve this transfer?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Reject (only pending) -->
                                    @if($transfer->status == 'pending')
                                        <form action="{{ route('admin.inventory.transfers.reject', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger" title="Reject" onclick="return confirm('Reject this transfer?')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Complete (only approved) -->
                                    @if($transfer->status == 'approved')
                                        <form action="{{ route('admin.inventory.transfers.complete', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Complete" onclick="return confirm('Complete this transfer?')">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete (only cancelled or completed) -->
                                    @if(in_array($transfer->status, ['cancelled', 'completed']))
                                        <form action="{{ route('admin.inventory.transfers.destroy', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Delete this transfer? This action cannot be undone.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-arrow-left-right display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No transfer requests found</p>
                                <div class="d-flex justify-content-center gap-2 mt-2">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                    </a>
                                    <a href="{{ route('admin.inventory.create-transfer') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-plus-circle me-1"></i> Create New Transfer
                                    </a>
                                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-box-seam me-1"></i> View Inventory
                                    </a>
                                </div>
                            </td>
                        </table>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $transfers->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Transfer Details Modal Container -->
<div class="modal fade" id="transferModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"><!-- loaded via AJAX --></div>
    </div>
</div>

<!-- Edit Transfer Modal Container -->
<div class="modal fade" id="editTransferModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"><!-- loaded via AJAX --></div>
    </div>
</div>

<script>
    // Transfer Details Modal
    function openTransferModal(id) {
        const modalElement = document.getElementById('transferModal');
        const modalContent = modalElement.querySelector('.modal-content');
        const url = '/admin/inventory/transfers/' + id + '/show-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-info" role="status"></div><p>Loading...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.text())
            .then(html => {
                modalContent.innerHTML = html;
                new bootstrap.Modal(modalElement).show();
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="alert alert-danger m-3">Error loading details</div>';
                new bootstrap.Modal(modalElement).show();
            });
    }
    
    // Edit Transfer Modal - Only for pending transfers
    function openEditTransferModal(id) {
        const modalElement = document.getElementById('editTransferModal');
        const modalContent = modalElement.querySelector('.modal-content');
        const url = '/admin/inventory/transfers/' + id + '/edit-modal';
        
        modalContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-warning" role="status"></div><p>Loading...</p></div>';
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Only pending transfers can be edited.');
                }
                return response.text();
            })
            .then(html => {
                modalContent.innerHTML = html;
                new bootstrap.Modal(modalElement).show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message);
                modalContent.innerHTML = '<div class="text-center p-5"><p class="text-danger">Error loading form</p></div>';
            });
    }
</script>
@endsection