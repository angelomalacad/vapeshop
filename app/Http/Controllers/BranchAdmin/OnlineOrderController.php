<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\BranchInventory;
use App\Models\InventoryReservation;
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
    $branchId = Auth::user()->branch_id;
    
    // ✅ FIX: Show ONLY orders for the current branch
    $orders = Order::where('branch_id', $branchId)
        ->where('order_number', 'NOT LIKE', 'POS-%');

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
            'delivery',
            'branch'
        ])
        ->orderByRaw("FIELD(order_status, 'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled')")
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // ✅ Add custom attribute for Staff vs Lalamove
    $orders->getCollection()->transform(function ($order) {
        $cityLower = strtolower(trim($order->city ?? ''));
        $isCalambaCity = ($cityLower === 'calamba city' || $cityLower === 'calamba');
        $order->is_lalamove = !$isCalambaCity;
        
        // ✅ This is always true now since we only show current branch orders
        $order->is_current_branch = true;
        
        return $order;
    });

    // ✅ Get all branches for filter (but only show current branch's orders)
    $branches = \App\Models\Branch::where('is_active', true)->get();

    // ✅ Counts for status cards - ONLY for current branch
    $counts = [
        'pending' => Order::where('branch_id', $branchId)->where('order_status', 'pending')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'confirmed' => Order::where('branch_id', $branchId)->where('order_status', 'confirmed')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'processing' => Order::where('branch_id', $branchId)->where('order_status', 'processing')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'ready' => Order::where('branch_id', $branchId)->where('order_status', 'ready')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'out_for_delivery' => Order::where('branch_id', $branchId)->where('order_status', 'out_for_delivery')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'delivered' => Order::where('branch_id', $branchId)->where('order_status', 'delivered')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
    ];

    return view('branch-admin.online-orders.index', compact('orders', 'counts', 'branches'));
}

    /**
     * Show a specific online order
     */
    public function show(Order $order)
    {
        // ✅ CHECK: Only the branch that owns the order can view it
        if ($order->branch_id !== Auth::user()->branch_id) {
            abort(403, 'You can only view orders for your branch.');
        }

        $order->load(['items.product', 'items.inventory.branch', 'branch', 'delivery']);
        
        // Check if this order belongs to the current branch
        $isCurrentBranch = ($order->branch_id === Auth::user()->branch_id);
        
        return view('branch-admin.online-orders.show', compact('order', 'isCurrentBranch'));
    }

    /**
     * Confirm order - RESERVE stock (not deduct)
     * Only the branch that owns the order can confirm
     */
    public function confirm(Order $order)
    {
        // ✅ CHECK: Only the branch that owns the order can confirm
        if ($order->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only confirm orders for your branch. This order belongs to another branch.'
            ], 403);
        }

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

                // Create inventory reservation record
                InventoryReservation::create([
                    'branch_inventory_id' => $inventory->id,
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'quantity' => $item->quantity,
                    'reservation_type' => 'online_order',
                    'status' => 'active',
                    'expires_at' => now()->addHours(24),
                    'notes' => "Order #{$order->order_number} confirmed by branch staff"
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
        // ✅ CHECK: Only the branch that owns the order can process
        if ($order->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only process orders for your branch.'
            ], 403);
        }

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
        // ✅ CHECK: Only the branch that owns the order can mark ready
        if ($order->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only mark orders ready for your branch.'
            ], 403);
        }

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
     * Mark order as out for delivery
     */
    public function markOutForDelivery(Order $order)
    {
        // ✅ CHECK: Only the branch that owns the order can mark out for delivery
        if ($order->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only mark orders out for delivery for your branch.'
            ], 403);
        }

        if ($order->order_status != 'ready') {
            return response()->json([
                'success' => false,
                'message' => 'Order must be ready first. Current status: ' . $order->order_status
            ]);
        }

        $order->update(['order_status' => 'out_for_delivery']);

        return response()->json([
            'success' => true,
            'message' => 'Order is out for delivery.',
            'new_status' => 'out_for_delivery'
        ]);
    }

    /**
     * Mark order as delivered - Deduct inventory
     */
    public function markAsDelivered(Order $order)
    {
        // ✅ CHECK: Only the branch that owns the order can mark delivered
        if ($order->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only mark orders delivered for your branch.'
            ], 403);
        }

        if ($order->order_status != 'out_for_delivery') {
            return response()->json([
                'success' => false,
                'message' => 'Order must be out for delivery first. Current status: ' . $order->order_status
            ]);
        }

        // Call the deductInventory method
        $result = $this->deductInventory($order);

        if ($result->getData()->success) {
            $order->update([
                'order_status' => 'delivered',
                'delivered_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order delivered and inventory deducted successfully.',
                'new_status' => 'delivered'
            ]);
        }

        return $result;
    }

    /**
     * Deduct inventory when order is delivered
     */
    public function deductInventory(Order $order)
    {
        $branchId = $order->branch_id;

        DB::beginTransaction();

        try {
            // Check if already deducted
            $alreadyDeducted = StockMovement::where('reference_type', 'order')
                ->where('reference_id', $order->id)
                ->where('movement_type', 'sale')
                ->exists();

            if ($alreadyDeducted) {
                throw new \Exception('Inventory already deducted for this order.');
            }

            // Release all inventory reservations for this order
            $reservations = InventoryReservation::where('order_id', $order->id)
                ->where('status', 'active')
                ->get();

            foreach ($reservations as $reservation) {
                $reservation->update([
                    'status' => 'converted',
                    'converted_at' => now()
                ]);
            }

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

    /**
     * Cancel order - Release reserved stock
     */
    public function cancelOrder(Order $order)
    {
        // ✅ CHECK: Only the branch that owns the order can cancel
        if ($order->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only cancel orders for your branch.'
            ], 403);
        }

        if (!in_array($order->order_status, ['pending', 'confirmed', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage. Current status: ' . $order->order_status
            ]);
        }

        $branchId = $order->branch_id;

        DB::beginTransaction();

        try {
            // Release all active reservations for this order
            $reservations = InventoryReservation::where('order_id', $order->id)
                ->where('status', 'active')
                ->get();

            foreach ($reservations as $reservation) {
                $inventory = BranchInventory::where('id', $reservation->branch_inventory_id)->first();

                if ($inventory) {
                    $inventory->update([
                        'reserved_quantity' => max(0, $inventory->reserved_quantity - $reservation->quantity)
                    ]);
                }

                $reservation->update([
                    'status' => 'released',
                    'released_at' => now()
                ]);
            }

            // Update order status
            $order->update([
                'order_status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled and reserved stock released.',
                'new_status' => 'cancelled'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Expire pending orders and release reservations
     */
    public function expireReservations()
    {
        $expiredOrders = Order::where('order_status', 'pending')
            ->where('created_at', '<', now()->subHours(48))
            ->get();

        $count = 0;

        foreach ($expiredOrders as $order) {
            // Release all reservations
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $order->branch_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($inventory && $inventory->reserved_quantity >= $item->quantity) {
                    $inventory->update([
                        'reserved_quantity' => $inventory->reserved_quantity - $item->quantity
                    ]);
                }
            }

            $order->update([
                'order_status' => 'expired',
                'expired_at' => now()
            ]);

            $count++;
        }

        return response()->json([
            'success' => true,
            'message' => "Expired {$count} order reservations."
        ]);
    }
}