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
    // Get all deliveries with relationships
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

    // Filter by status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter by date range
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $deliveries = $query->paginate(20);

    // Get all drivers
    $drivers = User::where('role', 'driver')->orderBy('name')->get();
    
    // Get all branches
    $branches = Branch::where('is_active', true)->get();

    // Get active deliveries for today
    $activeToday = Delivery::with(['order', 'order.branch', 'driver'])
        ->whereIn('status', ['assigned', 'picked_up', 'in_transit'])
        ->whereDate('assigned_at', Carbon::today())
        ->orderByRaw("FIELD(status, 'assigned', 'picked_up', 'in_transit')")
        ->get();

    // Get today's driver name
    $todayDriver = \App\Models\DriverShift::where('shift_date', Carbon::today())
        ->where('status', 'active')
        ->with('driver')
        ->first();
    $todayDriverName = $todayDriver ? $todayDriver->driver->name : 'Not assigned';

    // Calculate statistics
    $stats = [
        'total' => Delivery::count(),
        'pending' => Delivery::where('status', 'pending')->count(),
        'assigned' => Delivery::where('status', 'assigned')->count(),
        'picked_up' => Delivery::where('status', 'picked_up')->count(),
        'in_transit' => Delivery::where('status', 'in_transit')->count(),
        'delivered' => Delivery::where('status', 'delivered')->count(),
        'failed' => Delivery::where('status', 'failed')->count(),
        'today' => Delivery::whereDate('created_at', Carbon::today())->count(),
        'active_today' => $activeToday->count(),
    ];

    // Calculate total revenue from delivered orders
    $totalRevenue = Delivery::where('status', 'delivered')
        ->whereHas('order', function($q) {
            $q->where('payment_status', 'paid');
        })
        ->with('order')
        ->get()
        ->sum(function($delivery) {
            return $delivery->order->total_amount ?? 0;
        });

    return view('admin.deliveries.index', compact('deliveries', 'drivers', 'branches', 'stats', 'totalRevenue', 'activeToday', 'todayDriverName'));
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
            'driver_id' => 'required|exists:users,id',
        ]);

        $driver = User::findOrFail($request->driver_id);
        
        $delivery->update([
            'driver_id' => $driver->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        // Update order status if needed
        if ($delivery->order && $delivery->order->order_status == 'ready') {
            $delivery->order->update(['order_status' => 'out_for_delivery']);
        }

        return redirect()->back()->with('success', "Driver {$driver->name} assigned to delivery #{$delivery->tracking_number}");
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