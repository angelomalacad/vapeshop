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
use Illuminate\Support\Facades\Storage;
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
    // ✅ ADDED: ->where('is_archived', false)->where('is_disposed', false)
    $products = BranchInventory::with(['product', 'flavor'])
        ->where('branch_id', $branchId)
        ->where('quantity', '>', 0)
        ->where('is_archived', false)
        ->where('is_disposed', false)
        ->orderBy('product_id')
        ->get()
        ->groupBy(function($item) {
            return $item->product->category;
        });

    // Get cart from session
    $cart = session()->get('pos_cart', []);
    
    // Debug: Log cart contents
    \Log::info('Cart contents in index:', $cart);

    // Calculate cart totals - NO TAX
    $subtotal = 0;
    foreach ($cart as $item) {
        // Ensure we have valid numeric values
        $price = floatval($item['price'] ?? 0);
        $quantity = intval($item['quantity'] ?? 0);
        $subtotal += $price * $quantity;
        
        // Update subtotal in cart item if needed
        if (isset($cart[$item['inventory_id']])) {
            $cart[$item['inventory_id']]['subtotal'] = $price * $quantity;
        }
    }
    
    $tax = 0; // REMOVED TAX
    $total = $subtotal; // NO TAX ADDED

    // Debug: Log calculated values
    \Log::info('Calculated totals - Subtotal: ' . $subtotal . ', Total: ' . $total);

    // Update session with corrected subtotals
    session()->put('pos_cart', $cart);

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
        $cartKey = (string) $inventory->id; // Use string key for consistency

        if (isset($cart[$cartKey])) {
            // Update quantity if already in cart
            $newQuantity = intval($cart[$cartKey]['quantity']) + intval($request->quantity);

            // Check if new quantity exceeds available stock
            if ($newQuantity > $inventory->available_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot add {$request->quantity} more. Max available: {$inventory->available_quantity}"
                ], 400);
            }

            $cart[$cartKey]['quantity'] = $newQuantity;
            $cart[$cartKey]['subtotal'] = floatval($cart[$cartKey]['price']) * $newQuantity;
        } else {
            // Add new item to cart
            $cart[$cartKey] = [
                'inventory_id' => $inventory->id,
                'product_id' => $inventory->product_id,
                'flavor_id' => $inventory->flavor_id,
                'product_name' => $inventory->product->name,
                'flavor_name' => $inventory->flavor->name ?? null,
                'price' => floatval($inventory->product->price),
                'quantity' => intval($request->quantity),
                'subtotal' => floatval($inventory->product->price) * intval($request->quantity),
                'image' => $inventory->product->image ?? $inventory->product->image_url,
            ];
        }

        session()->put('pos_cart', $cart);

        // Calculate cart totals - NO TAX
        $subtotal = 0;
        foreach ($cart as $item) {
            $price = floatval($item['price'] ?? 0);
            $quantity = intval($item['quantity'] ?? 0);
            $subtotal += $price * $quantity;
        }
        $tax = 0; // REMOVED TAX
        $total = $subtotal; // NO TAX ADDED

        // Debug: Log cart after update
        \Log::info('Cart after add: ' . json_encode($cart));
        \Log::info('Subtotal after add: ' . $subtotal);

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
        $cartKey = (string) $request->inventory_id;

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
            $cart[$cartKey]['quantity'] = intval($request->quantity);
            $cart[$cartKey]['subtotal'] = floatval($cart[$cartKey]['price']) * intval($request->quantity);
        }

        session()->put('pos_cart', $cart);

        // Calculate cart totals - NO TAX
        $subtotal = 0;
        foreach ($cart as $item) {
            $price = floatval($item['price'] ?? 0);
            $quantity = intval($item['quantity'] ?? 0);
            $subtotal += $price * $quantity;
        }
        $tax = 0; // REMOVED TAX
        $total = $subtotal; // NO TAX ADDED

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
    // Validate request
    $validator = validator($request->all(), [
        'customer_name' => 'nullable|string|max:255',
        'customer_phone' => 'nullable|string|max:20',
        'payment_method' => 'required|in:cash,gcash',
        'amount_paid' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
        'payment_proof' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf', // 5MB max
    ]);

    if ($validator->fails()) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $branchId = Auth::user()->branch_id;
    $cart = session()->get('pos_cart', []);

    if (empty($cart)) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }
        return redirect()->back()->with('error', 'Cart is empty');
    }

    // Calculate totals - NO TAX
    $subtotal = 0;
    foreach ($cart as $item) {
        $price = floatval($item['price'] ?? 0);
        $quantity = intval($item['quantity'] ?? 0);
        $subtotal += $price * $quantity;
    }
    $tax = 0;
    $total = $subtotal;

    // Check if amount paid is sufficient
    if ($request->amount_paid < $total) {
        $errorMessage = "Insufficient payment. Amount paid: ₱" . number_format($request->amount_paid, 2) . " | Total: ₱" . number_format($total, 2);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 400);
        }
        return redirect()->back()->with('error', $errorMessage);
    }

    $change = $request->amount_paid - $total;

    // Handle GCash proof upload (supports both file upload and camera capture)
    $proofPath = null;
    if ($request->payment_method === 'gcash') {
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            
            // Log file details for debugging
            \Log::info('GCash file received:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'is_camera' => str_contains($file->getClientOriginalName(), 'captured_photo')
            ]);
            
            // Generate unique filename
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            
            // Store the file
            $proofPath = $file->storeAs('payment_proofs', $filename, 'public');
            
            // Log the saved path
            \Log::info('GCash proof saved at: ' . $proofPath);
            
            // Verify the file was saved
            if (!Storage::disk('public')->exists($proofPath)) {
                \Log::error('File was NOT saved successfully!');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to save proof of payment. Please try again.'
                    ], 500);
                }
                return redirect()->back()->with('error', 'Failed to save proof of payment. Please try again.');
            }
        } else {
            // No file uploaded for GCash
            \Log::warning('GCash payment but no file uploaded');
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please upload a proof of payment or take a photo for GCash.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Please upload a proof of payment or take a photo for GCash.');
        }
    }

    DB::beginTransaction();

    try {
        // Generate order number
        $orderNumber = 'POS-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Create order
        $orderData = [
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'branch_id' => $branchId,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => 0,
            'total_amount' => $total,
            'status' => 'completed',
            'payment_status' => $request->payment_method === 'gcash' ? 'pending' : 'paid',
            'payment_method' => $request->payment_method,
            'payment_proof' => $proofPath,
            'delivery_type' => 'pickup',
            'customer_name' => $request->customer_name ?: 'Walk-in Customer',
            'customer_phone' => $request->customer_phone,
            'notes' => $request->notes,
        ];

        // Log the order data
        \Log::info('Creating order with data:', $orderData);

        $order = Order::create($orderData);

        // Verify the order was created with payment_proof
        \Log::info('Order created. Payment proof in DB: ' . ($order->payment_proof ?? 'null'));

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
            $orderItemData = [
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => intval($item['quantity']),
                'price' => floatval($item['price']),
                'subtotal' => floatval($item['subtotal']),
            ];

            // Only add flavor_id if it exists in the cart item
            if (isset($item['flavor_id']) && $item['flavor_id']) {
                $orderItemData['flavor_id'] = $item['flavor_id'];
            }

            OrderItem::create($orderItemData);

            // Update inventory (deduct stock)
            $oldQuantity = $inventory->quantity;
            $newQuantity = $oldQuantity - intval($item['quantity']);

            $inventory->update([
                'quantity' => $newQuantity,
                'reserved_quantity' => max(0, $inventory->reserved_quantity - intval($item['quantity'])),
            ]);

            // Log stock movement
            StockMovement::create([
                'branch_id' => $branchId,
                'product_id' => $item['product_id'],
                'flavor_id' => $inventory->flavor_id ?? null,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => -intval($item['quantity']),
                'movement_type' => 'sale',
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'notes' => "POS Sale to " . ($request->customer_name ?: 'Walk-in Customer'),
                'created_by' => Auth::id(),
            ]);
        }

        DB::commit();

        // Clear cart after successful checkout
        session()->forget('pos_cart');

        // Store receipt data in session for printing
        session()->flash('receipt', [
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name ?: 'Walk-in Customer',
            'date' => now()->format('M d, Y h:i A'),
            'items' => $cart,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'amount_paid' => $request->amount_paid,
            'change' => $change,
            'payment_method' => $request->payment_method,
            'branch_name' => Auth::user()->branch->name ?? 'Branch',
            'cashier' => Auth::user()->name,
        ]);

        // For AJAX requests, return JSON response
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('branch-admin.pos.receipt'),
                'order_number' => $orderNumber,
                'payment_proof' => $proofPath
            ]);
        }

        return redirect()->route('branch-admin.pos.receipt')->with('success', 'Payment successful!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Delete uploaded proof if exists and there was an error
        if ($proofPath && Storage::disk('public')->exists($proofPath)) {
            Storage::disk('public')->delete($proofPath);
            \Log::info('Deleted proof file due to error: ' . $proofPath);
        }
        
        // Log the error for debugging
        \Log::error('POS Checkout Error: ' . $e->getMessage());
        \Log::error('Cart data: ' . json_encode($cart));
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
        
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

        $query = Order::with(['items.product', 'user'])
            ->where('branch_id', $branchId)
            ->where('delivery_type', 'pickup')
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
        $totalSales = Order::where('branch_id', $branchId)
            ->where('delivery_type', 'pickup')
            ->sum('total_amount');
            
        $totalOrders = Order::where('branch_id', $branchId)
            ->where('delivery_type', 'pickup')
            ->count();
            
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
    
    /**
     * Upload proof of payment (standalone endpoint for AJAX)
     */
    public function uploadProof(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|file|max:5120|mimes:jpg,jpeg,png,gif,pdf'
        ]);
        
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $path = $file->storeAs('payment_proofs/temp', $filename, 'public');
            
            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ], 400);
    }
}