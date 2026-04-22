<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PosController extends Controller
{
    /**
     * Display POS interface
     */
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        
        // Get all products with stock for this branch
        $products = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId)
            ->where('quantity', '>', 0)
            ->orderBy('product_id')
            ->get()
            ->groupBy(function($item) {
                return $item->product->category;
            });
        
        // Get cart from session
        $cart = session()->get('pos_cart', []);
        
        // Calculate cart totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.12; // 12% VAT
        $total = $subtotal + $tax;
        
        return view('branch-admin.pos.index', compact('products', 'cart', 'subtotal', 'tax', 'total'));
    }
    
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:branch_inventories,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $branchId = Auth::user()->branch_id;
        
        // Get inventory item
        $inventory = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId)
            ->where('id', $request->inventory_id)
            ->first();
        
        if (!$inventory) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in inventory'
            ], 404);
        }
        
        // Check if enough stock
        if ($inventory->available_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient stock. Available: {$inventory->available_quantity}"
            ], 400);
        }
        
        // Get cart from session
        $cart = session()->get('pos_cart', []);
        $cartKey = $inventory->id;
        
        if (isset($cart[$cartKey])) {
            // Update quantity if already in cart
            $newQuantity = $cart[$cartKey]['quantity'] + $request->quantity;
            
            // Check if new quantity exceeds available stock
            if ($newQuantity > $inventory->available_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot add {$request->quantity} more. Max available: {$inventory->available_quantity}"
                ], 400);
            }
            
            $cart[$cartKey]['quantity'] = $newQuantity;
            $cart[$cartKey]['subtotal'] = $cart[$cartKey]['price'] * $newQuantity;
        } else {
            // Add new item to cart
            $cart[$cartKey] = [
                'inventory_id' => $inventory->id,
                'product_id' => $inventory->product_id,
                'product_name' => $inventory->product->name,
                'flavor_name' => $inventory->flavor->name ?? null,
                'price' => $inventory->product->price,
                'quantity' => $request->quantity,
                'subtotal' => $inventory->product->price * $request->quantity,
                'image' => $inventory->product->image ?? $inventory->product->image_url,
            ];
        }
        
        session()->put('pos_cart', $cart);
        
        // Calculate cart totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
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
    }
    
    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:branch_inventories,id',
            'quantity' => 'required|integer|min:0',
        ]);
        
        $branchId = Auth::user()->branch_id;
        $cart = session()->get('pos_cart', []);
        $cartKey = $request->inventory_id;
        
        if (!isset($cart[$cartKey])) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        }
        
        if ($request->quantity <= 0) {
            // Remove item from cart
            unset($cart[$cartKey]);
        } else {
            // Check available stock
            $inventory = BranchInventory::where('branch_id', $branchId)
                ->where('id', $request->inventory_id)
                ->first();
            
            if ($inventory && $inventory->available_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Available: {$inventory->available_quantity}"
                ], 400);
            }
            
            // Update quantity
            $cart[$cartKey]['quantity'] = $request->quantity;
            $cart[$cartKey]['subtotal'] = $cart[$cartKey]['price'] * $request->quantity;
        }
        
        session()->put('pos_cart', $cart);
        
        // Calculate cart totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
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
    }
    
    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        session()->forget('pos_cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
    
    /**
     * Process payment and create order
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cash,gcash,paymaya,card',
            'amount_paid' => 'required|numeric|min:0',
        ]);
        
        $branchId = Auth::user()->branch_id;
        $cart = session()->get('pos_cart', []);
        
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty');
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;
        
        // Check if amount paid is sufficient
        if ($request->amount_paid < $total) {
            return redirect()->back()->with('error', "Insufficient payment. Total: ₱" . number_format($total, 2));
        }
        
        $change = $request->amount_paid - $total;
        
        DB::beginTransaction();
        
        try {
            // Generate order number
            $orderNumber = 'POS-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Create order
            $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'branch_id' => $branchId,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => 0,
            'total_amount' => $total,
            'status' => 'delivered',  // ← Changed from 'completed' to 'delivered'
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'delivery_type' => 'pickup',  // Walk-in = pickup
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'notes' => $request->notes,
            ]);
            
            // Process each item in cart
            foreach ($cart as $item) {
                // Get inventory
                $inventory = BranchInventory::where('branch_id', $branchId)
                    ->where('id', $item['inventory_id'])
                    ->first();
                
                if (!$inventory) {
                    throw new \Exception("Product not found: {$item['product_name']}");
                }
                
                // Check stock again
                if ($inventory->available_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$item['product_name']}. Available: {$inventory->available_quantity}");
                }
                
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
                
                // Update inventory (deduct stock)
                $oldQuantity = $inventory->quantity;
                $newQuantity = $oldQuantity - $item['quantity'];
                
                $inventory->update([
                    'quantity' => $newQuantity,
                    // If there was reserved quantity for this item, release it
                    'reserved_quantity' => max(0, $inventory->reserved_quantity - $item['quantity']),
                ]);
                
                // Log stock movement
                StockMovement::create([
                    'branch_id' => $branchId,
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
            
            // Clear cart after successful checkout
            session()->forget('pos_cart');
            
            // Store receipt data in session for printing
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
                'branch_name' => Auth::user()->branch->name,
                'cashier' => Auth::user()->name,
            ]);
            
            return redirect()->route('branch-admin.pos.receipt')->with('success', 'Payment successful!');
            
        } catch (\Exception $e) {
            DB::rollBack();
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
            return redirect()->route('branch-admin.pos.index')->with('error', 'No receipt found');
        }
        
        return view('branch-admin.pos.receipt', compact('receipt'));
    }
    /**
 * Show purchase history for the branch
 */
public function history(Request $request)
{
    $branchId = Auth::user()->branch_id;
    
    $query = Order::with(['items.product'])
        ->where('branch_id', $branchId)
        ->where('delivery_type', 'pickup') // POS orders only
        ->orderBy('created_at', 'desc');
    
    // Filter by date
    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }
    
    // Filter by customer
    if ($request->filled('customer')) {
        $query->where('customer_name', 'like', '%' . $request->customer . '%');
    }
    
    $orders = $query->paginate(20);
    
    // Calculate totals
    $totalSales = $orders->sum('total_amount');
    $totalOrders = $orders->total();
    $todaySales = Order::where('branch_id', $branchId)
        ->where('delivery_type', 'pickup')
        ->whereDate('created_at', today())
        ->sum('total_amount');
    
    return view('branch-admin.pos.history', compact('orders', 'totalSales', 'totalOrders', 'todaySales'));
}

/**
 * Show order receipt/invoice
 */
public function showOrder(Order $order)
{
    // Ensure order belongs to user's branch
    if ($order->branch_id !== Auth::user()->branch_id) {
        abort(403);
    }
    
    $order->load(['items.product', 'user']);
    
    return view('branch-admin.pos.show-order', compact('order'));
}
    /**
     * Get product details for quick add
     */
    public function searchProduct(Request $request)
    {
        $branchId = Auth::user()->branch_id;
        $search = $request->get('q');
        
        $products = BranchInventory::with(['product', 'flavor'])
            ->where('branch_id', $branchId)
            ->where('quantity', '>', 0)
            ->whereHas('product', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'flavor' => $item->flavor->name ?? null,
                    'price' => $item->product->price,
                    'available' => $item->available_quantity,
                    'image' => $item->product->image ?? $item->product->image_url,
                ];
            });
        
        return response()->json($products);
    }
}