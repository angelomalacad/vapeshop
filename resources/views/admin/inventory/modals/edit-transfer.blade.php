@extends('layouts.admin-modal')

@section('content')
<div class="modal-header-minimal">
    <h5 class="modal-title">
        <i class="bi bi-pencil-square"></i> Edit Transfer
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="closeAdminModal()"></button>
</div>

<form method="POST" action="{{ route('admin.inventory.transfers.update', $transfer) }}" id="editTransferForm">
    @csrf
    @method('PUT')
    <input type="hidden" name="transfer_id" value="{{ $transfer->id }}">
    
    <div class="admin-modal-container" style="padding: 0 0 1rem 0;">
        @if($transfer->status != 'pending')
        <div class="alert alert-danger alert-minimal">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            This transfer is {{ $transfer->status }} and cannot be edited.
        </div>
        @endif

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label-minimal">From Branch *</label>
                <select name="from_branch_id" class="form-select-minimal" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                    <option value="">Select Source Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('from_branch_id', $transfer->from_branch_id) == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }} ({{ $branch->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label-minimal">To Branch *</label>
                <select name="to_branch_id" class="form-select-minimal" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                    <option value="">Select Destination Branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('to_branch_id', $transfer->to_branch_id) == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }} ({{ $branch->code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label-minimal">Product *</label>
                <select name="product_id" class="form-select-minimal" id="product_id" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $transfer->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->brand }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label-minimal">Flavor</label>
                <select name="flavor_id" class="form-select-minimal" id="flavor_id" {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
                    <option value="">No Flavor</option>
                    @foreach($products as $product)
                        @if($product->id == $transfer->product_id)
                            @foreach($product->flavors as $flavor)
                                <option value="{{ $flavor->id }}" {{ $transfer->flavor_id == $flavor->id ? 'selected' : '' }}>
                                    {{ $flavor->name }}
                                </option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label-minimal">Quantity *</label>
                <input type="number" name="quantity" class="form-control-minimal" value="{{ old('quantity', $transfer->quantity) }}" min="1" required {{ $transfer->status != 'pending' ? 'disabled' : '' }}>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label-minimal">Notes (Optional)</label>
                <textarea name="notes" class="form-control-minimal" rows="2" {{ $transfer->status != 'pending' ? 'disabled' : '' }}>{{ old('notes', $transfer->notes) }}</textarea>
            </div>
        </div>
    </div>

    <div class="modal-footer" style="border-top: 1px solid #eef2f6; padding-top: 1rem;">
        <button type="button" class="btn-secondary-minimal" data-bs-dismiss="modal" onclick="closeAdminModal()">
            <i class="bi bi-x-circle me-1"></i> Cancel
        </button>
        @if($transfer->status == 'pending')
        <button type="submit" class="btn-update" id="submitEditTransferBtn" style="width: auto; background: #f59e0b;">
            <i class="bi bi-check-circle me-1"></i> Update Transfer
        </button>
        @endif
    </div>
</form>
@endsection