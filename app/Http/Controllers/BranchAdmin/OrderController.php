<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
{
    $branchId = Auth::user()->branch_id;
    $orders = Order::where('branch_id', $branchId)->orderBy('created_at', 'desc')->paginate(20);
    return view('branch-admin.orders.index', compact('orders'));
}

public function confirm(Order $order)
{
    if ($order->branch_id != Auth::user()->branch_id) abort(403);
    DB::transaction(function () use ($order) {
        // Deduct actual stock and release reservation
        foreach ($order->items as $item) {
            $inventory = BranchInventory::where('branch_id', $order->branch_id)
                ->where('product_id', $item->product_id)
                ->first();
            if ($inventory) {
                $inventory->confirmReservation($item->quantity);
            }
        }
        $order->update(['status' => 'confirmed']);
        // Notify customer (SMS/email)
    });
    return back()->with('success', 'Order confirmed and stock deducted.');
}

public function reject(Order $order)
{
    // similar to cancel but with stock release
}

public function markReady(Order $order)
{
    $order->update(['status' => 'ready']);
    return back()->with('success', 'Order marked ready for pickup/delivery.');
}

public function assignDriver(Request $request, Order $order)
{
    $request->validate(['driver_id' => 'required|exists:users,id']);
    $driver = User::find($request->driver_id);
    if ($driver->role != 'driver') return back()->with('error', 'Invalid driver');
    
    $delivery = $order->delivery ?? new Delivery();
    $delivery->order_id = $order->id;
    $delivery->driver_id = $driver->id;
    $delivery->tracking_number = 'D-' . $order->order_number;
    $delivery->delivery_address = $order->delivery_address;
    $delivery->recipient_name = $order->customer_name;
    $delivery->recipient_phone = $order->customer_phone;
    $delivery->assigned_at = now();
    $delivery->status = 'assigned';
    $delivery->save();
    
    $order->update(['status' => 'out_for_delivery']);
    
    return back()->with('success', 'Driver assigned.');
}
}
