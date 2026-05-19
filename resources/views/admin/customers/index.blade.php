@extends('layouts.admin')

@section('title', 'Customer Management - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Customer Management</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-people-fill me-1"></i> Manage your registered customers
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-person-plus me-1"></i> Add New Customer
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Customers</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalCustomers }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Active</h6>
                            <h2 class="mb-0 fw-bold">{{ $activeCustomers }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Inactive</h6>
                            <h2 class="mb-0 fw-bold">{{ $inactiveCustomers }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-x-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">New This Month</h6>
                            <h2 class="mb-0 fw-bold">{{ $newThisMonth }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-calendar-plus fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter Customers</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, email or phone..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                </div>
                <div class="col-12">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-people-fill me-2 text-primary"></i>
                Registered Customers
            </h5>
            <span class="text-muted small">Total: {{ $customers->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Registered</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="bi bi-person-circle text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $customer->name }}</span>
                                            <br>
                                            <small class="text-muted">ID: #{{ $customer->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone ?? 'N/A' }}</td>
                                <td>
                                    @if($customer->city)
                                        {{ $customer->city }}, {{ $customer->province ?? '' }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if ($customer->is_active)
                                        <span class="badge bg-success px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-warning" 
                                                onclick="openEditModal({{ $customer->id }})"
                                                title="Edit Customer">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $customer->id }}" 
                                                title="Delete"
                                                {{ $customer->id == Auth::id() ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-people display-1 text-muted"></i>
                                    <p class="mt-3 text-muted mb-3">No customers found</p>
                                    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-person-plus me-1"></i> Add First Customer
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($customers->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-center">
                    {{ $customers->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="mt-4 pt-3 text-muted border-top">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">
                    <i class="bi bi-telephone me-2"></i> Contact: <strong>Carlo Caranto - 0960 328 0432</strong>
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">
                    <i class="bi bi-shield-check me-2"></i> Vape Expo - Authorized Personnel Only
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal Container -->
<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading form...</p>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modals -->
@foreach ($customers as $customer)
    <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>{{ $customer->name }}</strong>?</p>
                    <p class="text-muted small mb-0">This action cannot be undone. All customer data will be removed.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    function openEditModal(userId) {
        const url = '/admin/customers/' + userId + '/modal-edit';
        console.log('Opening modal with URL:', url);
        
        const editModal = document.getElementById('editModal');
        const modalContent = editModal.querySelector('.modal-content');
        
        modalContent.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading form...</p>
            </div>
        `;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                modalContent.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Failed to load form.</p>
                        <p class="text-danger">Error: ${error.message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                `;
            });
        
        const bsModal = new bootstrap.Modal(editModal);
        bsModal.show();
    }
</script>
@endsection