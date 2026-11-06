<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Branch;
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

        // ✅ Driver sees any order that is 'ready' or beyond
        $orders = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->whereIn('order_status', ['ready', 'picked_up', 'out_for_delivery']);

        if ($request->filled('order_number')) {
            $orders->where('order_number', 'LIKE', "%{$request->order_number}%");
        }

        if ($request->filled('branch_id')) {
            $orders->where('branch_id', $request->branch_id);
        }

        if ($request->filled('delivery_type')) {
            $deliveryType = $request->delivery_type;
            if ($deliveryType === 'lalamove') {
                $orders->where(function ($q) {
                    $q->where('city', '!=', 'Calamba')
                      ->where('city', '!=', 'Calamba City');
                });
            } elseif ($deliveryType === 'staff') {
                $orders->where(function ($q) {
                    $q->where('city', 'Calamba')
                      ->orWhere('city', 'Calamba City');
                });
            }
        }

        if ($request->filled('status')) {
            $statusFilter = $request->status;
            $statusMap = [
                'ready' => 'ready',
                'out_for_delivery' => 'out_for_delivery',
                'picked_up' => 'picked_up',
            ];
            if (isset($statusMap[$statusFilter])) {
                $orders->where('order_status', $statusMap[$statusFilter]);
            }
        }

        if ($request->filled('date_from')) {
            $orders->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $orders->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $orders->with([
                'items.product',
                'items.inventory.branch',
                'branch',
                'delivery'
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(5);

        $orders->appends($request->except('page'));

        $orders->getCollection()->transform(function ($order) {
            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
            $order->is_lalamove = !$isCalambaCity;
            return $order;
        });

        $counts = [
            'ready' => Delivery::where('status', 'assigned')->count(),
            'picked_up' => Delivery::where('status', 'picked_up')->count(),
            'out_for_delivery' => Delivery::where('status', 'out_for_delivery')->count(),
        ];

        $branches = Branch::orderBy('name')->get();

        return view('driver.online-orders.index', compact('orders', 'counts', 'branches'));
    }

        public function show(Order $order)
    {
        // ✅ Added 'items.inventory.branch' so the branch name shows correctly
        $order->load(['items.product', 'items.inventory.branch', 'branch', 'delivery']);
        return view('driver.online-orders.show', compact('order'));
    }

    public function startDelivery(Order $order)
    {
        // ✅ FIX: Only allow starting delivery if ALL items are 'ready'
        $allItemsReady = !$order->items()->where('status', '!=', 'ready')->exists();

        if (!$allItemsReady) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot start delivery yet. Wait for all branches to mark their items as Ready.'
            ]);
        }

        if ($order->order_status != 'ready') {
            return response()->json(['success' => false, 'message' => 'Order is not ready.']);
        }

        if ($order->delivery_type == 'delivery') {
            $driverId = Auth::id();
            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
            $isLalamoveEligible = !$isCalambaCity;
            $delivery = $order->delivery;

            if (!$delivery) {
                $deliveryData = [
                    'order_id' => $order->id, 'status' => 'picked_up', 'delivery_address' => $order->delivery_address,
                    'recipient_name' => $order->customer_name, 'recipient_phone' => $order->customer_phone,
                    'assigned_at' => now(), 'picked_up_at' => now(),
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
                    'status' => 'picked_up', 'picked_up_at' => now(),
                    'driver_id' => $isLalamoveEligible ? $delivery->driver_id : $driverId,
                ]);
            }

            $order->update(['order_status' => 'picked_up', 'out_for_delivery_at' => now()]);
            session()->flash('success', 'Delivery started!');
            return response()->json(['success' => true, 'message' => 'Delivery started!', 'new_status' => 'picked_up']);
        }

        $order->update(['order_status' => 'out_for_delivery']);
        session()->flash('success', 'Order marked as out for delivery.');
        return response()->json(['success' => true, 'message' => 'Order marked as out for delivery.', 'new_status' => 'out_for_delivery']);
    }

    public function updateLalamove(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $request->validate([
            'tracking_url' => 'required|url',
            'delivery_proof' => 'nullable|image|max:5120',
            'lalamove_driver_name' => 'nullable|string|max:255',
        ]);

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
                'picked_up_at' => now(),
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
            'order_status' => 'picked_up',
            'out_for_delivery_at' => now(),
        ]);

        return back()->with('success', 'Lalamove tracking link submitted!');
    }

    public function updateDeliveryDate(Request $request, Order $order)
    {
        $request->validate([
            'delivery_date_from' => 'required|date',
            'delivery_date_to' => 'required|date|after_or_equal:delivery_date_from',
        ]);

        $order->delivery_date_from = $request->delivery_date_from;
        $order->delivery_date_to = $request->delivery_date_to;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Delivery dates updated successfully!',
            'delivery_date_from' => $order->fresh()->delivery_date_from,
            'delivery_date_to' => $order->fresh()->delivery_date_to
        ]);
    }
}
