<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\BranchInventory;
use App\Models\InventoryReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product', 'items.flavor']) 
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // ✅ ADD: Transform orders to include status badge class and label
        $orders->getCollection()->transform(function ($order) {
            $statusColors = [
                'pending' => 'warning',
                'confirmed' => 'info',
                'processing' => 'primary',
                'ready' => 'success',
                'picked_up' => 'secondary',
                'out_for_delivery' => 'secondary',
                'delivered' => 'dark',
                'cancelled' => 'danger',
            ];
            $statusLabels = [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'ready' => 'Ready',
                'picked_up' => 'Picked Up',
                'out_for_delivery' => 'Out for Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ];

            $order->order_status_badge_class = 'bg-' . ($statusColors[$order->order_status] ?? 'secondary');
            $order->order_status_label = $statusLabels[$order->order_status] ?? ucfirst(str_replace('_', ' ', $order->order_status));

            return $order;
        });

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id != Auth::id()) abort(403);
        $order->load('items.product', 'items.flavor', 'branch', 'delivery.driver');

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
            $reservations = InventoryReservation::where('order_id', $order->id)
                ->where('status', 'active')
                ->get();

            foreach ($reservations as $reservation) {
                $inventory = BranchInventory::where('id', $reservation->branch_inventory_id)->first();
                
                if ($inventory) {
                    $inventory->update([
                        'reserved_quantity' => max(0, $inventory->reserved_quantity - $reservation->quantity)
                    ]);
                }
                
                $reservation->update([
                    'status' => 'released',
                    'released_at' => now()
                ]);
            }
            
            foreach ($order->items as $item) {
                $inventory = BranchInventory::where('id', $item->inventory_id)->first();
                
                if ($inventory) {
                    if ($inventory->reserved_quantity > 0) {
                        $quantityToRelease = min($item->quantity, $inventory->reserved_quantity);
                        $inventory->decrement('reserved_quantity', $quantityToRelease);
                    }
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
            'picked_up' => null, // ✅ ADD THIS
            'out_for_delivery' => null,
            'in_transit' => null,
            'delivered' => null,
        ];

        $ensureCarbon = function($value) {
            if ($value instanceof Carbon) {
                return $value;
            }
            if ($value) {
                return Carbon::parse($value);
            }
            return null;
        };

        // Get confirmed timestamp
        if (in_array($order->order_status, ['confirmed', 'processing', 'ready', 'picked_up', 'out_for_delivery', 'delivered'])) {
            if (isset($order->confirmed_at) && $order->confirmed_at) {
                $timestamps['confirmed'] = $ensureCarbon($order->confirmed_at);
            } elseif ($order->delivery && $order->delivery->assigned_at) {
                $timestamps['confirmed'] = $ensureCarbon($order->delivery->assigned_at);
            } else {
                $timestamps['confirmed'] = $order->updated_at;
            }
        }

        // Get packing timestamp (maps from database 'processing')
        if (in_array($order->order_status, ['processing', 'ready', 'picked_up', 'out_for_delivery', 'delivered'])) {
            if (isset($order->processing_at) && $order->processing_at) {
                $timestamps['packing'] = $ensureCarbon($order->processing_at);
            } else {
                $timestamps['packing'] = $order->updated_at;
            }
        }

        // Get ready timestamp
        if (in_array($order->order_status, ['ready', 'picked_up', 'out_for_delivery', 'delivered'])) {
            if (isset($order->ready_at) && $order->ready_at) {
                $timestamps['ready'] = $ensureCarbon($order->ready_at);
            } else {
                $timestamps['ready'] = $order->updated_at;
            }
        }

        // ✅ ADD: Get picked_up timestamp
        if (in_array($order->order_status, ['picked_up', 'out_for_delivery', 'delivered'])) {
            if ($order->delivery && $order->delivery->picked_up_at) {
                $timestamps['picked_up'] = $ensureCarbon($order->delivery->picked_up_at);
            } elseif ($order->out_for_delivery_at) {
                $timestamps['picked_up'] = $ensureCarbon($order->out_for_delivery_at);
            } else {
                $timestamps['picked_up'] = $order->updated_at;
            }
        }

        // Get out for delivery timestamp
        if (in_array($order->order_status, ['out_for_delivery', 'delivered'])) {
            if (isset($order->out_for_delivery_at) && $order->out_for_delivery_at) {
                $timestamps['out_for_delivery'] = $ensureCarbon($order->out_for_delivery_at);
            } elseif ($order->delivery && $order->delivery->assigned_at) {
                $timestamps['out_for_delivery'] = $ensureCarbon($order->delivery->assigned_at);
            } else {
                $timestamps['out_for_delivery'] = $order->updated_at;
            }
        }

        // Get in_transit timestamp from delivery
        if ($order->delivery) {
            if ($order->delivery->status == 'in_transit') {
                $timestamps['in_transit'] = $ensureCarbon($order->delivery->updated_at);
            } elseif ($order->delivery->picked_up_at && !$order->delivery->delivered_at) {
                $timestamps['in_transit'] = $ensureCarbon($order->delivery->picked_up_at);
            } elseif ($order->delivery->picked_up_at && $order->delivery->delivered_at) {
                $timestamps['in_transit'] = $ensureCarbon($order->delivery->picked_up_at);
            }
        }

        // Get delivered timestamp
        if ($order->order_status == 'delivered') {
            if ($order->delivery && $order->delivery->delivered_at) {
                $timestamps['delivered'] = $ensureCarbon($order->delivery->delivered_at);
            } else {
                $timestamps['delivered'] = $order->updated_at;
            }
        }

        return $timestamps;
    }
}