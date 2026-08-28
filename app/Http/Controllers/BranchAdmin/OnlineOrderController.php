<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
     * Display all online orders for the branch admin
     */
    public function index(Request $request)
    {
        // Show ALL online orders (not just the current branch)
        $orders = Order::where('order_number', 'NOT LIKE', 'POS-%');

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

        // ✅ Search by order number
        if ($request->filled('search')) {
            $orders->where('order_number', 'LIKE', '%' . $request->search . '%');
        }

        // ✅ Load relationships
        $orders = $orders->with([
                'items.product',
                'items.inventory.branch',
                'delivery'
            ])
            ->orderByRaw("FIELD(order_status, 'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // ✅ Add custom attribute for Staff vs Lalamove
        $orders->getCollection()->transform(function ($order) {
            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = ($cityLower === 'calamba city' || $cityLower === 'calamba');
            $order->is_lalamove = !$isCalambaCity;
            return $order;
        });

        // Counts for status cards (Show ALL online orders)
        $counts = [
            'pending' => Order::where('order_status', 'pending')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'confirmed' => Order::where('order_status', 'confirmed')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'processing' => Order::where('order_status', 'processing')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'ready' => Order::where('order_status', 'ready')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'out_for_delivery' => Order::where('order_status', 'out_for_delivery')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
            'delivered' => Order::where('order_status', 'delivered')
                ->where('order_number', 'NOT LIKE', 'POS-%')
                ->count(),
        ];

        return view('branch-admin.online-orders.index', compact('orders', 'counts'));
    }

    /**
     * Show a specific online order
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'items.inventory.branch', 'branch', 'delivery']);
        return view('branch-admin.online-orders.show', compact('order'));
    }

    /**
     * Confirm order - RESERVE stock (not deduct)
     */
    public function confirm(Order $order)
    {
        if ($order->order_status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be confirmed. Current status: ' . $order->order_status
            ]);
        }

        $branchId = $order->branch_id;

        DB::beginTransaction();

        try {
            // Check and RESERVE inventory for each item
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $branchId)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$inventory || $inventory->available_quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for product: {$item->product->name}");
                }

                $oldQuantity = $inventory->quantity;
                $oldReserved = $inventory->reserved_quantity;
                $newQuantity = $oldQuantity; // Quantity stays the same
                $newReserved = $oldReserved + $item->quantity;

                // Update inventory - reserve stock
                $inventory->update([
                    'quantity' => $newQuantity,
                    'reserved_quantity' => $newReserved
                ]);

                // Create stock movement record for reservation
                StockMovement::create([
                    'branch_id' => $branchId,
                    'product_id' => $item->product_id,
                    'flavor_id' => $item->flavor_id ?? null,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => 0,
                    'movement_type' => 'reserve',
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => "Order #{$order->order_number} confirmed - stock reserved by branch staff",
                    'created_by' => Auth::id(),
                ]);
            }

            // Update order status
            $order->update(['order_status' => 'confirmed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order confirmed and stock reserved successfully.',
                'new_status' => 'confirmed'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Mark order as processing (packing)
     */
    public function markProcessing(Order $order)
    {
        if ($order->order_status != 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Order must be confirmed first. Current status: ' . $order->order_status
            ]);
        }

        $order->update(['order_status' => 'processing']);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as packing.',
            'new_status' => 'processing'
        ]);
    }

    /**
     * Mark order as ready
     */
    public function markReady(Order $order)
    {
        if (!in_array($order->order_status, ['confirmed', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be marked as ready. Current status: ' . $order->order_status
            ]);
        }

        $order->update(['order_status' => 'ready']);

        return response()->json([
            'success' => true,
            'message' => 'Order is ready for delivery.',
            'new_status' => 'ready'
        ]);
    }

    /**
     * Deduct inventory when order is delivered
     */
    public function deductInventory(Order $order)
    {
        $branchId = $order->branch_id;

        DB::beginTransaction();

        try {
            // Deduct reserved inventory for each item
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $branchId)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($inventory) {
                    $oldQuantity = $inventory->quantity;
                    $oldReserved = $inventory->reserved_quantity;
                    $newQuantity = $oldQuantity - $item->quantity;
                    $newReserved = max(0, $oldReserved - $item->quantity);

                    // Update inventory - deduct stock
                    $inventory->update([
                        'quantity' => $newQuantity,
                        'reserved_quantity' => $newReserved
                    ]);

                    // Create stock movement record for actual sale
                    StockMovement::create([
                        'branch_id' => $branchId,
                        'product_id' => $item->product_id,
                        'flavor_id' => $item->flavor_id ?? null,
                        'previous_quantity' => $oldQuantity,
                        'new_quantity' => $newQuantity,
                        'quantity_change' => -$item->quantity,
                        'movement_type' => 'sale',
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'notes' => "Order #{$order->order_number} delivered - stock deducted",
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory deducted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
