@php
    use App\Helpers\GoogleDriveHelper;
@endphp

<div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-box-seam me-2"></i>{{ $product->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="text-center p-3 bg-light rounded">
                            @if($product->image_url)
                                <img src="{{ GoogleDriveHelper::getDirectImageUrl($product->image_url) }}" 
                                     alt="{{ $product->name }}"
                                     style="max-height: 200px; max-width: 100%; object-fit: contain;"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Image+Error';">
                            @elseif($product->image)
                                <img src="{{ Storage::url($product->image) }}" 
                                     alt="{{ $product->name }}"
                                     style="max-height: 200px; max-width: 100%; object-fit: contain;">
                            @else
                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                                <p class="text-muted mt-2">No image available</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h5 class="mb-2">Product Details</h5>
                        <table class="table table-sm">
                            <tr>
                                <th style="width: 40%">Brand:</th>
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
                                <td class="fw-bold text-primary">₱{{ number_format($product->price, 2) }}</td>
                            </tr>
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
                        </table>
                    </div>
                </div>
                
                @if($product->description)
                <div class="mt-3">
                    <h6>Description</h6>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>
                @endif
                
                @if($product->flavors->count() > 0)
                <div class="mt-3">
                    <h6>Flavors</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($product->flavors as $flavor)
                            <span class="badge bg-secondary">{{ $flavor->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @php
                    $branchInventory = $product->branchInventories
                        ->where('branch_id', Auth::user()->branch_id)
                        ->first();
                @endphp
                
                @if($branchInventory)
                <div class="alert alert-info mt-3 mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Branch Stock:</strong> {{ $branchInventory->quantity }} units available
                    @if($branchInventory->reserved_quantity > 0)
                        <br><small>{{ $branchInventory->reserved_quantity }} reserved for pending orders</small>
                    @endif
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}" data-bs-dismiss="modal">
                    <i class="bi bi-pencil"></i> Edit Product
                </button>
                @if(!$branchInventory)
                    <a href="{{ route('branch-admin.inventory.add-to-inventory', $product->id) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add to Inventory
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>