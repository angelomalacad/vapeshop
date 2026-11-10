<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\BranchInventory;
use Illuminate\Support\Facades\Auth;

class CartHelper
{
    public static function getCart()
    {
        if (!Auth::check()) {
            return [];
        }

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('inventory.product', 'inventory.flavor', 'inventory.branch')
            ->get();

        $formattedCart = [];
        foreach ($cartItems as $cart) {
            $inventory = $cart->inventory;
            if (!$inventory) continue;

            $totalStock = BranchInventory::withoutGlobalScopes()
                ->where('product_id', $inventory->product_id)
                ->where('flavor_id', $inventory->flavor_id)
                ->get()
                ->sum('available_quantity');

            // ✅ SAFE FALLBACK (Controller overrides this with the exact split)
            $branchName = $inventory->branch->name ?? 'Unknown Branch';
            if ($cart->quantity > $inventory->available_quantity) {
                $branchName .= ' (+ Other Branches)';
            }

            $formattedCart[$cart->inventory_id] = [
                'inventory_id' => $cart->inventory_id,
                'branch_id' => $inventory->branch_id,
                'product_id' => $inventory->product_id,
                'product_name' => $inventory->product->name ?? 'Unknown Product',
                'flavor_name' => $inventory->flavor->name ?? null,
                'flavor_id' => $inventory->flavor->id ?? null,
                'price' => $inventory->product->price ?? 0,
                'quantity' => $cart->quantity,
                'product_image' => $inventory->product->image ? \Storage::url($inventory->product->image) : null,
                'max_quantity' => $totalStock,
                'branch_name' => $branchName, // ✅ REQUIRED FOR VIEW
            ];
        }

        return $formattedCart;
    }

    public static function getItemCount()
    {
        $cart = self::getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public static function getTotal()
    {
        $cart = self::getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public static function addItem($inventoryId, $quantity, $branchId, $productName, $price, $flavorName = null, $productId = null, $flavorId = null)
    {
        if (!Auth::check()) {
            return false;
        }

        $inventory = BranchInventory::withoutGlobalScopes()->find($inventoryId);
        $maxStock = 0;
        
        if ($inventory) {
            $maxStock = BranchInventory::withoutGlobalScopes()
                ->where('product_id', $inventory->product_id)
                ->where('flavor_id', $inventory->flavor_id)
                ->get()
                ->sum('available_quantity');
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('inventory_id', $inventoryId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            $cartItem->quantity = min($newQuantity, $maxStock);
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'inventory_id' => $inventoryId,
                'quantity' => min($quantity, $maxStock),
            ]);
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$inventoryId])) {
            $newQuantity = $cart[$inventoryId]['quantity'] + $quantity;
            $cart[$inventoryId]['quantity'] = min($newQuantity, $maxStock);
        } else {
            $cart[$inventoryId] = [
                'inventory_id' => $inventoryId,
                'branch_id' => $branchId,
                'product_id' => $productId,
                'product_name' => $productName,
                'flavor_name' => $flavorName,
                'flavor_id' => $flavorId,
                'price' => $price,
                'quantity' => min($quantity, $maxStock),
            ];
        }
        session()->put('cart', $cart);

        return true;
    }

    public static function updateQuantity($inventoryId, $quantity)
    {
        if (!Auth::check()) {
            return false;
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('inventory_id', $inventoryId)
            ->first();

        if ($cartItem) {
            if ($quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }
        return true;
    }

    public static function removeItem($inventoryId)
    {
        if (!Auth::check()) {
            return false;
        }

        Cart::where('user_id', Auth::id())
            ->where('inventory_id', $inventoryId)
            ->delete();

        return true;
    }

    public static function clearCart()
    {
        if (!Auth::check()) {
            return false;
        }

        Cart::where('user_id', Auth::id())->delete();
        return true;
    }

    public static function isEmpty()
    {
        return count(self::getCart()) === 0;
    }
}