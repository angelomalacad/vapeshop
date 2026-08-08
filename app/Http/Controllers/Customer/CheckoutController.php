<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Delivery;
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

        // --- PREPARE CART ITEMS FOR ORDER SUMMARY IMAGES ---
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $inventoryId => $item) {
            // Fetch the inventory and product to get the image
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

        $tax = $subtotal * 0.12;
        $total = $subtotal + $tax;

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

        // CRITICAL: Ensure $total is passed correctly!
        // Force the variable to exist just for this page
        $isInsideCalamba = $isInsideCalamba ?? false;

        return view('customer.checkout.index', compact('branch', 'subtotal', 'tax', 'total', 'cartItems', 'isInsideCalamba'));
    }

    public function store(Request $request)
    {
        
        // // ==========================================================
        // // DEBUG: Dump the incoming data to see what fails
        // // ==========================================================
        // dd([
        //     'all_data' => $request->all(),
        //     'city' => $request->city,
        //     'barangay' => $request->barangay,
        //     'address_option' => $request->address_option
        // ]);
        // // ==========================================================

        $cart = CartHelper::getCart();
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
                $inventory->reserve($item['quantity']);
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
                $otherBarangay = $request->new_other_barangay; // <--- FIX: Capture the new address version
                $province = $request->new_province;
                $zipCode = $request->new_zip_code;
                $landmark = $request->new_landmark ?? $request->landmark;
            }

            // 2. Calculate Totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $tax = $subtotal * 0.12;
            $total = $subtotal + $tax;

            // 3. Determine if the cart has mixed branches
            $branchIds = collect($cart)->pluck('branch_id')->unique();
            $isMixedCart = $branchIds->count() > 1;
            
            // If it's a single branch order, use that ID. If mixed, set to null
            $singleBranchId = $isMixedCart ? null : $branchIds->first();

            // 4. Create order
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'branch_id' => $singleBranchId, // Will be NULL if cart is mixed
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => 0,
                'total_amount' => $total,
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

            // =================================================================================
            // 5. Create order items (WITH LALAMOVE DEDUCTION LOGIC)
            // =================================================================================
            foreach ($cart as $inventoryId => $item) {
                $finalInventoryId = $inventoryId; // Default to the cart ID

                // If it's a Lalamove order (Outside Calamba) ...
                if (!$isInsideCalamba) {
                    // 1. Find ALL inventories for this product across ALL branches with stock
                    $allInventories = BranchInventory::where('product_id', $item['product_id'])
                        ->where('available_quantity', '>=', $item['quantity'])
                        ->get();

                    // 2. Define the Deduction Order for Lalamove
                    $fulfillmentBranchId = null;

                    // PRIORITY 1: Try Paciano (Branch ID 3)
                    $pacianoStock = $allInventories->where('branch_id', 3)->first();
                    if ($pacianoStock) {
                        $fulfillmentBranchId = 3;
                    } 
                    // PRIORITY 2: If Paciano is out, try Paciano V2 (Branch ID 4)
                    else {
                        $v2Stock = $allInventories->where('branch_id', 4)->first();
                        if ($v2Stock) {
                            $fulfillmentBranchId = 4;
                        }
                        // PRIORITY 3: If V2 is out, try Majada Out (Branch ID 5)
                        else {
                            $majadaStock = $allInventories->where('branch_id', 5)->first();
                            if ($majadaStock) {
                                $fulfillmentBranchId = 5;
                            }
                            // PRIORITY 4: If Majada is out, try MCDC (Branch ID 2)
                            else {
                                $mcdcStock = $allInventories->where('branch_id', 2)->first();
                                if ($mcdcStock) {
                                    $fulfillmentBranchId = 2;
                                }
                                // PRIORITY 5: If MCDC is out, try Canlubang Main (Branch ID 1)
                                else {
                                    $canlubangStock = $allInventories->where('branch_id', 1)->first();
                                    if ($canlubangStock) {
                                        $fulfillmentBranchId = 1;
                                    }
                                }
                            }
                        }
                    }

                    // 3. If we found a branch with stock, use it.
                    if ($fulfillmentBranchId) {
                        $targetInventory = $allInventories->where('branch_id', $fulfillmentBranchId)->first();
                        if ($targetInventory) {
                            $finalInventoryId = $targetInventory->id;
                        }
                    }
                }

                // 4. Create the order item
                $order->items()->create([
                    'inventory_id' => $finalInventoryId, 
                    'product_id' => $item['product_id'],
                    'flavor_id' => $item['flavor_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }
            // =================================================================================

            // --- LALAMOVE / BRANCH DRIVER LOGIC ---
            $isInsideCalamba = false;
            if ($city) {
                $cityLower = strtolower(trim($city));
                if ($cityLower === 'calamba city' || $cityLower === 'calamba') {
                    $isInsideCalamba = true;
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

            // --- SUCCESS MESSAGES REDIRECTING TO MY ORDERS ---
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