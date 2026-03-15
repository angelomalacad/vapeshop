@extends('layouts.admin')

@section('title', 'Branches Management - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">Branches Management</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-shop me-1"></i> Manage all Vape Expo branches
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.branches.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-1"></i> Add New Branch
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        @php
            $totalBranches = \App\Models\Branch::count();
            $activeBranches = \App\Models\Branch::where('is_active', true)->count();
            $totalStaff = \App\Models\User::whereIn('role', ['branch_admin', 'staff'])->count();
        @endphp
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Branches</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalBranches }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-shop fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Active Branches</h6>
                            <h2 class="mb-0 fw-bold">{{ $activeBranches }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Staff</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalStaff }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Branches Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Code</th>
                            <th>Branch Name</th>
                            <th>Manager</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                        <tr>
                            <td class="ps-4"><span class="badge bg-secondary">{{ $branch->code }}</span></td>
                            <td>
                                <span class="fw-semibold">{{ $branch->name }}</span>
                                @if($branch->opening_date)
                                    <br><small class="text-muted">Opened: {{ \Carbon\Carbon::parse($branch->opening_date)->format('M d, Y') }}</small>
                                @endif
                            </td>
                            <td>{{ $branch->manager_name }}</td>
                            <td>
                                {{ $branch->phone }}<br>
                                @if($branch->contact_number)
                                    <small class="text-muted">{{ $branch->contact_number }}</small>
                                @endif
                            </td>
                            <td>{{ $branch->email }}</td>
                            <td>
                                <small>{{ Str::limit($branch->address, 30) }}</small>
                            </td>
                            <td>
                                @if($branch->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.branches.show', $branch) }}" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.branches.toggle-status', $branch) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-{{ $branch->is_active ? 'danger' : 'success' }}" 
                                                title="{{ $branch->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $branch->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-danger" title="Delete"
                                            onclick="confirmDelete({{ $branch->id }}, '{{ $branch->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $branch->id }}" action="{{ route('admin.branches.destroy', $branch) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-shop display-1 text-muted"></i>
                                <p class="mt-3 text-muted">No branches found</p>
                                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-plus-circle me-1"></i> Add Your First Branch
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(branchId, branchName) {
        if (confirm(`Are you sure you want to delete "${branchName}"? This action cannot be undone.`)) {
            document.getElementById(`delete-form-${branchId}`).submit();
        }
    }
</script>
@endpush