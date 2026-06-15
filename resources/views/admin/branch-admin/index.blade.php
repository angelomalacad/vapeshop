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

    /* Individual icon colors - Light backgrounds only */
    .col-md-3:first-child .stat-icon-wrapper {
        background: #eef4ff;
        color: #3b82f6;
    }

    .col-md-3:nth-child(2) .stat-icon-wrapper {
        background: #e0f7fa;
        color: #06b6d4;
    }

    .col-md-3:nth-child(3) .stat-icon-wrapper {
        background: #e6f7e6;
        color: #10b981;
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

    /* Delete Modal Minimalist Styles - Added only, no deletions */
    .delete-modal-header {
        background: #dc2626;
        color: white;
        padding: 1rem 1.25rem;
        border-bottom: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .delete-modal-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }

    .delete-modal-body {
        padding: 1.25rem;
    }

    .delete-modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid #eef2f6;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #475569;
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .btn-delete {
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
        background: transparent;
        border: none;
        font-size: 1rem;
        cursor: pointer;
    }
</style>
@section('title', 'Branch Admin & Driver Management - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Branch Admin & Driver Management</h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-people-fill me-1"></i> Manage branch administrators and drivers
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.branch-admin.create') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-person-plus me-1"></i> Add New
            </a>
        </div>
    </div>

    <!-- Success Notification -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" id="successAlert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards - Modern Minimalist (White Theme) -->
    <div class="row g-4 mb-4">
        @php
            $totalBranchAdmins = \App\Models\User::where('role', 'branch_admin')->count();
            $totalDrivers = \App\Models\User::where('role', 'driver')->count();
            $activeUsers = \App\Models\User::whereIn('role', ['branch_admin', 'driver'])
                ->where('is_active', true)
                ->count();
            $totalBranches = \App\Models\Branch::count();
        @endphp
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Branch Admins</span>
                    <h3 class="stat-value">{{ $totalBranchAdmins }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Drivers</span>
                    <h3 class="stat-value">{{ $totalDrivers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Active Accounts</span>
                    <h3 class="stat-value">{{ $activeUsers }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card-modern">
                <div class="stat-icon-wrapper">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Branches</span>
                    <h3 class="stat-value">{{ $totalBranches }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-semibold"><i class="bi bi-funnel me-2 text-primary"></i>Filter</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="branch_admin" {{ request('role') == 'branch_admin' ? 'selected' : '' }}>Branch
                            Admin</option>
                        <option value="driver" {{ request('role') == 'driver' ? 'selected' : '' }}>Driver</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-funnel me-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.branch-admin.index') }}" class="btn btn-outline-secondary ms-2 px-4">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="bi bi-people-fill me-2 text-primary"></i>
                Branch Admins & Drivers
            </h5>
            <span class="text-muted small">Total: {{ $branchAdmins->total() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branchAdmins as $admin)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                            <i class="bi bi-person-circle text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $admin->name }}</span>
                                            <br>
                                            <small class="text-muted">ID: #{{ $admin->id }}</small>
                                        </div>
                                    </div>
            </div>
            <td class="align-middle">{{ $admin->email }}
        </div>
        <td class="align-middle">
            @if ($admin->role == 'branch_admin')
                <span class="badge bg-primary px-3 py-2">
                    <i class="bi bi-shield-check me-1"></i> Branch Admin
                </span>
            @elseif ($admin->role == 'driver')
                <span class="badge bg-success px-3 py-2">
                    <i class="bi bi-truck me-1"></i> Driver
                </span>
            @endif
    </div>
    <td class="align-middle">
        @if ($admin->branch)
            <span class="fw-semibold">{{ $admin->branch->name }}</span>
            <br>
            <small class="text-muted">{{ $admin->branch->address ?? '' }}</small>
        @else
            @if ($admin->role == 'driver')
                <span class="fw-semibold text-dark">All Branches</span>
            @else
                <span class="text-muted">N/A</span>
            @endif
        @endif
        </div>
    <td class="align-middle">{{ $admin->phone ?? 'N/A' }}</div>
    <td class="align-middle">
        @if ($admin->is_active ?? true)
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
            <button type="button" class="btn btn-outline-warning edit-btn" data-id="{{ $admin->id }}"
                title="Edit">
                <i class="bi bi-pencil"></i>
            </button>
            <button type="button" class="btn btn-outline-danger delete-btn" data-id="{{ $admin->id }}"
                data-name="{{ $admin->name }}" {{ $admin->id == Auth::id() ? 'disabled' : '' }} title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>
        </div>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <p class="mt-3 text-muted mb-3">No records found</p>
                <a href="{{ route('admin.branch-admin.create') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-person-plus me-1"></i> Add First Record
                </a>
                </div>
        </tr>
        @endforelse
        </tbody>
        </table>
        </div>
        </div>

        <!-- Pagination with Previous/Next only -->
        @if ($branchAdmins->lastPage() > 1)
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $branchAdmins->firstItem() }} to {{ $branchAdmins->lastItem() }} of
                        {{ $branchAdmins->total() }} results
                    </div>
                    <div class="pagination-container">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if ($branchAdmins->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $branchAdmins->previousPageUrl() }}"
                                            rel="prev">
                                            Previous
                                        </a>
                                    </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($branchAdmins->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $branchAdmins->nextPageUrl() }}" rel="next">
                                            Next
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
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

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="delete-modal-header">
                        <h5>
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Confirm Delete
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="delete-modal-body">
                        <p>Are you sure you want to delete <strong id="deleteUserName"></strong>?</p>
                        <p class="text-muted small mb-0">This action cannot be undone.</p>
                    </div>
                    <div class="delete-modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn-delete" id="confirmDeleteBtn">
                            <i class="bi bi-trash me-2"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto dismiss success alert after 5 seconds
                const successAlert = document.getElementById('successAlert');
                if (successAlert) {
                    setTimeout(function() {
                        successAlert.style.transition = 'opacity 0.5s ease';
                        successAlert.style.opacity = '0';
                        setTimeout(function() {
                            successAlert.remove();
                        }, 500);
                    }, 5000);
                }

                // Edit button functionality with AJAX form submission
                const editButtons = document.querySelectorAll('.edit-btn');

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const userId = this.getAttribute('data-id');
                        const url = '/admin/branch-admin/' + userId + '/modal-edit';

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
                            .then(response => response.text())
                            .then(html => {
                                modalContent.innerHTML = html;

                                // Attach form submit handler for AJAX update
                                const updateForm = modalContent.querySelector('#editForm');
                                if (updateForm) {
                                    const branchSelect = modalContent.querySelector(
                                        '#branchSelect');
                                    const addressField = modalContent.querySelector(
                                        '#addressField');
                                    const addressHint = modalContent.querySelector('#addressHint');

                                    // Handle address field behavior when "All Branches" is selected
                                    if (branchSelect) {
                                        function handleBranchChange() {
                                            if (branchSelect.value === 'all') {
                                                if (addressField) {
                                                    addressField.value = '';
                                                    addressField.disabled = true;
                                                }
                                                if (addressHint) {
                                                    addressHint.innerHTML =
                                                        '<i class="bi bi-info-circle me-1"></i>Address not required for "All Branches" option.';
                                                    addressHint.style.color = '#6c757d';
                                                }
                                            } else {
                                                if (addressField) {
                                                    addressField.disabled = false;
                                                }
                                                if (addressHint) {
                                                    addressHint.innerHTML = '';
                                                }
                                            }
                                        }

                                        handleBranchChange();
                                        branchSelect.addEventListener('change', handleBranchChange);
                                    }

                                    updateForm.addEventListener('submit', function(e) {
                                        e.preventDefault();

                                        if (branchSelect && branchSelect.value === 'all' &&
                                            addressField) {
                                            addressField.value = '';
                                        }

                                        const formData = new FormData(updateForm);
                                        const submitButton = updateForm.closest(
                                            '.modal-content').querySelector(
                                            'button[type="submit"]');
                                        const originalButtonText = submitButton.innerHTML;

                                        submitButton.disabled = true;
                                        submitButton.innerHTML =
                                            '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...';

                                        fetch(updateForm.action, {
                                                method: 'POST',
                                                headers: {
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                    'Accept': 'application/json'
                                                },
                                                body: formData
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    const modal = updateForm.closest(
                                                        '.modal');
                                                    const bootstrapModal = bootstrap
                                                        .Modal.getInstance(modal);
                                                    bootstrapModal.hide();

                                                    if (typeof window
                                                        .showNotification === 'function'
                                                    ) {
                                                        window.showNotification(data
                                                            .message, 'success');
                                                    } else {
                                                        alert(data.message);
                                                    }

                                                    setTimeout(() => {
                                                        window.location
                                                            .reload();
                                                    }, 1000);
                                                } else {
                                                    submitButton.disabled = false;
                                                    submitButton.innerHTML =
                                                        originalButtonText;
                                                    if (typeof window
                                                        .showNotification === 'function'
                                                    ) {
                                                        window.showNotification(data
                                                            .message ||
                                                            'Update failed. Please try again.',
                                                            'error');
                                                    } else {
                                                        alert(data.message ||
                                                            'Update failed');
                                                    }
                                                }
                                            })
                                            .catch(error => {
                                                submitButton.disabled = false;
                                                submitButton.innerHTML =
                                                    originalButtonText;
                                                console.error('Error:', error);
                                                if (typeof window.showNotification ===
                                                    'function') {
                                                    window.showNotification(
                                                        'An error occurred. Please try again.',
                                                        'error');
                                                } else {
                                                    alert('An error occurred');
                                                }
                                            });
                                    });
                                }
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        `;
                            });

                        const bsModal = new bootstrap.Modal(editModal);
                        bsModal.show();
                    });
                });

                // ========== DELETE BUTTON FUNCTIONALITY ==========
                const deleteButtons = document.querySelectorAll('.delete-btn');
                const deleteModal = document.getElementById('deleteModal');
                const deleteUserNameSpan = document.getElementById('deleteUserName');
                let currentDeleteUrl = '';

                // Simple URL - no route helper needed
                const baseDeleteUrl = '/admin/branch-admin/';

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const userId = this.getAttribute('data-id');
                        const userName = this.getAttribute('data-name');
                        currentDeleteUrl = baseDeleteUrl + userId;
                        deleteUserNameSpan.textContent = userName;

                        console.log('Delete URL:', currentDeleteUrl);

                        const bsModal = new bootstrap.Modal(deleteModal);
                        bsModal.show();
                    });
                });

                const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function(e) {
                        e.preventDefault();

                        if (!currentDeleteUrl) {
                            if (typeof window.showNotification === 'function') {
                                window.showNotification('No item selected for deletion', 'error');
                            }
                            return;
                        }

                        const bsModal = bootstrap.Modal.getInstance(deleteModal);
                        bsModal.hide();

                        if (typeof window.showNotification === 'function') {
                            window.showNotification('Deleting...', 'info');
                        }

                        fetch(currentDeleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('HTTP ' + response.status);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    if (typeof window.showNotification === 'function') {
                                        window.showNotification(data.message || 'Deleted successfully',
                                            'success');
                                    }
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    if (typeof window.showNotification === 'function') {
                                        window.showNotification(data.message || 'Delete failed', 'error');
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (typeof window.showNotification === 'function') {
                                    window.showNotification('Network error. Please try again.', 'error');
                                }
                            });
                    });
                }
            });
        </script>
    @endsection
