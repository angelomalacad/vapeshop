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

        $orders = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->whereNotIn('order_status', ['cancelled']);

        // ✅ NEW: Status filter
        if ($request->filled('status')) {
            $orders->where('order_status', $request->status);
        }

        // ✅ NEW: Date From filter
        if ($request->filled('date_from')) {
            $orders->whereDate('created_at', '>=', $request->date_from);
        }

        // ✅ NEW: Date To filter
        if ($request->filled('date_to')) {
            $orders->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $orders->orderByRaw("FIELD(order_status, 'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered')")
            ->orderBy('created_at', 'desc')
            ->paginate(5);

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
                $activeShift = DriverShift::where('shift_date', today())
                    ->where('status', 'active')
                    ->where('driver_id', $driverId)
                    ->first();

                if ($activeShift) {
                    $trackingNumber = 'DLV-' . strtoupper(uniqid());

                    Delivery::create([
                        'order_id' => $order->id,
                        'driver_id' => $driverId,
                        'tracking_number' => $trackingNumber,
                        'status' => 'assigned',
                        'delivery_address' => $order->delivery_address,
                        'recipient_name' => $order->customer_name,
                        'recipient_phone' => $order->customer_phone,
                        'assigned_at' => now(),
                    ]);
                }
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
}