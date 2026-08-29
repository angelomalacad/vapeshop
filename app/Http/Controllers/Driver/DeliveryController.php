<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\BranchInventory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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

        // Active deliveries (not yet delivered or failed) - with pagination
        $activeDeliveries = Delivery::where('driver_id', $driverId)
            ->whereNotIn('status', ['delivered', 'failed'])
            ->with(['order', 'order.branch'])
            ->orderByRaw("FIELD(status, 'assigned', 'picked_up', 'in_transit')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

        // Calculate active and completed counts for the header badges
        $activeCount = Delivery::where('driver_id', $driverId)->whereNotIn('status', ['delivered', 'failed'])->count();
        $completedCount = Delivery::where('driver_id', $driverId)->whereIn('status', ['delivered', 'failed'])->count();

        return view('driver.deliveries.index', compact('activeDeliveries', 'completedDeliveries', 'totalDeliveries', 'stats', 'activeCount', 'completedCount'));
    }

    /**
     * Show a specific delivery
     */
    public function show(Delivery $delivery)
    {
        // Allow Lalamove orders (driver_id = null) to be viewed
        if ($delivery->driver_id !== null && $delivery->driver_id !== Auth::id()) {
            abort(403, 'This delivery is not assigned to you.');
        }

        $delivery->load(['order', 'order.items.product', 'order.branch']);
        return view('driver.deliveries.show', compact('delivery'));
    }

    /**
     * Driver dashboard with all stats
     */
    public function dashboard()
    {
        // ✅ Check if driver has active shift
        $todayShift = \App\Models\DriverShift::where('shift_date', today())
            ->where('status', 'active')
            ->where('driver_id', Auth::id())
            ->first();

        if (!$todayShift) {
            // Allow dashboard access, but block other actions via checks in methods
        }
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

        // Get pending online orders (not yet started)
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

        // Get recent online orders (last 10, excluding cancelled)
        $recentOnlineOrders = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->whereNotIn('order_status', ['cancelled'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('driver.dashboard', compact(
            'totalDeliveries', 'inTransitCount', 'deliveredCount',
            'pendingDeliveries', 'pendingOrdersCount', 'readyOrdersCount',
            'outForDeliveryCount', 'recentDeliveries', 'todayShift',
            'recentOnlineOrders'
        ));
    }

    /**
     * Display the driver's delivery history (Active & Completed)
     */
    public function deliveryHistory(Request $request)
    {
        $driverId = Auth::id();

        // Active Deliveries: Allows BOTH your Staff deliveries AND Lalamove deliveries
        $activeQuery = Delivery::whereNotIn('status', ['delivered', 'failed'])
            ->where(function($query) use ($driverId) {
                $query->where('driver_id', $driverId)
                      ->orWhereNull('driver_id');
            })
            ->with(['order.items.product', 'order.branch']);

        // Completed Deliveries: Allows BOTH your Staff deliveries AND Lalamove deliveries
        $completedQuery = Delivery::whereIn('status', ['delivered', 'failed'])
            ->where(function($query) use ($driverId) {
                $query->where('driver_id', $driverId)
                      ->orWhereNull('driver_id');
            })
            ->with(['order.items.product', 'order.branch']);

        // --- FILTER: Search by Order Number ---
        if ($request->filled('search')) {
            $search = $request->search;
            $activeQuery->whereHas('order', function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%");
            });
            $completedQuery->whereHas('order', function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%");
            });
        }

        // --- FILTER: Delivery Type (Lalamove vs Staff) ---
        if ($request->filled('delivery_type')) {
            $type = $request->delivery_type;
            if ($type === 'lalamove') {
                $activeQuery->whereHas('order', function($q) {
                    $q->where('city', '!=', 'Calamba')->where('city', '!=', 'Calamba City');
                });
                $completedQuery->whereHas('order', function($q) {
                    $q->where('city', '!=', 'Calamba')->where('city', '!=', 'Calamba City');
                });
            } elseif ($type === 'staff') {
                $activeQuery->whereHas('order', function($q) {
                    $q->where('city', 'Calamba')->orWhere('city', 'Calamba City');
                });
                $completedQuery->whereHas('order', function($q) {
                    $q->where('city', 'Calamba')->orWhere('city', 'Calamba City');
                });
            }
        }

        // --- FILTER: Date Range ---
        if ($request->filled('date_from')) {
            $activeQuery->whereDate('created_at', '>=', $request->date_from);
            $completedQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $activeQuery->whereDate('created_at', '<=', $request->date_to);
            $completedQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Fetch Active Deliveries (with filters applied)
        $activeDeliveries = $activeQuery->orderBy('assigned_at', 'desc')
            ->paginate(5, ['*'], 'active_page');

        // Fetch Completed Deliveries (with filters applied)
        $completedDeliveries = $completedQuery->orderBy('delivered_at', 'desc')
            ->paginate(5, ['*'], 'completed_page');

        // Counts for the header stats (Includes Lalamove)
        $activeCount = Delivery::whereNotIn('status', ['delivered', 'failed'])
            ->where(function($query) use ($driverId) {
                $query->where('driver_id', $driverId)
                      ->orWhereNull('driver_id');
            })->count();

        $completedCount = Delivery::whereIn('status', ['delivered', 'failed'])
            ->where(function($query) use ($driverId) {
                $query->where('driver_id', $driverId)
                      ->orWhereNull('driver_id');
            })->count();

        $totalDeliveries = $activeCount + $completedCount;

        return view('driver.deliveries.delivery-history', compact(
            'activeDeliveries',
            'completedDeliveries',
            'activeCount',
            'completedCount',
            'totalDeliveries'
        ));
    }

    /**
     * Update delivery status (supports both AJAX and normal requests)
     * Driver ONLY handles: picked_up, in_transit, delivered, failed
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        // Allow Lalamove orders (driver_id = null) to be updated
        if ($delivery->driver_id !== null && $delivery->driver_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access.']);
            }
            abort(403, 'This delivery is not assigned to you.');
        }

        // Log the request for debugging
        \Log::info('Update Status Request', [
            'delivery_id' => $delivery->id,
            'request_status' => $request->status,
            'has_delivery_proof' => $request->hasFile('delivery_proof'),
            'has_payment_proof' => $request->hasFile('payment_proof'),
            'all_data' => $request->all()
        ]);

        $request->validate([
            'status' => 'required|in:picked_up,in_transit,delivered,failed',
            'notes' => 'nullable|string|max:500',
            'delivery_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $oldStatus = $delivery->status;
            $newStatus = $request->status;

            // Cannot update status if already delivered or failed
            if (in_array($delivery->status, ['delivered', 'failed'])) {
                $errorMessage = 'Cannot update status of a completed delivery.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMessage]);
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            // ========== VALIDATION FOR DELIVERED STATUS ==========
            if ($newStatus == 'delivered') {
                // Check if delivery proof is provided (either existing or new)
                $hasDeliveryProof = !empty($delivery->delivery_proof) || $request->hasFile('delivery_proof');
                $hasPaymentProof = !empty($delivery->payment_proof) || $request->hasFile('payment_proof');

                if (!$hasDeliveryProof) {
                    $errorMessage = 'Delivery proof photo is required when marking as delivered.';
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => $errorMessage]);
                    }
                    return redirect()->back()->with('error', $errorMessage)->withInput();
                }

                if (!$hasPaymentProof) {
                    $errorMessage = 'Payment proof photo is required when marking as delivered.';
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => $errorMessage]);
                    }
                    return redirect()->back()->with('error', $errorMessage)->withInput();
                }
            }

            // Handle status change timestamps
            if ($newStatus == 'picked_up' && !$delivery->picked_up_at) {
                $delivery->picked_up_at = now();
            }
            if ($newStatus == 'in_transit' && !$delivery->in_transit_at) {
                $delivery->in_transit_at = now();
            }
            if ($newStatus == 'delivered' && !$delivery->delivered_at) {
                $delivery->delivered_at = now();
            }

            // Handle proof of delivery images
            if ($request->hasFile('delivery_proof')) {
                // Delete old proof if exists
                if ($delivery->delivery_proof && Storage::disk('public')->exists($delivery->delivery_proof)) {
                    Storage::disk('public')->delete($delivery->delivery_proof);
                }
                $delivery->delivery_proof = $request->file('delivery_proof')->store('delivery-proofs', 'public');
                \Log::info('Delivery proof saved', ['path' => $delivery->delivery_proof]);
            }

            if ($request->hasFile('payment_proof')) {
                // Delete old proof if exists
                if ($delivery->payment_proof && Storage::disk('public')->exists($delivery->payment_proof)) {
                    Storage::disk('public')->delete($delivery->payment_proof);
                }
                $delivery->payment_proof = $request->file('payment_proof')->store('payment-proofs', 'public');
                \Log::info('Payment proof saved', ['path' => $delivery->payment_proof]);
            }

            // Update notes
            if ($request->filled('notes')) {
                $delivery->driver_notes = $request->notes;
            }

            // Update delivery status
            $delivery->status = $newStatus;
            $delivery->save();

            // Update order status based on delivery status
            if ($delivery->order) {
                $order = $delivery->order;

                // Auto-assign current driver if delivery is Staff and driver_id is NULL
                if ($delivery->driver_id === null) {
                    $cityLower = strtolower(trim($order->city ?? ''));
                    $isCalambaCity = $cityLower === 'calamba city' || $cityLower === 'calamba';

                    if ($isCalambaCity) {
                        $delivery->driver_id = Auth::id();
                        $delivery->save();
                    }
                }

                // Update order status based on delivery status
                if ($newStatus == 'picked_up') {
                    $order->order_status = 'out_for_delivery';
                    $order->out_for_delivery_at = now();
                } elseif ($newStatus == 'in_transit') {
                    $order->order_status = 'out_for_delivery';
                    // Keep as out_for_delivery during transit
                } elseif ($newStatus == 'delivered') {
                    $order->order_status = 'delivered';
                    $order->delivered_at = now();
                    
                    // ✅ NEW: Deduct inventory when order is delivered
                    $this->deductOrderInventory($order);
                } elseif ($newStatus == 'failed') {
                    $order->order_status = 'delivery_failed';
                }

                $order->save();
            }

            $message = 'Delivery status updated successfully to ' . ucfirst($newStatus) . '!';

            // For AJAX requests, return JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'new_status' => $newStatus,
                    'delivery' => $delivery->fresh()
                ]);
            }

            // For normal form submissions, redirect back with success message
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Delivery update error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Error updating delivery status: ' . $e->getMessage());
        }
    }

    /**
     * Deduct inventory when order is delivered
     */
    private function deductOrderInventory(Order $order)
    {
        $branchId = $order->branch_id;

        DB::beginTransaction();

        try {
            // Check if inventory already deducted for this order
            $alreadyDeducted = StockMovement::where('reference_type', 'order')
                ->where('reference_id', $order->id)
                ->where('movement_type', 'sale')
                ->exists();

            if ($alreadyDeducted) {
                \Log::info("Inventory already deducted for order #{$order->order_number}");
                return;
            }

            // Deduct reserved inventory for each item
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $branchId)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$inventory) {
                    \Log::warning("Inventory not found for product: {$item->product->name} in branch {$branchId}");
                    continue;
                }

                // Check if enough reserved quantity
                if ($inventory->reserved_quantity >= $item->quantity) {
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
                        'notes' => "Order #{$order->order_number} delivered by driver - stock deducted",
                        'created_by' => Auth::id(),
                    ]);

                    \Log::info("Stock deducted for product {$item->product_id} in order #{$order->order_number}");
                } else {
                    \Log::warning("Insufficient reserved quantity for product {$item->product_id}. Reserved: {$inventory->reserved_quantity}, Needed: {$item->quantity}");
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deducting inventory for order: ' . $e->getMessage());
            throw $e;
        }
    }
}