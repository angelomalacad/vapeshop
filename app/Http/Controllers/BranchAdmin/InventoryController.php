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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display branch inventory
     */
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;

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

        // Filter by stock status (including archived)
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->lowStock()->where('is_archived', false);
            } elseif ($request->stock_status === 'out') {
                $query->outOfStock()->where('is_archived', false);
            } elseif ($request->stock_status === 'archived') {
                $query->where('is_archived', true);
            }
        } else {
            // Default: exclude archived items unless explicitly requested
            $query->where('is_archived', false);
        }

        $inventories = $query->paginate(20);

        $products = Product::where('is_active', true)->get();
        $lowStockCount = BranchInventory::where('branch_id', $branchId)->lowStock()->where('is_archived', false)->count();

        return view('branch-admin.inventory.index', compact(
            'inventories', 'products', 'lowStockCount'
        ));
    }

    /**
     * Show stock movement history for the branch.
     * Can be filtered by product and flavor.
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

        // Order by most recent first and paginate
        $movements = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('branch-admin.inventory.stock-history', compact('movements'));
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
    // DEBUG: Log the start of method
    \Log::info('=== warehouseStock method called ===');

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

    // FIXED: Use paginate() NOT get()
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

    // IMPORTANT: Use paginate(10)
    $warehouseProducts = $warehouseQuery->paginate(10);

    // DEBUG: Log the type of $warehouseProducts
    \Log::info('$warehouseProducts type: ' . get_class($warehouseProducts));
    \Log::info('$warehouseProducts total: ' . ($warehouseProducts->total() ?? 'N/A'));
    \Log::info('$warehouseProducts count: ' . $warehouseProducts->count());

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
    public function receiveWarehouseStock(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        // Verify this transfer belongs to this branch
        if ($transfer->to_branch_id !== $branchId) {
            abort(403, 'Unauthorized access.');
        }

        if ($transfer->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved transfers can be received.');
        }

        if ($transfer->transfer_type !== 'warehouse_to_branch') {
            return redirect()->back()->with('error', 'Invalid transfer type.');
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
                // Create new branch inventory if it doesn't exist
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

            // Log stock movement for branch
            StockMovement::create([
                'branch_id' => $branchId,
                'product_id' => $transfer->product_id,
                'flavor_id' => $transfer->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => $transfer->quantity,
                'movement_type' => 'warehouse_transfer_in',
                'reference_type' => 'transfer',
                'reference_id' => $transfer->id,
                'notes' => 'Stock received from warehouse. Transfer #: ' . $transfer->transfer_number,
                'created_by' => Auth::id(),
            ]);

            // Update transfer status
            $transfer->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('branch-admin.warehouse.index')
                ->with('success', 'Stock received successfully and added to your inventory.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error receiving stock: ' . $e->getMessage());
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
     * Request stock transfer (for requesting branch)
     */
    public function requestTransfer(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $request->validate([
            'from_branch_id' => 'required|exists:branches,id|different:' . $branchId,
            'to_branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // Verify that to_branch_id is the requesting branch (your branch)
        if ($request->to_branch_id != $branchId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You can only request stock to your own branch.');
        }

        // Build the query to find inventory in source branch
        $query = BranchInventory::where('branch_id', $request->from_branch_id)
            ->where('product_id', $request->product_id);

        if ($request->filled('flavor_id')) {
            $query->where('flavor_id', $request->flavor_id);
        }

        $sourceInventory = $query->first();

        // Check if product exists in source branch
        if (!$sourceInventory) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This product is not available in the selected source branch.');
        }

        // Check if enough stock is available
        if ($sourceInventory->available_quantity < $request->quantity) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Insufficient stock at source branch. Available: {$sourceInventory->available_quantity}, Requested: {$request->quantity}");
        }

        DB::beginTransaction();

        try {
            // Create transfer request
            $transfer = StockTransfer::create([
                'transfer_type' => 'branch_to_branch',
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id' => $request->to_branch_id,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'quantity' => $request->quantity,
                'status' => 'pending',
                'requested_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            // Reserve stock at source branch
            $sourceInventory->update([
                'reserved_quantity' => $sourceInventory->reserved_quantity + $request->quantity
            ]);

            DB::commit();

            return redirect()->route('branch-admin.inventory.transfers')
                ->with('success', 'Transfer request submitted successfully. Waiting for source branch approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error requesting transfer: ' . $e->getMessage());
        }
    }

    /**
     * View transfer requests
     */
    public function transfers(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $query = StockTransfer::with([
                'fromBranch',
                'toBranch',
                'product',
                'flavor',
                'requestedBy',
                'approvedBy'
            ])
            ->where(function($q) use ($branchId) {
                $q->where('from_branch_id', $branchId)
                  ->orWhere('to_branch_id', $branchId);
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type (incoming/outgoing)
        if ($request->filled('filter')) {
            if ($request->filter === 'incoming') {
                $query->where('to_branch_id', $branchId);
            } elseif ($request->filter === 'outgoing') {
                $query->where('from_branch_id', $branchId);
            }
        }

        $transfers = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('branch-admin.inventory.transfers-list', compact('transfers'));
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

        if ($request->filled('inventory_id')) {
            $inventory = BranchInventory::with(['product', 'flavor'])
                ->where('branch_id', $branchId)
                ->findOrFail($request->inventory_id);

            $selectedProduct = $inventory->product;
            $selectedFlavor = $inventory->flavor;
            $maxQuantity = $inventory->available_quantity;
        }

        $preSelectedFromBranch = $request->get('from_branch');
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
     * Check product availability in a branch for transfer (AJAX)
     * SINGLE COPY - NO DUPLICATE
     */
    public function checkAvailability(Request $request)
    {
        $branchId = $request->branch_id;
        $productId = $request->product_id;
        $flavorId = $request->flavor_id;

        if (!$branchId || !$productId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required parameters',
                'available' => 0
            ]);
        }

        $query = BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $productId);

        if ($flavorId) {
            $query->where('flavor_id', $flavorId);
        }

        $inventory = $query->first();

        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in this branch',
                'available' => 0
            ]);
        }

        $available = $inventory->available_quantity;

        return response()->json([
            'success' => true,
            'available' => $available,
            'message' => $available > 0 ? 'Stock available' : 'Out of stock'
        ]);
    }

    // ===== END MODAL CONTENT METHODS =====

    /**
     * Approve a transfer (for SOURCE branch - the one sending stock)
     */
    public function approveTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        if ($transfer->from_branch_id !== $branchId) {
            abort(403, 'Unauthorized access. Only the source branch can approve transfers.');
        }

        if ($transfer->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transfers can be approved.');
        }

        DB::beginTransaction();

        try {
            $transfer->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transfer approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error approving transfer: ' . $e->getMessage());
        }
    }

    /**
     * Reject a transfer (for SOURCE branch - the one sending stock)
     */
    public function rejectTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        if ($transfer->from_branch_id !== $branchId) {
            abort(403, 'Unauthorized access. Only the source branch can reject transfers.');
        }

        if ($transfer->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transfers can be rejected.');
        }

        DB::beginTransaction();

        try {
            $sourceInventory = BranchInventory::where('branch_id', $transfer->from_branch_id)
                ->where('product_id', $transfer->product_id)
                ->when($transfer->flavor_id, function($query) use ($transfer) {
                    return $query->where('flavor_id', $transfer->flavor_id);
                })
                ->first();

            if ($sourceInventory) {
                $sourceInventory->update([
                    'reserved_quantity' => $sourceInventory->reserved_quantity - $transfer->quantity
                ]);
            }

            $transfer->update([
                'status' => 'cancelled',
                'notes' => $transfer->notes . ' | Rejected by source branch'
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transfer rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error rejecting transfer: ' . $e->getMessage());
        }
    }

    /**
     * Complete a transfer (for DESTINATION branch - the one receiving stock)
     */
    public function completeTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        if ($transfer->to_branch_id !== $branchId) {
            abort(403, 'Unauthorized access. Only the destination branch can complete transfers.');
        }

        if ($transfer->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved transfers can be completed.');
        }

        DB::beginTransaction();

        try {
            $sourceInventory = BranchInventory::where('branch_id', $transfer->from_branch_id)
                ->where('product_id', $transfer->product_id)
                ->when($transfer->flavor_id, function($query) use ($transfer) {
                    return $query->where('flavor_id', $transfer->flavor_id);
                })
                ->first();

            if ($sourceInventory) {
                $sourceInventory->update([
                    'reserved_quantity' => $sourceInventory->reserved_quantity - $transfer->quantity,
                    'quantity' => $sourceInventory->quantity - $transfer->quantity,
                ]);
            }

            $destInventory = BranchInventory::where('branch_id', $transfer->to_branch_id)
                ->where('product_id', $transfer->product_id)
                ->when($transfer->flavor_id, function($query) use ($transfer) {
                    return $query->where('flavor_id', $transfer->flavor_id);
                })
                ->first();

            if ($destInventory) {
                $destInventory->update([
                    'quantity' => $destInventory->quantity + $transfer->quantity,
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

            StockMovement::create([
                'branch_id' => $transfer->from_branch_id,
                'product_id' => $transfer->product_id,
                'flavor_id' => $transfer->flavor_id,
                'previous_quantity' => $sourceInventory ? $sourceInventory->quantity : 0,
                'new_quantity' => $sourceInventory ? $sourceInventory->quantity : 0,
                'quantity_change' => -$transfer->quantity,
                'movement_type' => 'transfer_out',
                'reference_type' => 'transfer',
                'reference_id' => $transfer->id,
                'notes' => 'Transfer to ' . $transfer->toBranch->name,
                'created_by' => Auth::id(),
            ]);

            StockMovement::create([
                'branch_id' => $transfer->to_branch_id,
                'product_id' => $transfer->product_id,
                'flavor_id' => $transfer->flavor_id,
                'previous_quantity' => $destInventory->quantity - $transfer->quantity,
                'new_quantity' => $destInventory->quantity,
                'quantity_change' => $transfer->quantity,
                'movement_type' => 'transfer_in',
                'reference_type' => 'transfer',
                'reference_id' => $transfer->id,
                'notes' => 'Transfer from ' . $transfer->fromBranch->name,
                'created_by' => Auth::id(),
            ]);

            $transfer->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transfer completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error completing transfer: ' . $e->getMessage());
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
            abort(403, 'Unauthorized access.');
        }

        if ($transfer->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transfers can be cancelled.');
        }

        DB::beginTransaction();

        try {
            $inventory = BranchInventory::where('branch_id', $transfer->from_branch_id)
                ->where('product_id', $transfer->product_id)
                ->when($transfer->flavor_id, function($query) use ($transfer) {
                    return $query->where('flavor_id', $transfer->flavor_id);
                })
                ->first();

            if ($inventory) {
                $inventory->update([
                    'reserved_quantity' => $inventory->reserved_quantity - $transfer->quantity
                ]);
            }

            $transfer->update(['status' => 'cancelled']);

            DB::commit();

            return redirect()->back()->with('success', 'Transfer cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error cancelling transfer: ' . $e->getMessage());
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
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'quantity' => 'nullable|integer|min:0',
            'reserved_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'reorder_point' => 'required|integer|min:1',
            'optimal_stock' => 'required|integer|min:1',
            'last_purchase_price' => 'nullable|numeric|min:0',
            'last_restocked_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $request->quantity ?? $inventory->quantity;

            $inventory->update([
                'quantity' => $newQuantity,
                'reserved_quantity' => $request->reserved_quantity ?? $inventory->reserved_quantity,
                'low_stock_threshold' => $request->low_stock_threshold,
                'reorder_point' => $request->reorder_point,
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

            return redirect()->route('branch-admin.inventory.show', $inventory)
                ->with('success', 'Inventory updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating inventory: ' . $e->getMessage());
        }
    }

    /**
     * Archive an inventory item (soft hide from active lists)
     */
    public function archive(BranchInventory $inventory)
    {
        // Ensure inventory belongs to user's branch
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $inventory->update(['is_archived' => true]);
        return redirect()->back()->with('success', 'Inventory item archived successfully.');
    }

    /**
     * Restore an archived inventory item
     */
    public function unarchive(BranchInventory $inventory)
    {
        // Ensure inventory belongs to user's branch
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $inventory->update(['is_archived' => false]);
        return redirect()->back()->with('success', 'Inventory item restored from archive.');
    }
}
