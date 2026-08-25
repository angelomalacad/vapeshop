<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    /**
     * Display all deliveries across all branches
     */
    public function index(Request $request)
    {
        // ================================================================
        // 1. BASE QUERY
        // ================================================================
        $query = Delivery::with(['order', 'order.branch', 'driver'])
            ->orderBy('created_at', 'desc');

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }

        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('order', function($sub) use ($search) {
                    $sub->where('order_number', 'LIKE', "%{$search}%");
                })->orWhere('tracking_number', 'LIKE', "%{$search}%");
            });
        }

        // Filter by Delivery Type
        if ($request->filled('delivery_type')) {
            $type = $request->delivery_type;
            if ($type === 'lalamove') {
                $query->whereHas('order', function($q) {
                    $q->whereNotNull('city')
                      ->where('city', '!=', 'Calamba')
                      ->where('city', '!=', 'calamba')
                      ->where('city', '!=', 'Calamba City')
                      ->where('city', '!=', 'calamba city');
                });
            } elseif ($type === 'staff') {
                $query->whereHas('order', function($q) {
                    $q->where(function($sub) {
                        $sub->where('city', 'Calamba')
                            ->orWhere('city', 'calamba')
                            ->orWhere('city', 'Calamba City')
                            ->orWhere('city', 'calamba city');
                    });
                });
            }
        }

        // Filter by Active/Completed Section
        $filterSection = $request->input('filter_section', 'all');
        if ($filterSection === 'active') {
            // Active means NOT delivered/cancelled
            $query->whereHas('order', function($q) {
                $q->whereNotIn('order_status', ['delivered', 'cancelled']);
            });
        } elseif ($filterSection === 'completed') {
            // Completed means either delivered OR out_for_delivery (for Lalamove status)
            $query->whereHas('order', function($q) {
                $q->whereIn('order_status', ['delivered', 'out_for_delivery']);
            });
        }

        // ================================================================
        // 2. PAGINATE THE DATA
        // ================================================================
        $deliveries = $query->paginate(20)->appends($request->query());

        // ================================================================
        // 3. GET ACTIVE TODAY LIST
        // ================================================================
        $activeToday = Delivery::with(['order', 'order.branch', 'driver'])
            ->whereHas('order', function($q) {
                $q->whereNotIn('order_status', ['delivered', 'cancelled']);
            })
            ->orderByRaw("FIELD(status, 'assigned', 'picked_up', 'in_transit')")
            ->get();

        // ================================================================
        // 4. CALCULATE STATS (FIXED: now matches your actual database statuses)
        // ================================================================
        $stats = [
            'total'           => Delivery::count(),
            'pending'         => Delivery::whereHas('order', fn($q) => $q->where('order_status', 'pending'))->count(),
            'lalamove_pending'=> Delivery::whereHas('order', fn($q) => $q->where('order_status', 'lalamove_pending'))->count(),
            'assigned'        => Delivery::where('status', 'assigned')->count(),
            'picked_up'       => Delivery::where('status', 'picked_up')->count(),
            'in_transit'      => Delivery::where('status', 'in_transit')->count(),
            // 🔥 FIX: Count 'out_for_delivery' AND 'delivered' as "Completed"
            'delivered'       => Delivery::whereHas('order', fn($q) => $q->whereIn('order_status', ['delivered', 'out_for_delivery']))->count(),
            'failed'          => Delivery::whereHas('order', fn($q) => $q->where('order_status', 'delivery_failed'))->count(),
            'today'           => Delivery::whereDate('created_at', Carbon::today())->count(),
            'active_today'    => Delivery::whereHas('order', fn($q) => $q->whereNotIn('order_status', ['delivered', 'cancelled']))->count(),
        ];

        // ================================================================
        // 5. SIDEBAR INFO
        // ================================================================
        $drivers = User::where('role', 'driver')->orderBy('name')->get();
        $branches = Branch::where('is_active', true)->get();

        $todayDriver = \App\Models\DriverShift::where('shift_date', Carbon::today())
            ->where('status', 'active')
            ->with('driver')
            ->first();
        $todayDriverName = $todayDriver ? $todayDriver->driver->name : 'Not assigned';

        // ================================================================
        // 6. RETURN
        // ================================================================
        return view('admin.deliveries.index', compact('deliveries', 'drivers', 'branches', 'stats', 'activeToday', 'todayDriverName'));
    }

    /**
     * Show delivery details modal
     */
    public function showModal(Delivery $delivery)
    {
        $delivery->load(['order', 'order.items.product', 'order.branch', 'driver']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show delivery details page
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['order', 'order.items.product', 'order.branch', 'driver']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * View proof image
     */
    public function viewProof(Delivery $delivery, $type)
    {
        if ($type == 'delivery') {
            $url = $delivery->delivery_proof ? Storage::url($delivery->delivery_proof) : null;
            $title = 'Delivery Proof';
        } elseif ($type == 'payment') {
            $url = $delivery->payment_proof ? Storage::url($delivery->payment_proof) : null;
            $title = 'Payment Proof';
        } else {
            abort(404);
        }

        if (!$url) {
            return redirect()->back()->with('error', 'Proof not found.');
        }

        return view('admin.deliveries.view-proof', compact('url', 'title', 'delivery'));
    }

        /**
     * Assign a driver to a delivery
     */
        public function assignDriver(Request $request, Delivery $delivery)
    {
        $request->validate([
            'driver_id' => 'nullable|exists:users,id',
            'lalamove_driver_name' => 'nullable|string|max:255',
        ]);

        if ($request->filled('driver_id')) {
            $driver = User::findOrFail($request->driver_id);
            $delivery->update([
                'driver_id' => $driver->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);
            $driverName = $driver->name;
        } else {
            // ✅ Lalamove: Store name in notes
            $driverName = $request->lalamove_driver_name;
            $delivery->update([
                'driver_id' => null,
                'notes' => $driverName,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);
        }

        if ($delivery->order && $delivery->order->order_status == 'ready') {
            $delivery->order->update(['order_status' => 'out_for_delivery']);
        }

        return redirect()->back()->with('success', "Driver {$driverName} assigned to delivery #{$delivery->tracking_number}");
    }

    /**
     * Export deliveries report
     */
    public function export(Request $request)
    {
        $query = Delivery::with(['order', 'order.branch', 'driver']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $deliveries = $query->get();

        $filename = 'deliveries_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';

        $handle = fopen('php://temp', 'w+');

        // Add headers
        fputcsv($handle, [
            'Tracking #', 'Order #', 'Branch', 'Driver', 'Customer',
            'Address', 'Status', 'Assigned At', 'Picked Up',
            'Delivered At', 'Delivery Proof', 'Payment Proof'
        ]);

        foreach ($deliveries as $delivery) {
            fputcsv($handle, [
                $delivery->tracking_number,
                $delivery->order->order_number ?? 'N/A',
                $delivery->order->branch->name ?? 'N/A',
                $delivery->driver->name ?? 'Unassigned',
                $delivery->recipient_name,
                $delivery->delivery_address,
                $delivery->status,
                $delivery->assigned_at?->format('Y-m-d H:i'),
                $delivery->picked_up_at?->format('Y-m-d H:i'),
                $delivery->delivered_at?->format('Y-m-d H:i'),
                $delivery->delivery_proof ? 'Yes' : 'No',
                $delivery->payment_proof ? 'Yes' : 'No',
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
