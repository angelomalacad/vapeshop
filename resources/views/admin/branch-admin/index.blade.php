@extends('layouts.admin')

@section('title', 'Branch Admin Management - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Branch Admin Management</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-people-fill me-1"></i> Manage branch administrators and staff
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.branch-admin.create') }}" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-person-plus me-1"></i> Add New
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        @php
            $totalBranchAdmins = \App\Models\User::where('role', 'branch_admin')->count();
            $totalStaff = \App\Models\User::where('role', 'staff')->count();
            $activeUsers = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->where('is_active', true)->count();
            $totalBranches = \App\Models\Branch::count();
        @endphp
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Branch Admins</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalBranchAdmins }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-shield-check fs-4"></i>
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
                            <h6 class="text-white-50 mb-1">Staff Members</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalStaff }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-person-badge fs-4"></i>
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
                            <h6 class="text-white-50 mb-1">Active Accounts</h6>
                            <h2 class="mb-0 fw-bold">{{ $activeUsers }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Branches</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalBranches }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-shop fs-4"></i>
                        </div>
                    </div>
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
                        <option value="branch_admin" {{ request('role') == 'branch_admin' ? 'selected' : '' }}>Branch Admin</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
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
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                Branch Personnel
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
                                </td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if ($admin->role == 'branch_admin')
                                        <span class="badge bg-primary px-3 py-2">
                                            <i class="bi bi-shield-check me-1"></i> Branch Admin
                                        </span>
                                    @else
                                        <span class="badge bg-info text-white px-3 py-2">
                                            <i class="bi bi-person-badge me-1"></i> Staff
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->branch)
                                        <span class="fw-semibold">{{ $admin->branch->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $admin->branch->address ?? '' }}</small>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>{{ $admin->phone ?? 'N/A' }}</td>
                                <td>
                                    @if ($admin->is_active ?? true)
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
                                        <button type="button" class="btn btn-outline-warning edit-btn" 
                                                data-id="{{ $admin->id }}"
                                                title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $admin->id }}" 
                                                title="Delete"
                                                {{ $admin->id == Auth::id() ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-people display-1 text-muted"></i>
                                    <p class="mt-3 text-muted mb-3">No records found</p>
                                    <a href="{{ route('admin.branch-admin.create') }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-person-plus me-1"></i> Add First Record
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($branchAdmins->hasPages())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-center">
                    {{ $branchAdmins->withQueryString()->links() }}
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
@foreach ($branchAdmins as $admin)
    <div class="modal fade" id="deleteModal{{ $admin->id }}" tabindex="-1">
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
                    <p>Are you sure you want to delete <strong>{{ $admin->name }}</strong>?</p>
                    <p class="text-muted small mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.branch-admin.destroy', $admin) }}" method="POST" class="d-inline">
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
    // Simple modal loader
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        
        // Add click handlers to edit buttons
        const editButtons = document.querySelectorAll('.edit-btn');
        console.log('Found ' + editButtons.length + ' edit buttons');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                const url = '/admin/branch-admin/' + userId + '/modal-edit';
                console.log('Opening modal for user:', userId);
                
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
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
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
    });
</script>
@endsection