@extends('layouts.branch-admin')

@section('title', 'Warehouse Stock Request - Vape Expo')

@section('content')

    {{-- ========== DEBUGGING SECTION ========== --}}
    @php
        echo '<!-- DEBUGGING INFORMATION -->';
        echo '<!-- warehouseProducts exists: ' . (isset($warehouseProducts) ? 'YES' : 'NO') . ' -->';

        if (isset($warehouseProducts)) {
            echo '<!-- warehouseProducts type: ' . get_class($warehouseProducts) . ' -->';
            echo '<!-- warehouseProducts is paginator: ' .
                (method_exists($warehouseProducts, 'total') ? 'YES' : 'NO') .
                ' -->';

            if (method_exists($warehouseProducts, 'total')) {
                echo '<!-- warehouseProducts total: ' . $warehouseProducts->total() . ' -->';
                echo '<!-- warehouseProducts per page: ' . $warehouseProducts->perPage() . ' -->';
                echo '<!-- warehouseProducts current page: ' . $warehouseProducts->currentPage() . ' -->';
                echo '<!-- warehouseProducts has pages: ' . ($warehouseProducts->hasPages() ? 'YES' : 'NO') . ' -->';
                echo '<!-- warehouseProducts count on current page: ' . $warehouseProducts->count() . ' -->';
            } else {
                echo '<!-- warehouseProducts count: ' . $warehouseProducts->count() . ' -->';
            }
        }

        if (isset($allWarehouseProducts)) {
            echo '<!-- allWarehouseProducts count: ' . $allWarehouseProducts->count() . ' -->';
        }

        echo '<!-- pendingRequests count: ' . ($pendingRequests->count() ?? 0) . ' -->';
        echo '<!-- completedRequests type: ' . get_class($completedRequests) . ' -->';
        if (method_exists($completedRequests, 'total')) {
            echo '<!-- completedRequests total: ' . $completedRequests->total() . ' -->';
        }
        echo '<!-- END DEBUGGING -->';
    @endphp
    {{-- ========== END DEBUGGING ========== --}}

    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 fw-bold">
                    <i class="bi bi-building me-2 text-primary"></i>Warehouse Stock Request
                </h1>
                <p class="text-muted small mb-0">Request additional stock from owner's warehouse</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('branch-admin.inventory.stock-history') }}" class="btn btn-info rounded-pill px-4">
                    <i class="bi bi-clock-history me-2"></i>View Stock History
                </a>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#requestModal">
                    <i class="bi bi-cart-plus me-2"></i>Request Stock
                </button>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="alert alert-info border-0 rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-3 me-3 text-primary"></i>
                <div>
                    <strong>How it works:</strong><br>
                    <small>Request stock from the main warehouse. The owner will review and approve your request. Once
                        approved, you'll need to receive the stock to add it to your branch inventory. The owner can also
                        directly distribute stock to your branch. All received stock will appear in your 
                        <a href="{{ route('branch-admin.inventory.stock-history') }}" class="alert-link">Stock History</a>.</small>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Available Products</h6>
                                <h2 class="mb-0 fw-bold">{{ $warehouseProducts->total() }}</h2>
                            </div>
                            <i class="bi bi-box-seam fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Pending Requests</h6>
                                <h2 class="mb-0 fw-bold">{{ $pendingRequests->count() }}</h2>
                            </div>
                            <i class="bi bi-clock-history fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Completed Transfers</h6>
                                <h2 class="mb-0 fw-bold">{{ $completedRequests->total() }}</h2>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Warehouse Stock Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Available Warehouse Stock</h5>
                    <form method="GET" action="{{ route('branch-admin.warehouse.index') }}" class="d-flex">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Search product..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                            @if (request('search'))
                                <a href="{{ route('branch-admin.warehouse.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>Flavor</th>
                                <th>Expiration Date</th>
                                <th class="pe-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouseProducts as $item)
                                <tr>
                                    <td class="ps-4">
                                        <strong>{{ $item->product->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $item->product->brand ?? 'No brand' }}</small>
                                    </td>
                                    <td>{{ $item->flavor ? $item->flavor->name : 'No flavor' }}</td>
                                    <td>
                                        @if ($item->expiration_date)
                                            {{ \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') }}
                                            @if (\Carbon\Carbon::parse($item->expiration_date)->isPast())
                                                <span class="badge bg-danger ms-1">Expired</span>
                                            @elseif(\Carbon\Carbon::parse($item->expiration_date)->diffInDays(now()) <= 30)
                                                <span class="badge bg-warning ms-1">Soon</span>
                                            @endif
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="pe-4 text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#requestProductModal{{ $item->id }}">
                                            <i class="bi bi-cart-plus me-1"></i>Request
                                        </button>
                                    </td>
                                </tr>

                                <!-- Request Modal -->
                                <div class="modal fade" id="requestProductModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="bi bi-cart-plus me-2"></i>Request Stock</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('branch-admin.warehouse.request') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="flavor_id" value="{{ $item->flavor_id }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Product</label>
                                                        <input type="text" class="form-control" value="{{ $item->product->name ?? 'N/A' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Flavor</label>
                                                        <input type="text" class="form-control" value="{{ $item->flavor ? $item->flavor->name : 'No flavor' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Expiration Date</label>
                                                        <input type="text" class="form-control" value="{{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') : 'No expiry' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Quantity to Request <span class="text-danger">*</span></label>
                                                        <input type="number" name="quantity" class="form-control" min="1" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Notes (Optional)</label>
                                                        <textarea name="notes" class="form-control" rows="2" placeholder="Reason for request..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-building fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No stock available in warehouse</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination for Available Warehouse Stock - Uses warehouse_page parameter -->
            @if ($warehouseProducts->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">Showing {{ $warehouseProducts->firstItem() }} to {{ $warehouseProducts->lastItem() }} of {{ $warehouseProducts->total() }} results</div>
                        <div class="d-flex gap-2">
                            @if ($warehouseProducts->onFirstPage())
                                <span class="btn btn-secondary disabled">Previous</span>
                            @else
                                <a href="{{ $warehouseProducts->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                            @endif
                            
                            @if ($warehouseProducts->hasMorePages())
                                <a href="{{ $warehouseProducts->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
                            @else
                                <span class="btn btn-secondary disabled">Next</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pending Requests Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-warning"></i>Pending Requests</h5>
            </div>
            <div class="card-body p-0">
                @if ($pendingRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Transfer #</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Flavor</th>
                                    <th>Quantity</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingRequests as $request)
                                    <tr>
                                        <td class="ps-4"><code>{{ $request->transfer_number }}</code></td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td><strong>{{ $request->product->name ?? 'N/A' }}</strong></td>
                                        <td>{{ $request->flavor ? $request->flavor->name : 'N/A' }}</td>
                                        <td>{{ number_format($request->quantity) }}</td>
                                        <td class="pe-4"><span class="badge bg-warning">Pending Approval</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted mb-0">No pending requests</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Request & Distribution History (LOG ONLY - NO RECEIVE BUTTON) -->
        @if ($completedRequests->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-info"></i>Request & Distribution History</h5>
                    <p class="text-muted small mb-0 mt-1">Log of all stock transfers from warehouse (view only)</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Transfer #</th>
                                    <th>Date Requested</th>
                                    <th>Product</th>
                                    <th>Flavor</th>
                                    <th>Quantity</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th class="pe-4">Date Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($completedRequests as $request)
                                    <tr>
                                        <td class="ps-4"><code>{{ $request->transfer_number }}</code></td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td><strong>{{ $request->product->name ?? 'N/A' }}</strong></td>
                                        <td>{{ $request->flavor ? $request->flavor->name : 'N/A' }}</td>
                                        <td>{{ number_format($request->quantity) }}</td>
                                        <td>
                                            @if ($request->requested_by == auth()->id())
                                                <span class="badge bg-primary">Requested by You</span>
                                            @else
                                                <span class="badge bg-info">Distributed by Owner</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($request->status == 'approved')
                                                <span class="badge bg-warning text-dark">Approved (Pending Receive)</span>
                                            @elseif($request->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            @if($request->received_at)
                                                {{ \Carbon\Carbon::parse($request->received_at)->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination for History Section - Uses history_page parameter -->
                @if ($completedRequests->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">Showing {{ $completedRequests->firstItem() }} to {{ $completedRequests->lastItem() }} of {{ $completedRequests->total() }} results</div>
                            <div class="d-flex gap-2">
                                @if ($completedRequests->onFirstPage())
                                    <span class="btn btn-secondary disabled">Previous</span>
                                @else
                                    <a href="{{ $completedRequests->previousPageUrl() }}" class="btn btn-outline-primary">Previous</a>
                                @endif
                                
                                @if ($completedRequests->hasMorePages())
                                    <a href="{{ $completedRequests->nextPageUrl() }}" class="btn btn-outline-primary">Next</a>
                                @else
                                    <span class="btn btn-secondary disabled">Next</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Main Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-cart-plus me-2"></i>Request Stock from Warehouse</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('branch-admin.warehouse.request') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="requestProductSelect" class="form-select" required>
                                    <option value="">Select product...</option>
                                    @foreach ($allWarehouseProducts->groupBy('product_id') as $productId => $items)
                                        @php $firstItem = $items->first(); @endphp
                                        <option value="{{ $firstItem->product_id }}">{{ $firstItem->product->name ?? 'Unknown' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Flavor <span class="text-danger">*</span></label>
                                <select name="flavor_id" id="requestFlavorSelect" class="form-select" required disabled>
                                    <option value="">First select a product...</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" id="requestQuantity" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Reason for request..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('requestProductSelect');
        const flavorSelect = document.getElementById('requestFlavorSelect');
        const quantityInput = document.getElementById('requestQuantity');
        const warehouseItems = @json(isset($allWarehouseProducts) ? $allWarehouseProducts : []);

        if (productSelect) {
            productSelect.addEventListener('change', function() {
                const productId = this.value;
                if (productId) {
                    const flavors = warehouseItems.filter(item => item.product_id == productId);
                    flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
                    if (flavors.length > 0) {
                        flavors.forEach(flavor => {
                            const option = document.createElement('option');
                            option.value = flavor.id;
                            option.textContent = flavor.flavor ? flavor.flavor.name : 'No flavor';
                            flavorSelect.appendChild(option);
                        });
                        flavorSelect.disabled = false;
                    } else {
                        flavorSelect.innerHTML = '<option value="">No flavors available</option>';
                        flavorSelect.disabled = true;
                    }
                    quantityInput.value = '';
                } else {
                    flavorSelect.disabled = true;
                    flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                    quantityInput.value = '';
                }
            });
        }
    </script>
@endsection