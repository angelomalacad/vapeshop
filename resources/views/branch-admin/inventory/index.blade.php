<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">VapeShop</a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('branch-admin.dashboard') }}" class="nav-link">Dashboard</a>
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Inventory Management</h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="mb-3">
            <a href="{{ route('branch-admin.inventory.create') }}" class="btn btn-primary">
                Add Product to Inventory
            </a>
            <a href="{{ route('branch-admin.products.create') }}" class="btn btn-success">
                Create New Product
            </a>
        </div>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Low Stock Alert</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inventory as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->low_stock_threshold }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">Edit</a>
                            <a href="#" class="btn btn-sm btn-danger">Remove</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No inventory items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{ $inventory->links() }}
    </div>
</body>
</html>