@extends('layouts.branch-admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add Stock</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>Product:</strong> {{ $inventory->product->name }}<br>
                        <strong>Flavor:</strong> {{ $inventory->flavor->name ?? 'N/A' }}<br>
                        <strong>Current Stock:</strong> {{ $inventory->quantity }}
                    </div>

                    <form method="POST" action="{{ route('branch-admin.inventory.add-stock.post', $inventory) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Quantity to Add</label>
                            <input type="number" name="quantity" class="form-control" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Received from supplier"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('branch-admin.inventory.show', $inventory) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Add Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection