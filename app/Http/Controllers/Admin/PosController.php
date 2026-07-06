<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Sales history - Complete method with payment proof support
     */
    public function history(Request $request)
    {
        $query = Order::with(['branch', 'user', 'items.product'])
            ->where('delivery_type', 'pickup')
            ->orderBy('created_at', 'desc');
        
        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        // Filter by date from
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        // Filter by date to
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by customer name
        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Get paginated results
        $orders = $query->paginate(20);
        
        // Get all branches for filter dropdown
        $branches = Branch::where('is_active', true)->get();
        
        // Summary Statistics
        $totalSales = Order::where('delivery_type', 'pickup')->sum('total_amount');
        $totalOrders = Order::where('delivery_type', 'pickup')->count();
        $todaySales = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', today())
            ->sum('total_amount');
        $todayOrders = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', today())
            ->count();
        
        // Sales by Branch
        $salesByBranch = Branch::withCount(['orders' => function($query) {
            $query->where('delivery_type', 'pickup');
        }])->withSum(['orders' => function($query) {
            $query->where('delivery_type', 'pickup');
        }], 'total_amount')->get();
        
        // Sales by Payment Method
        $salesByPayment = Order::where('delivery_type', 'pickup')
            ->select('payment_method', 
                DB::raw('count(*) as total_orders'), 
                DB::raw('sum(total_amount) as total_amount'))
            ->groupBy('payment_method')
            ->get();
        
        return view('admin.pos.history', compact(
            'orders',
            'branches',
            'totalSales',
            'totalOrders',
            'todaySales',
            'todayOrders',
            'salesByBranch',
            'salesByPayment'
        ));
    }
    
    /**
     * Show order receipt/invoice
     */
    public function showOrder($id)
    {
        $order = Order::with(['branch', 'user', 'items.product'])
            ->where('delivery_type', 'pickup')
            ->findOrFail($id);
        
        return view('admin.pos.show-order', compact('order'));
    }
}