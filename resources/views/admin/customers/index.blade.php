@extends('layouts.admin')
<style>
    .stat-card-modern {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        border: 1px solid #eef2f6;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .stat-card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
        border-color: #e0e7ed;
    }

    .stat-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: all 0.3s ease;
    }

    .stat-card-modern:hover .stat-icon-wrapper {
        transform: scale(1.02);
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 600;
        color: #8b9cb0;
        display: block;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        line-height: 1.2;
    }

    /* Individual icon colors */
    .col-md-3:first-child .stat-icon-wrapper {
        background: #eef4ff;
        color: #3b82f6;
    }

    .col-md-3:nth-child(2) .stat-icon-wrapper {
        background: #e6f7e6;
        color: #10b981;
    }

    .col-md-3:nth-child(3) .stat-icon-wrapper {
        background: #fef2f2;
        color: #ef4444;
    }

    .col-md-3:nth-child(4) .stat-icon-wrapper {
        background: #fefce8;
        color: #f59e0b;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .stat-card-modern {
            padding: 1rem;
            gap: 0.75rem;
        }

        .stat-icon-wrapper {
            width: 44px;
            height: 44px;
            font-size: 1.3rem;
            border-radius: 14px;
        }

        .stat-value {
            font-size: 1.4rem;
        }

        .stat-label {
            font-size: 0.65rem;
        }
    }
</style>
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
            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-person-plus me-1"></i> Add New Customer
            </a>
        </div>
    </div>

    <!-- Stats Cards - Modern Minimalist (Customers) -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Total Customers</span>
                    <h3 class="stat-value">{{ $totalCustomers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Active</span>
                    <h3 class="stat-value">{{ $activeCustomers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Inactive</span>
                    <h3 class="stat-value">{{ $inactiveCustomers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">New This Month</span>
                    <h3 class="stat-value">{{ $newThisMonth }}</h3>
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
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by name, email or phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary ms-2 px-4">
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
            </div>
            <td class="align-middle">{{ $customer->email }}
        </div>
        <td class="align-middle">{{ $customer->phone ?? 'N/A' }}
    </div>
    <td class="align-middle">
        @if ($customer->city)
            {{ $customer->city }}, {{ $customer->province ?? '' }}
        @else
            <span class="text-muted">N/A</span>
        @endif
        </div>
    <td class="align-middle">{{ $customer->created_at->format('M d, Y') }}</div>
    <td class="align-middle">
        @if ($customer->is_active)
            <span class="badge bg-success px-3 py-2">
                <i class="bi bi-check-circle me-1"></i> Active
            </span>
        @else
            <span class="badge bg-secondary px-3 py-2">
                <i class="bi bi-x-circle me-1"></i> Inactive
            </span>
        @endif
        </div>
    <td class="pe-4 text-end align-middle">
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-info" onclick="openViewModal({{ $customer->id }})"
                title="View Customer">
                <i class="bi bi-eye"></i>
            </button>
            <button type="button" class="btn btn-outline-warning" onclick="openEditModal({{ $customer->id }})"
                title="Edit Customer">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                data-bs-target="#deleteModal{{ $customer->id }}" title="Delete"
                {{ $customer->id == Auth::id() ? 'disabled' : '' }}>
                <i class="bi bi-trash"></i>
            </button>
        </div>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <p class="mt-3 text-muted mb-3">No customers found</p>
                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-person-plus me-1"></i> Add First Customer
                </a>
                </div>
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

        <!-- View Modal Container -->
        <div class="modal fade" id="viewModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="text-center p-5">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading customer information...</p>
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
                            <p class="text-muted small mb-0">This action cannot be undone. All customer data will be
                                removed.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                                class="d-inline">
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

            function openViewModal(userId) {
                const url = '/admin/customers/' + userId + '/show';
                console.log('Opening view modal with URL:', url);

                const viewModal = document.getElementById('viewModal');
                const modalContent = viewModal.querySelector('.modal-content');

                modalContent.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading customer information...</p>
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
                        <p>Failed to load customer information.</p>
                        <p class="text-danger">Error: ${error.message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                `;
                    });

                const bsModal = new bootstrap.Modal(viewModal);
                bsModal.show();
            }
        </script>
    @endsection
