<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        if ($order->user_id != Auth::id()) abort(403);
        $order->load('items.product', 'branch', 'delivery');
        return view('customer.orders.show', compact('order'));
    }
    
    public function cancel(Order $order)
    {
        if ($order->user_id != Auth::id() || !in_array($order->order_status, ['pending'])) {
            return back()->with('error', 'Cannot cancel this order.');
        }
        
        DB::transaction(function () use ($order) {
            // Release reserved stock
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('branch_id', $order->branch_id)
                    ->where('product_id', $item->product_id)
                    ->first();
                if ($inventory) {
                    $inventory->releaseReservation($item->quantity);
                }
            }
            $order->update(['order_status' => 'cancelled']);
        });
        
        return redirect()->route('customer.orders.index')->with('success', 'Order cancelled.');
    }
    
    public function track(Order $order)
    {
        if ($order->user_id != Auth::id()) abort(403);
        $delivery = $order->delivery;
        return view('customer.orders.track', compact('order', 'delivery'));
    }
}