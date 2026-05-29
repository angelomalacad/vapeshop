<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class CartHelper
{
    /**
     * Get all cart items
     */
    public static function getCart()
    {
        return Session::get('cart', []);
    }

    /**
     * Get total item count in cart (sum of quantities)
     */
    public static function getItemCount()
    {
        $cart = Session::get('cart', []);
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    /**
     * Get number of unique items in cart
     */
    public static function getCartItemCount()
    {
        $cart = Session::get('cart', []);
        return count($cart);
    }

    /**
     * Get cart subtotal (sum of price * quantity)
     */
    public static function getTotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    /**
     * Get subtotal (alias for getTotal)
     */
    public static function getSubtotal()
    {
        return self::getTotal();
    }

    /**
     * Add item to cart
     */
    public static function addItem($inventoryId, $quantity, $branchId, $productName, $price, $flavorName = null, $productId = null)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$inventoryId])) {
            $cart[$inventoryId]['quantity'] += $quantity;
        } else {
            $cart[$inventoryId] = [
                'inventory_id' => $inventoryId,
                'branch_id' => $branchId,
                'product_id' => $productId,
                'product_name' => $productName,
                'flavor_name' => $flavorName,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);
        return true;
    }

    /**
     * Update item quantity
     */
    public static function updateQuantity($inventoryId, $quantity)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$inventoryId])) {
            if ($quantity <= 0) {
                unset($cart[$inventoryId]);
            } else {
                $cart[$inventoryId]['quantity'] = $quantity;
            }
            Session::put('cart', $cart);
        }

        return true;
    }

    /**
     * Remove item from cart
     */
    public static function removeItem($inventoryId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$inventoryId])) {
            unset($cart[$inventoryId]);
            Session::put('cart', $cart);
        }

        return true;
    }

    /**
     * Clear entire cart
     */
    public static function clearCart()
    {
        Session::forget('cart');
        return true;
    }

    /**
     * Check if cart is empty
     */
    public static function isEmpty()
    {
        $cart = Session::get('cart', []);
        return empty($cart);
    }

    /**
     * Get branch ID from cart (all items should have same branch)
     */
    public static function getBranchId()
    {
        $cart = Session::get('cart', []);

        foreach ($cart as $item) {
            if (isset($item['branch_id'])) {
                return $item['branch_id'];
            }
        }

        return null;
    }

    /**
     * Get cart items as array for checkout
     */
    public static function getItemsForCheckout()
    {
        $cart = Session::get('cart', []);
        $items = [];

        foreach ($cart as $item) {
            $items[] = [
                'inventory_id' => $item['inventory_id'],
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['product_name'],
                'flavor_name' => $item['flavor_name'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ];
        }

        return $items;
    }
}
