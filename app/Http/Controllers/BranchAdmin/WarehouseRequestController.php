<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseInventory;
use App\Models\StockTransfer;
use App\Models\BranchInventory;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseRequestController extends Controller
{
    public function index()
{
    $branchId = Auth::user()->branch_id;
    
    // Get products available in warehouse
    $warehouseProducts = WarehouseInventory::with(['product', 'flavor'])
        ->where('quantity', '>', 0)
        ->when(request('search'), function($query) {
            $search = request('search');
            return $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
        ->paginate(10, ['*'], 'warehouse_page');
    
    // Get all warehouse products for flavor selector
    $allWarehouseProducts = WarehouseInventory::with(['product', 'flavor'])
        ->where('quantity', '>', 0)
        ->get();
    
    // Get pending requests
    $pendingRequests = StockTransfer::where('to_branch_id', $branchId)
        ->where('status', 'pending')
        ->where(function($query) {
            $query->where('transfer_type', 'warehouse_to_branch')
                  ->orWhere('transfer_type', 'branch_to_branch');
        })
        ->with(['product', 'flavor'])
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Get completed/approved requests - MAKE SURE received_at is selected
    $completedRequests = StockTransfer::where('to_branch_id', $branchId)
        ->whereIn('status', ['approved', 'completed'])
        ->where(function($query) {
            $query->where('transfer_type', 'warehouse_to_branch')
                  ->orWhere('transfer_type', 'branch_to_branch');
        })
        ->with(['product', 'flavor'])
        ->orderBy('created_at', 'desc')
        ->paginate(10, ['*'], 'history_page');
    
    return view('branch-admin.warehouse.index', compact(
        'warehouseProducts', 
        'allWarehouseProducts', 
        'pendingRequests', 
        'completedRequests'
    ));
}
    
    public function requestStock(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'flavor_id' => 'required|exists:product_flavors,id',
        'quantity' => 'required|integer|min:1',
        'notes' => 'nullable|string|max:500',
    ]);
    
    $branchId = Auth::user()->branch_id;
    
    $warehouse = WarehouseInventory::where('product_id', $request->product_id)
        ->where('flavor_id', $request->flavor_id)
        ->first();
    
    if (!$warehouse || $warehouse->quantity < $request->quantity) {
        return back()->with('error', 'Insufficient warehouse stock. Available: ' . ($warehouse->quantity ?? 0));
    }
    
    DB::beginTransaction();
    try {
        $transfer = StockTransfer::create([
            'from_branch_id' => null,
            'to_branch_id' => $branchId,
            'product_id' => $request->product_id,
            'flavor_id' => $request->flavor_id,
            'quantity' => $request->quantity,
            'status' => 'pending',
            'transfer_type' => 'warehouse_to_branch',
            'requested_by' => Auth::id(),
            'transfer_number' => 'WH-REQ-' . date('Ymd') . '-' . rand(1000, 9999),
            'notes' => $request->notes,
            'expiration_date' => $warehouse->expiration_date,
        ]);
        
        DB::commit();
        
        // Force clear cache
        \Artisan::call('cache:clear');
        
        return redirect()->route('branch-admin.warehouse.index')->with('success', 'Stock request sent to owner! Transfer #: ' . $transfer->transfer_number);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error creating request: ' . $e->getMessage());
    }
}
    
   public function receiveTransfer(StockTransfer $transfer)
{
    $branchId = Auth::user()->branch_id;
    
    if ($transfer->to_branch_id != $branchId) {
        abort(403, 'Unauthorized action.');
    }
    
    if ($transfer->status != 'approved') {
        return back()->with('error', 'Transfer must be approved first. Current status: ' . $transfer->status);
    }
    
    DB::beginTransaction();
    try {
        // Get product and flavor names safely for logging
        $productName = $transfer->product ? $transfer->product->name : 'Unknown Product';
        $flavorName = $transfer->flavor ? $transfer->flavor->name : 'No Flavor';
        
        \Log::info('Receiving transfer:', [
            'transfer_id' => $transfer->id,
            'expiration_date' => $transfer->expiration_date,
            'product_id' => $transfer->product_id,
            'product_name' => $productName,
            'flavor_id' => $transfer->flavor_id,
            'flavor_name' => $flavorName,
            'quantity' => $transfer->quantity
        ]);
        
        // Find or create branch inventory - MAKE SURE expiration_date is included in the search
        $inventory = BranchInventory::firstOrNew([
            'branch_id' => $branchId,
            'product_id' => $transfer->product_id,
            'flavor_id' => $transfer->flavor_id,
        ]);
        
        $oldQuantity = $inventory->quantity ?? 0;
        $newQuantity = $oldQuantity + $transfer->quantity;
        
        $inventory->quantity = $newQuantity;
        $inventory->last_restocked_at = now();
        
        // CRITICAL: Save the expiration date from the transfer to branch inventory
        if ($transfer->expiration_date) {
            $inventory->expiration_date = $transfer->expiration_date;
            \Log::info('Setting expiration date from transfer:', ['expiration_date' => $transfer->expiration_date]);
        } else {
            // If no expiration date in transfer, try to get from warehouse
            $warehouseStock = WarehouseInventory::where('product_id', $transfer->product_id)
                ->where('flavor_id', $transfer->flavor_id)
                ->first();
            if ($warehouseStock && $warehouseStock->expiration_date) {
                $inventory->expiration_date = $warehouseStock->expiration_date;
                \Log::info('Setting expiration date from warehouse:', ['expiration_date' => $warehouseStock->expiration_date]);
            } else {
                // If still no expiration date, check if inventory already has one
                if ($inventory->exists && $inventory->expiration_date) {
                    // Keep existing expiration date
                    \Log::info('Keeping existing expiration date:', ['expiration_date' => $inventory->expiration_date]);
                } else {
                    $inventory->expiration_date = null;
                    \Log::info('No expiration date set');
                }
            }
        }
        
        $inventory->save();
        
        \Log::info('Branch inventory saved:', [
            'id' => $inventory->id,
            'expiration_date' => $inventory->expiration_date,
            'quantity' => $inventory->quantity
        ]);
        
        // Record stock movement with safe product/flavor names and include expiration
        StockMovement::create([
            'branch_id' => $branchId,
            'product_id' => $transfer->product_id,
            'flavor_id' => $transfer->flavor_id,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'quantity_change' => $transfer->quantity,
            'movement_type' => 'transfer_in',
            'reference_type' => 'stock_transfer',
            'reference_id' => $transfer->id,
            'notes' => 'Received from warehouse: ' . $transfer->transfer_number . ' | Product: ' . $productName . ' | Flavor: ' . $flavorName . ' | Expiry: ' . ($inventory->expiration_date ? \Carbon\Carbon::parse($inventory->expiration_date)->format('Y-m-d') : 'N/A'),
            'created_by' => Auth::id(),
        ]);
        
        // Update transfer status
        $transfer->status = 'completed';
        $transfer->received_by = Auth::id();
        $transfer->received_at = now();
        $transfer->save();
        
        DB::commit();
        
        $expiryMessage = $inventory->expiration_date ? ' Expiration date: ' . \Carbon\Carbon::parse($inventory->expiration_date)->format('M d, Y') : '';
        
        return redirect()->route('branch-admin.warehouse.index')
            ->with('success', 'Stock received! ' . $transfer->quantity . ' units of ' . $productName . ($flavorName != 'No Flavor' ? ' (' . $flavorName . ')' : '') . ' added to inventory.' . $expiryMessage);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error receiving stock: ' . $e->getMessage());
        \Log::error('Transfer ID: ' . $transfer->id);
        return back()->with('error', 'Error receiving stock: ' . $e->getMessage());
    }
}
    
    public function getProductFlavors($productId)
    {
        $product = Product::findOrFail($productId);
        $flavors = $product->flavors()->where('is_active', true)->get(['id', 'name']);
        return response()->json($flavors);
    }
}