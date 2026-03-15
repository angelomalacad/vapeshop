@extends('layouts.admin')

@section('title', 'Add Stock - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Add Stock</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-plus-circle me-1"></i> {{ $inventory->product->name }} @if($inventory->flavor)- {{ $inventory->flavor->name }}@endif
                </p>
            </div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    </div>

    <!-- Current Stock Info -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Current Stock</h6>
                    <h2 class="mb-0 fw-bold">{{ $inventory->quantity }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Reserved</h6>
                    <h2 class="mb-0 fw-bold">{{ $inventory->reserved_quantity }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Available</h6>
                    <h2 class="mb-0 fw-bold">{{ $inventory->available_quantity }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Low Stock At</h6>
                    <h2 class="mb-0 fw-bold">{{ $inventory->low_stock_threshold }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.add-stock.post', $inventory) }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Quantity to Add *</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Enter the number of units to add to current stock</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Purchase Price (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}" min="0">
                        </div>
                        <small class="text-muted">Last purchase price for cost tracking</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Restock from supplier, transfer received, etc.">{{ old('notes') }}</textarea>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <strong>Note:</strong> Adding stock will update the inventory quantity and create a stock movement record. Current stock: <strong>{{ $inventory->quantity }}</strong> → New stock: <strong>{{ $inventory->quantity }} + [quantity]</strong>
                </div>

                <hr class="my-4">
                
                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.inventory.show', $inventory) }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 me-2">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-5">
                            <i class="bi bi-plus-circle me-1"></i> Add Stock
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection