<?php
// app/Http/Controllers/BranchAdmin/InventoryController.php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\StockTransfer;
use App\Models\StockMovement;
use App\Models\Branch;
use App\Models\WarehouseInventory;
use App\Models\InventoryReservation;
use App\Services\InventoryReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    protected $reservationService;

    public function __construct(InventoryReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Display branch inventory
     */
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        // Your existing query for my branch
        $query = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId);

        // Search filter
        if ($request->filled('search')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by flavor
        if ($request->filled('flavor_id')) {
            $query->where('flavor_id', $request->flavor_id);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->lowStock()->where('is_archived', false)->where('is_disposed', false);
            } elseif ($request->stock_status === 'out') {
                $query->outOfStock()->where('is_archived', false)->where('is_disposed', false);
            } elseif ($request->stock_status === 'archived') {
                $query->where('is_archived', true)->where('is_disposed', false);
            }
        } else {
            $query->where('is_archived', false)->where('is_disposed', false);
        }

        $inventories = $query->paginate(20);

        // Get all other branches
        $otherBranchesQuery = \App\Models\Branch::where('id', '!=', $branchId);

        if ($request->filled('branch_filter')) {
            $otherBranchesQuery->where('id', $request->branch_filter);
        }

        $otherBranches = $otherBranchesQuery->get();

        // Get all products for filter dropdown
        $allProducts = Product::where('is_active', true)->get();

        // Initialize array for branch inventories
        $branchInventories = [];

        foreach ($otherBranches as $branch) {
            // Get branch-specific page number
            $pageKey = 'branch_page_' . $branch->id;
            $page = $request->get($pageKey, 1);

            // Query for this branch's inventory
            $branchQuery = BranchInventory::with(['product', 'flavor', 'branch'])
                ->where('branch_id', $branch->id)
                ->where('is_archived', false)
                ->where('is_disposed', false)
                ->where('quantity', '>', 0);

            // Apply search filter
            if ($request->filled('search_other')) {
                $branchQuery->whereHas('product', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search_other . '%');
                });
            }

            // Apply product filter
            if ($request->filled('product_id_other')) {
                $branchQuery->where('product_id', $request->product_id_other);
            }

            // Get total count
            $total = $branchQuery->count();

            // Paginate with 5 items per page
            $branchItems = $branchQuery->paginate(5, ['*'], 'branch_page_' . $branch->id, $page);

            $branchInventories[$branch->id] = [
                'items' => $branchItems,
                'total' => $total
            ];
        }

        $products = Product::where('is_active', true)->get();
        $lowStockCount = BranchInventory::where('branch_id', $branchId)
            ->lowStock()
            ->where('is_archived', false)
            ->where('is_disposed', false)
            ->count();

        return view('branch-admin.inventory.index', compact(
            'inventories', 'products', 'lowStockCount', 'branchInventories', 'allProducts'
        ));
    }

    /**
 * Show stock movement history for the branch.
 * Can be filtered by product, flavor, type, and date range.
 */
public function stockHistory(Request $request)
{
    $branchId = Auth::user()->branch_id;

    // Build the query for StockMovement, eager loading relationships
    $query = StockMovement::with(['product', 'flavor', 'creator'])
        ->where('branch_id', $branchId);

    // Filter by product if provided in the request
    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    // Filter by flavor if provided in the request
    if ($request->filled('flavor_id')) {
        $query->where('flavor_id', $request->flavor_id);
    }

    // Filter by movement type (purchase, sale, receive, transfer_out, transfer_in, adjustment, etc.)
    if ($request->filled('type')) {
        $query->where('movement_type', $request->type);
    }

    // Filter by date range - from date
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    // Filter by date range - to date
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    // Get summary statistics for the filtered results
    $summary = [
        'total_in' => (clone $query)->where('quantity_change', '>', 0)->sum('quantity_change'),
        'total_out' => abs((clone $query)->where('quantity_change', '<', 0)->sum('quantity_change')),
        'total_movements' => (clone $query)->count(),
        'unique_products' => (clone $query)->distinct('product_id')->count('product_id'),
    ];

    // Order by most recent first and paginate
    $movements = $query->orderBy('created_at', 'desc')->paginate(20);

    // Get products for filter dropdown
    $products = \App\Models\Product::whereIn('id', function($subquery) use ($branchId) {
        $subquery->select('product_id')
            ->from('stock_movements')
            ->where('branch_id', $branchId)
            ->distinct();
    })->get();

    // Get flavors for filter dropdown
    $flavors = \App\Models\ProductFlavor::whereIn('id', function($subquery) use ($branchId) {
        $subquery->select('flavor_id')
            ->from('stock_movements')
            ->where('branch_id', $branchId)
            ->whereNotNull('flavor_id')
            ->distinct();
    })->get();

    // Get movement types for filter dropdown
    $movementTypes = StockMovement::where('branch_id', $branchId)
        ->distinct()
        ->pluck('movement_type');

    // Pass filter parameters to view for maintaining filter state
    return view('branch-admin.inventory.stock-history', compact(
        'movements',
        'products',
        'flavors',
        'summary',
        'movementTypes'
    ));
}

    /**
     * Show low stock items for this branch
     */
    public function lowStock()
    {
        $branchId = Auth::user()->branch_id;

        $items = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId)
            ->lowStock()
            ->get();

        return view('branch-admin.inventory.low-stock', compact('items'));
    }

    /**
     * Show form to quickly add stock to any existing product (with optional pre-selected product)
     */
    public function addProductForm(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        // Get existing inventory items
        $branchInventory = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId)
            ->orderBy('product_id')
            ->get();

        // Get products not yet in inventory (for adding new products)
        $existingProductIds = BranchInventory::where('branch_id', $branchId)
            ->pluck('product_id')
            ->toArray();

        $availableProducts = Product::with('flavors')
            ->where('is_active', true)
            ->whereNotIn('id', $existingProductIds)
            ->get();

        // Get pre-selected product ID from URL parameter
        $preSelectedProductId = $request->get('product_id');

        return view('branch-admin.inventory.add-stock-quick', compact('branchInventory', 'availableProducts', 'preSelectedProductId'));
    }

    /**
     * Display warehouse stock available for request
     */
    public function warehouseStock(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        // Get pending requests for this branch
        $pendingRequests = StockTransfer::with(['product', 'flavor'])
            ->where('to_branch_id', $branchId)
            ->where('transfer_type', 'warehouse_to_branch')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get completed requests (approved, completed, cancelled) for this branch
        $completedRequests = StockTransfer::with(['product', 'flavor'])
            ->where('to_branch_id', $branchId)
            ->where('transfer_type', 'warehouse_to_branch')
            ->whereIn('status', ['approved', 'completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Use paginate() NOT get()
        $warehouseQuery = WarehouseInventory::with(['product', 'flavor'])
            ->where('quantity', '>', 0);

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $warehouseQuery->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%');
            });
        }

        // Use paginate(10)
        $warehouseProducts = $warehouseQuery->paginate(10);

        // Preserve search query in pagination links
        if ($request->filled('search')) {
            $warehouseProducts->appends(['search' => $request->search]);
        }

        // Get all warehouse products for the modal dropdown (unpaginated)
        $allWarehouseQuery = WarehouseInventory::with(['product', 'flavor'])
            ->where('quantity', '>', 0);

        if ($request->filled('search')) {
            $allWarehouseQuery->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%');
            });
        }

        $allWarehouseProducts = $allWarehouseQuery->get();

        return view('branch-admin.warehouse.index', [
            'pendingRequests' => $pendingRequests,
            'completedRequests' => $completedRequests,
            'warehouseProducts' => $warehouseProducts,
            'allWarehouseProducts' => $allWarehouseProducts
        ]);
    }

    /**
     * Request stock from warehouse
     */
    public function requestWarehouseStock(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // Get warehouse inventory to check if product exists
        $query = WarehouseInventory::where('product_id', $request->product_id);

        if ($request->filled('flavor_id')) {
            $query->where('flavor_id', $request->flavor_id);
        } else {
            $query->whereNull('flavor_id');
        }

        $warehouseItem = $query->first();

        if (!$warehouseItem) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This product is not available in the warehouse.');
        }

        // Check if requested quantity is available
        if ($warehouseItem->quantity < $request->quantity) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Insufficient stock in warehouse. Available: ' . $warehouseItem->quantity);
        }

        DB::beginTransaction();

        try {
            // Generate transfer number
            $transferNumber = 'WH-' . strtoupper(uniqid());

            // Create transfer request
            $transfer = StockTransfer::create([
                'transfer_number' => $transferNumber,
                'transfer_type' => 'warehouse_to_branch',
                'from_branch_id' => null, // null indicates warehouse
                'to_branch_id' => $branchId,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'quantity' => $request->quantity,
                'status' => 'pending',
                'requested_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('branch-admin.warehouse.index')
                ->with('success', 'Stock request submitted successfully. Waiting for owner approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error requesting stock: ' . $e->getMessage());
        }
    }

    /**
 * Receive stock from warehouse (after owner approval)
 */
public function receiveWarehouseStock(Request $request, StockTransfer $transfer)
{
    $branchId = Auth::user()->branch_id;

    // Verify this transfer belongs to this branch
    if ($transfer->to_branch_id !== $branchId) {
        return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
    }

    if ($transfer->status !== 'approved') {
        return response()->json(['success' => false, 'message' => 'Only approved transfers can be received.'], 400);
    }

    if ($transfer->transfer_type !== 'warehouse_to_branch') {
        return response()->json(['success' => false, 'message' => 'Invalid transfer type.'], 400);
    }

    DB::beginTransaction();

    try {
        // Update warehouse inventory (deduct stock)
        $query = WarehouseInventory::where('product_id', $transfer->product_id);

        if ($transfer->flavor_id) {
            $query->where('flavor_id', $transfer->flavor_id);
        } else {
            $query->whereNull('flavor_id');
        }

        $warehouseItem = $query->first();

        if ($warehouseItem) {
            $warehouseItem->update([
                'quantity' => $warehouseItem->quantity - $transfer->quantity
            ]);
        }

        // Add to branch inventory
        $branchInventory = BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $transfer->product_id)
            ->when($transfer->flavor_id, function($query) use ($transfer) {
                return $query->where('flavor_id', $transfer->flavor_id);
            })
            ->first();

        if ($branchInventory) {
            $oldQuantity = $branchInventory->quantity;
            $newQuantity = $oldQuantity + $transfer->quantity;

            $branchInventory->update([
                'quantity' => $newQuantity,
                'last_restocked_at' => now(),
            ]);
        } else {
            $branchInventory = BranchInventory::create([
                'branch_id' => $branchId,
                'product_id' => $transfer->product_id,
                'flavor_id' => $transfer->flavor_id,
                'quantity' => $transfer->quantity,
                'reserved_quantity' => 0,
                'low_stock_threshold' => 10,
                'reorder_point' => 20,
                'optimal_stock' => 50,
                'last_restocked_at' => now(),
            ]);
            $oldQuantity = 0;
            $newQuantity = $transfer->quantity;
        }

        // ✅ LOG BRANCH MOVEMENT (Warehouse Receive)
        StockMovement::create([
            'branch_id' => $branchId,
            'product_id' => $transfer->product_id,
            'flavor_id' => $transfer->flavor_id,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'quantity_change' => $transfer->quantity,
            'movement_type' => 'warehouse_receive',
            'reference_type' => 'transfer',
            'reference_id' => $transfer->id,
            'notes' => 'Stock received from Main Warehouse. Transfer #: ' . $transfer->transfer_number,
            'created_by' => Auth::id(),
        ]);

        // Update transfer status
        $transfer->update([
            'status' => 'completed',
            'completed_at' => now(),
            'received_by' => Auth::id(),
            'received_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Stock received successfully and added to your inventory.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error receiving stock: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error receiving stock: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Show form to add product to inventory (for a specific product from catalog)
     */
    public function addToInventoryForm(Product $product)
    {
        // Check if product already in inventory
        $existingInventory = BranchInventory::where('branch_id', Auth::user()->branch_id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingInventory) {
            return redirect()->route('branch-admin.products.index')
                ->with('error', 'This product is already in your inventory.');
        }

        return view('branch-admin.inventory.add-to-inventory', compact('product'));
    }

    /**
     * Process adding product to inventory (from catalog)
     */
    public function addToInventory(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
        ]);

        // Check if already exists
        $exists = BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $request->product_id)
            ->where('flavor_id', $request->flavor_id)
            ->exists();

        if ($exists) {
            return redirect()->route('branch-admin.products.index')
                ->with('error', 'This product already exists in your inventory.');
        }

        BranchInventory::create([
            'branch_id' => $branchId,
            'product_id' => $request->product_id,
            'flavor_id' => $request->flavor_id,
            'quantity' => $request->quantity,
            'reserved_quantity' => 0,
            'low_stock_threshold' => $request->low_stock_threshold,
            'reorder_point' => 10,
            'optimal_stock' => 30,
            'last_restocked_at' => $request->quantity > 0 ? now() : null,
        ]);

        return redirect()->route('branch-admin.products.index')
            ->with('success', 'Product added to your branch inventory successfully!');
    }

    /**
     * Show inventory item details
     */
    public function show(BranchInventory $inventory)
    {
        // Ensure inventory belongs to user's branch
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $movements = StockMovement::where('branch_id', $inventory->branch_id)
            ->where('product_id', $inventory->product_id)
            ->when($inventory->flavor_id, function($query) use ($inventory) {
                return $query->where('flavor_id', $inventory->flavor_id);
            })
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('branch-admin.inventory.show', compact('inventory', 'movements'));
    }

    /**
     * Show form to add stock
     */
    public function addStockForm(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('branch-admin.inventory.add-stock', compact('inventory'));
    }

    /**
     * Add stock to inventory
     */
    public function addStock(Request $request, BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $oldQuantity + $request->quantity;

            $inventory->update([
                'quantity' => $newQuantity,
                'last_restocked_at' => now(),
            ]);

            // Log movement
            StockMovement::create([
                'branch_id' => $inventory->branch_id,
                'product_id' => $inventory->product_id,
                'flavor_id' => $inventory->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => $request->quantity,
                'movement_type' => 'purchase',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('branch-admin.inventory.show', $inventory)
                ->with('success', 'Stock added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error adding stock: ' . $e->getMessage());
        }
    }

    /**
     * Show form to adjust stock (remove damaged/expired)
     */
    public function adjustStockForm(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('branch-admin.inventory.adjust-stock', compact('inventory'));
    }

    /**
     * Adjust stock (remove damaged/expired items)
     */
    public function adjustStock(Request $request, BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
            'adjustment_type' => 'required|in:damaged,expired,return',
            'notes' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $oldQuantity - $request->quantity;

            $inventory->update(['quantity' => $newQuantity]);

            // Log movement
            StockMovement::create([
                'branch_id' => $inventory->branch_id,
                'product_id' => $inventory->product_id,
                'flavor_id' => $inventory->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => -$request->quantity,
                'movement_type' => $request->adjustment_type,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('branch-admin.inventory.show', $inventory)
                ->with('success', 'Stock adjusted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error adjusting stock: ' . $e->getMessage());
        }
    }

    /**
     * Show form to request stock transfer
     */
    public function transferForm(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $products = Product::with('flavors')->where('is_active', true)->get();

        // Get ALL OTHER branches (potential sources of stock)
        $sourceBranches = Branch::where('id', '!=', $branchId)->get();

        // Get current branch (destination)
        $currentBranch = Branch::where('id', $branchId)->first();

        $selectedProduct = null;
        $selectedFlavor = null;
        $maxQuantity = 0;

        if ($request->filled('inventory_id')) {
            $inventory = BranchInventory::with(['product', 'flavor'])
                ->where('branch_id', $branchId)
                ->findOrFail($request->inventory_id);

            $selectedProduct = $inventory->product;
            $selectedFlavor = $inventory->flavor;
            $maxQuantity = $inventory->available_quantity;
        }

        return view('branch-admin.inventory.transfer', compact(
            'products', 'sourceBranches', 'currentBranch',
            'selectedProduct', 'selectedFlavor', 'maxQuantity'
        ));
    }

    /**
     * Request stock transfer (for requesting branch) - FIXED
     */
    public function requestTransfer(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $request->validate([
            'from_branch_id' => 'required',
            'to_branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify that to_branch_id is the requesting branch (your branch)
        if ($request->to_branch_id != $branchId) {
            return response()->json([
                'success' => false,
                'message' => 'You can only request stock to your own branch.'
            ], 403);
        }

        // ✅ ADD THIS: Check for existing pending transfer for same product/flavor from same source
        $existingPending = StockTransfer::where('from_branch_id', $request->from_branch_id)
            ->where('to_branch_id', $request->to_branch_id)
            ->where('product_id', $request->product_id)
            ->when($request->filled('flavor_id'), function($query) use ($request) {
                return $query->where('flavor_id', $request->flavor_id);
            })
            ->where('status', 'pending')
            ->first();

        if ($existingPending) {
            return response()->json([
                'success' => false,
                'message' => "There is already a pending transfer request (#{$existingPending->transfer_number}) for this product from the same source branch. Please wait for it to be processed before requesting again."
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Check if requesting from Main Warehouse (value "0")
            if ($request->from_branch_id == '0') {
                // Check warehouse inventory
                $warehouseQuery = WarehouseInventory::where('product_id', $request->product_id);

                if ($request->filled('flavor_id')) {
                    $warehouseQuery->where('flavor_id', $request->flavor_id);
                } else {
                    $warehouseQuery->whereNull('flavor_id');
                }

                $warehouse = $warehouseQuery->first();

                if (!$warehouse) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This product is not available in the warehouse.'
                    ], 400);
                }

                if ($warehouse->quantity < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock at warehouse. Available: {$warehouse->quantity}, Requested: {$request->quantity}"
                    ], 400);
                }

                // Create transfer request from warehouse
                $transfer = StockTransfer::create([
                    'transfer_type' => 'warehouse_to_branch',
                    'from_branch_id' => null,
                    'to_branch_id' => $request->to_branch_id,
                    'product_id' => $request->product_id,
                    'flavor_id' => $request->flavor_id,
                    'quantity' => $request->quantity,
                    'status' => 'pending',
                    'requested_by' => Auth::id(),
                    'transfer_number' => 'WH-REQ-' . date('Ymd') . '-' . rand(1000, 9999),
                    'notes' => $request->notes,
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Stock request sent to owner for approval!'
                ]);

            } else {
                // Check branch inventory with lock for update
                $sourceInventory = BranchInventory::where('branch_id', $request->from_branch_id)
                    ->where('product_id', $request->product_id)
                    ->when($request->filled('flavor_id'), function($query) use ($request) {
                        return $query->where('flavor_id', $request->flavor_id);
                    })
                    ->lockForUpdate()
                    ->first();

                if (!$sourceInventory) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This product is not available in the selected source branch.'
                    ], 400);
                }

                // Check if enough stock is available (available_quantity = quantity - reserved_quantity)
                if ($sourceInventory->available_quantity < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock at source branch. Available: {$sourceInventory->available_quantity}, Requested: {$request->quantity}"
                    ], 400);
                }

                // Create transfer request for branch-to-branch
                $transfer = StockTransfer::create([
                    'transfer_type' => 'branch_to_branch',
                    'from_branch_id' => $request->from_branch_id,
                    'to_branch_id' => $request->to_branch_id,
                    'product_id' => $request->product_id,
                    'flavor_id' => $request->flavor_id,
                    'quantity' => $request->quantity,
                    'status' => 'pending',
                    'requested_by' => Auth::id(),
                    'transfer_number' => 'TRF-' . date('Ymd') . '-' . rand(1000, 9999),
                    'notes' => $request->notes,
                ]);

                // Use reservation service to reserve stock
                $reservationResult = $this->reservationService->reserveForTransfer(
                    $sourceInventory,
                    $request->quantity,
                    $transfer->id
                );

                if (!$reservationResult['success']) {
                    throw new \Exception($reservationResult['message']);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Transfer request submitted successfully. Waiting for source branch approval.'
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error requesting transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View Transfer
     */
    public function transfers(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $activeTab = $request->get('tab', 'all');
        $filter = $request->get('filter', 'all');
        $statusFilter = $request->get('status', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $query = StockTransfer::with([
                'fromBranch',
                'toBranch',
                'product',
                'flavor',
                'requestedBy',
                'approvedBy'
            ]);

        // 1. Apply Tab Filter (All / Warehouse / Branch)
        if ($activeTab == 'warehouse') {
            $query->whereNull('from_branch_id')
                  ->where('to_branch_id', $branchId);
        } elseif ($activeTab == 'branch') {
            $query->whereNotNull('from_branch_id')
                  ->where('transfer_type', 'branch_to_branch');

            if ($filter == 'incoming') {
                $query->where('to_branch_id', $branchId);
            } elseif ($filter == 'outgoing') {
                $query->where('from_branch_id', $branchId);
            } else {
                $query->where(function($q) use ($branchId) {
                    $q->where('to_branch_id', $branchId)
                      ->orWhere('from_branch_id', $branchId);
                });
            }
        } else {
            // All Transfers Tab - Show everything related to this branch
            $query->where(function($q) use ($branchId) {
                $q->where('to_branch_id', $branchId)
                  ->orWhere('from_branch_id', $branchId);
            });
        }

        // Apply Status Filter (if provided)
        if (!empty($statusFilter) && in_array($statusFilter, ['pending', 'approved', 'completed', 'cancelled'])) {
            $query->where('status', $statusFilter);
        }

        // Apply Date Range Filters
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $transfers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('branch-admin.inventory.transfers-list', compact('transfers', 'activeTab', 'filter'));
    }

    // ===== MODAL CONTENT METHODS =====

    /**
     * Return only the edit form (modal content) - no layout
     */
    public function editModal(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('branch-admin.inventory.modals.edit', compact('inventory'));
    }

    /**
     * Return only the add stock form (modal content) - no layout
     */
    public function addStockModal(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        return view('branch-admin.inventory.modals.add-stock', compact('inventory'));
    }

    /**
     * Return only the transfer form (modal content) - no layout
     */
    public function transferModal(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $products = Product::with('flavors')->where('is_active', true)->get();
        $sourceBranches = Branch::where('id', '!=', $branchId)->get();
        $currentBranch = Branch::where('id', $branchId)->first();

        $selectedProduct = null;
        $selectedFlavor = null;
        $maxQuantity = 0;
        $preSelectedFromBranch = null;

        // Check if we have an inventory_id from another branch
        if ($request->filled('inventory_id')) {
            // Don't filter by current branch - we need to get inventory from ANY branch
            $inventory = BranchInventory::with(['product', 'flavor'])
                ->findOrFail($request->inventory_id);

            $selectedProduct = $inventory->product;
            $selectedFlavor = $inventory->flavor;
            $maxQuantity = $inventory->available_quantity;
            $preSelectedFromBranch = $inventory->branch_id;
        }

        // Get from_branch parameter from request (if provided directly)
        if ($request->filled('from_branch')) {
            $preSelectedFromBranch = $request->from_branch;
        }

        $preSelectedProductId = $request->get('product_id');
        $preSelectedFlavorId = $request->get('flavor_id');

        return view('branch-admin.inventory.modals.transfer', compact(
            'products', 'sourceBranches', 'currentBranch',
            'selectedProduct', 'selectedFlavor', 'maxQuantity',
            'preSelectedFromBranch', 'preSelectedProductId', 'preSelectedFlavorId'
        ));
    }

    /**
     * Return only the show modal content (no layout)
     */
    public function showModal(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $movements = StockMovement::where('branch_id', $inventory->branch_id)
            ->where('product_id', $inventory->product_id)
            ->when($inventory->flavor_id, function($query) use ($inventory) {
                return $query->where('flavor_id', $inventory->flavor_id);
            })
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('branch-admin.inventory.modals.show', compact('inventory', 'movements'));
    }

    /**
     * Check product availability in a branch (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        $branchId = $request->branch_id;
        $productId = $request->product_id;
        $flavorId = $request->flavor_id;

        \Log::info('checkAvailability - branch_id=' . $branchId . ', product_id=' . $productId . ', flavor_id=' . $flavorId);

        if (!$branchId || !$productId) {
            return response()->json(['success' => false, 'message' => 'Missing parameters', 'available' => 0]);
        }

        $query = BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $productId);

        if ($flavorId) {
            $query->where('flavor_id', $flavorId);
        } else {
            $query->whereNull('flavor_id');
        }

        $inventory = $query->first();
        $available = $inventory ? $inventory->available_quantity : 0;

        \Log::info('checkAvailability result: available=' . $available);

        return response()->json([
            'success'   => true,
            'available' => $available,
            'message'   => $available > 0 ? 'Stock available' : 'Out of stock'
        ]);
    }

    /**
     * Check product availability in the main warehouse (AJAX)
     */
    public function checkWarehouseAvailability(Request $request)
    {
        $productId = $request->product_id;
        $flavorId  = $request->flavor_id;

        if (!$productId) {
            return response()->json(['success' => false, 'message' => 'Missing product ID', 'available' => 0]);
        }

        $query = WarehouseInventory::where('product_id', $productId);

        // Handle 'no_flavor' special case
        if ($flavorId === 'no_flavor') {
            $query->whereNull('flavor_id');
        } elseif ($flavorId) {
            $query->where('flavor_id', $flavorId);
        } else {
            $query->whereNull('flavor_id');
        }

        $item = $query->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Product not found in warehouse', 'available' => 0]);
        }

        return response()->json([
            'success'   => true,
            'available' => $item->quantity,
            'message'   => $item->quantity > 0 ? 'Stock available' : 'Out of stock'
        ]);
    }

    /**
     * Get flavors for a product that have stock > 0 in the given source
     */
    public function getFlavors($productId, Request $request)
    {
        $branchId = $request->query('branch_id');
        $product = Product::with('flavors')->find($productId);

        if (!$product) {
            return response()->json([]);
        }

        $flavors = $product->flavors;

        // Get flavor IDs with stock > 0
        if ($branchId == '0') {
            // Warehouse - Check if product has flavors in warehouse
            $flavorIdsWithStock = DB::table('warehouse_inventories')
                ->where('product_id', $productId)
                ->where('quantity', '>', 0)
                ->whereNotNull('flavor_id')
                ->pluck('flavor_id')
                ->toArray();

            // If no flavors with stock, check if product exists in warehouse without flavor
            if (empty($flavorIdsWithStock)) {
                $hasNoFlavorStock = DB::table('warehouse_inventories')
                    ->where('product_id', $productId)
                    ->whereNull('flavor_id')
                    ->where('quantity', '>', 0)
                    ->exists();

                // If product exists without flavor, we need to handle it
                if ($hasNoFlavorStock) {
                    // Check if product has flavors in the product_flavors table
                    if ($flavors->isEmpty()) {
                        // Product has no flavors, return a special entry
                        return response()->json([
                            ['id' => 'no_flavor', 'name' => 'No Flavor']
                        ]);
                    } else {
                        // Product has flavors but warehouse has no flavor-specific stock
                        // Return all flavors with quantity 0 or just the no_flavor option
                        return response()->json($flavors->map(function($flavor) {
                            return ['id' => $flavor->id, 'name' => $flavor->name];
                        }));
                    }
                }
            }
        } else {
            // Branch
            $flavorIdsWithStock = BranchInventory::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->where('quantity', '>', 0)
                ->pluck('flavor_id')
                ->toArray();
        }

        \Log::info('getFlavors - product_id=' . $productId . ', branch_id=' . $branchId . ', flavorIdsWithStock=' . json_encode($flavorIdsWithStock));

        // If no flavors have stock, return all flavors or empty
        if (empty($flavorIdsWithStock)) {
            // For warehouse, if product has no flavor stock but exists, return all flavors
            if ($branchId == '0') {
                $productExists = DB::table('warehouse_inventories')
                    ->where('product_id', $productId)
                    ->where('quantity', '>', 0)
                    ->exists();

                if ($productExists && $flavors->isNotEmpty()) {
                    return response()->json($flavors->map(function($flavor) {
                        return ['id' => $flavor->id, 'name' => $flavor->name];
                    }));
                }
            }
            return response()->json([]);
        }

        // Only keep flavors that have stock
        $flavors = $flavors->filter(function($flavor) use ($flavorIdsWithStock) {
            return in_array($flavor->id, $flavorIdsWithStock);
        })->values();

        return response()->json($flavors->map(function($flavor) {
            return ['id' => $flavor->id, 'name' => $flavor->name];
        }));
    }

    /**
     * Get warehouse products - WORKING VERSION
     */
    public function getWarehouseProducts(Request $request)
    {
        $branchId = $request->branch_id;

        // If branch_id is 0 (warehouse), get all warehouse products
        if ($branchId == '0') {
            $products = DB::table('warehouse_inventories')
                ->join('products', 'warehouse_inventories.product_id', '=', 'products.id')
                ->where('warehouse_inventories.quantity', '>', 0)
                ->where('products.is_active', 1)
                ->select('products.id', 'products.name')
                ->distinct()
                ->get();

            return response()->json($products);
        } else {
            // Branch - get products with stock > 0
            $items = BranchInventory::with('product')
                ->whereHas('product', function($q) {
                    $q->where('is_active', 1);
                })
                ->where('branch_id', $branchId)
                ->where('quantity', '>', 0)
                ->get();

            $result = [];
            $seen = [];
            foreach ($items as $item) {
                if ($item->product && !in_array($item->product_id, $seen)) {
                    $seen[] = $item->product_id;
                    $result[] = [
                        'id'   => $item->product_id,
                        'name' => $item->product->name,
                    ];
                }
            }
            return response()->json($result);
        }
    }

    // ===== END MODAL CONTENT METHODS =====

    /**
     * Approve a transfer (for SOURCE branch - the one sending stock)
     */
    public function approveTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        if ($transfer->from_branch_id !== $branchId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only the source branch can approve transfers.'
            ], 403);
        }

        if ($transfer->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending transfers can be approved.'
            ], 400);
        }

        // ✅ ADD THIS: Check if there's enough available stock
        $sourceInventory = BranchInventory::where('branch_id', $transfer->from_branch_id)
            ->where('product_id', $transfer->product_id)
            ->when($transfer->flavor_id, function($query) use ($transfer) {
                return $query->where('flavor_id', $transfer->flavor_id);
            })
            ->lockForUpdate()
            ->first();

        if (!$sourceInventory) {
            return response()->json([
                'success' => false,
                'message' => 'Source inventory not found.'
            ], 400);
        }

        if ($sourceInventory->available_quantity < $transfer->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient available stock. Available: {$sourceInventory->available_quantity}, Requested: {$transfer->quantity}"
            ], 400);
        }

        DB::beginTransaction();

        try {
            $transfer->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transfer approved successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error approving transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a transfer (for SOURCE branch - the one sending stock)
     */
    public function rejectTransfer(Request $request, StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        if ($transfer->from_branch_id !== $branchId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only the source branch can reject transfers.'
            ], 403);
        }

        if ($transfer->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending transfers can be rejected.'
            ], 400);
        }

        // ✅ ADD THIS: Validate rejection reason
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

    try {
        // Release all active reservations for this transfer
        $reservations = InventoryReservation::where('stock_transfer_id', $transfer->id)
            ->where('status', 'active')
            ->get();

        foreach ($reservations as $reservation) {
            $this->reservationService->releaseReservation($reservation->id);
        }

        $transfer->update([
            'status' => 'cancelled',
            'rejection_reason' => $request->rejection_reason,  // ✅ Save to dedicated column
            'rejected_by' => Auth::id(),
            'rejected_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transfer rejected successfully.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error rejecting transfer: ' . $e->getMessage()
        ], 500);
    }
}

    /**
 * Complete a transfer (for DESTINATION branch - the one receiving stock)
 */
public function completeTransfer(StockTransfer $transfer)
{
    $branchId = Auth::user()->branch_id;

    if ($transfer->to_branch_id !== $branchId) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access. Only the destination branch can complete transfers.'
        ], 403);
    }

    if ($transfer->status !== 'approved') {
        return response()->json([
            'success' => false,
            'message' => 'Only approved transfers can be completed.'
        ], 400);
    }

    DB::beginTransaction();

    try {
        // Release all active reservations for this transfer
        $reservations = InventoryReservation::where('stock_transfer_id', $transfer->id)
            ->where('status', 'active')
            ->get();

        foreach ($reservations as $reservation) {
            $this->reservationService->releaseReservation($reservation->id);
        }

        // --- SOURCE BRANCH INVENTORY (Deduct stock) ---
        if ($transfer->from_branch_id && $transfer->transfer_type != 'warehouse_to_branch') {
            $sourceInventory = BranchInventory::where('branch_id', $transfer->from_branch_id)
                ->where('product_id', $transfer->product_id)
                ->when($transfer->flavor_id, function($query) use ($transfer) {
                    return $query->where('flavor_id', $transfer->flavor_id);
                })
                ->first();

            if ($sourceInventory) {
                $oldSourceQty = $sourceInventory->quantity;
                $newSourceQty = max(0, $sourceInventory->quantity - $transfer->quantity);

                $sourceInventory->update([
                    'reserved_quantity' => max(0, $sourceInventory->reserved_quantity - $transfer->quantity),
                    'quantity' => $newSourceQty,
                ]);

                // ✅ LOG SOURCE BRANCH MOVEMENT (Transfer Out)
                StockMovement::create([
                    'branch_id' => $transfer->from_branch_id,
                    'product_id' => $transfer->product_id,
                    'flavor_id' => $transfer->flavor_id,
                    'previous_quantity' => $oldSourceQty,
                    'new_quantity' => $newSourceQty,
                    'quantity_change' => -$transfer->quantity,
                    'movement_type' => 'transfer_out',
                    'reference_type' => 'transfer',
                    'reference_id' => $transfer->id,
                    'notes' => 'Transfer to ' . ($transfer->toBranch ? $transfer->toBranch->name : 'Branch'),
                    'created_by' => Auth::id(),
                ]);
            }
        }

        // --- DESTINATION BRANCH INVENTORY (Add stock) ---
        $destInventory = BranchInventory::where('branch_id', $transfer->to_branch_id)
            ->where('product_id', $transfer->product_id)
            ->when($transfer->flavor_id, function($query) use ($transfer) {
                return $query->where('flavor_id', $transfer->flavor_id);
            })
            ->first();

        $oldDestQuantity = $destInventory ? $destInventory->quantity : 0;
        $newDestQuantity = $oldDestQuantity + $transfer->quantity;

        if ($destInventory) {
            $destInventory->update([
                'quantity' => $newDestQuantity,
                'last_restocked_at' => now(),
            ]);
        } else {
            $destInventory = BranchInventory::create([
                'branch_id' => $transfer->to_branch_id,
                'product_id' => $transfer->product_id,
                'flavor_id' => $transfer->flavor_id,
                'quantity' => $transfer->quantity,
                'reserved_quantity' => 0,
                'low_stock_threshold' => 10,
                'reorder_point' => 20,
                'optimal_stock' => 50,
                'last_restocked_at' => now(),
            ]);
        }

        // ✅ LOG DESTINATION BRANCH MOVEMENT (Transfer In)
        $fromSourceName = ($transfer->transfer_type == 'warehouse_to_branch') ? 'Main Warehouse' : ($transfer->fromBranch ? $transfer->fromBranch->name : 'Unknown');

        StockMovement::create([
            'branch_id' => $transfer->to_branch_id,
            'product_id' => $transfer->product_id,
            'flavor_id' => $transfer->flavor_id,
            'previous_quantity' => $oldDestQuantity,
            'new_quantity' => $newDestQuantity,
            'quantity_change' => $transfer->quantity,
            'movement_type' => 'transfer_in',
            'reference_type' => 'transfer',
            'reference_id' => $transfer->id,
            'notes' => 'Transfer from ' . $fromSourceName,
            'created_by' => Auth::id(),
        ]);

        // Update transfer status
        $transfer->update([
            'status' => 'completed',
            'completed_at' => now(),
            'received_by' => Auth::id(),
            'received_at' => now(),
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transfer completed successfully. Stock has been added to your inventory.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error completing transfer: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error completing transfer: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Cancel transfer (for the requester)
     */
    public function cancelTransfer(StockTransfer $transfer)
{
    $branchId = Auth::user()->branch_id;

    $isRequester = ($transfer->requested_by == Auth::user()->id);
    $isSourceBranch = ($transfer->from_branch_id == $branchId);

    if (!$isRequester && !$isSourceBranch) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized access.'
        ], 403);
    }

    if ($transfer->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Only pending transfers can be cancelled.'
        ], 400);
    }

    DB::beginTransaction();

    try {
        // Release all active reservations
        $reservations = InventoryReservation::where('stock_transfer_id', $transfer->id)
            ->where('status', 'active')
            ->get();

        foreach ($reservations as $reservation) {
            $this->reservationService->releaseReservation($reservation->id);
        }

        $transfer->update([
            'status' => 'cancelled',
            'cancelled_by' => Auth::id(), // ✅ ADD THIS
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Transfer cancelled successfully.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error cancelling transfer: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified inventory item.
     */
    public function destroy(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($inventory->quantity > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete inventory item with remaining stock. Please adjust stock to zero first.');
        }

        $inventory->delete();

        return redirect()->route('branch-admin.inventory.index')
            ->with('success', 'Inventory item removed successfully.');
    }

    /**
     * Show form to quickly add stock to any existing product
     */
    public function quickAddStockForm(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $branchInventory = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId)
            ->orderBy('product_id')
            ->get();

        return view('branch-admin.inventory.quick-add-stock', compact('branchInventory'));
    }

    /**
     * Process quick add stock
     */
    public function quickAddStock(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:branch_inventories,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $inventory = BranchInventory::findOrFail($request->inventory_id);

        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $oldQuantity + $request->quantity;

            $inventory->update([
                'quantity' => $newQuantity,
                'last_restocked_at' => now(),
            ]);

            StockMovement::create([
                'branch_id' => $inventory->branch_id,
                'product_id' => $inventory->product_id,
                'flavor_id' => $inventory->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => $request->quantity,
                'movement_type' => 'purchase',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('branch-admin.inventory.index')
                ->with('success', "Added {$request->quantity} units to {$inventory->product->name}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding stock: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified inventory item.
     */
    public function edit(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $inventory->load(['product', 'flavor']);

        return view('branch-admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'optimal_stock' => 'required|integer|min:1',
            'last_purchase_price' => 'nullable|numeric|min:0',
            'last_restocked_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $request->quantity ?? $inventory->quantity;

            $inventory->update([
                'quantity' => $newQuantity,
                'low_stock_threshold' => $request->low_stock_threshold,
                'optimal_stock' => $request->optimal_stock,
                'last_purchase_price' => $request->last_purchase_price,
                'last_restocked_at' => $request->last_restocked_at ? Carbon::parse($request->last_restocked_at) : $inventory->last_restocked_at,
            ]);

            if ($oldQuantity != $newQuantity) {
                StockMovement::create([
                    'branch_id' => $inventory->branch_id,
                    'product_id' => $inventory->product_id,
                    'flavor_id' => $inventory->flavor_id,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => $newQuantity - $oldQuantity,
                    'movement_type' => 'adjustment',
                    'notes' => 'Manual adjustment via edit form',
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory updated successfully!',
                'inventory' => $inventory->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Archive an inventory item (soft hide from active lists)
     */
    public function archive(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $inventory->update(['is_archived' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Item archived successfully.'
        ]);
    }

    /**
     * Restore an archived inventory item
     */
    public function unarchive(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $inventory->update(['is_archived' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Item restored from archive.'
        ]);
    }

    /**
     * Dispose an inventory item (permanently remove to disposed items)
     */
    public function dispose(Request $request, BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        try {
            $inventory->update([
                'is_disposed' => true,
                'dispose_reason' => $request->dispose_reason,
                'disposed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item disposed successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error disposing item: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error disposing item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore a disposed inventory item
     */
    public function restoreDisposed(BranchInventory $inventory)
    {
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        try {
            $inventory->update([
                'is_disposed' => false,
                'dispose_reason' => null,
                'disposed_at' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Item restored from disposed items.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error restoring item: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error restoring item: ' . $e->getMessage()
            ], 500);
        }
    }
 /**
 * Get transfer details for the "More" button
 */
public function getTransferDetails(StockTransfer $transfer)
{
    // Load ALL relationships including rejectedBy and cancelledBy
    $transfer->load([
        'fromBranch',
        'toBranch',
        'product',
        'flavor',
        'requestedBy',
        'approvedBy',
        'receivedBy',
        'rejectedBy',    // ✅ Make sure this is loaded
        'cancelledBy'    // ✅ Make sure this is loaded
    ]);

    // Debug: Log to see if values exist
    \Log::info('Transfer Details Debug:', [
        'transfer_id' => $transfer->id,
        'rejected_by_value' => $transfer->rejected_by,
        'rejected_by_relation' => $transfer->rejectedBy ? $transfer->rejectedBy->name : 'NULL',
        'cancelled_by_value' => $transfer->cancelled_by,
        'cancelled_by_relation' => $transfer->cancelledBy ? $transfer->cancelledBy->name : 'NULL',
    ]);

    return response()->json([
        'success' => true,
        'transfer' => [
            'id' => $transfer->id,
            'transfer_number' => $transfer->transfer_number,
            'status' => $transfer->status,
            'from_branch' => $transfer->fromBranch ? $transfer->fromBranch->name : 'Main Warehouse',
            'to_branch' => $transfer->toBranch ? $transfer->toBranch->name : 'N/A',
            'product_name' => $transfer->product ? $transfer->product->name : 'N/A',
            'flavor_name' => $transfer->flavor ? $transfer->flavor->name : 'N/A',
            'quantity' => $transfer->quantity,
            'requested_by' => $transfer->requestedBy ? $transfer->requestedBy->name : 'System',
            'created_at' => $transfer->created_at->format('M d, Y h:i A'),
            'approved_by' => $transfer->approvedBy ? $transfer->approvedBy->name : 'N/A',
            'approved_at' => $transfer->approved_at ? \Carbon\Carbon::parse($transfer->approved_at)->format('M d, Y h:i A') : 'N/A',
            'received_by' => $transfer->receivedBy ? $transfer->receivedBy->name : 'N/A',
            'received_at' => $transfer->received_at ? \Carbon\Carbon::parse($transfer->received_at)->format('M d, Y h:i A') : 'N/A',
            'rejection_reason' => $transfer->rejection_reason ?? null,
            'rejected_by' => $transfer->rejectedBy ? $transfer->rejectedBy->name : null,
            'cancelled_by' => $transfer->cancelledBy ? $transfer->cancelledBy->name : null,
            'notes' => $transfer->notes ?? null,
        ]
    ]);
}

}
