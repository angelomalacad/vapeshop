<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use App\Models\BranchInventory;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartHelper::getCart();
        $items = [];
        $subtotal = 0;
        foreach ($cart as $key => $item) {
            $inventory = BranchInventory::find($item['inventory_id']);
            if ($inventory && $inventory->available_quantity >= $item['quantity']) {
                $items[] = $item;
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
            $inventory->product_id
        );
        
        return redirect()->route('customer.cart.index')->with('success', 'Product added to cart.');
    }
    
    public function update(Request $request, $inventoryId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        CartHelper::updateQuantity($inventoryId, $request->quantity);
        return redirect()->route('customer.cart.index')->with('success', 'Cart updated.');
    }
    
    public function remove($inventoryId)
    {
        CartHelper::removeItem($inventoryId);
        return redirect()->route('customer.cart.index')->with('success', 'Item removed.');
    }
    
    public function clear()
    {
        CartHelper::clearCart();
        return redirect()->route('customer.cart.index')->with('success', 'Cart cleared.');
    }
}