@extends('layouts.branch-admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $product->name }}</h1>
            <p class="text-muted mb-0">
                <i class="bi bi-tag me-1"></i> {{ $product->category }}
                @if($product->brand)
                    <span class="mx-2">|</span>
                    <i class="bi bi-building"></i> {{ $product->brand }}
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('branch-admin.products.edit', $product) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('branch-admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Product Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Brand:</th>
                                    <td>{{ $product->brand ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $product->category }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ ucfirst(str_replace('-', ' ', $product->type)) }}</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td class="fw-bold">₱{{ number_format($product->price, 2) }}</td>
                                </tr>
                                @if($product->cost)
                                <tr>
                                    <th>Cost:</th>
                                    <td>₱{{ number_format($product->cost, 2) }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                @if($product->nicotine_strength)
                                <tr>
                                    <th>Nicotine:</th>
                                    <td>{{ $product->nicotine_strength }}</td>
                                </tr>
                                @endif
                                @if($product->puff_count)
                                <tr>
                                    <th>Puff Count:</th>
                                    <td>{{ number_format($product->puff_count) }}</td>
                                </tr>
                                @endif
                                @if($product->battery_capacity)
                                <tr>
                                    <th>Battery:</th>
                                    <td>{{ $product->battery_capacity }}mAh</td>
                                </tr>
                                @endif
                                @if($product->liquid_capacity)
                                <tr>
                                    <th>Liquid Capacity:</th>
                                    <td>{{ $product->liquid_capacity }}ml</td>
                                </tr>
                                @endif
                                @if($product->charging_type)
                                <tr>
                                    <th>Charging:</th>
                                    <td>{{ $product->charging_type }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    @if($product->description)
                    <div class="mt-3">
                        <h6>Description</h6>
                        <p class="text-muted">{{ $product->description }}</p>
                    </div>
                    @endif
                    
                    @if($product->adjustable_airflow || $product->smart_display)
                    <div class="mt-2">
                        @if($product->adjustable_airflow)
                            <span class="badge bg-info me-1">Adjustable Airflow</span>
                        @endif
                        @if($product->smart_display)
                            <span class="badge bg-info">Smart Display</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Flavors Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Available Flavors ({{ $product->flavors->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($product->flavors->count() > 0)
                        <div class="list-group">
                            @foreach($product->flavors as $flavor)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $flavor->name }}</strong>
                                        @if($flavor->category)
                                            <br>
                                            <small class="text-muted">{{ $flavor->category }}</small>
                                        @endif
                                    </div>
                                    <span class="badge bg-primary">{{ $flavor->code ?? '' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No flavors available</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('branch-admin.inventory.add-product') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add to Inventory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection