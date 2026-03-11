@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Low Stock Items</h1>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">Back to Inventory</a>
    </div>

    @forelse($lowStockItems as $branchName => $items)
    <div class="card mb-3">
        <div class="card-header bg-warning">
            <h5 class="mb-0">{{ $branchName }} ({{ $items->count() }} items)</h5>
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Flavor</th>
                        <th>Stock</th>
                        <th>Available</th>
                        <th>Threshold</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->flavor->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td class="text-warning fw-bold">{{ $item->available_quantity }}</td>
                        <td>{{ $item->low_stock_threshold }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="alert alert-success">No low stock items found!</div>
    @endforelse
</div>
@endsection