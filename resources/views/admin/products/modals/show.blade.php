@extends('layouts.admin-modal')

@section('title', 'Product Details')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-box-seam me-2"></i> Product Details
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body" style="padding: 0;">
        <div class="row">
            <div class="col-md-5">
                <div class="info-card h-100">
                    <div class="card-header-minimal">
                        <h6><i class="bi bi-image"></i> Product Image</h6>
                    </div>
                    <div class="card-body-minimal text-center">
                        @if ($product->image_url)
                            <img src="{{ \App\Helpers\GoogleDriveHelper::getDirectImageUrl($product->image_url) }}"
                                alt="{{ $product->name }}" class="img-fluid rounded"
                                style="max-height: 200px; width: auto; object-fit: contain;">
                        @elseif($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                class="img-fluid rounded" style="max-height: 200px; width: auto; object-fit: contain;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                style="height: 200px;">
                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="info-card h-100">
                    <div class="card-header-minimal">
                        <h6><i class="bi bi-info-circle"></i> {{ $product->name }}</h6>
                    </div>
                    <div class="card-body-minimal">
                        <div class="info-row">
                            <div class="info-label">Brand</div>
                            <div class="info-value">{{ $product->brand }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Category</div>
                            <div class="info-value">{{ $product->category }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Type</div>
                            <div class="info-value">{{ ucfirst($product->type) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Price</div>
                            <div class="info-value fw-bold text-primary">₱{{ number_format($product->price, 2) }}</div>
                        </div>
                        @if ($product->nicotine_strength)
                            <div class="info-row">
                                <div class="info-label">Nicotine</div>
                                <div class="info-value">{{ $product->nicotine_strength }}</div>
                            </div>
                        @endif
                        @if ($product->puff_count)
                            <div class="info-row">
                                <div class="info-label">Puff Count</div>
                                <div class="info-value">{{ number_format($product->puff_count) }}</div>
                            </div>
                        @endif
                        @if ($product->battery_capacity)
                            <div class="info-row">
                                <div class="info-label">Battery</div>
                                <div class="info-value">{{ $product->battery_capacity }}mAh</div>
                            </div>
                        @endif
                        @if ($product->liquid_capacity)
                            <div class="info-row">
                                <div class="info-label">Liquid Capacity</div>
                                <div class="info-value">{{ $product->liquid_capacity }}ml</div>
                            </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                @if ($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($product->description)
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-file-text"></i> Description</h6>
                </div>
                <div class="card-body-minimal">
                    <p class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;">{{ $product->description }}</p>
                </div>
            </div>
        @endif

        @if ($product->flavors->count() > 0)
            <div class="info-card mt-3">
                <div class="card-header-minimal">
                    <h6><i class="bi bi-box"></i> Variant ({{ $product->flavors->count() }})</h6>
                </div>
                <div class="card-body-minimal">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($product->flavors as $flavor)
                            <span class="badge bg-secondary">{{ $flavor->name }}</span>
                            @if ($flavor->code)
                                <small class="text-muted">({{ $flavor->code }})</small>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="info-card mt-3">
            <div class="card-header-minimal">
                <h6><i class="bi bi-shop"></i> Stock by Branch</h6>
            </div>
            <div class="card-body-minimal p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>In Stock</th>
                                <th>Reserved</th>
                                <th>Available</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branchInventories as $inv)
                                @php
                                    $available = $inv->quantity - $inv->reserved_quantity;
                                    $statusClass =
                                        $available <= 0
                                            ? 'danger'
                                            : ($available <= $inv->low_stock_threshold
                                                ? 'warning'
                                                : 'success');
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $inv->branch->name }}</td>
                                    <td>{{ $inv->quantity }}</td>
                                    <td>{{ $inv->reserved_quantity }}</td>
                                    <td>{{ $available }}</td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $available <= 0 ? 'Out of Stock' : ($available <= $inv->low_stock_threshold ? 'Low Stock' : 'In Stock') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No stock in any branch</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end mt-3">
        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Close
        </button>
    </div>
@endsection
