@extends('layouts.admin')

@section('title', $product->name . ' - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">{{ $product->name }}</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-box me-1"></i> Product Details
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning rounded-pill px-3">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Product Image -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    @if($product->image_url)
                        <img src="{{ \App\Helpers\GoogleDriveHelper::getDirectImageUrl($product->image_url) }}" 
                             alt="{{ $product->name }}"
                             class="img-fluid rounded"
                             style="max-height: 300px; object-fit: contain;">
                        <p class="text-muted small mt-2"><i class="bi bi-google"></i> Image from Google Drive</p>
                    @elseif($product->image)
                        <img src="{{ Storage::url($product->image) }}" 
                             alt="{{ $product->name }}"
                             class="img-fluid rounded"
                             style="max-height: 300px; object-fit: contain;">
                    @else
                        <div class="py-5">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted mt-3">No image available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Name:</td>
                                    <td class="fw-semibold">{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Brand:</td>
                                    <td>{{ $product->brand }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Category:</td>
                                    <td>{{ $product->category }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Type:</td>
                                    <td>{{ ucfirst($product->type) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Price:</td>
                                    <td class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($product->nicotine_strength)
                                <tr>
                                    <td class="text-muted">Nicotine:</td>
                                    <td>{{ $product->nicotine_strength }}</td>
                                </tr>
                                @endif
                                @if($product->puff_count)
                                <tr>
                                    <td class="text-muted">Puff Count:</td>
                                    <td>{{ number_format($product->puff_count) }}</td>
                                </tr>
                                @endif
                                @if($product->battery_capacity)
                                <tr>
                                    <td class="text-muted">Battery:</td>
                                    <td>{{ $product->battery_capacity }}mAh</td>
                                </tr>
                                @endif
                                @if($product->liquid_capacity)
                                <tr>
                                    <td class="text-muted">Liquid Capacity:</td>
                                    <td>{{ $product->liquid_capacity }}ml</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="text-muted">Status:</td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if($product->description)
                    <div class="mt-3">
                        <h6 class="fw-semibold">Description</h6>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Flavors -->
            @if($product->flavors->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-droplet me-2 text-primary"></i>Flavors ({{ $product->flavors->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($product->flavors as $flavor)
                        <div class="col-md-4 mb-2">
                            <div class="border rounded p-2">
                                <span class="fw-semibold">{{ $flavor->name }}</span>
                                @if($flavor->code)
                                    <br><small class="text-muted">Code: {{ $flavor->code }}</small>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Branch Inventory -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-shop me-2 text-primary"></i>Stock by Branch</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Branch</th>
                                    <th>Quantity</th>
                                    <th>Reserved</th>
                                    <th>Available</th>
                                    <th class="pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branchInventories as $inv)
                                @php
                                    $available = $inv->quantity - $inv->reserved_quantity;
                                    $statusClass = $available <= 0 ? 'danger' : ($available <= $inv->low_stock_threshold ? 'warning' : 'success');
                                    $statusText = $available <= 0 ? 'Out of Stock' : ($available <= $inv->low_stock_threshold ? 'Low Stock' : 'In Stock');
                                @endphp
                                <tr>
                                    <td class="ps-4">{{ $inv->branch->name }}</td>
                                    <td>{{ $inv->quantity }}</td>
                                    <td>{{ $inv->reserved_quantity }}</td>
                                    <td>{{ $available }}</td>
                                    <td class="pe-4">
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3 text-muted">No stock in any branch</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection