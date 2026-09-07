<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OnlineOrderController extends Controller
{
    /**
     * Display a listing of online orders (owner view - read only)
     */
    public function index(Request $request)
    {
        $query = Order::where('order_number', 'NOT LIKE', 'POS-%')
            ->with(['branch', 'delivery.driver', 'items.product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get counts for each status
        $counts = [
            'pending' => Order::where('order_status', 'pending')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'processing' => Order::where('order_status', 'processing')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'ready' => Order::where('order_status', 'ready')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'picked_up' => Order::where('order_status', 'picked_up')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'out_for_delivery' => Order::where('order_status', 'out_for_delivery')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'delivered' => Order::where('order_status', 'delivered')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        ];

        return view('admin.online-orders.index', compact('orders', 'counts'));
    }

    /**
     * Show order details modal (owner view - read only)
     */
    public function showModal(Order $order)
    {
        $order->load(['items.product', 'branch', 'delivery.driver']);
        return view('admin.online-orders.show-modal', compact('order'));
    }
}
