<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        $today = Carbon::today();
        
        $data = [
            'branch' => Auth::user()->branch,
            'totalProducts' => Inventory::where('branch_id', $branchId)->count(),
            'totalStockValue' => Inventory::where('branch_id', $branchId)
                ->with('product')
                ->get()
                ->sum(fn($inv) => $inv->quantity * $inv->product->price),
            'lowStockCount' => Inventory::where('branch_id', $branchId)
                ->whereColumn('quantity', '<=', 'low_stock_threshold')
                ->count(),
            'outOfStockCount' => Inventory::where('branch_id', $branchId)
                ->where('quantity', 0)
                ->count(),
            'todaySales' => Order::where('branch_id', $branchId)
                ->whereDate('created_at', $today)
                ->sum('total_amount'),
            'todayOrders' => Order::where('branch_id', $branchId)
                ->whereDate('created_at', $today)
                ->count(),
            'recentProducts' => Inventory::where('branch_id', $branchId)
                ->with('product')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(),
            'lowStockItems' => Inventory::where('branch_id', $branchId)
                ->whereColumn('quantity', '<=', 'low_stock_threshold')
                ->with('product')
                ->orderBy('quantity', 'asc')
                ->limit(10)
                ->get(),
        ];
        
        return view('branch-admin.dashboard', $data);
    }
    
    public function salesReport()
    {
        // Sales report logic
        return view('branch-admin.reports.sales');
    }
    
    public function inventoryReport()
    {
        // Inventory report logic
        return view('branch-admin.reports.inventory');
    }
}