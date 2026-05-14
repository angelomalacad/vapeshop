@extends('layouts.branch-admin')

@section('title', 'Warehouse Stock Request - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 fw-bold">
                <i class="bi bi-building me-2 text-primary"></i>Warehouse Stock Request
            </h1>
            <p class="text-muted small mb-0">Request additional stock from owner's warehouse</p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#requestModal">
            <i class="bi bi-cart-plus me-2"></i>Request Stock
        </button>
    </div>

    <!-- Info Banner -->
    <div class="alert alert-info border-0 rounded-3 mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-3 me-3 text-primary"></i>
            <div>
                <strong>How it works:</strong><br>
                <small>Request stock from the main warehouse. The owner will review and approve your request. Once approved, you'll need to receive the stock to add it to your branch inventory.</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Pending Requests Section -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-warning"></i>Pending Requests</h5>
                </div>
                <div class="card-body p-0">
                    @if($pendingRequests->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($pendingRequests as $request)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge bg-warning mb-1">Pending</span>
                                            <h6 class="mb-0">{{ $request->product->name ?? 'N/A' }}</h6>
                                            @if($request->flavor)
                                                <small class="text-muted">Flavor: {{ $request->flavor->name }}</small><br>
                                            @endif
                                            <small class="text-muted">Quantity: {{ number_format($request->quantity) }} units</small>
                                            <br>
                                            <small class="text-muted">Requested: {{ $request->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="text-end">
                                            <code class="small">{{ $request->transfer_number }}</code>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted mb-0">No pending requests</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Available Warehouse Stock (with expiration date) -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Available Warehouse Stock</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Flavor</th>
                                    <th>Available</th>
                                    <th>Expiration Date</th>
                                    <th class="pe-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($warehouseProducts as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <strong>{{ $item->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->product->brand ?? 'No brand' }}</small>
                                        </div>
                                        <td>
                                            @if($item->flavor)
                                                {{ $item->flavor->name }}
                                            @else
                                                <span class="text-muted">No flavor</span>
                                            @endif
                                        </div>
                                        <td>
                                            <span class="fw-bold text-success">{{ number_format($item->quantity) }} units</span>
                                        </div>
                                        <td>
                                            @if($item->expiration_date)
                                                {{ \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') }}
                                                @if(\Carbon\Carbon::parse($item->expiration_date)->isPast())
                                                    <span class="badge bg-danger ms-1">Expired</span>
                                                @elseif(\Carbon\Carbon::parse($item->expiration_date)->diffInDays(now()) <= 30)
                                                    <span class="badge bg-warning ms-1">Soon</span>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </div>
                                        <td class="pe-4 text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" 
                                                    data-bs-toggle="modal" data-bs-target="#requestProductModal{{ $item->id }}">
                                                <i class="bi bi-cart-plus me-1"></i>Request
                                            </button>
                                        </div>
                                    </tr>

                                    <!-- Request Modal for this specific product + flavor (with expiration) -->
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
                                                            <input type="text" class="form-control" value="{{ $item->product->name }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Flavor</label>
                                                            <input type="text" class="form-control" value="{{ $item->flavor ? $item->flavor->name : 'No flavor' }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Expiration Date</label>
                                                            <input type="text" class="form-control" 
                                                                   value="{{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') : 'No expiry' }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Available in Warehouse</label>
                                                            <input type="text" class="form-control" value="{{ number_format($item->quantity) }} units" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Quantity to Request <span class="text-danger">*</span></label>
                                                            <input type="number" name="quantity" class="form-control" min="1" max="{{ $item->quantity }}" required>
                                                            <div class="form-text">Max: {{ number_format($item->quantity) }} units</div>
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
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-building fs-1 text-muted d-block mb-2"></i>
                                            <p class="text-muted">No stock available in warehouse</p>
                                        </div>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Requests History -->
    @if($completedRequests->count() > 0)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-check-circle me-2 text-success"></i>Request History</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Request #</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th class="pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($completedRequests as $request)
                            <tr>
                                <td class="ps-4"><code>{{ $request->transfer_number }}</code></td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>{{ $request->product->name ?? 'N/A' }}</td>
                                <td>{{ $request->flavor ? $request->flavor->name : 'N/A' }}</td>
                                <td>{{ number_format($request->quantity) }}</td>
                                <td>
                                    @if($request->status == 'approved')
                                        <span class="badge bg-info">Approved - Ready to Receive</span>
                                    @elseif($request->status == 'completed')
                                        <span class="badge bg-success">Received</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($request->status) }}</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    @if($request->status == 'approved')
                                        <form action="{{ route('branch-admin.warehouse.receive', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill" onclick="return confirm('Receive this stock? This will add it to your branch inventory.')">
                                                <i class="bi bi-download me-1"></i>Receive Stock
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Main Request Modal (for quick request without flavor preselection) -->
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
                                @foreach($warehouseProducts->groupBy('product_id') as $productId => $items)
                                    @php $firstItem = $items->first(); @endphp
                                    <option value="{{ $firstItem->product_id }}" data-product-name="{{ $firstItem->product->name }}">
                                        {{ $firstItem->product->name }}
                                    </option>
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
                        <div class="form-text" id="maxQuantityHint"></div>
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
    // For the main request modal: load flavors and max quantity when product changes
    const productSelect = document.getElementById('requestProductSelect');
    const flavorSelect = document.getElementById('requestFlavorSelect');
    const quantityInput = document.getElementById('requestQuantity');
    const maxHint = document.getElementById('maxQuantityHint');

    // Store warehouse items data for quick lookup
    const warehouseItems = @json($warehouseProducts);

    if (productSelect) {
        productSelect.addEventListener('change', function() {
            const productId = this.value;
            if (productId) {
                // Find all flavors for this product
                const flavors = warehouseItems.filter(item => item.product_id == productId);
                flavorSelect.innerHTML = '<option value="">Select flavor...</option>';
                
                if (flavors.length > 0) {
                    flavors.forEach(flavor => {
                        const option = document.createElement('option');
                        option.value = flavor.id;
                        option.textContent = flavor.flavor ? flavor.flavor.name : 'No flavor';
                        option.dataset.maxQuantity = flavor.quantity;
                        flavorSelect.appendChild(option);
                    });
                    flavorSelect.disabled = false;
                } else {
                    flavorSelect.innerHTML = '<option value="">No flavors available</option>';
                    flavorSelect.disabled = true;
                }
                quantityInput.value = '';
                maxHint.textContent = '';
            } else {
                flavorSelect.disabled = true;
                flavorSelect.innerHTML = '<option value="">First select a product...</option>';
                quantityInput.value = '';
                maxHint.textContent = '';
            }
        });

        flavorSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const maxQty = selectedOption.dataset.maxQuantity || 0;
            quantityInput.max = maxQty;
            maxHint.textContent = `Max: ${parseInt(maxQty).toLocaleString()} units`;
            if (quantityInput.value > maxQty) {
                quantityInput.value = maxQty;
            }
        });
    }
</script>
@endsection