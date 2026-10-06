<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Cart; // <-- ADDED: change to your cart model name if different (e.g. CartItem)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get recent orders (last 5)
        $recentOrders = Order::where('user_id', Auth::id())
            ->with(['branch', 'delivery'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get order counts by status
        $orderCounts = [
            'pending' => Order::where('user_id', Auth::id())->where('order_status', 'pending')->count(),
            'processing' => Order::where('user_id', Auth::id())->where('order_status', 'processing')->count(),
            'out_for_delivery' => Order::where('user_id', Auth::id())->where('order_status', 'out_for_delivery')->count(),
            'delivered' => Order::where('user_id', Auth::id())->where('order_status', 'delivered')->count(),
            'cancelled' => Order::where('user_id', Auth::id())->where('order_status', 'cancelled')->count(),
        ];

        // Get total spent
        $totalSpent = Order::where('user_id', Auth::id())
            ->where('order_status', 'delivered')
            ->sum('total_amount');

        // ADDED: Get cart items count for the logged-in customer
        // sum('quantity') = total pieces (2x A + 3x B = 5)
        // For number of different products instead (= 2), use ->count()
        $cartCount = (int) Cart::where('user_id', Auth::id())->sum('quantity');

        // Get branches for map
        $branches = Branch::where('is_active', true)->get();

        return view('customer.dashboard', compact('user', 'recentOrders', 'orderCounts', 'totalSpent', 'branches', 'cartCount'));
    }
}