<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use App\Models\Branch;
use App\Models\Order;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
{
    // Check if this is a selected items checkout
    if (session()->has('selected_checkout')) {
        $cart = session()->get('selected_cart', []);
        session()->forget('selected_checkout');
        session()->forget('selected_cart');
    } else {
        $cart = CartHelper::getCart();
    }

    if (empty($cart)) {
        return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
    }

    // Ensure all items belong to same branch
    $branchId = null;
    foreach ($cart as $item) {
        if (!$branchId) $branchId = $item['branch_id'];
        elseif ($branchId != $item['branch_id']) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart contains products from different branches. Please clear and select one branch.');
        }
    }

    $branch = Branch::find($branchId);
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $tax = $subtotal * 0.12;
    $total = $subtotal + $tax;

    return view('customer.checkout.index', compact('branch', 'subtotal', 'tax', 'total'));
}

    public function store(Request $request)
    {
        $cart = CartHelper::getCart();
        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart is empty.');
        }

        $branchId = null;
        foreach ($cart as $key => $item) {
            if (!$branchId) $branchId = $item['branch_id'];
            elseif ($branchId != $item['branch_id']) {
                return back()->with('error', 'Products from multiple branches. Please clear cart and re-add.');
            }
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:delivery_type,delivery|nullable|string',
            'city' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'landmark' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cod,gcash',
            'gcash_reference' => 'required_if:payment_method,gcash|nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Check stock and reserve
            foreach ($cart as $inventoryId => $item) {
                $inventory = BranchInventory::lockForUpdate()->find($inventoryId);
                if (!$inventory || $inventory->available_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$item['product_name']}");
                }
                $inventory->reserve($item['quantity']);
            }

            // 2. Create order
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'branch_id' => $branchId,
                'subtotal' => CartHelper::getTotal(),
                'tax' => CartHelper::getTotal() * 0.12,
                'delivery_fee' => 0,
                'total_amount' => CartHelper::getTotal() * 1.12,
                'status' => 'pending',
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'delivery_type' => $request->delivery_type,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'delivery_address' => $request->delivery_address,
                'city' => $request->city,
                'barangay' => $request->barangay,
                'landmark' => $request->landmark,
                'gcash_reference' => $request->gcash_reference,
                'notes' => $request->notes,
            ]);

            // 3. Create order items - FIXED: use $inventoryId as the key
            foreach ($cart as $inventoryId => $item) {
                $order->items()->create([
                    'inventory_id' => $inventoryId,  // This is the correct key!
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            // 4. Clear cart
            CartHelper::clearCart();

            DB::commit();

            return redirect()->route('customer.orders.show', $order)->with('success', 'Order placed successfully! Awaiting confirmation.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
