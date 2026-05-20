<div class="modal-content">
    <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add Stock to Branch</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <form id="addStockForm" method="POST" action="{{ route('admin.products.add-stock', $product) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control" value="{{ $product->name }}" readonly>
            </div>

            @if($product->flavors->count() > 0)
            <div class="mb-3">
                <label class="form-label">Flavor (optional)</label>
                <select name="flavor_id" class="form-select">
                    <option value="">-- All flavors --</option>
                    @foreach($product->flavors as $flavor)
                        <option value="{{ $flavor->id }}">{{ $flavor->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Select Branch <span class="text-danger">*</span></label>
                <select name="branch_id" class="form-select" required>
                    <option value="">-- Choose Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" min="1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expiration Date</label>
                    <input type="date" name="expiration_date" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Purchase Price (₱) – optional</label>
                <input type="number" step="0.01" name="purchase_price" class="form-control" placeholder="e.g., 150.00">
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (optional)</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>

            <div id="formAlert" class="alert" style="display: none;"></div>
        </form>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="submitStockBtn">
            <i class="bi bi-check-circle me-1"></i> Add Stock
        </button>
    </div>
</div>

<script>
    document.getElementById('submitStockBtn').addEventListener('click', function() {
        const form = document.getElementById('addStockForm');
        const formData = new FormData(form);
        const alertDiv = document.getElementById('formAlert');
        const submitBtn = this;
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertDiv.className = 'alert alert-success';
                alertDiv.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> ' + data.message;
                alertDiv.style.display = 'block';
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addStockModal'));
                    modal.hide();
                    location.reload();
                }, 1500);
            } else {
                alertDiv.className = 'alert alert-danger';
                let errorHtml = '<i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Errors:</strong><ul>';
                for (const [field, errors] of Object.entries(data.errors)) {
                    errors.forEach(error => { errorHtml += `<li>${error}</li>`; });
                }
                errorHtml += '</ul>';
                alertDiv.innerHTML = errorHtml;
                alertDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i> An error occurred. Please try again.';
            alertDiv.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
</script>