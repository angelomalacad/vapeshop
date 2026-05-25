<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\BranchInventory;
use App\Models\Delivery;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnlineOrderController extends Controller
{
    /**
     * Display online orders for driver's assigned branch
     * (Driver manages orders for their branch, but deliveries are personal)
     */
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        if (!$branchId) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'You are not assigned to any branch. Please contact the owner.');
        }

        $orders = Order::where('branch_id', $branchId)
            ->where('order_number', 'NOT LIKE', 'POS-%')
            ->whereNotIn('order_status', ['cancelled', 'delivered', 'delivery_failed'])
            ->orderByRaw("FIELD(order_status, 'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $counts = [
            'pending' => Order::where('branch_id', $branchId)->where('order_status', 'pending')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'confirmed' => Order::where('branch_id', $branchId)->where('order_status', 'confirmed')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'processing' => Order::where('branch_id', $branchId)->where('order_status', 'processing')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'ready' => Order::where('branch_id', $branchId)->where('order_status', 'ready')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'out_for_delivery' => Order::where('branch_id', $branchId)->where('order_status', 'out_for_delivery')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'delivered' => Order::where('branch_id', $branchId)->where('order_status', 'delivered')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        ];

        return view('driver.online-orders.index', compact('orders', 'counts'));
    }

    /**
     * Show specific order
     */
    public function show(Order $order)
    {
        $branchId = Auth::user()->branch_id;

        // Driver can only view orders from their branch
        if ($order->branch_id != $branchId) {
            abort(403, 'You are not authorized to view this order.');
        }

        $order->load(['items.product', 'branch', 'delivery']);
        return view('driver.online-orders.show', compact('order'));
    }

    /**
     * Confirm order (deduct stock)
     */
    public function confirm(Order $order)
    {
        $branchId = Auth::user()->branch_id;

        if ($order->branch_id != $branchId) {
            abort(403);
        }

        if ($order->order_status != 'pending') {
            return redirect()->back()->with('error', 'Order cannot be confirmed at this stage.');
        }

        DB::beginTransaction();

        try {
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $branchId)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$inventory || $inventory->quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: {$item->product->name}");
                }

                // Deduct stock
                $oldQuantity = $inventory->quantity;
                $newQuantity = $oldQuantity - $item->quantity;

                $inventory->update(['quantity' => $newQuantity]);

                // Log stock movement
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
                    'notes' => "Order #{$order->order_number} confirmed",
                    'created_by' => Auth::id(),
                ]);
            }

            $order->update(['order_status' => 'confirmed']);

            DB::commit();

            return redirect()->back()->with('success', 'Order confirmed and stock deducted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mark order as processing
     */
    public function markProcessing(Order $order)
    {
        $branchId = Auth::user()->branch_id;

        if ($order->branch_id != $branchId) {
            abort(403);
        }

        if ($order->order_status != 'confirmed') {
            return redirect()->back()->with('error', 'Order must be confirmed first.');
        }

        $order->update(['order_status' => 'processing']);

        return redirect()->back()->with('success', 'Order marked as processing.');
    }

    /**
     * Mark order as ready for pickup/delivery
     */
    public function markReady(Order $order)
    {
        $branchId = Auth::user()->branch_id;

        if ($order->branch_id != $branchId) {
            abort(403);
        }

        if (!in_array($order->order_status, ['confirmed', 'processing'])) {
            return redirect()->back()->with('error', 'Order cannot be marked as ready at this stage.');
        }

        $order->update(['order_status' => 'ready']);

        return redirect()->back()->with('success', 'Order is now ready for ' . ($order->delivery_type == 'delivery' ? 'delivery' : 'pickup') . '.');
    }

    /**
     * Start delivery (create delivery record)
     */
    public function startDelivery(Order $order)
    {
        $branchId = Auth::user()->branch_id;
        $driverId = Auth::id();

        if ($order->branch_id != $branchId) {
            abort(403);
        }

        if ($order->order_status != 'ready') {
            return redirect()->back()->with('error', 'Order must be ready first.');
        }

        // Check if delivery already exists
        if ($order->delivery) {
            return redirect()->back()->with('error', 'Delivery already started for this order.');
        }

        // Create delivery record
        $trackingNumber = 'DLV-' . strtoupper(uniqid());

        $delivery = Delivery::create([
            'order_id' => $order->id,
            'driver_id' => $driverId,
            'tracking_number' => $trackingNumber,
            'status' => 'assigned',
            'delivery_address' => $order->delivery_address,
            'recipient_name' => $order->customer_name,
            'recipient_phone' => $order->customer_phone,
            'assigned_at' => now(),
        ]);

        $order->update(['order_status' => 'out_for_delivery']);

        return redirect()->route('driver.deliveries.show', $delivery)
            ->with('success', 'Delivery started! You can now track and update the delivery status.');
    }
}