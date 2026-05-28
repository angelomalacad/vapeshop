<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeliveryController extends Controller
{
    /**
     * Display all deliveries assigned to this driver (across all branches)
     * Separated into Active and Completed sections
     */
    public function index(Request $request)
    {
        $driverId = Auth::id();

        // Active deliveries (not yet delivered or failed)
        $activeDeliveries = Delivery::where('driver_id', $driverId)
            ->whereNotIn('status', ['delivered', 'failed'])
            ->with(['order', 'order.branch'])
            ->orderByRaw("FIELD(status, 'assigned', 'picked_up', 'in_transit')")
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Ensure order relationship is loaded for active deliveries
        $activeDeliveries->load('order');

        // Completed deliveries (delivered or failed) - with pagination
        $completedDeliveries = Delivery::where('driver_id', $driverId)
            ->whereIn('status', ['delivered', 'failed'])
            ->with(['order', 'order.branch'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        // Ensure order relationship is loaded for completed deliveries
        $completedDeliveries->load('order');

        // Calculate total deliveries count
        $totalDeliveries = Delivery::where('driver_id', $driverId)->count();

        // Calculate counts for stats
        $stats = [
            'total' => $totalDeliveries,
            'pending' => Delivery::where('driver_id', $driverId)->where('status', 'pending')->count(),
            'assigned' => Delivery::where('driver_id', $driverId)->where('status', 'assigned')->count(),
            'picked_up' => Delivery::where('driver_id', $driverId)->where('status', 'picked_up')->count(),
            'in_transit' => Delivery::where('driver_id', $driverId)->where('status', 'in_transit')->count(),
            'delivered' => Delivery::where('driver_id', $driverId)->where('status', 'delivered')->count(),
            'failed' => Delivery::where('driver_id', $driverId)->where('status', 'failed')->count(),
        ];

        return view('driver.deliveries.index', compact('activeDeliveries', 'completedDeliveries', 'totalDeliveries', 'stats'));
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
 * Update delivery status (supports both AJAX and normal requests)
 */
public function updateStatus(Request $request, Delivery $delivery)
{
    // Verify this delivery belongs to the logged-in driver
    if ($delivery->driver_id !== Auth::id()) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.']);
        }
        abort(403, 'This delivery is not assigned to you.');
    }

    $request->validate([
        'status' => 'required|in:picked_up,in_transit,delivered,failed',
        'notes' => 'nullable|string|max:500',
        'delivery_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    try {
        $oldStatus = $delivery->status;
        $newStatus = $request->status;

        // ========== VALIDATION FOR DELIVERED STATUS ==========
        if ($newStatus == 'delivered') {
            $hasDeliveryProof = $delivery->delivery_proof || $request->hasFile('delivery_proof');
            $hasPaymentProof = $delivery->payment_proof || $request->hasFile('payment_proof');
            
            if (!$hasDeliveryProof) {
                $errorMessage = 'Delivery proof is required when marking as delivered.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $errorMessage]);
                }
                return redirect()->back()->with('error', $errorMessage);
            }
            
            if (!$hasPaymentProof) {
                $errorMessage = 'Payment proof is required when marking as delivered.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $errorMessage]);
                }
                return redirect()->back()->with('error', $errorMessage);
            }
        }

        // Cannot update status if already delivered or failed
        if (in_array($delivery->status, ['delivered', 'failed'])) {
            $errorMessage = 'Cannot update status of a completed delivery.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMessage]);
            }
            return redirect()->back()->with('error', $errorMessage);
        }

        // Handle status change timestamps
        if ($newStatus == 'picked_up' && !$delivery->picked_up_at) {
            $delivery->picked_up_at = now();
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

        $message = 'Delivery status updated successfully!';
        
        // For AJAX requests, return JSON
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        // For normal form submissions, redirect back with success message
        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        return redirect()->back()->with('error', 'Error updating delivery status: ' . $e->getMessage());
    }
}

    /**
     * Driver dashboard with all stats
     */
    public function dashboard()
    {
        $driverId = Auth::id();

        // Get today's active shift for this driver
        $todayShift = \App\Models\DriverShift::where('shift_date', today())
            ->where('status', 'active')
            ->where('driver_id', $driverId)
            ->first();

        // Get all deliveries assigned to this driver
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

        // Get pending online orders (not yet started) - matches online orders index
        $pendingOrdersCount = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->whereIn('order_status', ['pending', 'confirmed', 'processing'])
            ->count();
        
        // Get ready orders count (needs start delivery)
        $readyOrdersCount = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->where('order_status', 'ready')
            ->count();
        
        // Get out for delivery count
        $outForDeliveryCount = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->where('order_status', 'out_for_delivery')
            ->count();

        $recentDeliveries = Delivery::where('driver_id', $driverId)
            ->with(['order', 'order.branch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('driver.dashboard', compact(
            'totalDeliveries', 'inTransitCount', 'deliveredCount',
            'pendingDeliveries', 'pendingOrdersCount', 'readyOrdersCount', 
            'outForDeliveryCount', 'recentDeliveries', 'todayShift'
        ));
    }
}