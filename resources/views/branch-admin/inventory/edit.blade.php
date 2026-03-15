@extends('layouts.branch-admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Inventory Item</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-pencil-square me-1"></i> Update inventory settings for {{ $inventory->product->name }}
                @if($inventory->flavor)
                    - {{ $inventory->flavor->name }}
                @endif
            </p>
        </div>
        <a href="{{ route('branch-admin.inventory.show', $inventory) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Details
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Please fix the following errors:</strong>
            <button type="button" class="btn-close float-end" data-bs-dismiss="alert"></button>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i> Inventory Settings</h5>
                </div>
                <div class="card-body">
                    <!-- Product Info Summary -->
                    <div class="alert alert-info bg-light border-0 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Product</small>
                                <strong>{{ $inventory->product->name }}</strong>
                                @if($inventory->flavor)
                                    <br><span class="badge bg-secondary mt-1">{{ $inventory->flavor->name }}</span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Category</small>
                                <strong>{{ $inventory->product->category }}</strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block">Price</small>
                                <strong>₱{{ number_format($inventory->product->price, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('branch-admin.inventory.update', $inventory) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Stock Quantities Section -->
                        <h6 class="mb-3 text-primary"><i class="bi bi-box-seam me-2"></i>Stock Quantities</h6>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                       value="{{ old('quantity', $inventory->quantity) }}" min="0" required>
                                <small class="text-muted">Physical stock count</small>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Reserved Quantity</label>
                                <input type="number" name="reserved_quantity" class="form-control @error('reserved_quantity') is-invalid @enderror" 
                                       value="{{ old('reserved_quantity', $inventory->reserved_quantity) }}" min="0">
                                <small class="text-muted">Stock reserved for pending orders</small>
                                @error('reserved_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Available Quantity</label>
                                <input type="text" class="form-control bg-light" value="{{ $inventory->available_quantity }}" readonly disabled>
                                <small class="text-muted">Auto-calculated (Quantity - Reserved)</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Threshold Settings Section -->
                        <h6 class="mb-3 text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Threshold Settings</h6>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Low Stock Threshold <span class="text-danger">*</span></label>
                                <input type="number" name="low_stock_threshold" class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                       value="{{ old('low_stock_threshold', $inventory->low_stock_threshold) }}" min="1" required>
                                <small class="text-muted">Alert when stock reaches this level</small>
                                @error('low_stock_threshold')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Reorder Point <span class="text-danger">*</span></label>
                                <input type="number" name="reorder_point" class="form-control @error('reorder_point') is-invalid @enderror" 
                                       value="{{ old('reorder_point', $inventory->reorder_point) }}" min="1" required>
                                <small class="text-muted">When to reorder</small>
                                @error('reorder_point')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Optimal Stock Level <span class="text-danger">*</span></label>
                                <input type="number" name="optimal_stock" class="form-control @error('optimal_stock') is-invalid @enderror" 
                                       value="{{ old('optimal_stock', $inventory->optimal_stock) }}" min="1" required>
                                <small class="text-muted">Target stock level</small>
                                @error('optimal_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Timestamp Section -->
                        <h6 class="mb-3 text-info"><i class="bi bi-calendar me-2"></i>Last Updated</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Restocked Date</label>
                                <input type="datetime-local" name="last_restocked_at" class="form-control @error('last_restocked_at') is-invalid @enderror" 
                                       value="{{ old('last_restocked_at', $inventory->last_restocked_at ? $inventory->last_restocked_at->format('Y-m-d\TH:i') : '') }}">
                                <small class="text-muted">When stock was last added</small>
                                @error('last_restocked_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Updated</label>
                                <input type="text" class="form-control bg-light" value="{{ $inventory->updated_at ? $inventory->updated_at->format('M d, Y h:i A') : 'Never' }}" readonly disabled>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex align-items-center">
                                        <span class="me-3">Current Status:</span>
                                        @if($inventory->available_quantity <= 0)
                                            <span class="badge bg-danger p-2">Out of Stock</span>
                                        @elseif($inventory->available_quantity <= $inventory->low_stock_threshold)
                                            <span class="badge bg-warning p-2">Low Stock</span>
                                        @else
                                            <span class="badge bg-success p-2">In Stock</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Warning:</strong> Changing quantity or reserved stock directly will affect inventory levels. Use "Add Stock" for regular restocking.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('branch-admin.inventory.show', $inventory) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Inventory Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stock Movement Summary -->
            <div class="card mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Recent Stock Movements</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentMovements = \App\Models\StockMovement::where('branch_id', $inventory->branch_id)
                            ->where('product_id', $inventory->product_id)
                            ->when($inventory->flavor_id, function($query) use ($inventory) {
                                return $query->where('flavor_id', $inventory->flavor_id);
                            })
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Change</th>
                                        <th>Previous</th>
                                        <th>New</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at->format('M d, h:i A') }}</td>
                                        <td>
                                            @php
                                                $typeColors = [
                                                    'purchase' => 'success',
                                                    'sale' => 'danger',
                                                    'transfer_out' => 'warning',
                                                    'transfer_in' => 'info',
                                                    'return' => 'primary',
                                                    'adjustment' => 'secondary',
                                                    'damaged' => 'dark',
                                                    'expired' => 'dark'
                                                ];
                                                $color = $typeColors[$movement->movement_type] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">
                                                {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                            </span>
                                        </td>
                                        <td class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                            <strong>{{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}</strong>
                                        </td>
                                        <td>{{ $movement->previous_quantity }}</td>
                                        <td>{{ $movement->new_quantity }}</td>
                                        <td>{{ Str::limit($movement->notes, 20) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-2">
                            <a href="{{ route('branch-admin.inventory.stock-history') }}?product_id={{ $inventory->product_id }}&flavor_id={{ $inventory->flavor_id }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-clock-history me-1"></i> View Full History
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                            <p>No recent stock movements found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection