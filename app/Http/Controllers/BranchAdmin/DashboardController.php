<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Order;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $branchId = $user->branch_id;
        $branch = $user->branch;
        $today = Carbon::today();
        
        // Initialize all variables
        $totalProducts = 0;
        $lowStockCount = 0;
        $outOfStockCount = 0;
        $todayOrders = 0;
        $todaySales = 0;
        $pendingTransfersIncoming = 0;
        $pendingTransfersOutgoing = 0;
        $pendingTransfersTotal = 0;
        $totalStockValue = 0;
        $recentProducts = collect();
        
        if ($branchId) {
            // TOTAL PRODUCTS
            $totalProducts = BranchInventory::where('branch_id', $branchId)->count();
            
            // LOW STOCK
            $lowStockCount = BranchInventory::where('branch_id', $branchId)
                ->whereRaw('quantity <= low_stock_threshold')
                ->count();
            
            // OUT OF STOCK
            $outOfStockCount = BranchInventory::where('branch_id', $branchId)
                ->where('quantity', 0)
                ->count();
            
            // TODAY'S ORDERS
            $todayOrders = Order::where('branch_id', $branchId)
                ->whereDate('created_at', $today)
                ->count();
            
            // TODAY'S SALES
            $todaySales = Order::where('branch_id', $branchId)
                ->whereDate('created_at', $today)
                ->sum('total_amount');
            
            // PENDING TRANSFERS - INCOMING (to your branch)
            $pendingTransfersIncoming = StockTransfer::where('to_branch_id', $branchId)
                ->where('status', 'pending')
                ->count();
            
            // PENDING TRANSFERS - OUTGOING (from your branch)
            $pendingTransfersOutgoing = StockTransfer::where('from_branch_id', $branchId)
                ->where('status', 'pending')
                ->count();
            
            // TOTAL PENDING TRANSFERS (incoming + outgoing)
            $pendingTransfersTotal = $pendingTransfersIncoming + $pendingTransfersOutgoing;
            
            // TOTAL STOCK VALUE
            $inventoryItems = BranchInventory::where('branch_id', $branchId)
                ->with('product')
                ->get();
            
            foreach ($inventoryItems as $item) {
                if ($item->product && $item->product->price) {
                    $totalStockValue += $item->quantity * $item->product->price;
                }
            }
            
            // RECENT PRODUCTS
            $recentProducts = BranchInventory::where('branch_id', $branchId)
                ->with('product')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
        
        return view('branch-admin.dashboard', [
            'branch' => $branch,
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'todayOrders' => $todayOrders,
            'todaySales' => $todaySales,
            'pendingTransfersIncoming' => $pendingTransfersIncoming,
            'pendingTransfersOutgoing' => $pendingTransfersOutgoing,
            'pendingTransfersTotal' => $pendingTransfersTotal,
            'totalStockValue' => $totalStockValue,
            'recentProducts' => $recentProducts
        ]);
    }
}