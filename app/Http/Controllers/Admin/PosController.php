<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    /**
     * Display POS interface
     */
    public function index(Request $request)
    {
        $branches = Branch::where('is_active', true)->get();
        $selectedBranchId = $request->get('branch_id', $branches->first()->id ?? null);
        
        $products = collect();
        $cart = session()->get('admin_pos_cart', []);
        
        if ($selectedBranchId) {
            $products = BranchInventory::with(['product', 'flavor', 'branch'])
                ->where('branch_id', $selectedBranchId)
                ->where('quantity', '>', 0)
                ->orderBy('product_id')
                ->get()
                ->groupBy(function($item) {
                    return $item->product->category ?? 'Uncategorized';
                });
        }
        
        // Calculate cart totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;
        
        return view('admin.pos.index', compact('branches', 'selectedBranchId', 'products', 'cart', 'subtotal', 'tax', 'total'));
    }
    
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'inventory_id' => 'required|exists:branch_inventories,id',
                'quantity' => 'required|integer|min:1',
            ]);
            
            $inventory = BranchInventory::with(['product', 'flavor'])
                ->where('id', $request->inventory_id)
                ->first();
            
            if (!$inventory) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }
            
            $availableQuantity = $inventory->quantity - ($inventory->reserved_quantity ?? 0);
            
            if ($availableQuantity < $request->quantity) {
                return response()->json([
                    'success' => false, 
                    'message' => "Insufficient stock. Available: {$availableQuantity}"
                ], 400);
            }
            
            $cart = session()->get('admin_pos_cart', []);
            $cartKey = $inventory->id;
            
            if (isset($cart[$cartKey])) {
                $newQuantity = $cart[$cartKey]['quantity'] + $request->quantity;
                if ($newQuantity > $availableQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add {$request->quantity} more. Max available: {$availableQuantity}"
                    ], 400);
                }
                $cart[$cartKey]['quantity'] = $newQuantity;
                $cart[$cartKey]['subtotal'] = $cart[$cartKey]['price'] * $newQuantity;
            } else {
                $cart[$cartKey] = [
                    'inventory_id' => $inventory->id,
                    'product_id' => $inventory->product_id,
                    'branch_id' => $inventory->branch_id,
                    'product_name' => $inventory->product->name,
                    'flavor_name' => $inventory->flavor->name ?? null,
                    'price' => $inventory->product->price,
                    'quantity' => $request->quantity,
                    'subtotal' => $inventory->product->price * $request->quantity,
                ];
            }
            
            session()->put('admin_pos_cart', $cart);
            
            $subtotal = collect($cart)->sum('subtotal');
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;
            
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'subtotal' => number_format($subtotal, 2),
                'tax' => number_format($tax, 2),
                'total' => number_format($total, 2),
                'cart_count' => count($cart),
                'message' => "{$inventory->product->name} added to cart"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        try {
            $request->validate([
                'inventory_id' => 'required|exists:branch_inventories,id',
                'quantity' => 'required|integer|min:0',
            ]);
            
            $cart = session()->get('admin_pos_cart', []);
            $cartKey = $request->inventory_id;
            
            if (!isset($cart[$cartKey])) {
                return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
            }
            
            if ($request->quantity <= 0) {
                unset($cart[$cartKey]);
            } else {
                $inventory = BranchInventory::find($request->inventory_id);
                if ($inventory) {
                    $availableQuantity = $inventory->quantity - ($inventory->reserved_quantity ?? 0);
                    if ($availableQuantity < $request->quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock. Available: {$availableQuantity}"
                        ], 400);
                    }
                }
                $cart[$cartKey]['quantity'] = $request->quantity;
                $cart[$cartKey]['subtotal'] = $cart[$cartKey]['price'] * $request->quantity;
            }
            
            session()->put('admin_pos_cart', $cart);
            
            $subtotal = collect($cart)->sum('subtotal');
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;
            
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'subtotal' => number_format($subtotal, 2),
                'tax' => number_format($tax, 2),
                'total' => number_format($total, 2),
                'cart_count' => count($cart)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Clear cart
     */
    public function clearCart()
    {
        session()->forget('admin_pos_cart');
        return response()->json(['success' => true, 'message' => 'Cart cleared']);
    }
    
    /**
     * Process checkout
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash,gcash,paymaya,card',
            'amount_paid' => 'required|numeric|min:0',
        ]);
        
        $cart = session()->get('admin_pos_cart', []);
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty');
        }
        
        $subtotal = collect($cart)->sum('subtotal');
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;
        
        if ($request->amount_paid < $total) {
            return redirect()->back()->with('error', "Insufficient payment. Total: ₱" . number_format($total, 2));
        }
        
        $change = $request->amount_paid - $total;
        
        DB::beginTransaction();
        
        try {
            $orderNumber = 'POS-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'branch_id' => $request->branch_id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => 0,
                'total_amount' => $total,
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'delivery_type' => 'pickup',
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'notes' => $request->notes,
            ]);
            
            foreach ($cart as $item) {
                $inventory = BranchInventory::where('branch_id', $request->branch_id)
                    ->where('id', $item['inventory_id'])
                    ->first();
                
                if (!$inventory) {
                    throw new \Exception("Product not found: {$item['product_name']}");
                }
                
                if ($inventory->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$item['product_name']}");
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
                
                $oldQuantity = $inventory->quantity;
                $newQuantity = $oldQuantity - $item['quantity'];
                
                $inventory->update([
                    'quantity' => $newQuantity,
                ]);
                
                StockMovement::create([
                    'branch_id' => $request->branch_id,
                    'product_id' => $item['product_id'],
                    'flavor_id' => $inventory->flavor_id,
                    'previous_quantity' => $oldQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => -$item['quantity'],
                    'movement_type' => 'sale',
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => "POS Sale to {$request->customer_name}",
                    'created_by' => Auth::id(),
                ]);
            }
            
            DB::commit();
            
            session()->forget('admin_pos_cart');
            
            session()->flash('receipt', [
                'order_number' => $orderNumber,
                'customer_name' => $request->customer_name,
                'date' => now()->format('M d, Y h:i A'),
                'items' => $cart,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'amount_paid' => $request->amount_paid,
                'change' => $change,
                'payment_method' => $request->payment_method,
                'branch_name' => Branch::find($request->branch_id)->name,
                'cashier' => Auth::user()->name,
            ]);
            
            return redirect()->route('admin.pos.receipt')->with('success', 'Payment successful!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing payment: ' . $e->getMessage());
        }
    }
    
    /**
     * Show receipt
     */
    public function receipt()
    {
        $receipt = session('receipt');
        if (!$receipt) {
            return redirect()->route('admin.pos.index')->with('error', 'No receipt found');
        }
        return view('admin.pos.receipt', compact('receipt'));
    }
    
    /**
     * Sales history
     */
    public function history(Request $request)
    {
        $query = Order::with(['branch', 'user', 'items.product'])
            ->where('delivery_type', 'pickup')
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('customer')) {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $orders = $query->paginate(20);
        $branches = Branch::where('is_active', true)->get();
        
        $totalSales = Order::where('delivery_type', 'pickup')->sum('total_amount');
        $totalOrders = Order::where('delivery_type', 'pickup')->count();
        $todaySales = Order::where('delivery_type', 'pickup')->whereDate('created_at', today())->sum('total_amount');
        $todayOrders = Order::where('delivery_type', 'pickup')->whereDate('created_at', today())->count();
        
        $salesByBranch = Branch::withCount(['orders' => function($query) {
            $query->where('delivery_type', 'pickup');
        }])->withSum(['orders' => function($query) {
            $query->where('delivery_type', 'pickup');
        }], 'total_amount')->get();
        
        $salesByPayment = Order::where('delivery_type', 'pickup')
            ->select('payment_method', DB::raw('count(*) as total_orders'), DB::raw('sum(total_amount) as total_amount'))
            ->groupBy('payment_method')
            ->get();
        
        return view('admin.pos.history', compact('orders', 'branches', 'totalSales', 'totalOrders', 'todaySales', 'todayOrders', 'salesByBranch', 'salesByPayment'));
    }
}