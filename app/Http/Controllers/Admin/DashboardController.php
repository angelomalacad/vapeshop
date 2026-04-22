<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Branch;
use App\Models\User;
use App\Models\BranchInventory;
use App\Models\StockTransfer;
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
        $data['totalOrders'] = Order::where('delivery_type', 'pickup')->count();
        
        // Today's Statistics (POS Orders only)
        $data['todayOrders'] = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', $today)
            ->count();
        
        $data['todaySales'] = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', $today)
            ->sum('total_amount');
        
        $data['todayNewCustomers'] = User::where('role', 'customer')
            ->whereDate('created_at', $today)
            ->count();
        
        // Weekly Statistics (POS Orders only)
        $data['weeklyOrders'] = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', '>=', $startOfWeek)
            ->count();
        
        $data['weeklySales'] = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', '>=', $startOfWeek)
            ->sum('total_amount');
        
        // Monthly Statistics (POS Orders only)
        $data['monthlyOrders'] = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->count();
        
        $data['monthlySales'] = Order::where('delivery_type', 'pickup')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->sum('total_amount');
        
        // Low Stock Alerts
        $data['lowStockItems'] = BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')
            ->with(['product', 'branch'])
            ->limit(10)
            ->get();
        $data['lowStockCount'] = BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count();
        
        // Out of Stock Count
        $data['outOfStockCount'] = BranchInventory::where('quantity', '<=', 0)->count();
        
        // Total Inventory Value
        $data['totalStockValue'] = BranchInventory::with('product')
            ->get()
            ->sum(function($item) {
                return $item->quantity * ($item->product->price ?? 0);
            });
        
        // Pending Transfers
        $data['pendingTransfers'] = StockTransfer::where('status', 'pending')->count();
        
        // Recent Orders (POS Orders only)
        $data['recentOrders'] = Order::with(['user', 'branch'])
            ->where('delivery_type', 'pickup')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Branch Performance (POS Orders only)
        $data['branchPerformance'] = Branch::withCount(['orders as total_orders' => function($query) {
            $query->where('delivery_type', 'pickup');
        }])->get();
        
        // Branch Sales Performance
        $data['branchSales'] = Branch::withSum(['orders as total_sales' => function($query) {
            $query->where('delivery_type', 'pickup');
        }], 'total_amount')->get();
        
        // Sales Chart Data (Last 30 days - POS Orders only)
        $salesData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $total = Order::where('delivery_type', 'pickup')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $ordersCount = Order::where('delivery_type', 'pickup')
                ->whereDate('created_at', $date)
                ->count();
            $salesData['labels'][] = $date->format('M d');
            $salesData['data'][] = $total;
            $salesData['orders'][] = $ordersCount;
        }
        $data['salesChart'] = $salesData;
        
        // Top Selling Products (from POS orders)
        $data['topProducts'] = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.delivery_type', 'pickup')
            ->select('products.name', 'products.price', \DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();
        
        // Sales by Payment Method
        $data['salesByPayment'] = Order::where('delivery_type', 'pickup')
            ->select('payment_method', \DB::raw('count(*) as total_orders'), \DB::raw('sum(total_amount) as total_amount'))
            ->groupBy('payment_method')
            ->get();
        
        // Order Status Distribution (POS Orders only)
        $data['orderStatus'] = Order::where('delivery_type', 'pickup')
            ->select('status', \DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Today's Sales by Hour (for chart)
        $hourlySales = [];
        for ($i = 0; $i <= 23; $i++) {
            $hourlySales[$i] = Order::where('delivery_type', 'pickup')
                ->whereDate('created_at', $today)
                ->whereHour('created_at', $i)
                ->sum('total_amount');
        }
        $data['hourlySales'] = $hourlySales;
        
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
                    'sales' => Order::where('delivery_type', 'pickup')
                        ->whereDate('created_at', $date)
                        ->sum('total_amount'),
                    'orders' => Order::where('delivery_type', 'pickup')
                        ->whereDate('created_at', $date)
                        ->count(),
                ];
            }
        } elseif ($period === 'weekly') {
            // Last 12 weeks
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
                $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
                $data[] = [
                    'week' => 'Week ' . ($i + 1),
                    'sales' => Order::where('delivery_type', 'pickup')
                        ->whereBetween('created_at', [$weekStart, $weekEnd])
                        ->sum('total_amount'),
                    'orders' => Order::where('delivery_type', 'pickup')
                        ->whereBetween('created_at', [$weekStart, $weekEnd])
                        ->count(),
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
                    'sales' => Order::where('delivery_type', 'pickup')
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('total_amount'),
                    'orders' => Order::where('delivery_type', 'pickup')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
                ];
            }
        } elseif ($period === 'yearly') {
            // Last 5 years
            $data = [];
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $start = Carbon::create($year, 1, 1)->startOfDay();
                $end = Carbon::create($year, 12, 31)->endOfDay();
                
                $data[] = [
                    'year' => $year,
                    'sales' => Order::where('delivery_type', 'pickup')
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('total_amount'),
                    'orders' => Order::where('delivery_type', 'pickup')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
                ];
            }
        }
        
        return response()->json($data);
    }
    
    /**
     * Get dashboard summary for API
     */
    public function summary()
    {
        $today = Carbon::today();
        
        $summary = [
            'total_branches' => Branch::count(),
            'total_products' => Product::count(),
            'total_inventory_items' => BranchInventory::count(),
            'low_stock_count' => BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count(),
            'out_of_stock_count' => BranchInventory::where('quantity', '<=', 0)->count(),
            'pending_transfers' => StockTransfer::where('status', 'pending')->count(),
            'total_stock_value' => BranchInventory::with('product')
                ->get()
                ->sum(function($item) {
                    return $item->quantity * ($item->product->price ?? 0);
                }),
            'today_sales' => Order::where('delivery_type', 'pickup')
                ->whereDate('created_at', $today)
                ->sum('total_amount'),
            'today_orders' => Order::where('delivery_type', 'pickup')
                ->whereDate('created_at', $today)
                ->count(),
        ];
        
        return response()->json($summary);
    }
}