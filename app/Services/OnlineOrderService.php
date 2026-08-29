<?php

namespace App\Services;

use App\Models\BranchInventory;
use App\Models\InventoryReservation;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OnlineOrderService
{
    protected $reservationService;

    public function __construct(InventoryReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Place an online order with stock reservation
     */
    public function placeOrder($orderData, $items)
    {
        return DB::transaction(function () use ($orderData, $items) {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'payment_status' => 'pending',
                'total_amount' => $orderData['total_amount'],
                'branch_id' => $orderData['branch_id'],
                'delivery_address' => $orderData['delivery_address'] ?? null,
                'notes' => $orderData['notes'] ?? null,
            ]);

            $reservations = [];

            // Process each item
            foreach ($items as $item) {
                // Find inventory
                $inventory = BranchInventory::where('branch_id', $order->branch_id)
                    ->where('product_id', $item['product_id'])
                    ->when(isset($item['flavor_id']), function($query) use ($item) {
                        return $query->where('flavor_id', $item['flavor_id']);
                    })
                    ->lockForUpdate()
                    ->first();

                if (!$inventory) {
                    throw new \Exception("Product not found in inventory");
                }

                // Reserve stock for this item
                $reservationResult = $this->reservationService->reserveForOrder(
                    $inventory,
                    $order->id,
                    $item['quantity']
                );

                if (!$reservationResult['success']) {
                    throw new \Exception($reservationResult['message']);
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'flavor_id' => $item['flavor_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);

                $reservations[] = $reservationResult['reservation'];
            }

            return [
                'success' => true,
                'order' => $order,
                'reservations' => $reservations,
                'message' => 'Order placed successfully. Stock reserved.'
            ];
        });
    }

    /**
     * Cancel an order and release reservations
     */
    public function cancelOrder($orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->findOrFail($orderId);

            // Verify user can cancel
            if ($order->user_id !== Auth::id()) {
                throw new \Exception('Unauthorized access.');
            }

            // Check if order can be cancelled
            if (!in_array($order->status, ['pending', 'processing'])) {
                throw new \Exception('Order cannot be cancelled at this stage.');
            }

            // Release all reservations
            $reservations = InventoryReservation::where('order_id', $order->id)
                ->where('status', 'active')
                ->get();

            foreach ($reservations as $reservation) {
                $this->reservationService->releaseReservation($reservation->id);
            }

            // Update order status
            $order->update(['status' => 'cancelled']);

            return [
                'success' => true,
                'message' => 'Order cancelled and stock released.'
            ];
        });
    }

    /**
     * Complete order and convert reservations to actual sales
     */
    public function completeOrder($orderId)
    {
        return DB::transaction(function () use ($orderId) {
            $order = Order::with('items')->findOrFail($orderId);

            // Get all active reservations for this order
            $reservations = InventoryReservation::where('order_id', $order->id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->get();

            // Convert each reservation to actual sale
            foreach ($reservations as $reservation) {
                $result = $this->reservationService->convertReservationToSale($reservation->id);
                
                if (!$result['success']) {
                    throw new \Exception($result['message']);
                }
            }

            // Update order status
            $order->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Order completed and stock deducted.'
            ];
        });
    }
}