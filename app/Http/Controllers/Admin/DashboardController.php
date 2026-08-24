<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Order;
use App\Models\Product;
use App\Models\Branch;
use App\Models\User;
use App\Models\StockTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfWeek = Carbon::now()->startOfWeek();

        // ===== EXPIRING SOON =====
        $expiringSoon = BranchInventory::whereNotNull('expiration_date')
            ->where('expiration_date', '>=', Carbon::today())
            ->where('expiration_date', '<=', Carbon::today()->addDays(30))
            ->with(['product', 'branch'])
            ->orderBy('expiration_date', 'asc')
            ->limit(10)
            ->get();

        // ===== FASTEST MOVING PRODUCTS =====
        $fastMovingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // ===== ONLINE ORDER STATUS =====
        $onlineOrderStatus = Order::where('delivery_type', 'delivery')
            ->selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->pluck('count', 'order_status')
            ->toArray();

        // ===== REPEAT CUSTOMER RATE =====
        $repeatCustomers = DB::table('orders')
            ->where('payment_status', 'paid')
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->having('order_count', '>', 1)
            ->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $repeatCustomerRate = $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100, 1) : 0;

        // ===== DELIVERY VS PICKUP (this month, paid only) =====
        $currentMonthStart = Carbon::now()->startOfMonth();
        $deliveryVsPickup = [
            'delivery_sales' => Order::where('delivery_type', 'delivery')
                ->where('payment_status', 'paid')
                ->where('created_at', '>=', $currentMonthStart)
                ->sum('total_amount'),
            'pickup_sales' => Order::where('delivery_type', 'pickup')
                ->where('payment_status', 'paid')
                ->where('created_at', '>=', $currentMonthStart)
                ->sum('total_amount'),
        ];

        // ===== SALES STATS =====
        $totalOrders = Order::where('payment_status', 'paid')->count();
        $todayOrders = Order::where('payment_status', 'paid')->whereDate('created_at', $today)->count();
        $todaySales = Order::where('payment_status', 'paid')->whereDate('created_at', $today)->sum('total_amount');
        $weeklyOrders = Order::where('payment_status', 'paid')->whereDate('created_at', '>=', $startOfWeek)->count();
        $weeklySales = Order::where('payment_status', 'paid')->whereDate('created_at', '>=', $startOfWeek)->sum('total_amount');
        $monthlyOrders = Order::where('payment_status', 'paid')->whereDate('created_at', '>=', $startOfMonth)->count();
        $monthlySales = Order::where('payment_status', 'paid')->whereDate('created_at', '>=', $startOfMonth)->sum('total_amount');

        // ===== INVENTORY & BRANCH STATS =====
        $totalProducts = Product::count();
        $totalBranches = Branch::count();
        $totalStaff = User::whereIn('role', ['branch_admin', 'staff'])->count();
        $totalInventoryItems = BranchInventory::count();
        $lowStockCount = BranchInventory::whereColumn('quantity', '<=', 'low_stock_threshold')->count();
        $outOfStockCount = BranchInventory::where('quantity', '<=', 0)->count();
        $totalStockValue = BranchInventory::with('product')->get()
            ->sum(fn($item) => $item->quantity * ($item->product->price ?? 0));
        $pendingTransfers = StockTransfer::where('status', 'pending')->count();

        // ===== CHART DATA =====
        $salesData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $total = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
            $ordersCount = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->count();
            $salesData['labels'][] = $date->format('M d');
            $salesData['data'][] = $total;
            $salesData['orders'][] = $ordersCount;
        }

        // ===== HOURLY SALES - FIXED VERSION =====
        $hourlySales = [];
        for ($i = 0; $i <= 23; $i++) {
            $hourlySales[$i] = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $today)
                ->whereRaw('HOUR(created_at) = ?', [$i])
                ->sum('total_amount');
        }

        // ===== RECENT ORDERS =====
        $recentOrders = Order::with(['user', 'branch'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $branchPerformance = Branch::withCount('orders')->get();
        $branchSales = Branch::withSum('orders', 'total_amount')->get();

        return view('admin.dashboard', [
            'expiringSoon' => $expiringSoon,
            'fastMovingProducts' => $fastMovingProducts,
            'onlineOrderStatus' => $onlineOrderStatus,
            'repeatCustomerRate' => $repeatCustomerRate,
            'deliveryVsPickup' => $deliveryVsPickup,
            'totalOrders' => $totalOrders,
            'todayOrders' => $todayOrders,
            'todaySales' => $todaySales,
            'weeklyOrders' => $weeklyOrders,
            'weeklySales' => $weeklySales,
            'monthlyOrders' => $monthlyOrders,
            'monthlySales' => $monthlySales,
            'totalProducts' => $totalProducts,
            'totalBranches' => $totalBranches,
            'totalStaff' => $totalStaff,
            'totalCustomers' => $totalCustomers,
            'totalInventoryItems' => $totalInventoryItems,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'totalStockValue' => $totalStockValue,
            'pendingTransfers' => $pendingTransfers,
            'salesChart' => $salesData,
            'hourlySales' => $hourlySales,
            'recentOrders' => $recentOrders,
            'branchPerformance' => $branchPerformance,
            'branchSales' => $branchSales,
        ]);
    }

    public function getSalesData(Request $request)
    {
        $period = $request->get('period', 'monthly');

        if ($period === 'daily') {
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'sales' => Order::where('payment_status', 'paid')
                        ->whereDate('created_at', $date)->sum('total_amount'),
                    'orders' => Order::where('payment_status', 'paid')
                        ->whereDate('created_at', $date)->count(),
                ];
            }
        } elseif ($period === 'weekly') {
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
                $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
                $data[] = [
                    'week' => 'Week ' . ($i + 1),
                    'sales' => Order::where('payment_status', 'paid')
                        ->whereBetween('created_at', [$weekStart, $weekEnd])->sum('total_amount'),
                    'orders' => Order::where('payment_status', 'paid')
                        ->whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                ];
            }
        } elseif ($period === 'monthly') {
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                $data[] = [
                    'month' => $date->format('M Y'),
                    'sales' => Order::where('payment_status', 'paid')
                        ->whereBetween('created_at', [$start, $end])->sum('total_amount'),
                    'orders' => Order::where('payment_status', 'paid')
                        ->whereBetween('created_at', [$start, $end])->count(),
                ];
            }
        } elseif ($period === 'yearly') {
            $data = [];
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $start = Carbon::create($year, 1, 1)->startOfDay();
                $end = Carbon::create($year, 12, 31)->endOfDay();
                $data[] = [
                    'year' => $year,
                    'sales' => Order::where('payment_status', 'paid')
                        ->whereBetween('created_at', [$start, $end])->sum('total_amount'),
                    'orders' => Order::where('payment_status', 'paid')
                        ->whereBetween('created_at', [$start, $end])->count(),
                ];
            }
        }

        return response()->json($data);
    }

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
            'total_stock_value' => BranchInventory::with('product')->get()
                ->sum(fn($item) => $item->quantity * ($item->product->price ?? 0)),
            'today_sales' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', $today)->sum('total_amount'),
            'today_orders' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', $today)->count(),
        ];

        return response()->json($summary);
    }

    // =============================================
    // 🔥 NEW ANALYTICS METHODS - ADDED BELOW 🔥
    // =============================================

    /**
     * Get monthly orders data for the line chart
     */
    public function monthlyOrders(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // Initialize data arrays
        $monthlyData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'pos' => array_fill(0, 12, 0),
            'online' => array_fill(0, 12, 0),
            'total' => array_fill(0, 12, 0)
        ];

        // Check if Order model exists and has data
        if (class_exists('\App\Models\Order')) {
            // Get POS orders by month
            $posOrders = Order::where('order_number', 'LIKE', 'POS-%')
                ->whereYear('created_at', $year)
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->get();

            foreach ($posOrders as $order) {
                $monthlyData['pos'][$order->month - 1] = $order->count;
            }

            // Get Online orders by month
            $onlineOrders = Order::where('order_number', 'LIKE', 'ORD-%')
                ->whereYear('created_at', $year)
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->groupBy('month')
                ->get();

            foreach ($onlineOrders as $order) {
                $monthlyData['online'][$order->month - 1] = $order->count;
            }

            // Calculate totals
            for ($i = 0; $i < 12; $i++) {
                $monthlyData['total'][$i] = $monthlyData['pos'][$i] + $monthlyData['online'][$i];
            }
        }

        return response()->json($monthlyData);
    }

    /**
     * Get sales comparison data for the bar chart
     */
    public function salesComparison(Request $request)
    {
        $type = $request->get('type', 'brand');
        $range = $request->get('range', 'week');
        $startDate = $request->get('start');
        $endDate = $request->get('end');

        $query = Order::where('payment_status', 'paid');

        // Apply date range filter
        switch ($range) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                }
                break;
        }

        // Get order items with product details
        $orderItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.id', $query->pluck('id'))
            ->select(
                'products.id',
                'products.name',
                'products.brand',
                'products.category',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->groupBy('products.id', 'products.name', 'products.brand', 'products.category')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        // Prepare data based on comparison type
        $labels = [];
        $data = [];
        $colors = ['#0d6efd', '#dc3545', '#198754', '#ffc107', '#6f42c1', '#fd7e14', '#20c997', '#0dcaf0', '#d63384', '#6610f2'];

        if ($type === 'brand') {
            // Group by brand
            $brandSales = [];
            foreach ($orderItems as $item) {
                $brand = $item->brand ?: 'Unbranded';
                if (!isset($brandSales[$brand])) {
                    $brandSales[$brand] = 0;
                }
                $brandSales[$brand] += $item->total_sales;
            }
            arsort($brandSales);
            $labels = array_keys($brandSales);
            $data = array_values($brandSales);
        } elseif ($type === 'category') {
            // Group by category
            $categorySales = [];
            foreach ($orderItems as $item) {
                $category = $item->category ?: 'Uncategorized';
                if (!isset($categorySales[$category])) {
                    $categorySales[$category] = 0;
                }
                $categorySales[$category] += $item->total_sales;
            }
            arsort($categorySales);
            $labels = array_keys($categorySales);
            $data = array_values($categorySales);
        } else {
            // Product (default)
            $labels = $orderItems->pluck('name')->toArray();
            $data = $orderItems->pluck('total_sales')->toArray();
        }

        // Limit to top 10
        if (count($labels) > 10) {
            $labels = array_slice($labels, 0, 10);
            $data = array_slice($data, 0, 10);
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ]);
    }
}