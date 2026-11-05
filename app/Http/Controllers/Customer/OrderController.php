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

        $orders->getCollection()->transform(function ($order) {
            $statusColors = [
                'pending' => 'warning',
                'confirmed' => 'info',
                'processing' => 'primary',
                'ready' => 'success',
                'picked_up' => 'secondary',
                'out_for_delivery' => 'secondary',
                'delivered' => 'dark',
                'delivery_failed' => 'danger',
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
                'delivery_failed' => 'Delivery Failed',
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

        $statusTimestamps = $this->getStatusTimestamps($order);
        $deliveryStatus = $order->delivery ? $order->delivery->status : null;

        // ✅ ADD THIS LINE TO ENSURE BRANCH IS ALWAYS LOADED
        $order->load('branch'); 

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

        public function removeItem(Request $request, Order $order)
    {
        if ($order->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($order->order_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Cannot remove items. Order is already being processed.'], 400);
        }

        $itemId = $request->input('item_id');
        $qtyToRemove = (int) $request->input('quantity', 1); 

        $item = $order->items()->find($itemId);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }

        if ($qtyToRemove <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid quantity.'], 400);
        }

        if ($qtyToRemove > $item->quantity) {
            return response()->json(['success' => false, 'message' => 'You are trying to remove more items than you have.'], 400);
        }

        $totalOrderItems = $order->items()->sum('quantity');
        if (($totalOrderItems - $qtyToRemove) <= 0) {
            return response()->json(['success' => false, 'message' => 'Cannot remove all items. Please use the Cancel Order button instead.'], 400);
        }

        try {
            DB::transaction(function () use ($order, $item, $qtyToRemove) {
                
                $reservation = InventoryReservation::where('order_id', $order->id)
                    ->where('branch_inventory_id', $item->inventory_id)
                    ->where('status', 'active')
                    ->first();

                $releaseAmount = min($qtyToRemove, $item->quantity); 
                
                if ($reservation) {
                    $inventory = BranchInventory::find($reservation->branch_inventory_id);
                    if ($inventory) {
                        $inventory->update([
                            'reserved_quantity' => max(0, $inventory->reserved_quantity - $releaseAmount)
                        ]);
                    }
                    
                    if ($reservation->quantity <= $releaseAmount) {
                        $reservation->update([
                            'status' => 'released',
                            'released_at' => now()
                        ]);
                    } else {
                        $reservation->decrement('quantity', $releaseAmount);
                    }
                }

                $inventoryDirect = BranchInventory::where('id', $item->inventory_id)->first();
                if ($inventoryDirect && $inventoryDirect->reserved_quantity > 0) {
                    $inventoryDirect->decrement('reserved_quantity', $releaseAmount);
                }

                if ($qtyToRemove >= $item->quantity) {
                    $item->delete();
                } else {
                    $newItemQty = $item->quantity - $qtyToRemove;
                    $newItemSubtotal = $newItemQty * $item->price;
                    
                    $item->update([
                        'quantity' => $newItemQty,
                        'subtotal' => $newItemSubtotal
                    ]);
                }

                $newSubtotal = $order->items()->sum('subtotal');
                $newTotal = $newSubtotal + ($order->delivery_fee ?? 0);

                // ✅ Force update directly to the database to preserve ALL other columns (like branch_id)
                Order::where('id', $order->id)->update([
                    'subtotal' => $newSubtotal,
                    'total_amount' => $newTotal,
                ]);
            });

            session()->flash('success', 'Item removed from order successfully.');
            
            return response()->json([
                'success' => true, 
                'message' => 'Item removed from order successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Remove Item Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while removing the item.'], 500);
        }
    }

    private function getStatusTimestamps($order)
    {
        $timestamps = [
            'pending' => $order->created_at,
            'confirmed' => null,
            'packing' => null,
            'ready' => null,
            'picked_up' => null, 
            'out_for_delivery' => null,
            'in_transit' => null,
            'delivered' => null,
            'failed' => null, 
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

        if (in_array($order->order_status, ['confirmed', 'processing', 'ready', 'picked_up', 'out_for_delivery', 'delivered'])) {
            if (isset($order->confirmed_at) && $order->confirmed_at) {
                $timestamps['confirmed'] = $ensureCarbon($order->confirmed_at);
            } elseif ($order->delivery && $order->delivery->assigned_at) {
                $timestamps['confirmed'] = $ensureCarbon($order->delivery->assigned_at);
            } else {
                $timestamps['confirmed'] = $order->updated_at;
            }
        }

        if (in_array($order->order_status, ['processing', 'ready', 'picked_up', 'out_for_delivery', 'delivered'])) {
            if (isset($order->processing_at) && $order->processing_at) {
                $timestamps['packing'] = $ensureCarbon($order->processing_at);
            } else {
                $timestamps['packing'] = $order->updated_at;
            }
        }

        if (in_array($order->order_status, ['ready', 'picked_up', 'out_for_delivery', 'delivered'])) {
            if (isset($order->ready_at) && $order->ready_at) {
                $timestamps['ready'] = $ensureCarbon($order->ready_at);
            } else {
                $timestamps['ready'] = $order->updated_at;
            }
        }

        if (in_array($order->order_status, ['picked_up', 'out_for_delivery', 'delivered'])) {
            if ($order->delivery && $order->delivery->picked_up_at) {
                $timestamps['picked_up'] = $ensureCarbon($order->delivery->picked_up_at);
            } elseif ($order->out_for_delivery_at) {
                $timestamps['picked_up'] = $ensureCarbon($order->out_for_delivery_at);
            } else {
                $timestamps['picked_up'] = $order->updated_at;
            }
        }

        if (in_array($order->order_status, ['out_for_delivery', 'delivered'])) {
            if (isset($order->out_for_delivery_at) && $order->out_for_delivery_at) {
                $timestamps['out_for_delivery'] = $ensureCarbon($order->out_for_delivery_at);
            } elseif ($order->delivery && $order->delivery->assigned_at) {
                $timestamps['out_for_delivery'] = $ensureCarbon($order->delivery->assigned_at);
            } else {
                $timestamps['out_for_delivery'] = $order->updated_at;
            }
        }

        if ($order->delivery) {
            if ($order->delivery->status == 'in_transit') {
                $timestamps['in_transit'] = $ensureCarbon($order->delivery->updated_at);
            } elseif ($order->delivery->picked_up_at && !$order->delivery->delivered_at) {
                $timestamps['in_transit'] = $ensureCarbon($order->delivery->picked_up_at);
            } elseif ($order->delivery->picked_up_at && $order->delivery->delivered_at) {
                $timestamps['in_transit'] = $ensureCarbon($order->delivery->picked_up_at);
            }
        }

        if ($order->order_status == 'delivered') {
            if ($order->delivery && $order->delivery->delivered_at) {
                $timestamps['delivered'] = $ensureCarbon($order->delivery->delivered_at);
            } else {
                $timestamps['delivered'] = $order->updated_at;
            }
        }

        if ($order->order_status == 'delivery_failed') {
            $delivery = $order->delivery;

            if (isset($order->confirmed_at) && $order->confirmed_at) {
                $timestamps['confirmed'] = $ensureCarbon($order->confirmed_at);
            } elseif ($delivery && $delivery->assigned_at) {
                $timestamps['confirmed'] = $ensureCarbon($delivery->assigned_at);
            } else {
                $timestamps['confirmed'] = $order->updated_at;
            }

            if (isset($order->processing_at) && $order->processing_at) {
                $timestamps['packing'] = $ensureCarbon($order->processing_at);
            } else {
                $timestamps['packing'] = $order->updated_at;
            }

            if (isset($order->ready_at) && $order->ready_at) {
                $timestamps['ready'] = $ensureCarbon($order->ready_at);
            } else {
                $timestamps['ready'] = $order->updated_at;
            }

            if ($delivery && $delivery->picked_up_at) {
                $timestamps['picked_up'] = $ensureCarbon($delivery->picked_up_at);
            } elseif ($order->out_for_delivery_at) {
                $timestamps['picked_up'] = $ensureCarbon($order->out_for_delivery_at);
            } else {
                $timestamps['picked_up'] = $order->updated_at;
            }

            if ($delivery && $delivery->in_transit_at) {
                $timestamps['out_for_delivery'] = $ensureCarbon($delivery->in_transit_at);
            }

            if ($delivery && $delivery->failed_at) {
                $timestamps['failed'] = $ensureCarbon($delivery->failed_at);
            } else {
                $timestamps['failed'] = $order->updated_at;
            }
        }

        return $timestamps;
    }
}