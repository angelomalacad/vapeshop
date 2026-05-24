<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display inventory across all branches
     */
    public function index(Request $request)
    {
        $query = BranchInventory::with(['branch', 'product', 'flavor']);

        if ($request->filled('search')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('quantity', '<=', 'low_stock_threshold')->where('is_archived', false);
            } elseif ($request->stock_status === 'out') {
                $query->where('quantity', '<=', 0)->where('is_archived', false);
            } elseif ($request->stock_status === 'archived') {
                $query->where('is_archived', true);
            }
        } else {
            // Default: exclude archived items unless explicitly requested
            $query->where('is_archived', false);
        }

        $inventories = $query->orderBy('branch_id')->paginate(20);

        $branches = Branch::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.inventory.index', compact('inventories', 'branches', 'products'));
    }

    /**
     * Show form to create new inventory item
     */
    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $products = Product::with('flavors')->where('is_active', true)->get();

        return view('admin.inventory.create', compact('branches', 'products'));
    }

    /**
     * Store new inventory item
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'reorder_point' => 'required|integer|min:1',
            'optimal_stock' => 'required|integer|min:1',
            'last_purchase_price' => 'nullable|numeric|min:0',
        ]);

        // Check if already exists
        $exists = BranchInventory::where('branch_id', $request->branch_id)
            ->where('product_id', $request->product_id)
            ->where('flavor_id', $request->flavor_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This product already exists in this branch inventory.');
        }

        DB::beginTransaction();

        try {
            $inventory = BranchInventory::create([
                'branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'quantity' => $request->quantity,
                'reserved_quantity' => 0,
                'low_stock_threshold' => $request->low_stock_threshold,
                'reorder_point' => $request->reorder_point,
                'optimal_stock' => $request->optimal_stock,
                'last_purchase_price' => $request->last_purchase_price,
                'last_restocked_at' => $request->quantity > 0 ? now() : null,
            ]);

            // Log initial stock movement
            if ($request->quantity > 0) {
                StockMovement::create([
                    'branch_id' => $request->branch_id,
                    'product_id' => $request->product_id,
                    'flavor_id' => $request->flavor_id,
                    'previous_quantity' => 0,
                    'new_quantity' => $request->quantity,
                    'quantity_change' => $request->quantity,
                    'movement_type' => 'initial',
                    'notes' => 'Initial stock setup by super admin',
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Inventory item created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating inventory: ' . $e->getMessage());
        }
    }

    /**
     * Show inventory item details
     */
    public function show(BranchInventory $inventory)
    {
        $inventory->load(['branch', 'product', 'flavor']);

        $movements = StockMovement::where('branch_id', $inventory->branch_id)
            ->where('product_id', $inventory->product_id)
            ->when($inventory->flavor_id, function($query) use ($inventory) {
                return $query->where('flavor_id', $inventory->flavor_id);
            })
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.inventory.show', compact('inventory', 'movements'));
    }

    /**
     * Show form to edit inventory item
     */
    public function edit(BranchInventory $inventory)
    {
        $inventory->load(['product', 'flavor']);
        $branches = Branch::where('is_active', true)->get();
        $products = Product::with('flavors')->where('is_active', true)->get();

        return view('admin.inventory.edit', compact('inventory', 'branches', 'products'));
    }

    /**
     * Update inventory item
     */
    public function update(Request $request, BranchInventory $inventory)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:0',
            'reserved_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'reorder_point' => 'required|integer|min:1',
            'optimal_stock' => 'required|integer|min:1',
            'last_purchase_price' => 'nullable|numeric|min:0',
            'last_restocked_at' => 'nullable|date',
        ]);

        // Check if changing branch/product/flavor combination would create duplicate
        if ($inventory->branch_id != $request->branch_id ||
            $inventory->product_id != $request->product_id ||
            $inventory->flavor_id != $request->flavor_id) {

            $exists = BranchInventory::where('branch_id', $request->branch_id)
                ->where('product_id', $request->product_id)
                ->where('flavor_id', $request->flavor_id)
                ->where('id', '!=', $inventory->id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This product already exists in this branch inventory.');
            }
        }

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $oldReserved = $inventory->reserved_quantity;

            $inventory->update([
                'branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'quantity' => $request->quantity,
                'reserved_quantity' => $request->reserved_quantity,
                'low_stock_threshold' => $request->low_stock_threshold,
                'reorder_point' => $request->reorder_point,
                'optimal_stock' => $request->optimal_stock,
                'last_purchase_price' => $request->last_purchase_price,
                'last_restocked_at' => $request->last_restocked_at ? Carbon::parse($request->last_restocked_at) : $inventory->last_restocked_at,
            ]);

            // Log stock movement if quantity changed
            if ($oldQuantity != $request->quantity) {
                StockMovement::create([
                    'branch_id' => $request->branch_id,
                    'product_id' => $request->product_id,
                    'flavor_id' => $request->flavor_id,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $request->quantity,
                    'quantity_change' => $request->quantity - $oldQuantity,
                    'movement_type' => 'adjustment',
                    'notes' => 'Manual adjustment by super admin',
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.inventory.show', $inventory)
                ->with('success', 'Inventory updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating inventory: ' . $e->getMessage());
        }
    }

    /**
     * Add stock to inventory
     */
    public function addStock(Request $request, BranchInventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
            'purchase_price' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $oldQuantity + $request->quantity;

            $updateData = [
                'quantity' => $newQuantity,
                'last_restocked_at' => now(),
            ];

            if ($request->filled('purchase_price')) {
                $updateData['last_purchase_price'] = $request->purchase_price;
            }

            $inventory->update($updateData);

            // Log movement
            StockMovement::create([
                'branch_id' => $inventory->branch_id,
                'product_id' => $inventory->product_id,
                'flavor_id' => $inventory->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => $request->quantity,
                'movement_type' => 'purchase',
                'notes' => $request->notes ?: 'Stock added by super admin',
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('admin.inventory.show', $inventory)
                ->with('success', "Added {$request->quantity} units to inventory.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error adding stock: ' . $e->getMessage());
        }
    }

    /**
     * Show form to add stock
     */
    public function addStockForm(BranchInventory $inventory)
    {
        $inventory->load(['product', 'flavor', 'branch']);
        return view('admin.inventory.add-stock', compact('inventory'));
    }

    /**
     * Remove stock from inventory (adjustment)
     */
    public function removeStock(Request $request, BranchInventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
            'reason' => 'required|string|max:500',
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
                'movement_type' => 'adjustment',
                'notes' => 'Removed: ' . $request->reason,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('admin.inventory.show', $inventory)
                ->with('success', "Removed {$request->quantity} units from inventory.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error removing stock: ' . $e->getMessage());
        }
    }

    /**
     * Delete inventory item
     */
    public function destroy(BranchInventory $inventory)
    {
        // Check if there's reserved stock
        if ($inventory->reserved_quantity > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete inventory with reserved stock.');
        }

        // Check if there's any stock
        if ($inventory->quantity > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete inventory with remaining stock. Please adjust stock to zero first.');
        }

        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Show branch specific inventory
     */
    public function branchInventory(Branch $branch)
    {
        $inventories = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branch->id)
            ->paginate(20);

        return view('admin.inventory.branch', compact('inventories', 'branch'));
    }

    /**
     * Show low stock items across all branches
     */
    public function lowStock()
    {
        $items = BranchInventory::with(['branch', 'product', 'flavor'])
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('branch_id')
            ->orderBy('quantity', 'asc')
            ->get()
            ->groupBy('branch.name');

        return view('admin.inventory.low-stock', compact('items'));
    }

    /**
     * Show stock movement history across all branches
     */
    public function stockHistory(Request $request)
    {
        $query = StockMovement::with(['branch', 'product', 'flavor', 'creator']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(50);

        $branches = Branch::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.inventory.stock-history', compact('movements', 'branches', 'products'));
    }

    /**
     * Show transfer requests across all branches
     */
    public function transfers(Request $request)
    {
        $query = StockTransfer::with([
            'fromBranch',
            'toBranch',
            'product',
            'flavor',
            'requestedBy',
            'approvedBy'
        ])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_branch')) {
            $query->where('from_branch_id', $request->from_branch);
        }

        if ($request->filled('to_branch')) {
            $query->where('to_branch_id', $request->to_branch);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transfers = $query->paginate(20);

        $branches = Branch::where('is_active', true)->get();
        $statuses = ['pending', 'approved', 'completed', 'cancelled'];

        return view('admin.inventory.transfers', compact('transfers', 'branches', 'statuses'));
    }

    /**
     * Show form to create a new transfer
     */
    public function createTransfer()
    {
        $branches = Branch::where('is_active', true)->get();
        $products = Product::with('flavors')->where('is_active', true)->get();

        return view('admin.inventory.create-transfer', compact('branches', 'products'));
    }

    /**
     * Store a new transfer request
     */
    public function storeTransfer(Request $request)
    {
        $request->validate([
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if source branch has enough stock
        $sourceInventory = BranchInventory::where('branch_id', $request->from_branch_id)
            ->where('product_id', $request->product_id)
            ->when($request->flavor_id, function($query) use ($request) {
                return $query->where('flavor_id', $request->flavor_id);
            })
            ->first();

        if (!$sourceInventory) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Product not found in source branch inventory.');
        }

        if ($sourceInventory->available_quantity < $request->quantity) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Insufficient stock at source branch. Available: {$sourceInventory->available_quantity}");
        }

        DB::beginTransaction();

        try {
            // Generate transfer number
            $transferNumber = 'TRF-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Create transfer request
            $transfer = StockTransfer::create([
                'transfer_number' => $transferNumber,
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

            return redirect()->route('admin.inventory.transfers')
                ->with('success', 'Transfer request created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating transfer: ' . $e->getMessage());
        }
    }

    /**
     * Show transfer details
     */
    public function showTransfer(StockTransfer $transfer)
    {
        $transfer->load([
            'fromBranch',
            'toBranch',
            'product',
            'flavor',
            'requestedBy',
            'approvedBy'
        ]);

        return view('admin.inventory.show-transfer', compact('transfer'));
    }

    /**
     * Edit transfer form
     */
    public function editTransfer(StockTransfer $transfer)
    {
        if ($transfer->status !== 'pending') {
            return redirect()->route('admin.inventory.transfers')
                ->with('error', 'Only pending transfers can be edited.');
        }

        $branches = Branch::where('is_active', true)->get();
        $products = Product::with('flavors')->where('is_active', true)->get();

        return view('admin.inventory.edit-transfer', compact('transfer', 'branches', 'products'));
    }

    /**
     * Update transfer
     */
    public function updateTransfer(Request $request, StockTransfer $transfer)
    {
        if ($transfer->status !== 'pending') {
            return redirect()->route('admin.inventory.transfers')
                ->with('error', 'Only pending transfers can be updated.');
        }

        $request->validate([
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Release old reserved stock
            $oldSourceInventory = BranchInventory::where('branch_id', $transfer->from_branch_id)
                ->where('product_id', $transfer->product_id)
                ->when($transfer->flavor_id, function($query) use ($transfer) {
                    return $query->where('flavor_id', $transfer->flavor_id);
                })
                ->first();

            if ($oldSourceInventory) {
                $oldSourceInventory->update([
                    'reserved_quantity' => $oldSourceInventory->reserved_quantity - $transfer->quantity
                ]);
            }

            // Check new source inventory
            $newSourceInventory = BranchInventory::where('branch_id', $request->from_branch_id)
                ->where('product_id', $request->product_id)
                ->when($request->flavor_id, function($query) use ($request) {
                    return $query->where('flavor_id', $request->flavor_id);
                })
                ->first();

            if (!$newSourceInventory) {
                throw new \Exception('Product not found in new source branch inventory.');
            }

            if ($newSourceInventory->available_quantity < $request->quantity) {
                throw new \Exception("Insufficient stock at new source branch. Available: {$newSourceInventory->available_quantity}");
            }

            // Reserve new stock
            $newSourceInventory->update([
                'reserved_quantity' => $newSourceInventory->reserved_quantity + $request->quantity
            ]);

            // Update transfer
            $transfer->update([
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id' => $request->to_branch_id,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.inventory.transfers.show', $transfer)
                ->with('success', 'Transfer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating transfer: ' . $e->getMessage());
        }
    }

    /**
     * Approve a transfer
     */
    public function approveTransfer(StockTransfer $transfer)
    {
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
     * Reject a transfer
     */
    public function rejectTransfer(StockTransfer $transfer)
    {
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
                'notes' => $transfer->notes . ' | Rejected by super admin'
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transfer rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error rejecting transfer: ' . $e->getMessage());
        }
    }

    /**
     * Complete a transfer
     */
    public function completeTransfer(StockTransfer $transfer)
    {
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
                'previous_quantity' => $sourceInventory ? $sourceInventory->quantity + $transfer->quantity : 0,
                'new_quantity' => $sourceInventory ? $sourceInventory->quantity : 0,
                'quantity_change' => -$transfer->quantity,
                'movement_type' => 'transfer_out',
                'reference_type' => 'transfer',
                'reference_id' => $transfer->id,
                'notes' => 'Transfer to ' . ($transfer->toBranch ? $transfer->toBranch->name : 'Unknown Branch'),
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
                'notes' => 'Transfer from ' . ($transfer->fromBranch ? $transfer->fromBranch->name : 'Unknown Branch'),
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
     * Cancel a transfer
     */
    public function cancelTransfer(StockTransfer $transfer)
    {
        if (!in_array($transfer->status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'Only pending or approved transfers can be cancelled.');
        }

        DB::beginTransaction();

        try {
            // Release reserved stock if still pending/approved
            if (in_array($transfer->status, ['pending', 'approved'])) {
                $sourceInventory = BranchInventory::where('branch_id', $transfer->from_branch_id)
                    ->where('product_id', $transfer->product_id)
                    ->when($transfer->flavor_id, function($query) use ($transfer) {
                        return $query->where('flavor_id', $transfer->flavor_id);
                    })
                    ->first();

                if ($sourceInventory && $sourceInventory->reserved_quantity >= $transfer->quantity) {
                    $sourceInventory->update([
                        'reserved_quantity' => $sourceInventory->reserved_quantity - $transfer->quantity
                    ]);
                }
            }

            $transfer->update([
                'status' => 'cancelled',
                'notes' => $transfer->notes . ' | Cancelled by super admin'
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Transfer cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error cancelling transfer: ' . $e->getMessage());
        }
    }

    /**
     * Delete a transfer
     */
    public function destroyTransfer(StockTransfer $transfer)
    {
        if ($transfer->status !== 'cancelled' && $transfer->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Only cancelled or completed transfers can be deleted.');
        }

        $transfer->delete();

        return redirect()->route('admin.inventory.transfers')
            ->with('success', 'Transfer deleted successfully.');
    }

    /**
     * Get inventory summary for dashboard
     */
    public function summary()
    {
        $summary = [
            'total_branches' => Branch::count(),
            'total_products' => Product::count(),
            'total_inventory_items' => BranchInventory::count(),
            'low_stock_count' => BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count(),
            'out_of_stock_count' => BranchInventory::where('quantity', '<=', 0)->count(),
            'pending_transfers' => StockTransfer::where('status', 'pending')->count(),
            'total_stock_value' => BranchInventory::with('product')
                ->get()
                ->sum(function($item) {
                    return $item->quantity * ($item->product->price ?? 0);
                }),
        ];

        return response()->json($summary);
    }

    // ========== ARCHIVE / UNARCHIVE METHODS ==========

    /**
     * Archive an inventory item (soft hide from active lists)
     */
    public function archive(BranchInventory $inventory)
    {
        $inventory->update(['is_archived' => true]);
        return redirect()->back()->with('success', 'Inventory item archived successfully.');
    }

    /**
     * Restore an archived inventory item
     */
    public function unarchive(BranchInventory $inventory)
    {
        $inventory->update(['is_archived' => false]);
        return redirect()->back()->with('success', 'Inventory item restored from archive.');
    }
}