@extends('layouts.customer')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-cart"></i> Shopping Cart</h2>

    @if(count($items) > 0)
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table cart-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Flavor</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light rounded p-2" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-box-seam fs-3 text-muted"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $item['product_name'] }}</strong>
                                        </div>
                                    </div>
                                 </td>
                                <td>{{ $item['flavor_name'] ?? '—' }}</td>
                                <td>₱{{ number_format($item['price'], 2) }}</td>
                                <td>
                                    <form action="{{ route('customer.cart.update', $item['inventory_id']) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control quantity-input" style="width: 80px;">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill">Update</button>
                                    </form>
                                 </td>
                                <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                <td>
                                    <form action="{{ route('customer.cart.remove', $item['inventory_id']) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                                    </form>
                                 </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h4 class="mb-3">Total: <span class="text-danger">₱{{ number_format($subtotal, 2) }}</span></h4>
                        <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary btn-lg rounded-pill px-5">Proceed to Checkout <i class="bi bi-arrow-right"></i></a>
                        <form action="{{ route('customer.cart.clear') }}" method="POST" class="d-inline-block ms-2">
                            @csrf
                            <button class="btn btn-outline-danger rounded-pill">Clear Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="mt-3">Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any items yet.</p>
            <a href="{{ route('customer.products.index') }}" class="btn btn-primary rounded-pill px-4">Start Shopping</a>
        </div>
    @endif
</div>
@endsection