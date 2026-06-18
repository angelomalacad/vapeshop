@extends('layouts.admin-modal')

@section('title', 'Distribute Stock')

@section('content')
    <div class="modal-header-minimal">
        <h5 class="modal-title">
            <i class="bi bi-send"></i> Distribute Stock
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <form action="{{ route('admin.warehouse.distribute') }}" method="POST" id="distributeWarehouseForm">
        @csrf
        <input type="hidden" name="warehouse_stock_id" value="{{ $item->id }}">
        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
        <input type="hidden" name="flavor_id" value="{{ $item->flavor_id }}">

        <div class="alert alert-info alert-minimal">
            <i class="bi bi-info-circle me-1"></i>
            Distributing stock will immediately create an approved transfer. The branch can then receive it.
        </div>

        <div class="mb-3">
            <label class="form-label-minimal">Product</label>
            <input type="text" class="form-control-minimal" value="{{ $item->product->name ?? 'N/A' }}" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label-minimal">Flavor</label>
            <input type="text" class="form-control-minimal" value="{{ $item->flavor ? $item->flavor->name : 'N/A' }}"
                readonly>
        </div>

        <div class="mb-3">
            <label class="form-label-minimal">Expiration Date</label>
            <input type="text" class="form-control-minimal"
                value="{{ $item->expiration_date ? \Carbon\Carbon::parse($item->expiration_date)->format('M d, Y') : 'No expiry' }}"
                readonly>
        </div>

        <div class="mb-3">
            <label class="form-label-minimal">Available in Warehouse</label>
            <input type="text" class="form-control-minimal" value="{{ number_format($item->quantity) }} units" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label-minimal">Select Branch <span class="text-danger">*</span></label>
            <select name="branch_id" class="form-select-minimal" required>
                <option value="">Select branch...</option>
                @foreach (\App\Models\Branch::where('is_active', true)->get() as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label-minimal">Quantity <span class="text-danger">*</span></label>
            <input type="number" name="quantity" class="form-control-minimal" min="1" max="{{ $item->quantity }}"
                required>
            <div class="form-text" style="font-size: 0.7rem; color: #94a3b8;">Max: {{ number_format($item->quantity) }}
                units</div>
        </div>

        <div class="mb-3">
            <label class="form-label-minimal">Notes (Optional)</label>
            <textarea name="notes" class="form-control-minimal" rows="2" placeholder="Distribution notes..."></textarea>
        </div>

        <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding-top: 1rem;">
            <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-update" id="distributeSubmitBtn"
                style="width: auto; background: #3b82f6;">Send to Branch</button>
        </div>
    </form>
@endsection
