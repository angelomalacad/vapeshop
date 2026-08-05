<?php

namespace App\Helpers;

use App\Models\Cart;
use App\Models\BranchInventory;
use Illuminate\Support\Facades\Auth;

class CartHelper
{
    /**
     * Get all cart items for the logged-in user
     */
    public static function getCart()
    {
        if (!Auth::check()) {
            return []; // Guests cannot have persistent carts
        }

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('inventory.product', 'inventory.flavor', 'inventory.branch')
            ->get();

        $formattedCart = [];
        foreach ($cartItems as $cart) {
            $inventory = $cart->inventory;
            if (!$inventory) continue; // Skip if inventory was deleted

            $formattedCart[$cart->inventory_id] = [
                'inventory_id' => $cart->inventory_id,
                'branch_id' => $inventory->branch_id,
                'product_id' => $inventory->product_id,
                'product_name' => $inventory->product->name ?? 'Unknown Product',
                'flavor_name' => $inventory->flavor->name ?? null,
                'price' => $inventory->product->price ?? 0,
                'quantity' => $cart->quantity,
                'product_image' => $inventory->product->image ? \Storage::url($inventory->product->image) : null,
                'max_quantity' => $inventory->available_quantity,
            ];
        }

        return $formattedCart;
    }

    /**
     * Get total item count in cart
     */
    public static function getItemCount()
    {
        $cart = self::getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Get cart subtotal
     */
    public static function getTotal()
    {
        $cart = self::getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Add item to cart
     */
    public static function addItem($inventoryId, $quantity, $branchId, $productName, $price, $flavorName = null, $productId = null)
    {
        if (!Auth::check()) {
            return false;
        }

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('inventory_id', $inventoryId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'inventory_id' => $inventoryId,
                'quantity' => $quantity,
            ]);
        }

        return true;
    }

    /**
     * Update item quantity
     */
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

    /**
     * Remove item from cart
     */
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

    /**
     * Clear entire cart for user
     */
    public static function clearCart()
    {
        if (!Auth::check()) {
            return false;
        }

        Cart::where('user_id', Auth::id())->delete();
        return true;
    }

    /**
     * Check if cart is empty
     */
    public static function isEmpty()
    {
        return count(self::getCart()) === 0;
    }
}