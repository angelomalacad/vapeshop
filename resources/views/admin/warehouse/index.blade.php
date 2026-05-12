@extends('layouts.admin')

@section('title', 'Warehouse Management - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">
                <i class="bi bi-building me-2 text-primary"></i>Warehouse Management
            </h1>
            <p class="text-muted small mb-0">Manage central stock and distribute to branches</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addStockModal">
            <i class="bi bi-plus-circle me-2"></i>Add Stock to Warehouse
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Products</h6>
                            <h2 class="mb-0 fw-bold">{{ $inventory->total() }}</h2>
                        </div>
                        <i class="bi bi-box-seam fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Low Stock Items</h6>
                            <h2 class="mb-0 fw-bold">{{ $lowStockCount }}</h2>
                        </div>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Value</h6>
                            <h2 class="mb-0 fw-bold">₱{{ number_format($totalValue, 2) }}</h2>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.warehouse.pending') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Pending Requests</h6>
                                <h2 class="mb-0 fw-bold">{{ \App\Models\StockTransfer::where('transfer_type', 'warehouse_to_branch')->where('status', 'pending')->count() }}</h2>
                            </div>
                            <i class="bi bi-clock-history fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Warehouse Inventory</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Product</th>
                            <th>Quantity</th>
                            <th>Low Stock Alert</th>
                            <th>Last Purchase Price</th>
                            <th>Total Value</th>
                            <th>Last Restocked</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory as $item)
                            @php
                                $product = $item->product;
                                $isLowStock = $item->quantity <= $item->low_stock_threshold;
                                $totalItemValue = $item->quantity * ($item->last_purchase_price ?? 0);
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                <i class="bi bi-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $product->brand ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </div>
                                <td>
                                    <span class="fw-bold {{ $isLowStock ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($item->quantity) }}
                                    </span>
                                </div>
                                <td>
                                    @if($isLowStock)
                                        <span class="badge bg-warning">Below {{ $item->low_stock_threshold }}</span>
                                    @else
                                        <span class="text-muted">≥ {{ $item->low_stock_threshold }}</span>
                                    @endif
                                </div>
                                <td>₱{{ number_format($item->last_purchase_price ?? 0, 2) }}</div>
                                <td>₱{{ number_format($totalItemValue, 2) }}</div>
                                <td>{{ $item->last_restocked_at ? $item->last_restocked_at->format('M d, Y') : 'Never' }}</div>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-warning rounded-pill me-1" 
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-outline-primary rounded-pill" 
                                                data-bs-toggle="modal" data-bs-target="#distributeModal{{ $item->id }}">
                                            <i class="bi bi-send"></i> Distribute
                                        </button>
                                    </div>
                                </div>
                            </tr>

                            <!-- Edit Modal for this item -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Warehouse Stock</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.warehouse.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="alert alert-info bg-light border-0 mb-4">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <small class="text-muted d-block">Product</small>
                                                            <strong>{{ $product->name }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                        <input type="number" name="quantity" id="quantity_{{ $item->id }}" class="form-control" 
                                                               value="{{ $item->quantity }}" min="0" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Last Purchase Price (₱) <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.01" name="last_purchase_price" id="price_{{ $item->id }}" class="form-control" 
                                                               value="{{ $item->last_purchase_price }}" min="0" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                                                        <input type="number" name="low_stock_threshold" class="form-control" 
                                                               value="{{ $item->low_stock_threshold }}" min="1" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Reorder Point <span class="text-danger">*</span></label>
                                                        <input type="number" name="reorder_point" class="form-control" 
                                                               value="{{ $item->reorder_point }}" min="1" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Last Restocked</label>
                                                        <input type="text" class="form-control bg-light" 
                                                               value="{{ $item->last_restocked_at ? $item->last_restocked_at->format('M d, Y h:i A') : 'Never' }}" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Total Inventory Value</label>
                                                        <input type="text" id="total_{{ $item->id }}" class="form-control bg-primary text-white fw-bold" 
                                                               value="₱{{ number_format($item->quantity * ($item->last_purchase_price ?? 0), 2) }}" readonly>
                                                    </div>
                                                </div>
                                                
                                                <hr>
                                                
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                    <strong>Note:</strong> Changing quantity will be recorded in stock movement history.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Inventory</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Distribute Modal -->
                            <div class="modal fade" id="distributeModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title"><i class="bi bi-send me-2"></i>Distribute Stock</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.warehouse.distribute') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Product</label>
                                                    <input type="text" class="form-control" value="{{ $product->name }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Available in Warehouse</label>
                                                    <input type="text" class="form-control" value="{{ number_format($item->quantity) }} units" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Select Branch <span class="text-danger">*</span></label>
                                                    <select name="branch_id" class="form-select" required>
                                                        <option value="">Select branch...</option>
                                                        @foreach(\App\Models\Branch::where('is_active', true)->get() as $branch)
                                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                    <input type="number" name="quantity" class="form-control" min="1" max="{{ $item->quantity }}" required>
                                                    <div class="form-text">Max: {{ number_format($item->quantity) }} units</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Send to Branch</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                    <p class="text-muted">No products in warehouse inventory</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStockModal">
                                        Add First Stock
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $inventory->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Stock to Warehouse</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.warehouse.add-stock') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Product <span class="text-danger">*</span></label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Select product...</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purchase Price (₱)</label>
                        <input type="number" step="0.01" name="purchase_price" class="form-control" min="0" placeholder="Optional">
                        <div class="form-text">For tracking inventory value</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add to Warehouse</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto-calculate total value when quantity or price changes in any edit modal
    @foreach($inventory as $item)
    (function() {
        const quantityInput = document.getElementById('quantity_{{ $item->id }}');
        const priceInput = document.getElementById('price_{{ $item->id }}');
        const totalDisplay = document.getElementById('total_{{ $item->id }}');
        
        if (quantityInput && priceInput && totalDisplay) {
            function updateTotal() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;
                totalDisplay.value = '₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }
            
            quantityInput.addEventListener('input', updateTotal);
            priceInput.addEventListener('input', updateTotal);
        }
    })();
    @endforeach
</script>
@endsection