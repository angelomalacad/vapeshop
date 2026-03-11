@extends('layouts.branch-admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Low Stock Items</h1>
        <a href="{{ route('branch-admin.inventory.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($items->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Flavor</th>
                            <th>Stock</th>
                            <th>Available</th>
                            <th>Threshold</th>
                            <th>Action</th>
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
                            <td>
                                <a href="{{ route('branch-admin.inventory.add-stock', $item) }}" class="btn btn-sm btn-success">Add Stock</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center text-muted py-3">No low stock items found!</p>
            @endif
        </div>
    </div>
</div>
@endsection