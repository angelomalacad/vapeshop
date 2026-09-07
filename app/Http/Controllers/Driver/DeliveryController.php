<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\StockMovement;
use App\Models\InventoryReservation;
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
            ->orderByRaw("FIELD(status, 'pending', 'assigned', 'picked_up', 'out_for_delivery')")
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
            'out_for_delivery' => Delivery::where('driver_id', $driverId)->where('status', 'out_for_delivery')->count(),
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
            ->whereIn('status', ['picked_up', 'out_for_delivery'])
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
            ->whereIn('order_status', ['out_for_delivery', 'picked_up'])
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
     * Display the driver's delivery history (Active & Completed & Cancelled/Failed)
     */
    public function deliveryHistory(Request $request)
    {
        $driverId = Auth::id();

        // ========== ACTIVE DELIVERIES (Staff + Lalamove) ==========
        $activeQuery = Delivery::whereNotIn('status', ['delivered', 'failed', 'cancelled'])
            ->where(function($query) use ($driverId) {
                $query->where('driver_id', $driverId)
                      ->orWhereNull('driver_id');
            })
            ->with(['order.items.product', 'order.branch']);

        // ========== COMPLETED DELIVERIES (Delivered) ==========
        $completedQuery = Delivery::where('status', 'delivered')
            ->where(function($query) use ($driverId) {
                $query->where('driver_id', $driverId)
                      ->orWhereNull('driver_id');
            })
            ->with(['order.items.product', 'order.branch']);

        // ========== CANCELLED/FAILED DELIVERIES ==========
        $cancelledQuery = Delivery::whereIn('status', ['cancelled', 'failed'])
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
            $cancelledQuery->whereHas('order', function($q) use ($search) {
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
                $cancelledQuery->whereHas('order', function($q) {
                    $q->where('city', '!=', 'Calamba')->where('city', '!=', 'Calamba City');
                });
            } elseif ($type === 'staff') {
                $activeQuery->whereHas('order', function($q) {
                    $q->where('city', 'Calamba')->orWhere('city', 'Calamba City');
                });
                $completedQuery->whereHas('order', function($q) {
                    $q->where('city', 'Calamba')->orWhere('city', 'Calamba City');
                });
                $cancelledQuery->whereHas('order', function($q) {
                    $q->where('city', 'Calamba')->orWhere('city', 'Calamba City');
                });
            }
        }

        // --- FILTER: Branch ---
        if ($request->filled('branch_id')) {
            $branchId = $request->branch_id;
            $activeQuery->whereHas('order', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
            $completedQuery->whereHas('order', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
            $cancelledQuery->whereHas('order', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        }

        // --- FILTER: Status ---
        if ($request->filled('status')) {
            $statusFilter = $request->status;
            
            // Map status filters to actual delivery statuses
            $statusMap = [
                'ready' => 'assigned',
                'picked_up' => 'picked_up',
                'out_for_delivery' => ['out_for_delivery', 'in_transit'],
                'delivered' => 'delivered',
                'delivery_failed' => ['delivery_failed', 'failed'],
                'cancelled' => 'cancelled',
                'failed' => 'failed',
            ];

            $deliveryStatuses = $statusMap[$statusFilter] ?? $statusFilter;
            
            if (is_array($deliveryStatuses)) {
                $activeQuery->whereIn('status', $deliveryStatuses);
            } else {
                $activeQuery->where('status', $deliveryStatuses);
            }
        }

        // --- FILTER: Date Range ---
        if ($request->filled('date_from')) {
            $activeQuery->whereDate('created_at', '>=', $request->date_from);
            $completedQuery->whereDate('created_at', '>=', $request->date_from);
            $cancelledQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $activeQuery->whereDate('created_at', '<=', $request->date_to);
            $completedQuery->whereDate('created_at', '<=', $request->date_to);
            $cancelledQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // ✅ FIX: Get counts BEFORE pagination (to avoid "0 active" when paginating)
        $activeCount = (clone $activeQuery)->count();
        $completedCount = (clone $completedQuery)->count();
        $cancelledCount = (clone $cancelledQuery)->count();

        // Fetch Active Deliveries (with filters applied)
        $activeDeliveries = $activeQuery->orderBy('assigned_at', 'desc')
            ->paginate(5, ['*'], 'active_page');

        // Fetch Completed Deliveries (with filters applied)
        $completedDeliveries = $completedQuery->orderBy('delivered_at', 'desc')
            ->paginate(5, ['*'], 'completed_page');

        // Fetch Cancelled/Failed Deliveries (with filters applied)
        $cancelledDeliveries = $cancelledQuery->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'cancelled_page');

        // ✅ FIX: Preserve filters in pagination links
        $activeDeliveries->appends($request->except(['active_page', 'completed_page', 'cancelled_page']));
        $completedDeliveries->appends($request->except(['active_page', 'completed_page', 'cancelled_page']));
        $cancelledDeliveries->appends($request->except(['active_page', 'completed_page', 'cancelled_page']));

        $totalDeliveries = Delivery::count();

        // ✅ NEW: Get all branches for filter dropdown
        $branches = Branch::orderBy('name')->get();

        return view('driver.deliveries.delivery-history', compact(
            'activeDeliveries',
            'completedDeliveries',
            'cancelledDeliveries',
            'activeCount',
            'completedCount',
            'cancelledCount',
            'totalDeliveries',
            'branches'
        ));
    }

    /**
     * Update delivery status (supports both AJAX and normal requests)
     * Driver ONLY handles: picked_up, out_for_delivery, delivered, failed
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
            'status' => 'required|in:picked_up,out_for_delivery,delivered,failed',
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
            if ($newStatus == 'out_for_delivery' && !$delivery->in_transit_at) {
                $delivery->in_transit_at = now();
            }
            if ($newStatus == 'delivered' && !$delivery->delivered_at) {
                $delivery->delivered_at = now();
            }
            if ($newStatus == 'failed' && !$delivery->failed_at) {
                $delivery->failed_at = now();
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

                // ✅ FIXED: Update order status based on delivery status
                switch ($newStatus) {
                    case 'picked_up':
                        $order->order_status = 'picked_up';
                        $order->out_for_delivery_at = now();
                        break;
                    case 'out_for_delivery':
                        $order->order_status = 'out_for_delivery';
                        break;
                    case 'delivered':
                        $order->order_status = 'delivered';
                        $order->delivered_at = now();
                        
                        // ✅ NEW: Deduct inventory when order is delivered
                        $this->deductOrderInventory($order);
                        break;
                    case 'failed':
                        $order->order_status = 'delivery_failed';
                        break;
                }

                $order->save();
                
                // Log the actual order status after update
                \Log::info('Order status after update: ' . $order->fresh()->order_status . ' for order #' . $order->order_number . ' (Delivery status: ' . $newStatus . ')');
            }

            $message = 'Delivery status updated successfully to ' . ucfirst(str_replace('_', ' ', $newStatus)) . '!';

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
     * ✅ NEW: Update Lalamove tracking info for a delivery
     */
    public function updateLalamoveTracking(Request $request, Delivery $delivery)
    {
        // Allow Lalamove orders (driver_id = null) to be updated
        if ($delivery->driver_id !== null && $delivery->driver_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'tracking_number' => 'required|url|max:255',
            'lalamove_driver_name' => 'nullable|string|max:255',
        ]);

        try {
            // ✅ Shorten the URL if it exceeds 255 characters
            $trackingUrl = $request->tracking_number;
            if (strlen($trackingUrl) > 255) {
                $trackingUrl = substr($trackingUrl, 0, 255);
            }

            // ✅ Update tracking number and driver name
            $delivery->update([
                'tracking_number' => $trackingUrl,
                'notes' => $request->lalamove_driver_name ?? $delivery->notes,
            ]);

            \Log::info('Lalamove tracking updated', [
                'delivery_id' => $delivery->id,
                'tracking_number' => $trackingUrl,
                'driver_name' => $request->lalamove_driver_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lalamove tracking info updated successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating Lalamove tracking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating tracking info: ' . $e->getMessage()
            ], 500);
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

            // ✅ RELEASE ALL ACTIVE RESERVATIONS FOR THIS ORDER
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

            // Deduct reserved inventory for each item
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $branchId)
                    ->where('product_id', $item->product_id)
                    ->when($item->flavor_id, function($query) use ($item) {
                        return $query->where('flavor_id', $item->flavor_id);
                    })
                    ->first();

                if (!$inventory) {
                    \Log::warning("Inventory not found for product: {$item->product->name} in branch {$branchId}");
                    continue;
                }

                $oldQuantity = $inventory->quantity;
                $newQuantity = max(0, $oldQuantity - $item->quantity);
                $oldReserved = $inventory->reserved_quantity;
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
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deducting inventory for order: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getModalData(Delivery $delivery)
{
    // ✅ LOAD ALL RELATED DATA
    $delivery->load([
        'order.items.product', 
        'order.branch', 
        'order.customer'
    ]);
    
    // ✅ ADD STOCK INFO TO EACH ITEM
    if ($delivery->order) {
        $order = $delivery->order;
        $branchId = $order->branch_id;
        
        foreach ($order->items as $item) {
            // Get branch inventory for this product + flavor
            $branchInventory = \App\Models\BranchInventory::where('branch_id', $branchId)
                ->where('product_id', $item->product_id)
                ->when($item->flavor_id, function($query) use ($item) {
                    return $query->where('flavor_id', $item->flavor_id);
                }, function($query) {
                    return $query->whereNull('flavor_id');
                })
                ->first();
            
            if ($branchInventory) {
                $item->stock_available = $branchInventory->available_quantity;
                $item->stock_reserved = $branchInventory->reserved_quantity ?? 0;
                $item->stock_total = $branchInventory->quantity;
                $item->low_stock_threshold = $branchInventory->low_stock_threshold ?? 10;
            } else {
                $item->stock_available = 0;
                $item->stock_reserved = 0;
                $item->stock_total = 0;
                $item->low_stock_threshold = 10;
            }
        }
    }
    
    return response()->json([
        'success' => true,
        'delivery' => $delivery
    ]);
}
}