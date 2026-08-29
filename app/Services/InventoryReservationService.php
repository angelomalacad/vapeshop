<?php

namespace App\Services;

use App\Models\BranchInventory;
use App\Models\InventoryReservation;
use App\Models\StockTransfer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventoryReservationService
{
    /**
     * Reserve stock for online order
     */
    public function reserveForOrder(BranchInventory $inventory, $orderId, $quantity, $expiresInHours = 24)
    {
        return DB::transaction(function () use ($inventory, $orderId, $quantity, $expiresInHours) {
            // Lock the inventory row for update
            $lockedInventory = BranchInventory::where('id', $inventory->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedInventory) {
                return [
                    'success' => false,
                    'message' => 'Inventory item not found.'
                ];
            }

            // Check if enough stock is available
            if ($lockedInventory->available_quantity < $quantity) {
                return [
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $lockedInventory->available_quantity
                ];
            }

            // Create reservation
            $reservation = InventoryReservation::create([
                'branch_inventory_id' => $lockedInventory->id,
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'quantity' => $quantity,
                'reservation_type' => 'online_order',
                'status' => 'active',
                'expires_at' => now()->addHours($expiresInHours),
                'notes' => 'Auto-reserved for online order'
            ]);

            // Update reserved quantity
            $lockedInventory->update([
                'reserved_quantity' => $lockedInventory->reserved_quantity + $quantity
            ]);

            return [
                'success' => true,
                'message' => 'Stock reserved successfully.',
                'reservation' => $reservation
            ];
        });
    }

    /**
     * Reserve stock for stock transfer
     */
    public function reserveForTransfer(BranchInventory $inventory, $quantity, $transferId = null, $expiresInHours = 72)
    {
        return DB::transaction(function () use ($inventory, $quantity, $transferId, $expiresInHours) {
            // Lock the inventory row for update
            $lockedInventory = BranchInventory::where('id', $inventory->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedInventory) {
                return [
                    'success' => false,
                    'message' => 'Inventory item not found.'
                ];
            }

            // Check if enough stock is available
            if ($lockedInventory->available_quantity < $quantity) {
                return [
                    'success' => false,
                    'message' => 'Insufficient stock for transfer. Available: ' . $lockedInventory->available_quantity
                ];
            }

            // Create reservation
            $reservation = InventoryReservation::create([
                'branch_inventory_id' => $lockedInventory->id,
                'stock_transfer_id' => $transferId,
                'user_id' => Auth::id(),
                'quantity' => $quantity,
                'reservation_type' => 'stock_transfer',
                'status' => 'active',
                'expires_at' => now()->addHours($expiresInHours),
                'notes' => 'Auto-reserved for stock transfer'
            ]);

            // Update reserved quantity
            $lockedInventory->update([
                'reserved_quantity' => $lockedInventory->reserved_quantity + $quantity
            ]);

            return [
                'success' => true,
                'message' => 'Stock reserved for transfer successfully.',
                'reservation' => $reservation
            ];
        });
    }

    /**
     * Release a reservation (cancel/expire)
     */
    public function releaseReservation($reservationId)
    {
        return DB::transaction(function () use ($reservationId) {
            // Lock the reservation
            $reservation = InventoryReservation::where('id', $reservationId)
                ->lockForUpdate()
                ->first();

            if (!$reservation) {
                return [
                    'success' => false,
                    'message' => 'Reservation not found.'
                ];
            }

            if ($reservation->status !== 'active') {
                return [
                    'success' => false,
                    'message' => 'Only active reservations can be released.'
                ];
            }

            // Lock and update inventory
            $inventory = BranchInventory::where('id', $reservation->branch_inventory_id)
                ->lockForUpdate()
                ->first();

            if ($inventory) {
                $inventory->update([
                    'reserved_quantity' => max(0, $inventory->reserved_quantity - $reservation->quantity)
                ]);
            }

            // Update reservation status
            $reservation->update([
                'status' => 'released',
                'released_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Reservation released successfully.'
            ];
        });
    }

    /**
     * Convert reservation to actual sale (deduct from quantity)
     */
    public function convertReservationToSale($reservationId)
    {
        return DB::transaction(function () use ($reservationId) {
            // Lock the reservation
            $reservation = InventoryReservation::where('id', $reservationId)
                ->lockForUpdate()
                ->first();

            if (!$reservation) {
                return [
                    'success' => false,
                    'message' => 'Reservation not found.'
                ];
            }

            if ($reservation->status !== 'active') {
                return [
                    'success' => false,
                    'message' => 'Only active reservations can be converted.'
                ];
            }

            // Lock and update inventory
            $inventory = BranchInventory::where('id', $reservation->branch_inventory_id)
                ->lockForUpdate()
                ->first();

            if ($inventory) {
                // Deduct from quantity and release reservation
                $inventory->update([
                    'quantity' => $inventory->quantity - $reservation->quantity,
                    'reserved_quantity' => max(0, $inventory->reserved_quantity - $reservation->quantity)
                ]);
            }

            // Update reservation status
            $reservation->update([
                'status' => 'converted',
                'converted_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Reservation converted to sale successfully.'
            ];
        });
    }

    /**
     * Complete transfer and release reservation
     */
    public function completeTransfer($transferId)
    {
        return DB::transaction(function () use ($transferId) {
            $transfer = StockTransfer::with('reservations')->find($transferId);
            
            if (!$transfer) {
                return [
                    'success' => false,
                    'message' => 'Transfer not found.'
                ];
            }

            // Find active reservations for this transfer
            $reservations = InventoryReservation::where('stock_transfer_id', $transferId)
                ->where('status', 'active')
                ->lockForUpdate()
                ->get();

            foreach ($reservations as $reservation) {
                // Release the reservation
                $inventory = BranchInventory::where('id', $reservation->branch_inventory_id)
                    ->lockForUpdate()
                    ->first();

                if ($inventory) {
                    $inventory->update([
                        'reserved_quantity' => max(0, $inventory->reserved_quantity - $reservation->quantity)
                    ]);
                }

                $reservation->update([
                    'status' => 'converted',
                    'converted_at' => now()
                ]);
            }

            return [
                'success' => true,
                'message' => 'Transfer completed and reservations released.'
            ];
        });
    }

    /**
     * Expire old reservations
     */
    public function expireReservations()
    {
        $expiredReservations = InventoryReservation::where('status', 'active')
            ->where('expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($expiredReservations as $reservation) {
            $result = $this->releaseReservation($reservation->id);
            if ($result['success']) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Check availability without reserving
     */
    public function checkAvailability($branchId, $productId, $quantity, $flavorId = null)
    {
        $inventory = BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->when($flavorId, function($query) use ($flavorId) {
                return $query->where('flavor_id', $flavorId);
            })
            ->first();

        if (!$inventory) {
            return [
                'available' => false,
                'quantity' => 0,
                'message' => 'Product not found in inventory'
            ];
        }

        $available = $inventory->available_quantity;

        return [
            'available' => $available >= $quantity,
            'quantity' => $available,
            'message' => $available >= $quantity ? 'Stock available' : 'Insufficient stock'
        ];
    }
}