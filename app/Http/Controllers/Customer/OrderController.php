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
        $order->load('items.product', 'branch', 'delivery.driver');

        // Get timestamps for each status
        $statusTimestamps = $this->getStatusTimestamps($order);

        // Get delivery status for in_transit
        $deliveryStatus = $order->delivery ? $order->delivery->status : null;

        return view('customer.orders.show', compact('order', 'statusTimestamps', 'deliveryStatus'));
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

    /**
     * Get timestamps for each order status
     */
    private function getStatusTimestamps($order)
    {
        $timestamps = [
            'pending' => $order->created_at,
            'confirmed' => null,
            'packing' => null,
            'ready' => null,
            'out_for_delivery' => null,
            'in_transit' => null,
            'delivered' => null,
        ];

        // Get confirmed timestamp
        if (in_array($order->order_status, ['confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered'])) {
            if (isset($order->confirmed_at) && $order->confirmed_at) {
                $timestamps['confirmed'] = $order->confirmed_at;
            } elseif ($order->delivery && $order->delivery->assigned_at) {
                $timestamps['confirmed'] = $order->delivery->assigned_at;
            } else {
                $timestamps['confirmed'] = $order->updated_at;
            }
        }

        // Get packing timestamp (maps from database 'processing')
        if (in_array($order->order_status, ['processing', 'ready', 'out_for_delivery', 'delivered'])) {
            if (isset($order->processing_at) && $order->processing_at) {
                $timestamps['packing'] = $order->processing_at;
            } else {
                $timestamps['packing'] = $order->updated_at;
            }
        }

        // Get ready timestamp
        if (in_array($order->order_status, ['ready', 'out_for_delivery', 'delivered'])) {
            if (isset($order->ready_at) && $order->ready_at) {
                $timestamps['ready'] = $order->ready_at;
            } else {
                $timestamps['ready'] = $order->updated_at;
            }
        }

        // Get out for delivery timestamp
        if (in_array($order->order_status, ['out_for_delivery', 'delivered'])) {
            if (isset($order->out_for_delivery_at) && $order->out_for_delivery_at) {
                $timestamps['out_for_delivery'] = $order->out_for_delivery_at;
            } elseif ($order->delivery && $order->delivery->assigned_at) {
                $timestamps['out_for_delivery'] = $order->delivery->assigned_at;
            } else {
                $timestamps['out_for_delivery'] = $order->updated_at;
            }
        }

        // Get in_transit timestamp from delivery
        // FIXED: Also show in_transit if picked_up has happened (as a fallback)
        if ($order->delivery) {
            if ($order->delivery->status == 'in_transit') {
                // If driver explicitly set in_transit status
                $timestamps['in_transit'] = $order->delivery->updated_at;
            } elseif ($order->delivery->picked_up_at && !$order->delivery->delivered_at) {
                // If picked up but not delivered yet, use picked_up time as in_transit
                $timestamps['in_transit'] = $order->delivery->picked_up_at;
            } elseif ($order->delivery->picked_up_at && $order->delivery->delivered_at) {
                // If delivered, still show in_transit using picked_up time
                $timestamps['in_transit'] = $order->delivery->picked_up_at;
            }
        }

        // Get delivered timestamp
        if ($order->order_status == 'delivered') {
            if ($order->delivery && $order->delivery->delivered_at) {
                $timestamps['delivered'] = $order->delivery->delivered_at;
            } else {
                $timestamps['delivered'] = $order->updated_at;
            }
        }

        return $timestamps;
    }
}
