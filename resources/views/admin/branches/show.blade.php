@extends('layouts.admin')

@section('title', $branch->name . ' - Vape Expo')

@section('content')
<div class="container-fluid px-4">
    <!-- Header with Dashboard Button -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="45" class="me-3">
            <div>
                <h1 class="h3 mb-1 fw-bold">{{ $branch->name }}</h1>
                <p class="text-muted mb-0">
                    <i class="bi bi-shop me-1"></i> Branch Details
                </p>
            </div>
        </div>
        <div class="mt-2 mt-md-0 d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary rounded-pill px-3">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-warning rounded-pill px-3">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
            <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Branch Information -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2 text-primary"></i>Branch Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 150px;">Branch Code:</td>
                            <td class="fw-semibold"><span class="badge bg-secondary">{{ $branch->code }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Branch Name:</td>
                            <td class="fw-semibold">{{ $branch->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Manager:</td>
                            <td>{{ $branch->manager_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status:</td>
                            <td>
                                @if($branch->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Opening Date:</td>
                            <td>{{ $branch->opening_date ? \Carbon\Carbon::parse($branch->opening_date)->format('F d, Y') : 'Not set' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-telephone me-2 text-primary"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 150px;">Phone:</td>
                            <td class="fw-semibold">{{ $branch->phone }}</td>
                        </tr>
                        @if($branch->contact_number)
                        <tr>
                            <td class="text-muted">Alternative:</td>
                            <td>{{ $branch->contact_number }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td>{{ $branch->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Address:</td>
                            <td>{{ $branch->address }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Section -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-people me-2 text-primary"></i>Branch Staff ({{ $branch->users->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    @if($branch->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Name</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th class="pe-4">Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($branch->users as $staff)
                                    <tr>
                                        <td class="ps-4">{{ $staff->name }}</td>
                                        <td>
                                            @if($staff->role == 'branch_admin')
                                                <span class="badge bg-primary">Branch Admin</span>
                                            @else
                                                <span class="badge bg-info">Staff</span>
                                            @endif
                                        </td>
                                        <td>{{ $staff->email }}</td>
                                        <td class="pe-4">{{ $staff->phone ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No staff assigned to this branch yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-info bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Inventory Items</h6>
                            <h2 class="mb-0 fw-bold">{{ $inventoryCount }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm bg-success bg-gradient text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Staff Members</h6>
                            <h2 class="mb-0 fw-bold">{{ $branch->users->count() }}</h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection