<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Branch;
use App\Models\User;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfWeek = Carbon::now()->startOfWeek();
        
        // Overall Statistics
        $data['totalBranches'] = Branch::count();
        $data['totalProducts'] = Product::count();
        $data['totalCustomers'] = User::where('role', 'customer')->count();
        $data['totalOrders'] = Order::count();
        
        // Today's Statistics
        $data['todayOrders'] = Order::whereDate('created_at', $today)->count();
        $data['todaySales'] = Order::whereDate('created_at', $today)->sum('total_amount');
        $data['todayNewCustomers'] = User::where('role', 'customer')
            ->whereDate('created_at', $today)
            ->count();
        
        // Low Stock Alerts
        $data['lowStockItems'] = Inventory::whereColumn('quantity', '<=', 'low_stock_threshold')
            ->with(['product', 'branch'])
            ->limit(10)
            ->get();
        $data['lowStockCount'] = $data['lowStockItems']->count();
        
        // Recent Orders
        $data['recentOrders'] = Order::with(['user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Branch Performance
        $data['branchPerformance'] = Branch::withCount(['orders as total_orders', 'orders as total_sales' => function($query) {
            $query->select(\DB::raw('SUM(total_amount)'));
        }])->get();
        
        // Sales Chart Data (Last 30 days)
        $salesData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $total = Order::whereDate('created_at', $date)->sum('total_amount');
            $salesData['labels'][] = $date->format('M d');
            $salesData['data'][] = $total;
        }
        $data['salesChart'] = $salesData;
        
        // Top Selling Products
        $data['topProducts'] = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', \DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();
        
        // Order Status Distribution
        $data['orderStatus'] = Order::select('status', \DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        return view('admin.dashboard', $data);
    }
    
    public function getSalesData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        
        if ($period === 'daily') {
            // Last 7 days
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'sales' => Order::whereDate('created_at', $date)->sum('total_amount'),
                    'orders' => Order::whereDate('created_at', $date)->count(),
                ];
            }
        } elseif ($period === 'monthly') {
            // Last 12 months
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                
                $data[] = [
                    'month' => $date->format('M Y'),
                    'sales' => Order::whereBetween('created_at', [$start, $end])->sum('total_amount'),
                    'orders' => Order::whereBetween('created_at', [$start, $end])->count(),
                ];
            }
        }
        
        return response()->json($data);
    }
}