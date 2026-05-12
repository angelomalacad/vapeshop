<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WarehouseInventory;
use App\Models\StockTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseRequestController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        
        // Get products that are available in warehouse
        $warehouseProducts = WarehouseInventory::with('product')
            ->where('quantity', '>', 0)
            ->get();
        
        // Get pending requests from this branch to warehouse
        $pendingRequests = StockTransfer::where('transfer_type', 'warehouse_to_branch')
            ->where('to_branch_id', $branchId)
            ->where('status', 'pending')
            ->with('product')
            ->get();
        
        // Get completed requests history
        $completedRequests = StockTransfer::where('transfer_type', 'warehouse_to_branch')
            ->where('to_branch_id', $branchId)
            ->whereIn('status', ['completed', 'approved'])
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('branch-admin.warehouse.index', compact('warehouseProducts', 'pendingRequests', 'completedRequests'));
    }
    
    public function requestStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $branchId = Auth::user()->branch_id;
        $warehouse = WarehouseInventory::where('product_id', $request->product_id)->first();
        
        if (!$warehouse || $warehouse->quantity < $request->quantity) {
            return back()->with('error', 'Insufficient warehouse stock. Available: ' . ($warehouse->quantity ?? 0));
        }
        
        DB::beginTransaction();
        try {
            $transfer = StockTransfer::create([
                'from_branch_id' => null,
                'to_branch_id' => $branchId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'status' => 'pending',
                'transfer_type' => 'warehouse_to_branch',
                'requested_by' => Auth::id(),
                'transfer_number' => 'WH-REQ-' . date('Ymd') . '-' . rand(1000, 9999),
                'notes' => $request->notes,
            ]);
            
            DB::commit();
            return redirect()->route('branch-admin.warehouse.index')->with('success', 'Stock request sent to owner!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating request: ' . $e->getMessage());
        }
    }
    
    public function receiveTransfer(StockTransfer $transfer)
    {
        $branchId = Auth::user()->branch_id;
        
        if ($transfer->to_branch_id != $branchId || $transfer->transfer_type != 'warehouse_to_branch') {
            abort(403);
        }
        
        if ($transfer->status != 'approved') {
            return back()->with('error', 'Transfer must be approved by owner first.');
        }
        
        DB::beginTransaction();
        try {
            // Add to branch inventory
            $inventory = \App\Models\BranchInventory::firstOrNew([
                'branch_id' => $branchId,
                'product_id' => $transfer->product_id,
            ]);
            
            $oldQuantity = $inventory->quantity ?? 0;
            $inventory->quantity = $oldQuantity + $transfer->quantity;
            $inventory->last_restocked_at = now();
            $inventory->save();
            
            // Update transfer status
            $transfer->status = 'completed';
            $transfer->completed_at = now();
            $transfer->save();
            
            DB::commit();
            return redirect()->route('branch-admin.warehouse.index')->with('success', 'Stock received and added to inventory!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error receiving stock: ' . $e->getMessage());
        }
    }
}