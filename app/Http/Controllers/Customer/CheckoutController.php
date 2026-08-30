<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\BranchInventory;
use App\Models\InventoryReservation;
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
            // Do NOT forget the flag immediately, keep it in session for form validation!
            // session()->forget('selected_checkout');
            // session()->forget('selected_cart');
        } else {
            $cart = CartHelper::getCart();
        }

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
        }

        // --- PREPARE CART ITEMS FOR ORDER SUMMARY IMAGES ---
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $inventoryId => $item) {
            $inventory = \App\Models\BranchInventory::with('product')->find($inventoryId);

            $imageUrl = null;
            if ($inventory && $inventory->product && $inventory->product->image) {
                $imageUrl = \Storage::url($inventory->product->image);
            }

            $cartItems[] = [
                'inventory_id' => $inventoryId,
                'product_name' => $item['product_name'],
                'flavor_id' => $item['flavor_id'] ?? null,
                'flavor_name' => $item['flavor_name'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'image_url' => $imageUrl,
            ];

            $subtotal += $item['price'] * $item['quantity'];
        }

        $firstBranchId = collect($cart)->first()['branch_id'] ?? null;
        $branch = $firstBranchId ? Branch::find($firstBranchId) : null;

        $tax = 0; // ✅ No tax
        $total = $subtotal; // ✅ Total is just subtotal

        // ================================================================
        // FIX: Determine Calamba status from the User Profile
        // ================================================================
        $isInsideCalamba = false;
        $userCity = Auth::check() ? Auth::user()->city : null;

        if ($userCity) {
            $cityLower = strtolower(trim($userCity));
            if ($cityLower === 'calamba city' || $cityLower === 'calamba') {
                $isInsideCalamba = true;
            }
        }
        // ================================================================

        $isInsideCalamba = $isInsideCalamba ?? false;

        return view('customer.checkout.index', compact('branch', 'subtotal', 'tax', 'total', 'cartItems', 'isInsideCalamba'));
    }

    public function store(Request $request)
    {
        // Determine if we are using a selected cart
        $isSelectedCheckout = session()->has('selected_checkout');
        if ($isSelectedCheckout) {
            $cart = session()->get('selected_cart', []);
            // Clear the session AFTER we retrieve it so it doesn't persist
            session()->forget('selected_checkout');
            session()->forget('selected_cart');
        } else {
            $cart = CartHelper::getCart();
        }

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart is empty.');
        }

        // --- VALIDATION TO HANDLE NEW ADDRESS DROPDOWNS ---
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'delivery_type' => 'required|in:pickup,delivery',

            'delivery_address' => 'required_if:address_option,saved|nullable|string',
            'new_delivery_address' => 'required_if:address_option,new|nullable|string',

            'new_city' => 'required_if:address_option,new|nullable|string|max:100',
            'new_barangay' => 'required_if:address_option,new|nullable|string|max:100',
            'new_province' => 'required_if:address_option,new|nullable|string|max:100',
            'new_zip_code' => 'nullable|string|max:20',

            'landmark' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cod,gcash',
            'gcash_reference' => 'required_if:payment_method,gcash|nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Check stock and reserve (Iterates through ALL branches in the cart)
            foreach ($cart as $inventoryId => $item) {
                $inventory = BranchInventory::lockForUpdate()->find($inventoryId);
                if (!$inventory || $inventory->available_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$item['product_name']}");
                }
                // Reserve stock
                $inventory->update([
                    'reserved_quantity' => $inventory->reserved_quantity + $item['quantity']
                ]);
            }

            // --- MAP ADDRESS DATA BASED ON SELECTION ---
            $addressOption = $request->input('address_option', 'saved');
            $deliveryAddress = $request->delivery_address;
            $city = $request->city;
            $barangay = $request->barangay;
            $otherBarangay = $request->other_barangay;
            $province = $request->province;
            $zipCode = $request->zip_code;
            $landmark = $request->landmark;

            if ($addressOption === 'new') {
                $deliveryAddress = $request->new_delivery_address;
                $city = $request->new_city;
                $barangay = $request->new_barangay;
                $otherBarangay = $request->new_other_barangay;
                $province = $request->new_province;
                $zipCode = $request->new_zip_code;
                $landmark = $request->new_landmark ?? $request->landmark;
            }

            // 2. Calculate Totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $tax = 0; // ✅ No tax
            $total = $subtotal; // ✅ Total is just subtotal

            // 3. Determine if the cart has mixed branches
            $branchIds = collect($cart)->pluck('branch_id')->unique();
            $isMixedCart = $branchIds->count() > 1;

            $singleBranchId = $isMixedCart ? null : $branchIds->first();

            // 4. Create order
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'branch_id' => $singleBranchId,
                'subtotal' => $subtotal,
                'tax' => 0, // ✅ No tax
                'delivery_fee' => 0, // ✅ No delivery fee
                'total_amount' => $subtotal, // ✅ Total is just subtotal
                'status' => 'pending',
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'delivery_type' => $request->delivery_type,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'delivery_address' => $deliveryAddress,
                'city' => $city,
                'barangay' => $barangay,
                'other_barangay' => $otherBarangay,
                'province' => $province,
                'zip_code' => $zipCode,
                'landmark' => $landmark,
                'gcash_reference' => $request->gcash_reference,
                'notes' => $request->notes,
            ]);

            // 5. Create order items AND create reservation records
            foreach ($cart as $inventoryId => $item) {
                // Create order item
                $order->items()->create([
                    'inventory_id' => $inventoryId,
                    'product_id' => $item['product_id'],
                    'flavor_id' => $item['flavor_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
                
                // CREATE INVENTORY RESERVATION RECORD
                InventoryReservation::create([
                    'branch_inventory_id' => $inventoryId,
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'quantity' => $item['quantity'],
                    'reservation_type' => 'online_order',
                    'status' => 'active',
                    'expires_at' => now()->addHours(24),
                    'notes' => 'Auto-reserved for order #' . $order->order_number
                ]);
            }

            // --- LALAMOVE / BRANCH DRIVER LOGIC ---
            // Determine if inside Calamba
            $isInsideCalamba = false;
            if ($city) {
                $cityLower = strtolower(trim($city));
                if ($cityLower === 'calamba city' || $cityLower === 'calamba') {
                    $isInsideCalamba = true;
                }
            }

            // If it's a Lalamove order (Outside Calamba) swap the inventory to Paciano logic
            if (!$isInsideCalamba) {
                foreach ($order->items as $orderItem) {
                    // Optional: Implement your Lalamove Proximity Logic here
                    // For now, we keep the original inventory_id as is
                }
            }

            $deliveryStatus = $isInsideCalamba ? 'pending' : 'lalamove_pending';

            Delivery::create([
                'order_id' => $order->id,
                'driver_id' => null,
                'driver_shift_id' => null,
                'status' => $deliveryStatus,
                'delivery_address' => $deliveryAddress,
                'recipient_name' => $request->customer_name,
                'recipient_phone' => $request->customer_phone,
                'tracking_number' => null,
                'notes' => $request->notes . ($isInsideCalamba ? '' : ' [LALAMOVE REQUIRED]'),
            ]);

            // 6. Clear cart
            CartHelper::clearCart();

            DB::commit();

            if ($isInsideCalamba) {
                return redirect()->route('customer.orders.index')->with('success', 'Order placed successfully! Our rider will contact you soon.');
            } else {
                return redirect()->route('customer.orders.index')->with('success', 'Order placed successfully! We are processing your Lalamove booking. A tracking link will be at view details.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}