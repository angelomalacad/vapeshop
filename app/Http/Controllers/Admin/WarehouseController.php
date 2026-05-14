<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\WarehouseInventory;
use App\Models\StockTransfer;
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
            'expiration_date' => 'nullable|date', // ADDED
        ]);
        
        $inventory = WarehouseInventory::firstOrNew([
            'product_id' => $request->product_id,
            'flavor_id' => $request->flavor_id,
        ]);
        
        $oldQuantity = $inventory->quantity ?? 0;
        $oldPurchasePrice = $inventory->last_purchase_price ?? 0;
        $newPurchasePrice = $request->purchase_price ?? $oldPurchasePrice;
        
        $inventory->quantity = $oldQuantity + $request->quantity;
        $inventory->last_purchase_price = $newPurchasePrice;
        $inventory->last_restocked_at = now();
        $inventory->expiration_date = $request->expiration_date; // ADDED
        $inventory->save();
        
        // Record stock movement
        \App\Models\StockMovement::create([
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
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'required|exists:product_flavors,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        DB::beginTransaction();
        try {
            $warehouse = WarehouseInventory::where('product_id', $request->product_id)
                ->where('flavor_id', $request->flavor_id)
                ->first();
            
            if (!$warehouse || $warehouse->quantity < $request->quantity) {
                return back()->with('error', 'Insufficient warehouse stock!');
            }
            
            $oldQuantity = $warehouse->quantity;
            $newQuantity = $oldQuantity - $request->quantity;
            
            // Deduct from warehouse
            $warehouse->quantity = $newQuantity;
            $warehouse->save();
            
            // Record warehouse stock movement
            \App\Models\StockMovement::create([
                'branch_id' => null,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => -$request->quantity,
                'movement_type' => 'transfer_out',
                'reference_type' => 'warehouse_transfer',
                'notes' => "Stock distributed to branch ID: {$request->branch_id}",
                'created_by' => auth()->id(),
            ]);
            
            // Create transfer request to branch
            $transfer = StockTransfer::create([
                'from_branch_id' => null,
                'to_branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
                'flavor_id' => $request->flavor_id,
                'quantity' => $request->quantity,
                'status' => 'pending',
                'transfer_type' => 'warehouse_to_branch',
                'requested_by' => auth()->id(),
                'transfer_number' => 'WH-' . date('Ymd') . '-' . rand(1000, 9999),
                'notes' => 'Stock distribution from warehouse',
            ]);
            
            DB::commit();
            return redirect()->route('admin.warehouse.index')->with('success', "{$request->quantity} units sent to branch!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing distribution: ' . $e->getMessage());
        }
    }
    
    public function pendingDistributions()
    {
        $transfers = StockTransfer::where('transfer_type', 'warehouse_to_branch')
            ->where('status', 'pending')
            ->with(['toBranch', 'product', 'flavor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.warehouse.pending', compact('transfers'));
    }
    
    public function approveDistribution(StockTransfer $transfer)
    {
        if ($transfer->status != 'pending') {
            return back()->with('error', 'Transfer is no longer pending.');
        }
        
        $warehouse = WarehouseInventory::where('product_id', $transfer->product_id)
            ->where('flavor_id', $transfer->flavor_id)
            ->first();
        
        if (!$warehouse || $warehouse->quantity < $transfer->quantity) {
            return back()->with('error', 'Insufficient warehouse stock!');
        }
        
        DB::beginTransaction();
        try {
            $oldQuantity = $warehouse->quantity;
            $newQuantity = $oldQuantity - $transfer->quantity;
            
            // Deduct from warehouse
            $warehouse->quantity = $newQuantity;
            $warehouse->save();
            
            // Record warehouse stock movement
            \App\Models\StockMovement::create([
                'branch_id' => null,
                'product_id' => $transfer->product_id,
                'flavor_id' => $transfer->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => -$transfer->quantity,
                'movement_type' => 'transfer_out',
                'reference_type' => 'warehouse_transfer',
                'reference_id' => $transfer->id,
                'notes' => "Stock transferred to branch: " . ($transfer->toBranch->name ?? 'Unknown'),
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
            // Update transfer status to rejected/cancelled
            $transfer->status = 'cancelled';
            $transfer->notes = $transfer->notes . ' | Rejected by owner. Reason: ' . request('rejection_reason', 'No reason provided');
            $transfer->save();
            
            DB::commit();
            return redirect()->route('admin.warehouse.pending')->with('success', 'Distribution request rejected.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error rejecting request: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form to edit warehouse inventory item
     */
    public function edit($id)
    {
        $inventory = WarehouseInventory::with(['product', 'flavor'])->findOrFail($id);
        $products = Product::where('is_active', true)->with('flavors')->get();
        
        return view('admin.warehouse.edit', compact('inventory', 'products'));
    }
    
    /**
     * Update warehouse inventory item
     */
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
            'expiration_date' => 'nullable|date', // ADDED
        ]);
        
        try {
            $oldQuantity = $inventory->quantity;
            $oldPurchasePrice = $inventory->last_purchase_price;
            $newQuantity = $request->quantity;
            $newPurchasePrice = $request->last_purchase_price;
            
            // Update warehouse inventory
            $inventory->product_id = $request->product_id;
            $inventory->flavor_id = $request->flavor_id;
            $inventory->quantity = $newQuantity;
            $inventory->low_stock_threshold = $request->low_stock_threshold;
            $inventory->reorder_point = $request->reorder_point;
            $inventory->last_purchase_price = $newPurchasePrice;
            $inventory->expiration_date = $request->expiration_date; // ADDED
            
            if ($newQuantity > $oldQuantity) {
                $inventory->last_restocked_at = now();
            }
            
            $inventory->save();
            
            // Record stock movement if quantity changed
            if ($oldQuantity != $newQuantity) {
                \App\Models\StockMovement::create([
                    'branch_id' => null,
                    'product_id' => $inventory->product_id,
                    'flavor_id' => $inventory->flavor_id,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => $newQuantity - $oldQuantity,
                    'movement_type' => 'adjustment',
                    'reference_type' => 'warehouse',
                    'reference_id' => $inventory->id,
                    'notes' => 'Manual quantity adjustment by owner',
                    'created_by' => auth()->id(),
                ]);
            }
            
            // Record purchase price change if different
            if ($oldPurchasePrice != $newPurchasePrice) {
                \App\Models\StockMovement::create([
                    'branch_id' => null,
                    'product_id' => $inventory->product_id,
                    'flavor_id' => $inventory->flavor_id,
                    'previous_quantity' => $newQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => 0,
                    'movement_type' => 'adjustment',
                    'reference_type' => 'warehouse',
                    'reference_id' => $inventory->id,
                    'notes' => "Purchase price updated from ₱" . number_format($oldPurchasePrice, 2) . " to ₱" . number_format($newPurchasePrice, 2),
                    'created_by' => auth()->id(),
                ]);
            }
            
            return redirect()->route('admin.warehouse.index')->with('success', 'Warehouse inventory updated successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating inventory: ' . $e->getMessage())->withInput();
        }
    }
}