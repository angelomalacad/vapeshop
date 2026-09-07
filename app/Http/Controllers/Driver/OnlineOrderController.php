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

        // ✅ Show ALL orders that are in the delivery process
        $orders = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->where(function($query) {
                $query->whereHas('delivery', function($q) {
                    $q->whereIn('status', [
                        'assigned', 
                        'picked_up', 
                        'out_for_delivery', 
                        'delivered', 
                        'failed'
                    ]);
                })
                ->orWhere('order_status', 'ready');
            })
            ->where('order_status', '!=', 'pending')
            ->where('order_status', '!=', 'confirmed')
            ->where('order_status', '!=', 'processing')
            ->where('order_status', '!=', 'cancelled');

        // ✅ NEW: Search by Order Number
        if ($request->filled('order_number')) {
            $search = $request->order_number;
            $orders->where('order_number', 'LIKE', "%{$search}%");
        }

        // ✅ NEW: Filter by Branch
        if ($request->filled('branch_id')) {
            $orders->where('branch_id', $request->branch_id);
        }

        // ✅ FIXED: Filter by Delivery Type (Lalamove/Staff)
        if ($request->filled('delivery_type')) {
            $deliveryType = $request->delivery_type;
            if ($deliveryType === 'lalamove') {
                // ✅ FIXED: Lalamove orders are those NOT in Calamba City
                // Use whereNotIn or proper AND logic
                $orders->where(function($q) {
                    $q->where('city', '!=', 'Calamba')
                      ->where('city', '!=', 'Calamba City');
                });
            } elseif ($deliveryType === 'staff') {
                // ✅ FIXED: Staff orders are those in Calamba City
                $orders->where(function($q) {
                    $q->where('city', 'Calamba')
                      ->orWhere('city', 'Calamba City');
                });
            }
        }

        // ✅ Status filter
        if ($request->filled('status')) {
            $statusFilter = $request->status;
            
            $statusMap = [
                'ready' => 'assigned',
                'out_for_delivery' => 'out_for_delivery',
                'picked_up' => 'picked_up',
                'delivered' => 'delivered',
                'delivery_failed' => 'failed'
            ];
            
            $deliveryStatus = $statusMap[$statusFilter] ?? $statusFilter;
            
            $orders->whereHas('delivery', function($query) use ($deliveryStatus) {
                $query->where('status', $deliveryStatus);
            });
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
                'branch',
                'delivery'
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(5);

        // ✅ Preserve filters in pagination links
        $orders->appends($request->except('page'));

        // Add custom attribute for Staff vs Lalamove
        $orders->getCollection()->transform(function ($order) {
            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = ($cityLower === 'calamba city' || $cityLower === 'calamba');
            $order->is_lalamove = !$isCalambaCity;
            return $order;
        });

        // ✅ Counts for status cards
        $counts = [
            'ready' => Delivery::where('status', 'assigned')->count(),
            'picked_up' => Delivery::where('status', 'picked_up')->count(),
            'out_for_delivery' => Delivery::where('status', 'out_for_delivery')->count(),
            'delivered' => Delivery::where('status', 'delivered')->count(),
            'delivery_failed' => Delivery::where('status', 'failed')->count(),
        ];

        // ✅ Get all branches for filter dropdown
        $branches = Branch::orderBy('name')->get();

        return view('driver.online-orders.index', compact('orders', 'counts', 'branches'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'branch', 'delivery']);
        return view('driver.online-orders.show', compact('order'));
    }

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

            $cityLower = strtolower(trim($order->city ?? ''));
            $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';
            $isLalamoveEligible = !$isCalambaCity;

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
                'order_status' => 'picked_up',
                'out_for_delivery_at' => now(),
            ]);

            session()->flash('success', 'Delivery started! Order has been picked up.');

            return response()->json([
                'success' => true,
                'message' => 'Delivery started! Order has been picked up.',
                'new_status' => 'picked_up'
            ]);
        }

        $order->update(['order_status' => 'out_for_delivery']);

        session()->flash('success', 'Order marked as out for delivery.');

        return response()->json([
            'success' => true,
            'message' => 'Order marked as out for delivery.',
            'new_status' => 'out_for_delivery'
        ]);
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
        'delivery_date_to' => 'required|date|after_or_equal:delivery_date_from', // ✅ Allows same date
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