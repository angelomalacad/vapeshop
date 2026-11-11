<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\BranchInventory;
use App\Models\InventoryReservation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (session()->has('selected_checkout')) {
            $cart = session()->get('selected_cart', []);
        } else {
            $cart = CartHelper::getCart();
        }

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
        }

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

        $isInsideCalamba = false;
        $userCity = Auth::check() ? Auth::user()->city : null;
        if ($userCity) {
            $cityLower = strtolower(trim($userCity));
            if ($cityLower === 'calamba city' || $cityLower === 'calamba') {
                $isInsideCalamba = true;
            }
        }

        $tax = 0;
        $calambaFee = (float) (\DB::table('settings')->where('key', 'calamba_delivery_fee')->value('value') ?? 50);
        $deliveryFee = $isInsideCalamba ? $calambaFee : 0;
        $total = $subtotal + $deliveryFee;

        return view('customer.checkout.index', compact('branch', 'subtotal', 'tax', 'deliveryFee', 'total', 'cartItems', 'isInsideCalamba'));
    }

    public function store(Request $request)
    {
        $isSelectedCheckout = session()->has('selected_checkout');
        if ($isSelectedCheckout) {
            $cart = session()->get('selected_cart', []);
        } else {
            $cart = CartHelper::getCart();
        }

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Cart is empty.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'delivery_type' => 'required|in:pickup,delivery',
            'delivery_address' => 'required_if:address_option,saved|nullable|string',
            'delivery_date' => 'nullable|date|after_or_equal:today',
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

        // ✅ Proximity Map for Auto-Split
        $proximityMap = [
            1 => [2, 5, 4, 3], // Asia 1 (Main) -> MCDC -> Majada Out -> V2 -> Paciano
            2 => [1, 5, 4, 3], // MCDC -> Asia 1 -> Majada Out -> V2 -> Paciano
            3 => [4, 5, 2, 1], // Paciano -> V2 -> Majada Out -> MCDC -> Asia 1
            4 => [3, 5, 2, 1], // V2 -> Paciano -> Majada Out -> MCDC -> Asia 1
            5 => [2, 1, 4, 3], // Majada Out -> MCDC -> Asia 1 -> V2 -> Paciano
        ];
        
        $userBranchId = null;
        if (Auth::check() && Auth::user()->barangay) {
            $servingBranch = DB::table('branch_barangay')->where('barangay_name', Auth::user()->barangay)->first();
            if ($servingBranch) $userBranchId = $servingBranch->branch_id;
        }

        DB::beginTransaction();
        try {
            $splitItems = [];

            // ✅ AUTO-SPLIT AND RESERVE STOCK
            foreach ($cart as $primaryInventoryId => $item) {
                $neededQty = $item['quantity'];
                $productId = $item['product_id'];
                $flavorId = $item['flavor_id'] ?? null;

                // ✅ FIX: Fetch all, filter in PHP, then sort
                $availableBranches = BranchInventory::with('branch')
                    ->where('product_id', $productId)
                    ->when($flavorId, fn($q) => $q->where('flavor_id', $flavorId), fn($q) => $q->whereNull('flavor_id'))
                    ->get()
                    ->filter(function($inv) {
                        return $inv->available_quantity > 0;
                    })
                    ->sortBy(function($inv) use ($proximityMap, $userBranchId) {
                        if ($userBranchId) {
                            if ($inv->branch_id == $userBranchId) return -1;
                            $priority = array_search($inv->branch_id, $proximityMap[$userBranchId] ?? []);
                            if ($priority !== false) return $priority;
                        }
                        return 999;
                    });

                // Check if total stock across ALL branches is enough
                if ($availableBranches->sum('available_quantity') < $neededQty) {
                    throw new \Exception("Insufficient total stock for {$item['product_name']} across all branches.");
                }

                // Deduct and split across branches
                foreach ($availableBranches as $branchInv) {
                    if ($neededQty <= 0) break;

                    $deductQty = min($neededQty, $branchInv->available_quantity);
                    
                    // Lock and Reserve
                    $branchInv = BranchInventory::lockForUpdate()->find($branchInv->id);
                    $branchInv->update([
                        'reserved_quantity' => $branchInv->reserved_quantity + $deductQty
                    ]);

                    $splitItems[] = [
                        'inventory_id' => $branchInv->id,
                        'product_id' => $productId,
                        'flavor_id' => $flavorId,
                        'quantity' => $deductQty,
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $deductQty,
                    ];

                    $neededQty -= $deductQty;
                }
            }

            // Address logic
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

            $isInsideCalambaForFee = false;
            if ($city) {
                $cityLower = strtolower(trim($city));
                if ($cityLower === 'calamba city' || $cityLower === 'calamba') {
                    $isInsideCalambaForFee = true;
                }
            }

            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $tax = 0;
            $calambaFee = (float) (\DB::table('settings')->where('key', 'calamba_delivery_fee')->value('value') ?? 50);
            $deliveryFee = $isInsideCalambaForFee ? $calambaFee : 0;
            $total = $subtotal + $deliveryFee;

            // Assign primary branch (first split item's branch, or user's branch)
            $singleBranchId = !empty($splitItems) ? BranchInventory::find($splitItems[0]['inventory_id'])->branch_id : ($userBranchId ?? 1);

            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'branch_id' => $singleBranchId,
                'subtotal' => $subtotal,
                'tax' => 0,
                'delivery_fee' => $deliveryFee,
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
                'delivery_date' => $request->delivery_date,
                'city' => $city,
                'barangay' => $barangay,
                'other_barangay' => $otherBarangay,
                'province' => $province,
                'zip_code' => $zipCode,
                'landmark' => $landmark,
                'gcash_reference' => $request->gcash_reference,
                'notes' => $request->notes,
            ]);

            // Create Order Items based on the split array
            foreach ($splitItems as $split) {
                $order->items()->create([
                    'inventory_id' => $split['inventory_id'],
                    'product_id' => $split['product_id'],
                    'flavor_id' => $split['flavor_id'],
                    'quantity' => $split['quantity'],
                    'price' => $split['price'],
                    'subtotal' => $split['subtotal'],
                    'status' => 'pending',
                ]);

                InventoryReservation::create([
                    'branch_inventory_id' => $split['inventory_id'],
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'quantity' => $split['quantity'],
                    'reservation_type' => 'online_order',
                    'status' => 'active',
                    'expires_at' => now()->addHours(24),
                    'notes' => 'Auto-split reserved for order #' . $order->order_number
                ]);
            }

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

            foreach ($cart as $inventoryId => $item) {
                CartHelper::removeItem($inventoryId);
            }

            if ($isSelectedCheckout) {
                session()->forget('selected_checkout');
                session()->forget('selected_cart');
            }

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