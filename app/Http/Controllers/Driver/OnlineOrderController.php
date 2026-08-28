<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DriverShift;
use App\Models\BranchInventory;
use App\Models\Delivery;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OnlineOrderController extends Controller
{
    /**
     * Display online orders for the driver
     * Shows: ready, out_for_delivery, delivered, delivery_failed
     */
    public function index(Request $request)
    {
        $todayShift = DriverShift::where('shift_date', today())
            ->where('status', 'active')
            ->where('driver_id', Auth::id())
            ->first();

        if (!$todayShift) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'You are not assigned for today. Please contact the owner.');
        }

        // Build the base query - Driver sees: ready, out_for_delivery, delivered, delivery_failed
        $orders = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->whereIn('order_status', ['ready', 'out_for_delivery', 'delivered', 'delivery_failed']);

        // ✅ Status filter
        if ($request->filled('status')) {
            $orders->where('order_status', $request->status);
        }

        // ✅ Date From filter
        if ($request->filled('date_from')) {
            $orders->whereDate('created_at', '>=', $request->date_from);
        }

        // ✅ Date To filter
        if ($request->filled('date_to')) {
            $orders->whereDate('created_at', '<=', $request->date_to);
        }

        // ✅ Load the relationships needed for the table
        $orders = $orders->with([
                'items.product',
                'items.inventory.branch',
                'delivery'
            ])
            ->orderByRaw("FIELD(order_status, 'ready', 'out_for_delivery', 'delivered', 'delivery_failed')")
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Add custom attribute for Staff vs Lalamove
        $orders->getCollection()->transform(function ($order) {
            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = ($cityLower === 'calamba city' || $cityLower === 'calamba');
            $order->is_lalamove = !$isCalambaCity;
            return $order;
        });

        // Counts for the driver dashboard
        $counts = [
            'ready' => Order::where('order_status', 'ready')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'out_for_delivery' => Order::where('order_status', 'out_for_delivery')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'delivered' => Order::where('order_status', 'delivered')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'delivery_failed' => Order::where('order_status', 'delivery_failed')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
        ];

        return view('driver.online-orders.index', compact('orders', 'counts'));
    }

    /**
     * Show a specific online order
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'branch', 'delivery']);
        return view('driver.online-orders.show', compact('order'));
    }

    /**
     * Driver starts delivery - order becomes out_for_delivery
     * Also creates/updates delivery with picked_up status
     */
    public function startDelivery(Order $order)
{
    if ($order->order_status != 'ready') {
        return response()->json([
            'success' => false,
            'message' => 'Order must be ready first. Current status: ' . $order->order_status
        ]);
    }

    if ($order->delivery_type == 'delivery') {
        $driverId = Auth::id();

        // Check if it's Lalamove
        $cityLower = strtolower(trim($order->city ?? ''));
        $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
        $isLalamoveEligible = !$isCalambaCity;

        // Create or Update Delivery
        $delivery = $order->delivery;

        if (!$delivery) {
            $deliveryData = [
                'order_id' => $order->id,
                'status' => 'picked_up',
                'delivery_address' => $order->delivery_address,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'assigned_at' => now(),
                'picked_up_at' => now(),
            ];

            if (!$isLalamoveEligible) {
                $deliveryData['driver_id'] = $driverId;
                $deliveryData['tracking_number'] = 'DLV-' . strtoupper(uniqid());
            } else {
                $deliveryData['tracking_number'] = 'LAL-' . strtoupper(uniqid());
            }

            $delivery = Delivery::create($deliveryData);
        } else {
            $delivery->update([
                'status' => 'picked_up',
                'picked_up_at' => now(),
                'driver_id' => $isLalamoveEligible ? $delivery->driver_id : $driverId,
            ]);
        }

        $order->update([
            'order_status' => 'out_for_delivery',
            'out_for_delivery_at' => now(),
        ]);

        // ✅ SET FLASH MESSAGE FOR NEXT PAGE LOAD
        session()->flash('success', 'Delivery started! Order is out for delivery.');

        return response()->json([
            'success' => true,
            'message' => 'Delivery started! Order is out for delivery.',
            'new_status' => 'out_for_delivery'
        ]);
    }

    // For pickup orders
    $order->update(['order_status' => 'out_for_delivery']);

    // ✅ SET FLASH MESSAGE FOR NEXT PAGE LOAD
    session()->flash('success', 'Order marked as out for delivery.');

    return response()->json([
        'success' => true,
        'message' => 'Order marked as out for delivery.',
        'new_status' => 'out_for_delivery'
    ]);
}

    /**
     * Update Lalamove tracking
     */
    public function updateLalamove(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'tracking_url' => 'required|url',
            'delivery_proof' => 'nullable|image|max:5120',
            'lalamove_driver_name' => 'nullable|string|max:255',
        ]);

        // Shorten the URL if it exceeds 255 characters
        $trackingUrl = $request->tracking_url;
        if (strlen($trackingUrl) > 255) {
            $trackingUrl = substr($trackingUrl, 0, 255);
        }

        $delivery = $order->delivery()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'tracking_number' => $trackingUrl,
                'status' => 'picked_up',
                'assigned_at' => now(),
                'notes' => $request->lalamove_driver_name ?? null,
            ]
        );

        if ($request->hasFile('delivery_proof')) {
            if ($delivery->delivery_proof && Storage::disk('public')->exists($delivery->delivery_proof)) {
                Storage::disk('public')->delete($delivery->delivery_proof);
            }
            $path = $request->file('delivery_proof')->store('delivery_proofs', 'public');
            $delivery->delivery_proof = $path;
            $delivery->save();
        }

        $order->update([
            'order_status' => 'out_for_delivery'
        ]);

        return back()->with('success', 'Lalamove tracking link submitted!');
    }
}
