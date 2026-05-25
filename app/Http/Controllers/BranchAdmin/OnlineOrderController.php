<?php
namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\BranchInventory;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OnlineOrderController extends Controller
{
    public function index()
{
    $branchId = Auth::user()->branch_id;
    $orders = Order::where('branch_id', $branchId)
        ->whereNotNull('order_status')  // Only orders with order_status
        ->where('order_number', 'NOT LIKE', 'POS-%')  // Exclude POS order numbers
        ->whereIn('order_status', ['pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled'])
        ->orderByRaw("FIELD(order_status, 'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled')")
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    $counts = [
        'pending' => Order::where('branch_id', $branchId)->where('order_status', 'pending')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'confirmed' => Order::where('branch_id', $branchId)->where('order_status', 'confirmed')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'processing' => Order::where('branch_id', $branchId)->where('order_status', 'processing')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'ready' => Order::where('branch_id', $branchId)->where('order_status', 'ready')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'out_for_delivery' => Order::where('branch_id', $branchId)->where('order_status', 'out_for_delivery')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'delivered' => Order::where('branch_id', $branchId)->where('order_status', 'delivered')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
        'cancelled' => Order::where('branch_id', $branchId)->where('order_status', 'cancelled')->where('order_number', 'NOT LIKE', 'POS-%')->count(),
    ];
    
    return view('branch-admin.online-orders.index', compact('orders', 'counts'));
}
    
    public function show(Order $order)
    {
        if ($order->branch_id != Auth::user()->branch_id) abort(403);
        $order->load('items.product', 'user', 'delivery.driver');
        $drivers = User::where('role', 'driver')->get();
        $statusHistory = $this->getStatusHistory($order);
        
        return view('branch-admin.online-orders.show', compact('order', 'drivers', 'statusHistory'));
    }
    
    public function confirm(Order $order)
    {
        if ($order->branch_id != Auth::user()->branch_id) abort(403);
        if ($order->order_status != 'pending') {
            return back()->with('error', 'Order cannot be confirmed.');
        }
        
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                // Find inventory
                $inventory = null;
                
                if ($item->inventory_id) {
                    $inventory = BranchInventory::lockForUpdate()->find($item->inventory_id);
                }
                
                if (!$inventory) {
                    $inventory = BranchInventory::lockForUpdate()
                        ->where('branch_id', $order->branch_id)
                        ->where('product_id', $item->product_id)
                        ->first();
                }
                
                if (!$inventory) {
                    throw new \Exception("Inventory not found for product ID: {$item->product_id}");
                }
                
                // If reserved_quantity is less than requested, handle it gracefully
                if ($inventory->reserved_quantity < $item->quantity) {
                    // This handles orders placed before reservation was implemented
                    // Directly deduct from quantity without checking reserved
                    $inventory->decrement('quantity', $item->quantity);
                    $inventory->decrement('reserved_quantity', $inventory->reserved_quantity);
                } else {
                    $inventory->confirmReservation($item->quantity);
                }
            }
            
            $order->update([
                'order_status' => 'confirmed',
                'admin_notes' => request('admin_notes'),
            ]);
            
            DB::commit();
            return back()->with('success', 'Order confirmed. Stock deducted.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error confirming order: ' . $e->getMessage());
        }
    }
    
    public function reject(Order $order)
    {
        if ($order->branch_id != Auth::user()->branch_id) abort(403);
        if ($order->order_status != 'pending') {
            return back()->with('error', 'Order cannot be rejected.');
        }
        
        DB::beginTransaction();
        try {
            foreach ($order->items as $item) {
                $inventory = null;
                
                if ($item->inventory_id) {
                    $inventory = BranchInventory::lockForUpdate()->find($item->inventory_id);
                }
                
                if (!$inventory) {
                    $inventory = BranchInventory::lockForUpdate()
                        ->where('branch_id', $order->branch_id)
                        ->where('product_id', $item->product_id)
                        ->first();
                }
                
                if ($inventory) {
                    // If reserved_quantity is less than requested, just set to 0
                    if ($inventory->reserved_quantity < $item->quantity) {
                        $inventory->update(['reserved_quantity' => 0]);
                    } else {
                        $inventory->releaseReservation($item->quantity);
                    }
                }
            }
            $order->update(['order_status' => 'cancelled']);
            
            DB::commit();
            return back()->with('success', 'Order rejected and stock released.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error rejecting order: ' . $e->getMessage());
        }
    }
    
    public function markProcessing(Order $order)
    {
        if ($order->branch_id != Auth::user()->branch_id) abort(403);
        if ($order->order_status != 'confirmed') {
            return back()->with('error', 'Order cannot be marked as processing.');
        }
        $order->update(['order_status' => 'processing']);
        return back()->with('success', 'Order marked as processing.');
    }
    
    public function markReady(Order $order)
    {
        if ($order->branch_id != Auth::user()->branch_id) abort(403);
        if (!in_array($order->order_status, ['confirmed', 'processing'])) {
            return back()->with('error', 'Order cannot be marked ready.');
        }
        $order->update(['order_status' => 'ready']);
        return back()->with('success', 'Order marked ready for pickup/delivery.');
    }
    
    public function assignDriver(Request $request, Order $order)
    {
        if ($order->branch_id != Auth::user()->branch_id) abort(403);
        if ($order->order_status != 'ready') {
            return back()->with('error', 'Order must be ready before assigning driver.');
        }
        if ($order->delivery_type != 'delivery') {
            return back()->with('error', 'Only delivery orders need a driver.');
        }
        
        $request->validate(['driver_id' => 'required|exists:users,id']);
        $driver = User::find($request->driver_id);
        if ($driver->role != 'driver') {
            return back()->with('error', 'Invalid driver.');
        }
        
        DB::transaction(function () use ($order, $driver) {
            $delivery = Delivery::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'driver_id' => $driver->id,
                    'tracking_number' => 'D-' . $order->order_number,
                    'status' => 'assigned',
                    'delivery_address' => $order->delivery_address,
                    'recipient_name' => $order->customer_name,
                    'recipient_phone' => $order->customer_phone,
                    'assigned_at' => now(),
                ]
            );
            $order->update(['order_status' => 'out_for_delivery']);
        });
        
        return back()->with('success', 'Driver assigned. Order is out for delivery.');
    }
    
    public function markDelivered(Order $order)
    {
        if ($order->branch_id != Auth::user()->branch_id) abort(403);
        if ($order->order_status != 'out_for_delivery') {
            return back()->with('error', 'Order cannot be marked as delivered.');
        }
        
        DB::transaction(function () use ($order) {
            if ($order->delivery) {
                $order->delivery->update([
                    'status' => 'delivered',
                    'delivered_at' => now(),
                ]);
            }
            $order->update(['order_status' => 'delivered']);
        });
        
        return back()->with('success', 'Order marked as delivered.');
    }
    
    public function updateTracking(Request $request, Delivery $delivery)
    {
        if ($delivery->order->branch_id != Auth::user()->branch_id) abort(403);
        
        $request->validate([
            'status' => 'required|in:assigned,picked_up,in_transit,delivered,failed',
            'notes' => 'nullable|string',
        ]);
        
        $delivery->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'picked_up_at' => $request->status == 'picked_up' ? now() : $delivery->picked_up_at,
            'delivered_at' => $request->status == 'delivered' ? now() : $delivery->delivered_at,
        ]);
        
        if ($request->status == 'delivered') {
            $delivery->order->update(['order_status' => 'delivered']);
        }
        
        return back()->with('success', 'Tracking status updated.');
    }
    
    private function getStatusHistory($order)
    {
        $history = [];
        $statuses = ['pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered'];
        
        // Check each status
        $statusOrder = [
            'pending' => $order->created_at,
            'confirmed' => $order->updated_at,
            'processing' => $order->updated_at,
            'ready' => $order->updated_at,
            'out_for_delivery' => $order->delivery ? $order->delivery->assigned_at : null,
            'delivered' => $order->delivery ? $order->delivery->delivered_at : null,
        ];
        
        $currentStatusIndex = array_search($order->order_status, $statuses);
        
        foreach ($statuses as $index => $status) {
            $completed = $index <= $currentStatusIndex;
            $date = null;
            
            if ($completed) {
                if ($status == 'pending') $date = $order->created_at;
                elseif ($status == 'out_for_delivery' && $order->delivery) $date = $order->delivery->assigned_at;
                elseif ($status == 'delivered' && $order->delivery) $date = $order->delivery->delivered_at;
                else $date = $order->updated_at;
            }
            
            $history[] = [
                'status' => $status,
                'completed' => $completed,
                'date' => $date,
                'label' => ucfirst(str_replace('_', ' ', $status)),
            ];
        }
        
        return $history;
    }
}