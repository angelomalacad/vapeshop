<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\BranchInventory;
use App\Models\Delivery;
use App\Models\DriverShift;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage; // <--- ADDED FOR IMAGE UPLOADS

class OnlineOrderController extends Controller
{
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

        // Build the base query
        $orders = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->whereNotIn('order_status', ['cancelled', 'delivered']);

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
                'items.product',             // For Product Name & Image
                'items.inventory.branch'     // For "Fulfilled By" column (Pickup Branch)
            ])
            ->orderByRaw("FIELD(order_status, 'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered')")
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // ✅ ADD THIS BLOCK: Add a custom attribute for "Staff" vs "Lalamove"
        $orders->getCollection()->transform(function ($order) {
            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = ($cityLower === 'calamba city' || $cityLower === 'calamba');
            $order->is_lalamove = !$isCalambaCity;
            return $order;
        });
        // ================================================================

        $counts = [
            'pending' => Order::where('order_status', 'pending')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'processing' => Order::where('order_status', 'processing')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'ready' => Order::where('order_status', 'ready')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'out_for_delivery' => Order::where('order_status', 'out_for_delivery')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'delivered' => Order::where('order_status', 'delivered')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        ];

        return view('driver.online-orders.index', compact('orders', 'counts'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'branch', 'delivery']);
        return view('driver.online-orders.show', compact('order'));
    }

    public function confirm(Order $order)
    {
        if ($order->order_status != 'pending') {
            return response()->json(['success' => false, 'message' => 'Order cannot be confirmed. Current status: ' . $order->order_status]);
        }

        $branchId = $order->branch_id;

        DB::beginTransaction();

        try {
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $branchId)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$inventory || $inventory->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: {$item->product->name}");
                }

                $oldQuantity = $inventory->quantity;
                $newQuantity = $oldQuantity - $item->quantity;

                $inventory->update(['quantity' => $newQuantity]);

                StockMovement::create([
                    'branch_id' => $branchId,
                    'product_id' => $item->product_id,
                    'flavor_id' => null,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => -$item->quantity,
                    'movement_type' => 'sale',
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => "Order #{$order->order_number} confirmed by driver",
                    'created_by' => Auth::id(),
                ]);
            }

            $order->update(['order_status' => 'confirmed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order confirmed and stock deducted successfully.',
                'new_status' => 'confirmed'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function markProcessing(Order $order)
    {
        if ($order->order_status != 'confirmed') {
            return response()->json(['success' => false, 'message' => 'Order must be confirmed first. Current status: ' . $order->order_status]);
        }

        $order->update(['order_status' => 'processing']);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as processing.',
            'new_status' => 'processing'
        ]);
    }

             public function markReady(Order $order)
    {
        if (!in_array($order->order_status, ['confirmed', 'processing'])) {
            return response()->json(['success' => false, 'message' => 'Order cannot be marked as ready. Current status: ' . $order->order_status]);
        }

        if ($order->delivery_type == 'delivery') {
            // Create delivery if not exists
            if (!$order->delivery) {
                $driverId = Auth::id();

                // 🔥 FIX: Always create the delivery record, whether it's Staff or Lalamove
                $deliveryData = [
                    'order_id' => $order->id,
                    'status' => 'assigned',
                    'delivery_address' => $order->delivery_address,
                    'recipient_name' => $order->customer_name,
                    'recipient_phone' => $order->customer_phone,
                    'assigned_at' => now(),
                ];

                // Check if it's Lalamove
                $cityLower = strtolower(trim($order->city ?? ''));
                $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
                $isLalamoveEligible = !$isCalambaCity;

                // IF STAFF: Assign the driver ID (ALWAYS)
                if (!$isLalamoveEligible) {
                    $deliveryData['driver_id'] = $driverId; // 🔥 ALWAYS assign
                    $deliveryData['tracking_number'] = 'DLV-' . strtoupper(uniqid());
                } else {
                    // IF LALAMOVE: Keep driver_id as null
                    $deliveryData['tracking_number'] = 'LAL-' . strtoupper(uniqid());

                    // ✅ Save Lalamove Driver Name if provided
                    if (!empty($request->lalamove_driver_name)) {
                        $deliveryData['notes'] = $request->lalamove_driver_name;
                    }
                }

                // 🔥 Create the delivery
                Delivery::create($deliveryData);
            }

            // Record the time when the driver started the delivery
            $order->delivered_at = now();

            $order->update(['order_status' => 'out_for_delivery']);

            return response()->json([
                'success' => true,
                'message' => 'Delivery started! Order is out for delivery.',
                'new_status' => 'out_for_delivery'
            ]);
        }

        $order->update(['order_status' => 'ready']);

        return response()->json([
            'success' => true,
            'message' => 'Order is ready for pickup.',
            'new_status' => 'ready'
        ]);
    }

    // =============================================================
    // FIXED: Lalamove Tracking & Proof Upload Method
    // =============================================================
             public function updateLalamove(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'tracking_url' => 'required|url',
            'delivery_proof' => 'nullable|image|max:5120',
            'lalamove_driver_name' => 'nullable|string|max:255',
        ]);

        // 🔥 FIX: Shorten the URL if it exceeds 255 characters (column limit)
        $trackingUrl = $request->tracking_url;
        if (strlen($trackingUrl) > 255) {
            $trackingUrl = substr($trackingUrl, 0, 255);
        }

        $delivery = $order->delivery()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'tracking_number' => $trackingUrl, // ✅ Uses shortened URL
                'status' => 'picked_up',
                'assigned_at' => now(),
                // ✅ Saves the Lalamove Driver Name into notes
                'notes' => $request->lalamove_driver_name ?? $delivery->notes ?? null,
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

        return back()->with('success', 'Lalamove tracking link submitted! Customer can now track their package.');
    }
}
