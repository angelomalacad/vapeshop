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
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $orders = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->whereNotIn('order_status', ['delivered', 'delivery_failed', 'cancelled'])
            ->whereHas('items.inventory', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });

        if ($request->filled('status')) {
            $orders->where('order_status', $request->status);
        }
        if ($request->filled('date_from')) {
            $orders->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $orders->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $orders->where('order_number', 'LIKE', '%' . $request->search . '%');
        }

        $orders = $orders->with([
                'items.product',
                'items.inventory.branch',
                'delivery',
                'branch'
            ])
            ->orderByRaw("FIELD(order_status, 'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $orders->getCollection()->transform(function ($order) {
            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = ($cityLower === 'calamba city' || $cityLower === 'calamba');
            $order->is_lalamove = !$isCalambaCity;
            $order->is_current_branch = true;
            return $order;
        });

        $branches = \App\Models\Branch::where('is_active', true)->get();

        $counts = [
            'pending' => Order::where('order_number', 'NOT LIKE', 'POS-%')->where('order_status', 'pending')->whereHas('items.inventory', fn($q) => $q->where('branch_id', $branchId))->count(),
            'confirmed' => Order::where('order_number', 'NOT LIKE', 'POS-%')->where('order_status', 'confirmed')->whereHas('items.inventory', fn($q) => $q->where('branch_id', $branchId))->count(),
            'processing' => Order::where('order_number', 'NOT LIKE', 'POS-%')->where('order_status', 'processing')->whereHas('items.inventory', fn($q) => $q->where('branch_id', $branchId))->count(),
            'ready' => Order::where('order_number', 'NOT LIKE', 'POS-%')->where('order_status', 'ready')->whereHas('items.inventory', fn($q) => $q->where('branch_id', $branchId))->count(),
            'out_for_delivery' => Order::where('order_number', 'NOT LIKE', 'POS-%')->where('order_status', 'out_for_delivery')->whereHas('items.inventory', fn($q) => $q->where('branch_id', $branchId))->count(),
            'delivered' => Order::where('order_number', 'NOT LIKE', 'POS-%')->where('order_status', 'delivered')->whereHas('items.inventory', fn($q) => $q->where('branch_id', $branchId))->count(),
        ];

        return view('branch-admin.online-orders.index', compact('orders', 'counts', 'branches'));
    }

    public function show(Order $order)
    {
        $hasBranchItems = $order->items()->whereHas('inventory', fn($q) => $q->where('branch_id', Auth::user()->branch_id))->exists();
        if (!$hasBranchItems) {
            abort(403, 'You can only view orders for your branch.');
        }

        $order->load(['items.product', 'items.inventory.branch', 'branch', 'delivery']);
        $isCurrentBranch = true;

        return view('branch-admin.online-orders.show', compact('order', 'isCurrentBranch'));
    }

    public function confirm(Order $order)
    {
        $branchId = Auth::user()->branch_id;

        $branchItems = $order->items()->whereHas('inventory', fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'pending')->get();

        if ($branchItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No pending items for your branch.']);
        }

        DB::beginTransaction();
        try {
            foreach ($branchItems as $item) {
                $inventory = BranchInventory::where('id', $item->inventory_id)->lockForUpdate()->first();
                if (!$inventory) throw new \Exception("Inventory record not found.");

                $existingReservation = InventoryReservation::where('order_id', $order->id)
                    ->where('branch_inventory_id', $inventory->id)
                    ->where('status', 'active')
                    ->first();

                if ($existingReservation) {
                    $existingReservation->update(['expires_at' => now()->addHours(24), 'notes' => "Order #{$order->order_number} confirmed"]);
                } else {
                    if ($inventory->available_quantity < $item->quantity) throw new \Exception("Insufficient stock for {$item->product->name}.");

                    $inventory->update(['reserved_quantity' => $inventory->reserved_quantity + $item->quantity]);

                    InventoryReservation::create([
                        'branch_inventory_id' => $inventory->id,
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'quantity' => $item->quantity,
                        'reservation_type' => 'online_order',
                        'status' => 'active',
                        'expires_at' => now()->addHours(24),
                        'notes' => "Order #{$order->order_number} confirmed"
                    ]);
                }

                StockMovement::create([
                    'branch_id' => $inventory->branch_id,
                    'product_id' => $item->product_id,
                    'flavor_id' => $item->flavor_id ?? null,
                    'previous_quantity' => $inventory->quantity,
                    'new_quantity' => $inventory->quantity,
                    'quantity_change' => 0,
                    'movement_type' => 'reserve',
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => "Order #{$order->order_number} confirmed - stock reserved",
                    'created_by' => Auth::id(),
                ]);

                $item->update(['status' => 'confirmed']);
            }

            if (!$order->items()->where('status', 'pending')->exists()) {
                $order->update(['order_status' => 'confirmed']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Your branch items confirmed.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function markProcessing(Order $order)
    {
        $branchId = Auth::user()->branch_id;

        $branchItems = $order->items()->whereHas('inventory', fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'confirmed')->get();

        if ($branchItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No confirmed items to pack.']);
        }

        foreach ($branchItems as $item) {
            $item->update(['status' => 'processing']);
        }

        if (!$order->items()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            $order->update(['order_status' => 'processing']);
        }

        return response()->json(['success' => true, 'message' => 'Your branch items marked as packing.']);
    }

    public function markReady(Order $order)
    {
        $branchId = Auth::user()->branch_id;

        $branchItems = $order->items()->whereHas('inventory', fn($q) => $q->where('branch_id', $branchId))
            ->where('status', 'processing')->get();

        if ($branchItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No processing items to mark ready.']);
        }

        foreach ($branchItems as $item) {
            $item->update(['status' => 'ready']);
        }

        // ✅ ALWAYS set the order to 'ready' so the driver can see it immediately
        $order->update([
            'order_status' => 'ready',
            'delivery_date_from' => now(),
            'delivery_date_to' => now()->addDays(3),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your branch is ready. Waiting for other branches.',
            'new_status' => 'ready'
        ]);
    }

    public function updateDeliveryDate(Request $request, Order $order)
    {
        if ($order->branch_id !== Auth::user()->branch_id) return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        if (in_array($order->order_status, ['delivered', 'cancelled'])) return response()->json(['success' => false, 'message' => 'Cannot change dates.'], 422);

        $request->validate([
            'delivery_date_from' => 'required|date',
            'delivery_date_to' => 'required|date|after_or_equal:delivery_date_from',
        ]);

        $order->delivery_date_from = $request->delivery_date_from;
        $order->delivery_date_to = $request->delivery_date_to;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Dates updated.']);
    }

    public function markOutForDelivery(Order $order)
    {
        if ($order->branch_id !== Auth::user()->branch_id) return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        if ($order->order_status != 'ready') return response()->json(['success' => false, 'message' => 'Not ready.']);

        $order->update(['order_status' => 'out_for_delivery']);
        return response()->json(['success' => true, 'message' => 'Out for delivery.', 'new_status' => 'out_for_delivery']);
    }

    public function markAsDelivered(Order $order)
    {
        if ($order->branch_id !== Auth::user()->branch_id) return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        if ($order->order_status != 'out_for_delivery') return response()->json(['success' => false, 'message' => 'Must be out for delivery first.']);

        $result = $this->deductInventory($order);
        if ($result->getData()->success) {
            $order->update(['order_status' => 'delivered', 'delivered_at' => now()]);
            return response()->json(['success' => true, 'message' => 'Delivered.', 'new_status' => 'delivered']);
        }
        return $result;
    }

    public function deductInventory(Order $order)
    {
        DB::beginTransaction();
        try {
            $alreadyDeducted = StockMovement::where('reference_type', 'order')->where('reference_id', $order->id)->where('movement_type', 'sale')->exists();
            if ($alreadyDeducted) throw new \Exception('Inventory already deducted.');

            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('id', $item->inventory_id)->lockForUpdate()->first();
                if ($inventory) {
                    $oldQuantity = $inventory->quantity;
                    $newQuantity = $oldQuantity - $item->quantity;

                    $inventory->update([
                        'quantity' => $newQuantity,
                        'reserved_quantity' => max(0, $inventory->reserved_quantity - $item->quantity)
                    ]);

                    StockMovement::create([
                        'branch_id' => $inventory->branch_id,
                        'product_id' => $item->product_id,
                        'flavor_id' => $item->flavor_id ?? null,
                        'previous_quantity' => $oldQuantity,
                        'new_quantity' => $newQuantity,
                        'quantity_change' => -$item->quantity,
                        'movement_type' => 'sale',
                        'reference_type' => 'order',
                        'reference_id' => $order->id,
                        'notes' => "Order #{$order->order_number} delivered",
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            InventoryReservation::where('order_id', $order->id)->where('status', 'active')->update(['status' => 'converted', 'converted_at' => now()]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Inventory deducted.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function cancelOrder(Order $order)
    {
        $hasBranchItems = $order->items()->whereHas('inventory', fn($q) => $q->where('branch_id', Auth::user()->branch_id))->exists();
        if (!$hasBranchItems) return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);

        DB::beginTransaction();
        try {
            $reservations = InventoryReservation::where('order_id', $order->id)->where('status', 'active')->get();
            foreach ($reservations as $reservation) {
                $inventory = BranchInventory::where('id', $reservation->branch_inventory_id)->first();
                if ($inventory) $inventory->update(['reserved_quantity' => max(0, $inventory->reserved_quantity - $reservation->quantity)]);
                $reservation->update(['status' => 'released', 'released_at' => now()]);
            }

            $order->update(['order_status' => 'cancelled']);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order cancelled.', 'new_status' => 'cancelled']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function expireReservations()
    {
        $expiredOrders = Order::where('order_status', 'pending')->where('created_at', '<', now()->subHours(48))->get();
        $count = 0;
        foreach ($expiredOrders as $order) {
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('id', $item->inventory_id)->first();
                if ($inventory && $inventory->reserved_quantity >= $item->quantity) $inventory->update(['reserved_quantity' => $inventory->reserved_quantity - $item->quantity]);
            }
            $order->update(['order_status' => 'expired', 'expired_at' => now()]);
            $count++;
        }
        return response()->json(['success' => true, 'message' => "Expired {$count} order reservations."]);
    }

    public function showModal(Order $order)
    {
        $order->load(['items.product', 'branch', 'delivery.driver']);
        return view('branch-admin.online-orders.show-modal', compact('order'));
    }
}
