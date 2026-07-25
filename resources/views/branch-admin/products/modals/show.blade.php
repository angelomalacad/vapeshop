@php
    use App\Helpers\GoogleDriveHelper;
@endphp

<style>
    /* === MODERN MINIMALIST MODAL STYLES (from admin-modal.blade.php) === */
    .admin-modal-container {
        padding: 1.5rem;
        max-height: 85vh;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .admin-modal-container::-webkit-scrollbar {
        width: 6px;
    }

    .admin-modal-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .admin-modal-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .modal-header-minimal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eef2f6;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .modal-title i {
        color: #3b82f6;
        margin-right: 0.5rem;
    }

    .btn-update {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        background: #2563eb;
        transform: translateY(-1px);
        color: white;
    }

    .btn-secondary-minimal {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 12px;
        padding: 0.6rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary-minimal:hover {
        background: #e2e8f0;
    }

    .alert-minimal {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        margin-bottom: 1rem;
    }

    .alert-info-minimal {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        color: #2563eb;
    }

    @media (max-width: 768px) {
        .admin-modal-container {
            padding: 1rem;
        }
    }
</style>

<div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="admin-modal-container">
                <!-- Modal Header -->
                <div class="modal-header-minimal">
                    <h5 class="modal-title"><i class="bi bi-box-seam"></i>{{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0">
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
                    <div class="alert alert-info alert-minimal mt-3 mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Branch Stock:</strong> {{ $branchInventory->quantity }} units available
                        @if($branchInventory->reserved_quantity > 0)
                            <br><small>{{ $branchInventory->reserved_quantity }} reserved for pending orders</small>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Footer (Global UI, no modal-footer) -->
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn-update" style="width: auto; padding: 0.5rem 1.25rem; background: #f59e0b;" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}" data-bs-dismiss="modal">
                        <i class="bi bi-pencil"></i> Edit Product
                    </button>
                    @if(!$branchInventory)
                        <a href="{{ route('branch-admin.inventory.add-to-inventory', $product->id) }}" class="btn-update" style="width: auto; padding: 0.5rem 1.25rem; text-decoration: none;">
                            <i class="bi bi-plus-circle"></i> Add to Inventory
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>