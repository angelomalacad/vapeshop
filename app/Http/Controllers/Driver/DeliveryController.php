<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    /**
     * Display all deliveries assigned to this driver (across all branches)
     */
    public function index(Request $request)
    {
        // Get all deliveries assigned to THIS driver - NO branch filter
        $query = Delivery::where('driver_id', Auth::id())
            ->with(['order', 'order.branch'])
            ->orderBy('created_at', 'desc');

        // Optional filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deliveries = $query->paginate(20);

        // Calculate counts for stats
        $stats = [
            'total' => Delivery::where('driver_id', Auth::id())->count(),
            'pending' => Delivery::where('driver_id', Auth::id())->where('status', 'pending')->count(),
            'assigned' => Delivery::where('driver_id', Auth::id())->where('status', 'assigned')->count(),
            'picked_up' => Delivery::where('driver_id', Auth::id())->where('status', 'picked_up')->count(),
            'in_transit' => Delivery::where('driver_id', Auth::id())->where('status', 'in_transit')->count(),
            'delivered' => Delivery::where('driver_id', Auth::id())->where('status', 'delivered')->count(),
            'failed' => Delivery::where('driver_id', Auth::id())->where('status', 'failed')->count(),
        ];

        return view('driver.deliveries.index', compact('deliveries', 'stats'));
    }

    /**
     * Show a specific delivery
     */
    public function show(Delivery $delivery)
    {
        // Verify this delivery belongs to the logged-in driver
        if ($delivery->driver_id !== Auth::id()) {
            abort(403, 'This delivery is not assigned to you.');
        }

        $delivery->load(['order', 'order.items.product', 'order.branch']);
        return view('driver.deliveries.show', compact('delivery'));
    }

    /**
     * Update delivery status
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        // Verify this delivery belongs to the logged-in driver
        if ($delivery->driver_id !== Auth::id()) {
            abort(403, 'This delivery is not assigned to you.');
        }

        $request->validate([
            'status' => 'required|in:picked_up,in_transit,delivered,failed',
            'notes' => 'nullable|string|max:500',
            'delivery_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'driver_latitude' => 'nullable|numeric',
            'driver_longitude' => 'nullable|numeric',
        ]);

        $oldStatus = $delivery->status;
        $newStatus = $request->status;

        // Handle status change timestamps
        if ($newStatus == 'picked_up' && !$delivery->picked_up_at) {
            $delivery->picked_up_at = now();
        }
        if ($newStatus == 'in_transit') {
            // No specific timestamp needed
        }
        if ($newStatus == 'delivered' && !$delivery->delivered_at) {
            $delivery->delivered_at = now();
        }

        // Handle proof of delivery images
        if ($request->hasFile('delivery_proof')) {
            if ($delivery->delivery_proof) {
                Storage::disk('public')->delete($delivery->delivery_proof);
            }
            $delivery->delivery_proof = $request->file('delivery_proof')->store('delivery-proofs', 'public');
        }

        if ($request->hasFile('payment_proof')) {
            if ($delivery->payment_proof) {
                Storage::disk('public')->delete($delivery->payment_proof);
            }
            $delivery->payment_proof = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        // Update location if provided
        if ($request->filled('driver_latitude') && $request->filled('driver_longitude')) {
            $delivery->driver_latitude = $request->driver_latitude;
            $delivery->driver_longitude = $request->driver_longitude;
            $delivery->last_location_update = now();
        }

        // Update notes
        if ($request->filled('notes')) {
            $delivery->driver_notes = $request->notes;
        }

        $delivery->status = $newStatus;
        $delivery->save();

        // Update order status based on delivery status
        if ($delivery->order) {
            $order = $delivery->order;
            if ($newStatus == 'picked_up') {
                $order->order_status = 'out_for_delivery';
            } elseif ($newStatus == 'delivered') {
                $order->order_status = 'delivered';
            } elseif ($newStatus == 'failed') {
                $order->order_status = 'delivery_failed';
            }
            $order->save();
        }

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    /**
     * Update driver's live location
     */
    public function updateLocation(Request $request, Delivery $delivery)
    {
        if ($delivery->driver_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $delivery->driver_latitude = $request->latitude;
        $delivery->driver_longitude = $request->longitude;
        $delivery->last_location_update = now();
        $delivery->save();

        return response()->json(['success' => true]);
    }

    /**
     * Driver dashboard with all stats
     */
    public function dashboard()
    {
        $driverId = Auth::id();

        // Get all deliveries assigned to this driver (across all branches)
        $totalDeliveries = Delivery::where('driver_id', $driverId)->count();
        $inTransitCount = Delivery::where('driver_id', $driverId)
            ->whereIn('status', ['picked_up', 'in_transit'])
            ->count();
        $deliveredCount = Delivery::where('driver_id', $driverId)
            ->where('status', 'delivered')
            ->count();
        $pendingDeliveries = Delivery::where('driver_id', $driverId)
            ->whereIn('status', ['pending', 'assigned'])
            ->count();

        // Get online orders for driver's branch (for order management)
        $branchId = Auth::user()->branch_id;
        $pendingOrders = Order::where('branch_id', $branchId)
            ->whereIn('order_status', ['pending', 'confirmed', 'processing'])
            ->where('order_number', 'NOT LIKE', 'POS-%')
            ->count();

        $recentDeliveries = Delivery::where('driver_id', $driverId)
            ->with(['order', 'order.branch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('driver.dashboard', compact(
            'totalDeliveries', 'inTransitCount', 'deliveredCount',
            'pendingDeliveries', 'pendingOrders', 'recentDeliveries'
        ));
    }
}