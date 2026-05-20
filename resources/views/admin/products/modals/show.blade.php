<div class="modal-content">
    <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>Product Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-5 text-center">
                @if($product->image_url)
                    <img src="{{ \App\Helpers\GoogleDriveHelper::getDirectImageUrl($product->image_url) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 200px;">
                @elseif($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 200px;">
                @else
                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                @endif
            </div>
            <div class="col-md-7">
                <h4>{{ $product->name }}</h4>
                <table class="table table-sm mt-2">
                    <tr><th>Brand:</th><td>{{ $product->brand }}</td></tr>
                    <tr><th>Category:</th><td>{{ $product->category }}</td></tr>
                    <tr><th>Type:</th><td>{{ ucfirst($product->type) }}</td></tr>
                    <tr><th>Price:</th><td class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</td></tr>
                    @if($product->nicotine_strength) <tr><th>Nicotine:</th><td>{{ $product->nicotine_strength }}</td></tr> @endif
                    @if($product->puff_count) <tr><th>Puff Count:</th><td>{{ number_format($product->puff_count) }}</td></tr> @endif
                    @if($product->battery_capacity) <tr><th>Battery:</th><td>{{ $product->battery_capacity }}mAh</td></tr> @endif
                    @if($product->liquid_capacity) <tr><th>Liquid Capacity:</th><td>{{ $product->liquid_capacity }}ml</td></tr> @endif
                    <tr><th>Status:</th><td>@if($product->is_active) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td></tr>
                </table>
                @if($product->description)
                    <p class="mt-2 mb-0"><strong>Description:</strong><br>{{ $product->description }}</p>
                @endif
            </div>
        </div>

        @if($product->flavors->count() > 0)
        <hr>
        <h6 class="fw-semibold"><i class="bi bi-droplet me-1"></i>Flavors ({{ $product->flavors->count() }})</h6>
        <div class="row">
            @foreach($product->flavors as $flavor)
                <div class="col-md-4 mb-1">
                    <span class="badge bg-secondary">{{ $flavor->name }}</span>
                    @if($flavor->code) <small class="text-muted">({{ $flavor->code }})</small> @endif
                </div>
            @endforeach
        </div>
        @endif

        <hr>
        <h6 class="fw-semibold"><i class="bi bi-shop me-1"></i>Stock by Branch</h6>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr><th>Branch</th><th>In Stock</th><th>Reserved</th><th>Available</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($branchInventories as $inv)
                    @php
                        $available = $inv->quantity - $inv->reserved_quantity;
                        $statusClass = $available <= 0 ? 'danger' : ($available <= $inv->low_stock_threshold ? 'warning' : 'success');
                    @endphp
                    <tr>
                        <td>{{ $inv->branch->name }}</td>
                        <td>{{ $inv->quantity }}</td>
                        <td>{{ $inv->reserved_quantity }}</td>
                        <td>{{ $available }}</td>
                        <td><span class="badge bg-{{ $statusClass }}">{{ $available <= 0 ? 'Out' : ($available <= $inv->low_stock_threshold ? 'Low' : 'In Stock') }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">No stock in any branch</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
</div>