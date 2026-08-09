<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartHelper::getCart();
        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $inventory = BranchInventory::with(['product', 'flavor', 'branch'])
                ->find($item['inventory_id']);

            if ($inventory && $inventory->available_quantity >= $item['quantity']) {
                // Get product image URL
                $product = $inventory->product;
                $imageUrl = null;

                if ($product) {
                    // Check if product has image
                    if ($product->image) {
                        // Check if it's a URL
                        if (filter_var($product->image, FILTER_VALIDATE_URL)) {
                            $imageUrl = $product->image;
                        }
                        // Check if it exists in storage
                        elseif (\Storage::disk('public')->exists($product->image)) {
                            $imageUrl = \Storage::url($product->image);
                        }
                    }

                    // Fallback to image_url column if available
                    if (!$imageUrl && $product->image_url) {
                        if (filter_var($product->image_url, FILTER_VALIDATE_URL)) {
                            $imageUrl = $product->image_url;
                        }
                    }
                }

                $items[] = [
                    'inventory_id' => $item['inventory_id'],
                    'product_id' => $item['product_id'] ?? $product->id ?? null,
                    'product_name' => $item['product_name'],
                    'flavor_name' => $item['flavor_name'] ?? $inventory->flavor->name ?? null,
                    'branch_name' => $inventory->branch->name ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'max_quantity' => $inventory->available_quantity,
                    'product_image' => $imageUrl,
                ];
                $subtotal += $item['price'] * $item['quantity'];
            } else {
                CartHelper::removeItem($key);
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

        $inventory = BranchInventory::with(['product', 'flavor'])->find($request->inventory_id);

        if (!$inventory || $inventory->available_quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock.');
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
            $inventory = BranchInventory::find($inventoryId);
            if ($inventory && $inventory->available_quantity < $request->quantity) {
                return response()->json(['success' => false, 'message' => 'Insufficient stock available.']);
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

    /**
     * Checkout selected items from cart
     */
    public function checkoutSelected(Request $request)
    {
        \Log::info('Checkout selected called', [
            'method' => $request->method(),
            'all_data' => $request->all()
        ]);

        $request->validate([
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:branch_inventories,id'
        ]);

        $selectedInventoryIds = $request->selected_items;
        $cart = CartHelper::getCart();
        $selectedCart = [];

        // Filter cart to only selected items
        foreach ($selectedInventoryIds as $inventoryId) {
            if (isset($cart[$inventoryId])) {
                $selectedCart[$inventoryId] = $cart[$inventoryId];
            }
        }

        if (empty($selectedCart)) {
            return redirect()->route('customer.cart.index')->with('error', 'No items selected for checkout.');
        }

        // Store the selected cart AND a flag to verify it is selected
        Session::put('selected_cart', $selectedCart);
        Session::put('selected_checkout', true);

        return redirect()->route('customer.checkout.index');
    }
}
