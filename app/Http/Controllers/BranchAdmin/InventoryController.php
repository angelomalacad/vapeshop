<?php
// app/Http/Controllers/BranchAdmin/InventoryController.php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\StockTransfer;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Add this import

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

        // Add this with your other filters
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
                $query->lowStock();
            } elseif ($request->stock_status === 'out') {
                $query->outOfStock();
            }
        }

        $inventories = $query->paginate(20);

        $products = Product::where('is_active', true)->get();
        $lowStockCount = BranchInventory::where('branch_id', $branchId)->lowStock()->count();

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
     * Show form to quickly add stock to any existing product
     */
    public function addProductForm()
    {
        $branchId = Auth::user()->branch_id;

        // Get existing inventory items
        $branchInventory = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId)
            ->orderBy('product_id')
            ->get();

        return view('branch-admin.inventory.add-stock-quick', compact('branchInventory'));
    }

    /**
     * Add existing product to branch inventory
     */
    public function addProduct(Request $request)
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
            return back()->with('error', 'This product already exists in your inventory.');
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
            'last_restocked_at' => now(),
        ]);

        return redirect()->route('branch-admin.inventory.index')
            ->with('success', 'Product added to branch inventory.');
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
        $sourceBranches = \App\Models\Branch::where('id', '!=', $branchId)->get();

        // Get current branch (destination)
        $currentBranch = \App\Models\Branch::where('id', $branchId)->first();

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

    /**
     * Approve a transfer (for SOURCE branch - the one sending stock)
     */
    public function approveTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        // Only SOURCE branch can approve
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

            return redirect()->back()->with('success', 'Transfer approved successfully. The stock is now reserved and ready for pickup by the requesting branch.');
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

        // Only SOURCE branch can reject
        if ($transfer->from_branch_id !== $branchId) {
            abort(403, 'Unauthorized access. Only the source branch can reject transfers.');
        }

        if ($transfer->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transfers can be rejected.');
        }

        DB::beginTransaction();

        try {
            // Release reserved stock at source branch
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

        // Only DESTINATION branch can complete
        if ($transfer->to_branch_id !== $branchId) {
            abort(403, 'Unauthorized access. Only the destination branch can complete transfers.');
        }

        if ($transfer->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved transfers can be completed.');
        }

        DB::beginTransaction();

        try {
            // Remove from source branch reserved stock and actual stock
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

            // Add to destination branch
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
                // Create new inventory record if it doesn't exist
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

            // Log movements
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

            return redirect()->back()->with('success', 'Transfer completed successfully. Stock has been added to your inventory.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error completing transfer: ' . $e->getMessage());
        }
    }

    /**
     * Cancel transfer (for the requester - anyone can cancel their own pending request)
     */
    public function cancelTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;

        // Allow cancellation if:
        // 1. User is the one who requested it, OR
        // 2. User is from the source branch (they can cancel requests sent from their branch)
        $isRequester = ($transfer->requested_by == Auth::user()->id);
        $isSourceBranch = ($transfer->from_branch_id == $branchId);

        if (!$isRequester && !$isSourceBranch) {
            abort(403, 'Unauthorized access. You can only cancel your own requests or requests from your branch.');
        }

        if ($transfer->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending transfers can be cancelled.');
        }

        DB::beginTransaction();

        try {
            // Release reserved stock
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
        // Ensure inventory belongs to user's branch
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if there's any stock
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
    public function quickAddStockForm()
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

        // Ensure inventory belongs to user's branch
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
     * This allows editing ALL inventory fields.
     */
    public function edit(BranchInventory $inventory)
    {
        // Ensure inventory belongs to user's branch
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        // Load relationships
        $inventory->load(['product', 'flavor']);

        return view('branch-admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified inventory item.
     * This updates ALL inventory fields.
     */
    public function update(Request $request, BranchInventory $inventory)
    {
        // Ensure inventory belongs to user's branch
        if ($inventory->branch_id !== Auth::user()->branch_id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            // Stock management fields
            'quantity' => 'nullable|integer|min:0',
            'reserved_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'reorder_point' => 'required|integer|min:1',
            'optimal_stock' => 'required|integer|min:1',

            // Financial fields
            'last_purchase_price' => 'nullable|numeric|min:0',

            // Timestamps
            'last_restocked_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $request->quantity ?? $inventory->quantity;

            $inventory->update([
                // Stock management
                'quantity' => $newQuantity,
                'reserved_quantity' => $request->reserved_quantity ?? $inventory->reserved_quantity,
                'low_stock_threshold' => $request->low_stock_threshold,
                'reorder_point' => $request->reorder_point,
                'optimal_stock' => $request->optimal_stock,

                // Financial
                'last_purchase_price' => $request->last_purchase_price,

                // Timestamps
                'last_restocked_at' => $request->last_restocked_at ? Carbon::parse($request->last_restocked_at) : $inventory->last_restocked_at,
            ]);

            // Log stock movement if quantity changed
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
}
