<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartHelper::getCart();
        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $inventory = BranchInventory::withoutGlobalScopes()->with(['product', 'flavor', 'branch'])
                ->find($item['inventory_id']);

            if ($inventory) {
                // ✅ Calculate total stock across ALL branches
                $totalStock = BranchInventory::withoutGlobalScopes()
                    ->where('product_id', $inventory->product_id)
                    ->where('flavor_id', $inventory->flavor_id)
                    ->get()
                    ->sum('available_quantity');

                if ($totalStock >= $item['quantity']) {
                    $product = $inventory->product;
                    $imageUrl = null;

                    if ($product) {
                        if ($product->image) {
                            if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                                $imageUrl = $product->image;
                            } elseif (\Storage::disk('public')->exists($product->image)) {
                                $imageUrl = \Storage::url($product->image);
                            }
                        }
                        if (!$imageUrl && $product->image_url) {
                            if (filter_var($product->image_url, FILTER_VALIDATE_URL)) {
                                $imageUrl = $product->image_url;
                            }
                        }
                    }

                    // ✅ DYNAMIC BRANCH DISPLAY LOGIC
                    $userBranchId = null;
                    if (Auth::check() && Auth::user()->barangay) {
                        $servingBranch = DB::table('branch_barangay')
                            ->where('barangay_name', Auth::user()->barangay)->first();
                        if ($servingBranch) {
                            $userBranchId = $servingBranch->branch_id;
                        }
                    }

                    $proximityMap = [
                        1 => [2, 5, 4, 3],
                        2 => [1, 5, 4, 3],
                        3 => [4, 5, 2, 1],
                        4 => [3, 5, 2, 1],
                        5 => [2, 1, 4, 3],
                    ];

                    // ✅ FIX: Fetch all, filter in PHP, then sort
                    $allBranches = BranchInventory::withoutGlobalScopes()
                        ->with('branch')
                        ->where('product_id', $inventory->product_id)
                        ->where('flavor_id', $inventory->flavor_id)
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

                    $remainingQty = $item['quantity'];
                    $usedBranches = [];

                    foreach ($allBranches as $b) {
                        if ($remainingQty <= 0) break;
                        $deduct = min($remainingQty, $b->available_quantity);
                        $usedBranches[] = $b->branch->name ?? 'Unknown';
                        $remainingQty -= $deduct;
                    }

                    if (count($usedBranches) > 1) {
                        $branchDisplayName = implode(' + ', $usedBranches);
                    } elseif (count($usedBranches) == 1) {
                        $branchDisplayName = $usedBranches[0];
                    } else {
                        $branchDisplayName = $inventory->branch->name ?? 'Unknown Branch';
                    }

                    $items[] = [
                        'inventory_id' => $item['inventory_id'],
                        'product_id' => $item['product_id'] ?? $product->id ?? null,
                        'product_name' => $item['product_name'],
                        'flavor_name' => $item['flavor_name'] ?? $inventory->flavor->name ?? null,
                        'branch_name' => $branchDisplayName, // ✅ Show combined branches
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'max_quantity' => $totalStock,
                        'product_image' => $imageUrl,
                    ];
                    $subtotal += $item['price'] * $item['quantity'];
                }
            }
        }

        return view('customer.cart.index', compact('items', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|exists:branch_inventories,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $inventory = BranchInventory::withoutGlobalScopes()->with(['product', 'flavor'])->find($request->inventory_id);

        if (!$inventory) {
            return back()->with('error', 'Product not found.');
        }

        $totalStock = BranchInventory::withoutGlobalScopes()
            ->where('product_id', $inventory->product_id)
            ->where('flavor_id', $inventory->flavor_id)
            ->get()
            ->sum('available_quantity');

        if ($totalStock < $request->quantity) {
            return back()->with('error', 'Insufficient total stock. Only ' . $totalStock . ' left in all branches.');
        }

        $existingCartItem = \App\Models\Cart::where('user_id', Auth::id())
            ->where('inventory_id', $inventory->id)
            ->first();
            
        $currentQtyInCart = $existingCartItem ? $existingCartItem->quantity : 0;

        if (($currentQtyInCart + $request->quantity) > $totalStock) {
            $remainingStock = $totalStock - $currentQtyInCart;
            return back()->with('error', 'Cannot add more. You already have ' . $currentQtyInCart . ' in your cart. Only ' . $remainingStock . ' more available across all branches.');
        }

        CartHelper::addItem(
            $inventory->id,
            $request->quantity,
            $inventory->branch_id,
            $inventory->product->name,
            $inventory->product->price,
            $inventory->flavor->name ?? null,
            $inventory->product_id,
            $inventory->flavor->id ?? null
        );

        return redirect()->route('customer.cart.index')->with('success', 'Product added to cart.');
    }

        public function update(Request $request, $inventoryId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = CartHelper::getCart();
        if (isset($cart[$inventoryId])) {
            $inventory = BranchInventory::withoutGlobalScopes()->find($inventoryId);
            
            if (!$inventory) {
                return response()->json(['success' => false, 'message' => 'This item is no longer available.', 'max_stock' => 0]);
            }

            $totalStock = BranchInventory::withoutGlobalScopes()
                ->where('product_id', $inventory->product_id)
                ->where('flavor_id', $inventory->flavor_id)
                ->get()
                ->sum('available_quantity');

            if ($totalStock <= 0) {
                return response()->json(['success' => false, 'message' => 'This item is currently out of stock everywhere.', 'max_stock' => 0]);
            }

            if ($totalStock < $request->quantity) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Insufficient total stock available. Only ' . $totalStock . ' left across all branches.',
                    'max_stock' => $totalStock // ✅ Return the max so JS can update the input
                ]);
            }
        }

        CartHelper::updateQuantity($inventoryId, $request->quantity);
        return response()->json(['success' => true, 'message' => 'Cart updated.']);
    }

    public function remove($inventoryId)
    {
        CartHelper::removeItem($inventoryId);
        return response()->json(['success' => true, 'message' => 'Item removed.']);
    }

    public function clear()
    {
        CartHelper::clearCart();
        return response()->json(['success' => true, 'message' => 'Cart cleared.']);
    }

    public function checkoutSelected(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:branch_inventories,id'
        ]);

        $selectedInventoryIds = $request->selected_items;
        $cart = CartHelper::getCart();
        $selectedCart = [];

        foreach ($selectedInventoryIds as $inventoryId) {
            if (isset($cart[$inventoryId])) {
                $selectedCart[$inventoryId] = $cart[$inventoryId];
            }
        }

        if (empty($selectedCart)) {
            return redirect()->route('customer.cart.index')->with('error', 'No items selected for checkout.');
        }

        Session::put('selected_cart', $selectedCart);
        Session::put('selected_checkout', true);

        return redirect()->route('customer.checkout.index');
    }
}