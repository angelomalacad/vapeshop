<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\WarehouseInventory;
use App\Models\StockTransfer;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $inventory = WarehouseInventory::with(['product', 'flavor'])->orderBy('product_id')->paginate(20);
        $products = Product::where('is_active', true)->with('flavors')->get();
        $lowStockCount = WarehouseInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count();
        $totalValue = WarehouseInventory::with('product')->get()->sum(fn($item) => $item->quantity * ($item->last_purchase_price ?? 0));
        
        return view('admin.warehouse.index', compact('inventory', 'products', 'lowStockCount', 'totalValue'));
    }
    
    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'required|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'nullable|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);
        
        $inventory = WarehouseInventory::firstOrNew([
            'product_id' => $request->product_id,
            'flavor_id' => $request->flavor_id,
        ]);
        
        $oldQuantity = $inventory->quantity ?? 0;
        $newPurchasePrice = $request->purchase_price ?? $inventory->last_purchase_price ?? 0;
        
        $inventory->quantity = $oldQuantity + $request->quantity;
        $inventory->last_purchase_price = $newPurchasePrice;
        $inventory->last_restocked_at = now();
        $inventory->expiration_date = $request->expiration_date;
        
        if (!$inventory->exists) {
            $inventory->low_stock_threshold = 10;
            $inventory->reorder_point = 20;
        }
        
        $inventory->save();
        
        // Record stock movement
        StockMovement::create([
            'branch_id' => null,
            'product_id' => $request->product_id,
            'flavor_id' => $request->flavor_id,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $inventory->quantity,
            'quantity_change' => $request->quantity,
            'movement_type' => 'purchase',
            'reference_type' => 'warehouse',
            'reference_id' => $inventory->id,
            'notes' => "Stock added to warehouse. Purchase price: ₱{$newPurchasePrice}" . ($request->expiration_date ? " Expiry: {$request->expiration_date}" : ''),
            'created_by' => auth()->id(),
        ]);
        
        return redirect()->route('admin.warehouse.index')->with('success', 'Stock added to warehouse!');
    }
    
    public function distributeToBranch(Request $request)
{
    $request->validate([
        'warehouse_stock_id' => 'required|exists:warehouse_inventories,id',
        'product_id' => 'required|exists:products,id',
        'flavor_id' => 'required|exists:product_flavors,id',
        'branch_id' => 'required|exists:branches,id',
        'quantity' => 'required|integer|min:1',
        'notes' => 'nullable|string',
    ]);
    
    DB::beginTransaction();
    try {
        $warehouse = WarehouseInventory::findOrFail($request->warehouse_stock_id);
        
        if ($warehouse->quantity < $request->quantity) {
            return back()->with('error', "Insufficient stock! Available: {$warehouse->quantity}");
        }
        
        $oldQuantity = $warehouse->quantity;
        $warehouse->quantity = $oldQuantity - $request->quantity;
        $warehouse->save();
        
        $transferNumber = 'DIST-' . date('Ymd') . '-' . rand(1000, 9999);
        
        $transfer = StockTransfer::create([
            'transfer_number' => $transferNumber,
            'from_branch_id' => null,
            'to_branch_id' => $request->branch_id,
            'product_id' => $request->product_id,
            'flavor_id' => $request->flavor_id,
            'quantity' => $request->quantity,
            'status' => 'approved',
            'transfer_type' => 'warehouse_to_branch', // MAKE SURE THIS IS SET
            'requested_by' => auth()->id(),
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->notes ?? 'Direct distribution from warehouse',
            'expiration_date' => $warehouse->expiration_date,
        ]);
        
        // Log to verify
        \Log::info('Transfer created:', [
            'id' => $transfer->id,
            'transfer_type' => $transfer->transfer_type,
            'from_branch_id' => $transfer->from_branch_id,
            'to_branch_id' => $transfer->to_branch_id
        ]);
        
        StockMovement::create([
            'branch_id' => null,
            'product_id' => $request->product_id,
            'flavor_id' => $request->flavor_id,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $warehouse->quantity,
            'quantity_change' => -$request->quantity,
            'movement_type' => 'transfer_out',
            'reference_type' => 'stock_transfer',
            'reference_id' => $transfer->id,
            'notes' => "Distributed to branch ID: {$request->branch_id}",
            'created_by' => auth()->id(),
        ]);
        
        DB::commit();
        
        return redirect()->route('admin.warehouse.index')
            ->with('success', "Successfully distributed {$request->quantity} units! Transfer #: {$transferNumber}");
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Distribution failed: ' . $e->getMessage());
    }
}
    
   public function pendingDistributions(Request $request)
{
    // Get ALL pending transfers regardless of type first
    $allPending = StockTransfer::where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Then filter for warehouse_to_branch
    $transfers = StockTransfer::where('status', 'pending')
        ->where(function($q) {
            $q->where('transfer_type', 'warehouse_to_branch')
              ->orWhereNull('transfer_type')
              ->orWhere('transfer_type', '');
        })
        ->with(['toBranch', 'product', 'flavor'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    $historyTransfers = StockTransfer::whereIn('status', ['completed', 'cancelled', 'approved'])
        ->where(function($q) {
            $q->where('transfer_type', 'warehouse_to_branch')
              ->orWhereNull('transfer_type')
              ->orWhere('transfer_type', '');
        })
        ->with(['toBranch', 'product', 'flavor'])
        ->orderBy('created_at', 'desc')
        ->paginate(20, ['*'], 'history_page');
    
    $pendingCount = StockTransfer::where('status', 'pending')
        ->where(function($q) {
            $q->where('transfer_type', 'warehouse_to_branch')
              ->orWhereNull('transfer_type')
              ->orWhere('transfer_type', '');
        })
        ->count();
    
    return view('admin.warehouse.pending', compact('transfers', 'historyTransfers', 'pendingCount'));
}
    public function approveDistribution(StockTransfer $transfer)
{
    if ($transfer->status != 'pending') {
        return back()->with('error', 'Transfer is no longer pending.');
    }
    
    // Check if enough stock is available in warehouse
    $warehouse = WarehouseInventory::where('product_id', $transfer->product_id)
        ->where('flavor_id', $transfer->flavor_id)
        ->first();
    
    if (!$warehouse || $warehouse->quantity < $transfer->quantity) {
        return back()->with('error', 'Insufficient warehouse stock! Available: ' . ($warehouse->quantity ?? 0));
    }
    
    DB::beginTransaction();
    try {
        // Deduct from warehouse
        $oldQuantity = $warehouse->quantity;
        $warehouse->quantity = $oldQuantity - $transfer->quantity;
        $warehouse->save();
        
        // Record warehouse stock movement
        StockMovement::create([
            'branch_id' => null,
            'product_id' => $transfer->product_id,
            'flavor_id' => $transfer->flavor_id,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $warehouse->quantity,
            'quantity_change' => -$transfer->quantity,
            'movement_type' => 'transfer_out',
            'reference_type' => 'stock_transfer',
            'reference_id' => $transfer->id,
            'notes' => 'Stock transferred to branch: ' . ($transfer->toBranch->name ?? 'Unknown'),
            'created_by' => auth()->id(),
        ]);
        
        // Update transfer status
        $transfer->status = 'approved';
        $transfer->approved_by = auth()->id();
        $transfer->approved_at = now();
        $transfer->save();
        
        DB::commit();
        return redirect()->route('admin.warehouse.pending')->with('success', 'Distribution approved! Stock deducted from warehouse.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
    public function rejectDistribution(StockTransfer $transfer)
    {
        if ($transfer->status != 'pending') {
            return back()->with('error', 'Transfer is no longer pending.');
        }
        
        DB::beginTransaction();
        try {
            $transfer->status = 'cancelled';
            $transfer->notes = $transfer->notes . ' | Rejected by owner.';
            $transfer->save();
            
            DB::commit();
            return redirect()->route('admin.warehouse.pending')->with('success', 'Distribution request rejected.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error rejecting request: ' . $e->getMessage());
        }
    }
    
    public function getProductFlavors($productId)
    {
        $product = Product::findOrFail($productId);
        $flavors = $product->flavors()->where('is_active', true)->get(['id', 'name']);
        return response()->json($flavors);
    }
    
    public function edit($id)
    {
        $inventory = WarehouseInventory::with(['product', 'flavor'])->findOrFail($id);
        $products = Product::where('is_active', true)->with('flavors')->get();
        return view('admin.warehouse.edit', compact('inventory', 'products'));
    }
    
    public function update(Request $request, $id)
    {
        $inventory = WarehouseInventory::findOrFail($id);
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'required|exists:product_flavors,id',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:1',
            'reorder_point' => 'required|integer|min:1',
            'last_purchase_price' => 'required|numeric|min:0',
            'expiration_date' => 'nullable|date',
        ]);
        
        try {
            $oldQuantity = $inventory->quantity;
            $newQuantity = $request->quantity;
            
            $inventory->product_id = $request->product_id;
            $inventory->flavor_id = $request->flavor_id;
            $inventory->quantity = $newQuantity;
            $inventory->low_stock_threshold = $request->low_stock_threshold;
            $inventory->reorder_point = $request->reorder_point;
            $inventory->last_purchase_price = $request->last_purchase_price;
            $inventory->expiration_date = $request->expiration_date;
            
            if ($newQuantity > $oldQuantity) {
                $inventory->last_restocked_at = now();
            }
            
            $inventory->save();
            
            return redirect()->route('admin.warehouse.index')->with('success', 'Warehouse inventory updated successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating inventory: ' . $e->getMessage())->withInput();
        }
    }
}