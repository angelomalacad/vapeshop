<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Vape Expo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .page-header {
            background: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #dee2e6;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
            border-radius: 0.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .table th {
            border-top: none;
            color: #6c757d;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .btn-warning {
            color: #fff;
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-warning:hover {
            color: #fff;
            background-color: #ffca2c;
            border-color: #ffc720;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }

        /* Fixed Pagination Styles */
        .pagination {
            margin-bottom: 0;
            gap: 0.25rem;
        }

        .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            color: #0d6efd;
            border-radius: 0.375rem !important;
            margin: 0 0.125rem;
        }

        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            border-radius: 0.375rem !important;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vape Expo Logo" height="30"
                    class="d-inline-block align-text-top me-2">
                Vape Expo - Staff Management
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-light btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Staff Management</h1>
                    <p class="text-muted mb-0">Manage your branch staff and administrators</p>
                </div>
                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>Add New Staff
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Staff Table Card -->
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">
                            <i class="bi bi-people-fill me-2 text-primary"></i>
                            Staff Members
                        </h5>
                    </div>
                    <div class="col-auto">
                        <span class="text-muted small">
                            Total: {{ $staff->total() }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Branch</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $member)
                                <tr>
                                    <td class="fw-semibold">{{ $member->name }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        @if ($member->role == 'branch_admin')
                                            <span class="badge bg-primary">Branch Admin</span>
                                        @else
                                            <span class="badge bg-info text-white">Staff</span>
                                        @endif
                                    </td>
                                    <td>{{ $member->branch->name ?? 'N/A' }}</td>
                                    <td>{{ $member->phone ?? 'N/A' }}</td>
                                    <td>
                                        @if ($member->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="action-buttons justify-content-end">
                                            <a href="{{ route('admin.staff.edit', $member) }}"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                title="Edit Staff">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $member->id }}" title="Delete Staff">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <p class="mb-3">No staff members found</p>
                                        <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">
                                            <i class="bi bi-person-plus me-2"></i>Add Your First Staff Member
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Fixed Pagination Section -->
            @if ($staff->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="pagination-info">
                            Showing {{ $staff->firstItem() }} to {{ $staff->lastItem() }} of {{ $staff->total() }}
                            results
                        </div>
                        <div class="d-flex align-items-center">
                            @if ($staff->onFirstPage())
                                <span class="page-link disabled border-0 bg-transparent text-muted">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </span>
                            @else
                                <a href="{{ $staff->previousPageUrl() }}"
                                    class="page-link border-0 text-decoration-none">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </a>
                            @endif

                            <div class="d-flex gap-1 mx-2">
                                @foreach ($staff->getUrlRange(1, $staff->lastPage()) as $page => $url)
                                    @if ($page == $staff->currentPage())
                                        <span class="page-link active">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                    @endif
                                @endforeach
                            </div>

                            @if ($staff->hasMorePages())
                                <a href="{{ $staff->nextPageUrl() }}" class="page-link border-0 text-decoration-none">
                                    Next <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <span class="page-link disabled border-0 bg-transparent text-muted">
                                    Next <i class="bi bi-chevron-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Shop Information Footer -->
        <div class="mt-4 text-center text-muted small">
            <p class="mb-0">
                <i class="bi bi-shop me-1"></i> Vape Expo - 5 Branches in Calamba |
                <i class="bi bi-telephone me-1 ms-2"></i> 0960 328 0432 |
                <i class="bi bi-clock me-1 ms-2"></i> 9:00 AM - 10:00 PM Daily
            </p>
        </div>
    </div>

    <!-- Delete Confirmation Modals -->
    @foreach ($staff as $member)
        <div class="modal fade" id="deleteModal{{ $member->id }}" tabindex="-1"
            aria-labelledby="deleteModalLabel{{ $member->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $member->id }}">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Confirm Delete
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong>{{ $member->name }}</strong>?</p>
                        <p class="text-muted small mb-0">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
</body>

</html>
