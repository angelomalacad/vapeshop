<?php
// app/Http/Controllers/Admin/InventoryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\BranchInventory;
use App\Models\StockTransfer;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display inventory overview for all branches
     */
    public function index(Request $request)
    {
        $branches = Branch::all();
        $products = Product::with('flavors')->get();
        
        $query = BranchInventory::with(['branch', 'product', 'flavor']);
        
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
                $query->lowStock();
            } elseif ($request->stock_status === 'out') {
                $query->outOfStock();
            }
        }
        
        $inventories = $query->paginate(20);
        
        // Get low stock counts for alerts
        $lowStockCount = BranchInventory::lowStock()->count();
        $outOfStockCount = BranchInventory::outOfStock()->count();
        
        return view('admin.inventory.index', compact(
            'branches', 'products', 'inventories', 
            'lowStockCount', 'outOfStockCount'
        ));
    }

    /**
     * Show low stock items across all branches
     */
    public function lowStock()
    {
        $lowStockItems = BranchInventory::with(['branch', 'product', 'flavor'])
            ->lowStock()
            ->orderBy('branch_id')
            ->orderBy('product_id')
            ->get()
            ->groupBy('branch.name');
        
        return view('admin.inventory.low-stock', compact('lowStockItems'));
    }

    /**
     * Show stock transfer requests
     */
    public function transfers(Request $request)
    {
        $query = StockTransfer::with([
            'fromBranch', 'toBranch', 'product', 'flavor', 'requester', 'approver'
        ]);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $transfers = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.inventory.transfers', compact('transfers'));
    }

    /**
     * Approve stock transfer
     */
    public function approveTransfer(StockTransfer $transfer)
    {
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
     * View stock movement history
     */
    public function movements(Request $request)
    {
        $query = StockMovement::with(['branch', 'product', 'flavor', 'creator'])
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        
        $movements = $query->paginate(50);
        $branches = Branch::all();
        $products = Product::all();
        
        return view('admin.inventory.movements', compact('movements', 'branches', 'products'));
    }

    /**
     * Export inventory report
     */
    public function export(Request $request)
    {
        $query = BranchInventory::with(['branch', 'product', 'flavor']);
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        $inventories = $query->get();
        
        // Generate CSV
        $filename = 'inventory-report-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Add headers
        fputcsv($handle, [
            'Branch', 'Product', 'Flavor', 'Quantity', 'Available', 
            'Reserved', 'Low Stock Threshold', 'Status', 'Last Restocked'
        ]);
        
        foreach ($inventories as $inv) {
            fputcsv($handle, [
                $inv->branch->name,
                $inv->product->name,
                $inv->flavor->name ?? 'N/A',
                $inv->quantity,
                $inv->available_quantity,
                $inv->reserved_quantity,
                $inv->low_stock_threshold,
                $inv->stock_status['label'],
                $inv->last_restocked_at ? $inv->last_restocked_at->format('Y-m-d H:i') : 'N/A',
            ]);
        }
        
        fclose($handle);
        exit;
    }
}