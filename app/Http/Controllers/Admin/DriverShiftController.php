<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DriverShift;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DriverShiftController extends Controller
{
    /**
     * Display driver shift management page
     */
    public function index(Request $request)
    {
        // Get the selected date from request, default to today
        $date = $request->get('date', date('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Get all drivers (users with role 'driver')
        $drivers = User::where('role', 'driver')->orderBy('name')->get();

        // Get active shift for the selected date
        $activeShift = DriverShift::whereDate('shift_date', $selectedDate)
            ->where('status', 'active')
            ->with('driver')
            ->first();

        // Get deliveries assigned to drivers on this date
        $deliveriesByDriver = Delivery::whereDate('assigned_at', $selectedDate)
            ->whereNotNull('driver_id')
            ->with('driver')
            ->get()
            ->groupBy('driver_id')
            ->map(function($deliveries) {
                return [
                    'count' => $deliveries->count(),
                    'delivered' => $deliveries->where('status', 'delivered')->count(),
                    'in_progress' => $deliveries->whereIn('status', ['picked_up', 'in_transit'])->count(),
                ];
            });

        // Get ALL shift history - ORDER BY shift_date ASC (oldest first)
        // This shows: May 26, May 27, May 28 in order
        $allHistory = DriverShift::with(['driver', 'assigner'])
            ->orderBy('shift_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.driver-shifts.index', compact(
            'drivers', 'activeShift', 'selectedDate',
            'deliveriesByDriver', 'allHistory'
        ));
    }

    /**
     * Assign a driver for a specific date
     */
    public function assign(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $shiftDate = Carbon::parse($request->shift_date)->startOfDay();
        $today = Carbon::today();
        
        // Check if date is in the past
        if ($shiftDate->lt($today)) {
            return redirect()->back()->with('error', 'Cannot assign driver to past dates.');
        }

        $driver = User::findOrFail($request->driver_id);

        // Verify this is a driver
        if ($driver->role !== 'driver') {
            return redirect()->back()->with('error', 'Only users with driver role can be assigned.');
        }

        // Delete any existing shift for this date (reassign)
        DriverShift::whereDate('shift_date', $shiftDate)->delete();

        // Create the shift
        DriverShift::create([
            'driver_id' => $driver->id,
            'shift_date' => $shiftDate,
            'status' => 'active',
            'start_time' => $request->start_time ?: '09:00',
            'end_time' => $request->end_time ?: '22:00',
            'notes' => $request->notes,
            'assigned_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', "{$driver->name} assigned as driver for " . $shiftDate->format('M d, Y'));
    }

    /**
     * Cancel a driver shift
     */
    public function cancel(DriverShift $shift)
    {
        if ($shift->shift_date->isPast()) {
            return redirect()->back()->with('error', 'Cannot cancel past shifts');
        }

        $driverName = $shift->driver->name;
        $shift->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', "Shift for {$driverName} on {$shift->shift_date->format('M d, Y')} has been cancelled.");
    }

    /**
     * Get the active driver for today (API endpoint)
     */
    public function getActiveDriver()
    {
        $activeShift = DriverShift::whereDate('shift_date', Carbon::today())
            ->where('status', 'active')
            ->with('driver')
            ->first();

        return response()->json([
            'has_driver' => $activeShift ? true : false,
            'driver' => $activeShift ? [
                'id' => $activeShift->driver->id,
                'name' => $activeShift->driver->name,
                'phone' => $activeShift->driver->phone,
                'shift_start' => $activeShift->start_time,
                'shift_end' => $activeShift->end_time,
            ] : null,
        ]);
    }
}