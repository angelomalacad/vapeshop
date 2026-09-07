@extends('layouts.admin')

@section('title', 'Driver Shift Management - Vape Expo')

@section('content')
    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold">
                    <i class="bi bi-calendar-check me-2 text-primary"></i>Driver Shift Management
                </h1>
                <p class="text-muted mb-0">Assign drivers for daily deliveries (any future date)</p>
            </div>
        </div>

        <!-- Date Selector -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end" id="dateForm">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Select Date</label>
                        <input type="date" name="date" class="form-control"
                            value="{{ $selectedDate->format('Y-m-d') }}" id="dateInput">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-calendar"></i> View
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.driver-shifts.index', ['date' => date('Y-m-d')]) }}"
                            class="btn btn-outline-secondary w-100">
                            <i class="bi bi-calendar-today"></i> Today
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.driver-shifts.index', ['date' => date('Y-m-d', strtotime('+1 day'))]) }}"
                            class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-right"></i> Tomorrow
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Current Driver Assignment -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-person-badge me-2 text-primary"></i>
                            Driver for {{ $selectedDate->format('F d, Y') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($activeShift)
                            <div class="text-center mb-3">
                                <div class="bg-success rounded-circle d-inline-flex p-3 mb-2"
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-person-check fs-1 text-white mx-auto"></i>
                                </div>
                                <h3 class="mb-1">{{ $activeShift->driver->name }}</h3>
                                <p class="text-muted">{{ $activeShift->driver->email }}</p>
                                <p><i class="bi bi-telephone"></i> {{ $activeShift->driver->phone ?? 'N/A' }}</p>
                                <div class="alert alert-info">
                                    <i class="bi bi-clock"></i> Shift:
                                    {{ date('h:i A', strtotime($activeShift->start_time)) }} -
                                    {{ date('h:i A', strtotime($activeShift->end_time)) }}
                                </div>
                                @if ($activeShift->notes)
                                    <div class="alert alert-secondary">
                                        <i class="bi bi-note"></i> {{ $activeShift->notes }}
                                    </div>
                                @endif

                                @if (strtotime($selectedDate->format('Y-m-d')) >= strtotime(date('Y-m-d')))
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#changeDriverModal">
                                        <i class="bi bi-arrow-repeat"></i> Change Driver
                                    </button>
                                    
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#cancelShiftModal" data-action="{{ route('admin.driver-shifts.cancel', $activeShift) }}">
                                        <i class="bi bi-x-circle"></i> Cancel Shift
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-person-x display-1 text-muted"></i>
                                <h5 class="mt-3 text-muted">No driver assigned</h5>
                                <p class="text-muted">Select a driver below to assign for this day</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if (!$activeShift && strtotime($selectedDate->format('Y-m-d')) >= strtotime(date('Y-m-d')))
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="bi bi-plus-circle me-2 text-success"></i>
                                Assign Driver for {{ $selectedDate->format('F d, Y') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.driver-shifts.assign') }}" method="POST" id="assignDriverForm">
                                @csrf
                                <input type="hidden" name="shift_date" value="{{ $selectedDate->format('Y-m-d') }}">

                                <div class="mb-3">
                                    <label class="form-label">Select Driver *</label>
                                    <select name="driver_id" class="form-select" required>
                                        <option value="">Choose driver...</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">
                                                {{ $driver->name }} - {{ $driver->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Time</label>
                                        <input type="time" name="start_time" class="form-control" value="09:00">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Time</label>
                                        <input type="time" name="end_time" class="form-control" value="22:00">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes (Optional)</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Special instructions..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id="assignDriverBtn">
                                    <i class="bi bi-check-circle"></i> Assign Driver
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                @if (strtotime($selectedDate->format('Y-m-d')) < strtotime(date('Y-m-d')) && !$activeShift)
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-calendar-x display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Cannot assign driver to past dates</h5>
                            <p class="text-muted">Please select today or a future date to assign a driver.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Deliveries for this date -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-truck me-2 text-primary"></i>
                            Deliveries on {{ $selectedDate->format('F d, Y') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Driver</th>
                                        <th>Deliveries</th>
                                        <th>Delivered</th>
                                        <th>In Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deliveriesByDriver as $driverId => $stats)
                                        @php
                                            $driver = $drivers->firstWhere('id', $driverId);
                                        @endphp
                                        <tr>
                                            <td>{{ $driver->name ?? 'Unknown' }}</td>
                                            <td>{{ $stats['count'] }}</td>
                                            <td><span class="badge bg-success">{{ $stats['delivered'] }}</span></td>
                                            <td><span class="badge bg-warning">{{ $stats['in_progress'] }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No deliveries on this date
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shift History -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2 text-secondary"></i>
                    Driver Assignment History
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Driver</th>
                                <th>Shift Hours</th>
                                <th>Status</th>
                                <th>Assigned By</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allHistory->sortByDesc(function($shift) {
                                return strtotime($shift->shift_date);
                            }) as $shift)
                                <tr>
                                    <td>
                                        {{ date('M d, Y', strtotime($shift->shift_date)) }}
                                        @if (date('Y-m-d', strtotime($shift->shift_date)) == date('Y-m-d'))
                                            <span class="badge bg-primary ms-1">Today</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $shift->driver->name }}</strong>
                                        <br><small class="text-muted">{{ $shift->driver->email }}</small>
                                    </td>
                                    <td>{{ date('h:i A', strtotime($shift->start_time)) }} -
                                        {{ date('h:i A', strtotime($shift->end_time)) }}
                                    </td>
                                    <td>
                                        @if ($shift->status == 'active' && strtotime($shift->shift_date) > strtotime(date('Y-m-d')))
                                            <span class="badge bg-success">Scheduled</span>
                                        @elseif($shift->status == 'active' && date('Y-m-d', strtotime($shift->shift_date)) == date('Y-m-d'))
                                            <span class="badge bg-info">Active Today</span>
                                        @elseif($shift->status == 'completed')
                                            <span class="badge bg-secondary">Completed</span>
                                        @elseif($shift->status == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($shift->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $shift->assigner->name ?? 'System' }}</td>
                                    <td>{{ Str::limit($shift->notes, 40) ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No driver assignments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-center">
                    {{ $allHistory->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Change Driver Modal -->
    <div class="modal fade" id="changeDriverModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-arrow-repeat"></i> Change Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.driver-shifts.assign') }}" method="POST" id="changeDriverForm">
                    @csrf
                    <input type="hidden" name="shift_date" value="{{ $selectedDate->format('Y-m-d') }}">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Changing the driver will replace the current assignment for
                            {{ $selectedDate->format('F d, Y') }}.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select New Driver *</label>
                            <select name="driver_id" class="form-select" required>
                                <option value="">Choose driver...</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ $activeShift && $activeShift->driver_id == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }} - {{ $driver->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning" id="changeDriverBtn">Change Driver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Cancel Shift Modal -->
    <div class="modal fade" id="cancelShiftModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cancel Shift</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this shift?</p>
                    <p class="text-muted small mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> No, Cancel
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmCancelShiftBtn">
                        <i class="bi bi-check-circle"></i> Yes, Cancel Shift
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('dateInput').addEventListener('change', function() {
            document.getElementById('dateForm').submit();
        });

        document.addEventListener('DOMContentLoaded', function() {
            
            function handleButtonLoading(btn, isLoading, text) {
                if (!btn) return;
                if (isLoading) {
                    btn.dataset.originalHtml = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + (text || 'Processing...');
                } else {
                    btn.disabled = false;
                    if (btn.dataset.originalHtml) btn.innerHTML = btn.dataset.originalHtml;
                }
            }

            // 1. ASSIGN DRIVER FORM
            const assignForm = document.getElementById('assignDriverForm');
            if (assignForm) {
                assignForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('assignDriverBtn');
                    handleButtonLoading(btn, true, 'Assigning...');
                    const formData = new FormData(this);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message || 'Driver assigned successfully!', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message || 'Failed to assign driver.', 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('An error occurred while assigning driver.', 'error');
                    })
                    .finally(() => {
                        handleButtonLoading(btn, false);
                    });
                });
            }

            // 2. CHANGE DRIVER FORM
            const changeForm = document.getElementById('changeDriverForm');
            if (changeForm) {
                changeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = document.getElementById('changeDriverBtn');
                    handleButtonLoading(btn, true, 'Changing...');
                    const formData = new FormData(this);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message || 'Driver changed successfully!', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message || 'Failed to change driver.', 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('An error occurred while changing driver.', 'error');
                    })
                    .finally(() => {
                        handleButtonLoading(btn, false);
                    });
                });
            }

            // 3. CANCEL SHIFT - REMOVED _method: 'DELETE' TO FIX 405!
            document.addEventListener('click', function(e) {
                if (e.target.closest('#confirmCancelShiftBtn')) {
                    
                    const cancelBtn = document.querySelector('button[data-bs-target="#cancelShiftModal"]');
                    const formAction = cancelBtn ? cancelBtn.getAttribute('data-action') : null;

                    if (!formAction) {
                        showNotification('Shift action not found!', 'error');
                        return;
                    }

                    const btn = document.getElementById('confirmCancelShiftBtn');
                    handleButtonLoading(btn, true, 'Cancelling...');

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Send pure POST, no _method
                    fetch(formAction, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            _token: csrfToken
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const modalEl = document.getElementById('cancelShiftModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();

                        if (data.success) {
                            showNotification(data.message || 'Shift cancelled successfully!', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showNotification(data.message || 'Failed to cancel shift.', 'error');
                        }
                    })
                    .catch(error => {
                        const modalEl = document.getElementById('cancelShiftModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                        
                        showNotification('An error occurred while cancelling shift.', 'error');
                    })
                    .finally(() => {
                        handleButtonLoading(btn, false);
                    });
                }
            });

            // 4. Check for session flash messages
            @if (session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif

            @if (session('warning'))
                showNotification('{{ session('warning') }}', 'warning');
            @endif

            @if (session('info'))
                showNotification('{{ session('info') }}', 'info');
            @endif
        });
    </script>
@endsection