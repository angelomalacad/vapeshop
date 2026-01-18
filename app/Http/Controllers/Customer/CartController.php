<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $branchId = Session::get('selected_branch');
        
        if (!$branchId) {
            return redirect()->route('branches.index')
                ->with('error', 'Please select a branch first');
        }
        
        $branch = Branch::findOrFail($branchId);
        $items = [];
        $subtotal = 0;
        
        foreach ($cart as $productId => $quantity) {
            $inventory = Inventory::where('branch_id', $branchId)
                ->where('product_id', $productId)
                ->with('product')
                ->first();
            
            if ($inventory && $inventory->product) {
                $price = $inventory->product->price;
                $items[] = [
                    'id' => $productId,
                    'name' => $inventory->product->name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'available' => $inventory->available_quantity,
                    'image' => $inventory->product->image,
                    'total' => $price * $quantity
                ];
                $subtotal += $price * $quantity;
            }
        }
        
        return view('customer.cart.index', compact('items', 'subtotal', 'branch'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'branch_id' => 'required|exists:branches,id'
        ]);
        
        // Check inventory
        $inventory = Inventory::where('branch_id', $request->branch_id)
            ->where('product_id', $request->product_id)
            ->first();
        
        if (!$inventory || $inventory->available_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available'
            ]);
        }
        
        // Set selected branch
        Session::put('selected_branch', $request->branch_id);
        
        // Add to cart
        $cart = Session::get('cart', []);
        $productId = $request->product_id;
        
        if (isset($cart[$productId])) {
            $cart[$productId] += $request->quantity;
        } else {
            $cart[$productId] = $request->quantity;
        }
        
        Session::put('cart', $cart);
        
        return response()->json([
            'success' => true,
            'cart_count' => array_sum($cart),
            'message' => 'Product added to cart'
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $branchId = Session::get('selected_branch');
        $inventory = Inventory::where('branch_id', $branchId)
            ->where('product_id', $id)
            ->first();
        
        if (!$inventory || $inventory->available_quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock available');
        }
        
        $cart = Session::get('cart', []);
        $cart[$id] = $request->quantity;
        Session::put('cart', $cart);
        
        return back()->with('success', 'Cart updated successfully');
    }
    
    public function remove($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        
        return back()->with('success', 'Item removed from cart');
    }
    
    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Cart cleared successfully');
    }
}