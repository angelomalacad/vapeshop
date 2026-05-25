<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class CartHelper
{
    public static function getCart()
    {
        return Session::get('cart', []);
    }

    public static function addItem($inventoryId, $quantity, $branchId, $productName, $price, $flavorName = null, $productId = null)
    {
        $cart = self::getCart();
        $key = $inventoryId;
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'inventory_id' => $inventoryId,
                'branch_id' => $branchId,
                'product_id' => $productId,      // <<< ADD THIS
                'product_name' => $productName,
                'flavor_name' => $flavorName,
                'price' => $price,
                'quantity' => $quantity,
            ];
        }
        Session::put('cart', $cart);
    }

    public static function updateQuantity($inventoryId, $quantity)
    {
        $cart = self::getCart();
        if (isset($cart[$inventoryId])) {
            if ($quantity <= 0) {
                unset($cart[$inventoryId]);
            } else {
                $cart[$inventoryId]['quantity'] = $quantity;
            }
            Session::put('cart', $cart);
        }
    }

    public static function removeItem($inventoryId)
    {
        $cart = self::getCart();
        unset($cart[$inventoryId]);
        Session::put('cart', $cart);
    }

    public static function clearCart()
    {
        Session::forget('cart');
    }

    public static function getTotal()
    {
        $total = 0;
        foreach (self::getCart() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public static function getItemCount()
    {
        return array_sum(array_column(self::getCart(), 'quantity'));
    }
}