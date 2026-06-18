@extends('layouts.admin')

@section('title', 'Edit Warehouse Stock - Vape Expo')

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
            <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal"
                data-bs-target="#addStockModal">
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
                                    <h2 class="mb-0 fw-bold">
                                        {{ \App\Models\StockTransfer::where('transfer_type', 'warehouse_to_branch')->where('status', 'pending')->count() }}
                                    </h2>
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
                                <th>Flavor</th>
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
                                            @if ($product->image)
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
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $product->brand ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->flavor ? $item->flavor->name : 'N/A' }}</td>
                                    <td>
                                        <span class="fw-bold {{ $isLowStock ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($item->quantity) }}
                                        </span>
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
                                    <td>{{ $item->last_restocked_at ? $item->last_restocked_at->format('M d, Y') : 'Never' }}
                                    </td>
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
                                            <button type="button" class="btn btn-outline-warning rounded-pill me-1"
                                                data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-outline-primary rounded-pill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#distributeModal{{ $item->id }}">
                                                <i class="bi bi-send"></i> Distribute
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Modal for this item -->
                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit
                                                    Warehouse Stock</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.warehouse.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Product <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="product_id"
                                                                class="form-select product-select-edit"
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
                                                            <label class="form-label">Flavor <span
                                                                    class="text-danger">*</span></label>
                                                            <!-- FIXED: Added data-current-flavor-id -->
                                                            <select name="flavor_id"
                                                                class="form-select flavor-select-edit"
                                                                data-edit-id="{{ $item->id }}"
                                                                data-current-flavor-id="{{ $item->flavor_id }}" required>
                                                                <option value="">-- Select flavor --</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Quantity <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" name="quantity"
                                                                class="form-control quantity-input"
                                                                value="{{ $item->quantity }}" min="0" required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Last Purchase Price (₱) <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" step="0.01"
                                                                name="last_purchase_price"
                                                                class="form-control price-input"
                                                                value="{{ $item->last_purchase_price }}" min="0"
                                                                required>
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label">Expiration Date</label>
                                                            <input type="date" name="expiration_date"
                                                                class="form-control"
                                                                value="{{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('Y-m-d') : '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Low Stock Threshold <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" name="low_stock_threshold"
                                                                class="form-control"
                                                                value="{{ $item->low_stock_threshold }}" min="1"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Reorder Point <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="number" name="reorder_point"
                                                                class="form-control" value="{{ $item->reorder_point }}"
                                                                min="1" required>
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
                                                        <strong>Note:</strong> Changing quantity will be recorded in stock
                                                        movement history.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update
                                                        Inventory</button>
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
                                                <h5 class="modal-title"><i class="bi bi-send me-2"></i>Distribute Stock
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.warehouse.distribute') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="flavor_id" value="{{ $item->flavor_id }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Product</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $product->name }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Flavor</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $item->flavor ? $item->flavor->name : 'N/A' }}"
                                                            readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Available in Warehouse</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ number_format($item->quantity) }} units" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Branch <span
                                                                class="text-danger">*</span></label>
                                                        <select name="branch_id" class="form-select" required>
                                                            <option value="">Select branch...</option>
                                                            @foreach (\App\Models\Branch::where('is_active', true)->get() as $branch)
                                                                <option value="{{ $branch->id }}">{{ $branch->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Quantity <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="quantity" class="form-control"
                                                            min="1" max="{{ $item->quantity }}" required>
                                                        <div class="form-text">Max: {{ number_format($item->quantity) }}
                                                            units</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Send to Branch</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No products in warehouse inventory</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addStockModal">
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
                            <select name="product_id" id="productSelectAdd" class="form-select" required>
                                <option value="">Select product...</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Flavor <span class="text-danger">*</span></label>
                            <select name="flavor_id" id="flavorSelectAdd" class="form-select" required disabled>
                                <option value="">First select a product...</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiration Date</label>
                                <input type="date" name="expiration_date" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purchase Price (₱)</label>
                            <input type="number" step="0.01" name="purchase_price" class="form-control"
                                min="0" placeholder="Optional">
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
        // Pre-load all flavors data from products to avoid AJAX issues
        const productsData = @json(
            $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'flavors' => $product->flavors->map(function ($flavor) {
                        return ['id' => $flavor->id, 'name' => $flavor->name];
                    }),
                ];
            }));

        console.log('Products Data loaded:', productsData);

        // Function to populate flavor dropdown
        function populateFlavors(productId, flavorSelect, currentFlavorId = null) {
            console.log('populateFlavors called with productId:', productId, 'currentFlavorId:', currentFlavorId);
            const product = productsData.find(p => p.id == productId);
            console.log('Product found:', product);
            if (!product || product.flavors.length === 0) {
                flavorSelect.innerHTML = '<option value="">No flavors available</option>';
                flavorSelect.disabled = true;
                return;
            }

            let options = '<option value="">Select flavor...</option>';
            product.flavors.forEach(flavor => {
                options += `<option value="${flavor.id}">${flavor.name}</option>`;
            });
            flavorSelect.innerHTML = options;
            flavorSelect.disabled = false;

            if (currentFlavorId) {
                flavorSelect.value = currentFlavorId;
                console.log('Set selected flavor to:', currentFlavorId);
            }
        }

        // Auto-calculate total value when quantity or price changes in edit modals
        document.querySelectorAll('.quantity-input, .price-input').forEach(input => {
            input.addEventListener('input', function() {
                const modal = this.closest('.modal');
                const quantity = modal.querySelector('.quantity-input')?.value || 0;
                const price = modal.querySelector('.price-input')?.value || 0;
                const totalValue = quantity * price;
                const totalDisplay = modal.querySelector('.total-value-display');
                if (totalDisplay) {
                    totalDisplay.value = '₱' + parseFloat(totalValue).toFixed(2).replace(
                        /\B(?=(\d{3})+(?!\d))/g, ',');
                }
            });
        });

        // Add Stock modal flavors
        const productSelectAdd = document.getElementById('productSelectAdd');
        const flavorSelectAdd = document.getElementById('flavorSelectAdd');
        if (productSelectAdd && flavorSelectAdd) {
            productSelectAdd.addEventListener('change', function() {
                const productId = this.value;
                console.log('Add stock product changed to:', productId);
                if (productId) {
                    const product = productsData.find(p => p.id == productId);
                    if (product && product.flavors.length > 0) {
                        let options = '<option value="">Select flavor...</option>';
                        product.flavors.forEach(flavor => {
                            options += `<option value="${flavor.id}">${flavor.name}</option>`;
                        });
                        flavorSelectAdd.innerHTML = options;
                        flavorSelectAdd.disabled = false;
                    } else {
                        flavorSelectAdd.innerHTML = '<option value="">No flavors available</option>';
                        flavorSelectAdd.disabled = true;
                    }
                } else {
                    flavorSelectAdd.innerHTML = '<option value="">First select a product...</option>';
                    flavorSelectAdd.disabled = true;
                }
            });
            if (productSelectAdd.value) {
                productSelectAdd.dispatchEvent(new Event('change'));
            }
        }

        // Edit modals flavors
        document.querySelectorAll('.product-select-edit').forEach(productSelect => {
            const editId = productSelect.dataset.editId;
            const flavorSelect = document.querySelector(`.flavor-select-edit[data-edit-id="${editId}"]`);
            if (!flavorSelect) return;

            // Store current flavor ID from the data attribute (FIXED: now it's set in the blade)
            const currentFlavorId = flavorSelect.getAttribute('data-current-flavor-id') || '';
            console.log('Edit modal - editId:', editId, 'currentFlavorId:', currentFlavorId);

            // Function to load flavors for this specific edit modal
            function loadFlavorsForEdit() {
                const productId = productSelect.value;
                console.log('loadFlavorsForEdit - productId:', productId);
                if (productId) {
                    populateFlavors(productId, flavorSelect, currentFlavorId);
                } else {
                    flavorSelect.innerHTML = '<option value="">Select product first</option>';
                    flavorSelect.disabled = true;
                }
            }

            // When modal is shown, load flavors
            const modal = productSelect.closest('.modal');
            if (modal) {
                modal.addEventListener('show.bs.modal', function() {
                    console.log('Modal show event fired for editId:', editId);
                    loadFlavorsForEdit();
                });
            }

            // When product changes, reload flavors
            productSelect.addEventListener('change', function() {
                console.log('Product select changed for editId:', editId);
                loadFlavorsForEdit();
            });
        });
    </script>
@endsection
