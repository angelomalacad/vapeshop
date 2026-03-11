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
        $branches = \App\Models\Branch::where('id', '!=', $branchId)->get();
        
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
            'products', 'branches', 'selectedProduct', 'selectedFlavor', 'maxQuantity'
        ));
    }

    /**
     * Request stock transfer
     */
    public function requestTransfer(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        
        $request->validate([
            'to_branch_id' => 'required|exists:branches,id|different:' . $branchId,
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Check if source branch has enough stock
        $inventory = BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $request->product_id)
            ->when($request->flavor_id, function($query) use ($request) {
                return $query->where('flavor_id', $request->flavor_id);
            })
            ->firstOrFail();
        
        if ($inventory->available_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock available.');
        }
        
        DB::beginTransaction();
        
        try {
            // Create transfer request
            $transfer = StockTransfer::create([
                'from_branch_id' => $branchId,
                'to_branch_id' => $request->to_branch_id,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'quantity' => $request->quantity,
                'status' => 'pending',
                'requested_by' => Auth::id(),
                'notes' => $request->notes,
            ]);
            
            // Reserve stock
            $inventory->update([
                'reserved_quantity' => $inventory->reserved_quantity + $request->quantity
            ]);
            
            DB::commit();
            
            return redirect()->route('branch-admin.inventory.transfers')
                ->with('success', 'Transfer request submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error requesting transfer: ' . $e->getMessage());
        }
    }

    /**
     * View transfer requests
     */
    public function transfers(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        
        $query = StockTransfer::with(['fromBranch', 'toBranch', 'product', 'flavor', 'requester'])
            ->where('from_branch_id', $branchId)
            ->orWhere('to_branch_id', $branchId);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $transfers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('branch-admin.inventory.transfers-list', compact('transfers'));
    }

    /**
     * Complete a transfer (for receiving branch)
     */
    public function completeTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;
        
        // Only receiving branch can complete
        if ($transfer->to_branch_id !== $branchId) {
            abort(403, 'Unauthorized access.');
        }
        
        if (!in_array($transfer->status, ['approved', 'in_transit'])) {
            return redirect()->back()->with('error', 'Transfer cannot be completed in its current state.');
        }
        
        DB::beginTransaction();
        
        try {
            // Remove from source branch reserved stock
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
            $destInventory = BranchInventory::firstOrCreate(
                [
                    'branch_id' => $transfer->to_branch_id,
                    'product_id' => $transfer->product_id,
                    'flavor_id' => $transfer->flavor_id,
                ],
                [
                    'quantity' => 0,
                    'reserved_quantity' => 0,
                    'low_stock_threshold' => 10,
                    'reorder_point' => 20,
                    'optimal_stock' => 50,
                ]
            );
            
            $oldQuantity = $destInventory->quantity;
            $destInventory->update([
                'quantity' => $oldQuantity + $transfer->quantity,
                'last_restocked_at' => now(),
            ]);
            
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
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $oldQuantity + $transfer->quantity,
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
     * Cancel transfer
     */
    public function cancelTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;
        
        // Only source branch can cancel pending transfers
        if ($transfer->from_branch_id !== $branchId) {
            abort(403, 'Unauthorized access.');
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
}