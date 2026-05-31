@extends('layouts.admin')

@section('title', 'Warehouse Management - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
        <!-- Success Message Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-3 me-3 text-success"></i>
                    <div>
                        <strong>Success!</strong> {{ session('success') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Message Alert -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-danger"></i>
                    <div>
                        <strong>Error!</strong> {{ session('error') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 fw-bold">
                    <i class="bi bi-building me-2 text-primary"></i>Warehouse Management
                </h1>
                <p class="text-muted small mb-0">Manage central stock and distribute to branches</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.warehouse.pending') }}" class="btn btn-warning rounded-pill px-4">
                    <i class="bi bi-clock-history me-2"></i>Pending Requests
                    @php $pendingCount = \App\Models\StockTransfer::where('transfer_type', 'warehouse_to_branch')->where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                    @endif
                </a>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addStockModal">
                    <i class="bi bi-plus-circle me-2"></i>Add Stock to Warehouse
                </button>
            </div>
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
                                    <h2 class="mb-0 fw-bold">
                                        {{ $pendingCount }}
                                    </h2>
                                </div>
                                <i class="bi bi-clock-history fs-1 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="alert alert-info border-0 rounded-3 mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-3 me-3 text-primary"></i>
                <div>
                    <strong>Warehouse Management Guide:</strong><br>
                    <small>• Use <strong>"Add Stock"</strong> to increase warehouse inventory with new products or restock existing ones.<br>
                    • Use <strong>"Distribute"</strong> to send stock directly to any branch. This will be immediately available for the branch to receive.<br>
                    • Products with <strong class="text-warning">expiration dates</strong> will show warnings when nearing expiry.</small>
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Warehouse Inventory</h5>
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.warehouse.index') }}" class="d-flex">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Search product..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.warehouse.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x"></i>
                                </a>
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
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                                <th>Low Stock Alert</th>
                                <th>Last Purchase Price</th>
                                <th>Total Value</th>
                                <th>Last Restocked</th>
                                <th>Expiration Date</th>
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
                                            @if ($product && $product->image)
                                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                                    class="rounded me-2"
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-box text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $product->name ?? 'N/A' }}</strong>
                                                @if($product && $product->sku)
                                                    <br><small class="text-muted">SKU: {{ $product->sku }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->flavor ? $item->flavor->name : 'N/A' }}</td>
                                    <td>{{ $product->category ?? 'Uncategorized' }}</td>
                                    <td>{{ $product->brand ?? 'N/A' }}</td>
                                    <td>
                                        <span class="fw-bold {{ $isLowStock ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($item->quantity) }}
                                        </span>
                                        @if($item->quantity <= 0)
                                            <span class="badge bg-danger ms-1">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($isLowStock)
                                            <span class="badge bg-warning">Below {{ $item->low_stock_threshold }}</span>
                                        @else
                                            <span class="text-muted">≥ {{ $item->low_stock_threshold }}</span>
                                        @endif
                                    </td>
                                    <td>₱{{ number_format($item->last_purchase_price ?? 0, 2) }}</td>
                                    <td>₱{{ number_format($totalItemValue, 2) }}</td>
                                    <td>{{ $item->last_restocked_at ? $item->last_restocked_at->format('M d, Y') : 'Never' }}</td>
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
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-warning rounded-pill me-1" data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $item->id }}" title="Edit Stock">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-outline-primary rounded-pill" data-bs-toggle="modal"
                                                data-bs-target="#distributeModal{{ $item->id }}">
                                                <i class="bi bi-send"></i> Distribute
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- ========== EDIT MODAL ========== -->
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
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Product <span class="text-danger">*</span></label>
                                                            <select name="product_id" class="form-select product-select-edit"
                                                                data-edit-id="{{ $item->id }}" required>
                                                                <option value="">Select product...</option>
                                                                @foreach ($products as $productOption)
                                                                    <option value="{{ $productOption->id }}"
                                                                        {{ $item->product_id == $productOption->id ? 'selected' : '' }}>
                                                                        {{ $productOption->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Flavor <span class="text-danger">*</span></label>
                                                            <select name="flavor_id" class="form-select flavor-select-edit"
                                                                data-edit-id="{{ $item->id }}"
                                                                data-current-flavor-id="{{ $item->flavor_id }}" required>
                                                                <option value="">Loading flavors...</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                            <input type="number" name="quantity" class="form-control quantity-input"
                                                                value="{{ $item->quantity }}" min="0" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Last Purchase Price (₱) <span class="text-danger">*</span></label>
                                                            <input type="number" step="0.01" name="last_purchase_price"
                                                                class="form-control price-input" value="{{ $item->last_purchase_price }}"
                                                                min="0" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Expiration Date</label>
                                                            <input type="date" name="expiration_date" class="form-control"
                                                                value="{{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('Y-m-d') : '' }}">
                                                            <div class="form-text">Optional – leave empty if no expiry</div>
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
                                                                value="{{ $item->last_restocked_at ? $item->last_restocked_at->format('M d, Y h:i A') : 'Never' }}"
                                                                readonly>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Total Inventory Value</label>
                                                            <input type="text"
                                                                class="form-control total-value-display bg-primary text-white fw-bold"
                                                                value="₱{{ number_format($item->quantity * ($item->last_purchase_price ?? 0), 2) }}"
                                                                readonly>
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
                                                <input type="hidden" name="warehouse_stock_id" value="{{ $item->id }}">
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="flavor_id" value="{{ $item->flavor_id }}">
                                                <div class="modal-body">
                                                    <div class="alert alert-info small">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Distributing stock will immediately create an approved transfer. The branch can then receive it.
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Product</label>
                                                        <input type="text" class="form-control" value="{{ $item->product->name ?? 'N/A' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Flavor</label>
                                                        <input type="text" class="form-control" value="{{ $item->flavor ? $item->flavor->name : 'N/A' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Expiration Date</label>
                                                        <input type="text" class="form-control" value="{{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') : 'No expiry' }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Available in Warehouse</label>
                                                        <input type="text" class="form-control" value="{{ number_format($item->quantity) }} units" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Branch <span class="text-danger">*</span></label>
                                                        <select name="branch_id" class="form-select" required>
                                                            <option value="">Select branch...</option>
                                                            @foreach (\App\Models\Branch::where('is_active', true)->get() as $branch)
                                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                                        <input type="number" name="quantity" class="form-control" min="1" max="{{ $item->quantity }}" required>
                                                        <div class="form-text">Max: {{ number_format($item->quantity) }} units</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Notes (Optional)</label>
                                                        <textarea name="notes" class="form-control" rows="2" placeholder="Distribution notes..."></textarea>
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
                                    <td colspan="11" class="text-center py-5">
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
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $inventory->firstItem() ?? 0 }} to {{ $inventory->lastItem() ?? 0 }} of {{ $inventory->total() }} results
                    </div>
                    <div>
                        @if ($inventory->hasPages())
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end mb-0">
                                    @if ($inventory->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $inventory->previousPageUrl() }}" rel="prev">Previous</a></li>
                                    @endif

                                    @if ($inventory->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $inventory->nextPageUrl() }}" rel="next">Next</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Stock Modal -->
    <div class="modal fade" id="addStockModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Stock to Warehouse</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.warehouse.add-stock') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-1"></i>
                            Adding stock will increase the warehouse inventory. The purchase price and expiration date will be tracked.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="productSelectAdd" class="form-select" required>
                                    <option value="">Select product...</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Select Flavor <span class="text-danger">*</span></label>
                                <select name="flavor_id" id="flavorSelectAdd" class="form-select" required disabled>
                                    <option value="">First select a product...</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Purchase Price (₱)</label>
                                <input type="number" step="0.01" name="purchase_price" class="form-control"
                                    min="0" placeholder="Optional">
                                <div class="form-text">Cost price per unit</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Expiration Date</label>
                                <input type="date" name="expiration_date" class="form-control">
                                <div class="form-text">Optional – leave empty if no expiry</div>
                            </div>
                        </div>

                        <div class="alert alert-secondary small">
                            <i class="bi bi-box-seam me-1"></i>
                            <strong>Note:</strong> Stock added here will be available for distribution to all branches.
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
        // Auto-calculate total value when quantity or price changes in edit modals
        document.querySelectorAll('.quantity-input, .price-input').forEach(input => {
            input.addEventListener('input', function() {
                const modal = this.closest('.modal');
                const quantity = modal.querySelector('.quantity-input')?.value || 0;
                const price = modal.querySelector('.price-input')?.value || 0;
                const totalValue = quantity * price;
                const totalDisplay = modal.querySelector('.total-value-display');
                if (totalDisplay) {
                    totalDisplay.value = '₱' + parseFloat(totalValue).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }
            });
        });

        // Function to load flavors for a given product select element
        function loadFlavors(productSelect, flavorSelect, currentFlavorId = null) {
            const productId = productSelect.value;
            if (!productId) {
                flavorSelect.disabled = true;
                flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                return;
            }

            flavorSelect.disabled = true;
            flavorSelect.innerHTML = '<option value="">Loading flavors...</option>';

            fetch(`/admin/api/products/${productId}/flavors`)
                .then(response => response.json())
                .then(data => {
                    flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
                    if (data.length > 0) {
                        data.forEach(flavor => {
                            const option = document.createElement('option');
                            option.value = flavor.id;
                            option.textContent = flavor.name;
                            flavorSelect.appendChild(option);
                        });
                        flavorSelect.disabled = false;
                        if (currentFlavorId) {
                            flavorSelect.value = currentFlavorId;
                        }
                    } else {
                        flavorSelect.innerHTML = '<option value="">No flavors available for this product</option>';
                        flavorSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading flavors:', error);
                    flavorSelect.innerHTML = '<option value="">Error loading flavors</option>';
                    flavorSelect.disabled = true;
                });
        }

        // Setup for Add Stock modal
        const productSelectAdd = document.getElementById('productSelectAdd');
        const flavorSelectAdd = document.getElementById('flavorSelectAdd');
        if (productSelectAdd && flavorSelectAdd) {
            productSelectAdd.addEventListener('change', function() {
                loadFlavors(productSelectAdd, flavorSelectAdd);
            });
            if (productSelectAdd.value) {
                loadFlavors(productSelectAdd, flavorSelectAdd);
            }
        }

        // Setup for each Edit modal
        document.querySelectorAll('.product-select-edit').forEach(productSelect => {
            const editId = productSelect.dataset.editId;
            const flavorSelect = document.querySelector(`.flavor-select-edit[data-edit-id="${editId}"]`);
            if (!flavorSelect) return;

            const currentFlavorId = flavorSelect.getAttribute('data-current-flavor-id');

            const modal = productSelect.closest('.modal');
            if (modal) {
                modal.addEventListener('show.bs.modal', function() {
                    const currentProductId = productSelect.value;
                    if (currentProductId) {
                        loadFlavors(productSelect, flavorSelect, currentFlavorId);
                    } else {
                        flavorSelect.disabled = true;
                        flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                    }
                });
            }

            productSelect.addEventListener('change', function() {
                loadFlavors(productSelect, flavorSelect);
            });
        });
    </script>
@endsection