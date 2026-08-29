<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;

class BranchInventory extends Model
{
    use HasFactory;

    protected $table = 'branch_inventories';

    protected $fillable = [
        'branch_id', 
        'product_id', 
        'flavor_id', 
        'quantity', 
        'reserved_quantity',
        'low_stock_threshold', 
        'reorder_point', 
        'optimal_stock',
        'last_purchase_price', 
        'last_restocked_at', 
        'expiration_date', 
        'is_archived', 
        'is_disposed', 
        'dispose_reason', 
        'disposed_at'
    ];

    protected $casts = [
        'last_restocked_at' => 'datetime',
        'expiration_date' => 'date',
        'is_archived' => 'boolean',
        'is_disposed' => 'boolean',
        'disposed_at' => 'datetime',
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'reorder_point' => 'integer',
        'optimal_stock' => 'integer',
    ];

    // Append computed attributes
    protected $appends = ['available_quantity', 'is_low_stock', 'is_out_of_stock'];

    /**
     * Relationships
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flavor()
    {
        return $this->belongsTo(ProductFlavor::class);
    }

    /**
     * Reservation relationships
     */
    public function reservations()
    {
        return $this->hasMany(InventoryReservation::class);
    }

    public function activeReservations()
    {
        return $this->hasMany(InventoryReservation::class)
            ->where('status', 'active');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Accessors
     */
    public function getAvailableQuantityAttribute()
    {
        return max(0, ($this->quantity ?? 0) - ($this->reserved_quantity ?? 0));
    }

    public function getIsLowStockAttribute()
    {
        return $this->available_quantity > 0 && $this->available_quantity <= $this->low_stock_threshold;
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->available_quantity <= 0;
    }

    public function getStockStatusAttribute()
    {
        if ($this->is_archived) {
            return 'archived';
        }
        if ($this->available_quantity <= 0) {
            return 'out_of_stock';
        }
        if ($this->available_quantity <= $this->low_stock_threshold) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    /**
     * Scopes
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('(quantity - reserved_quantity) <= low_stock_threshold')
            ->whereRaw('(quantity - reserved_quantity) > 0')
            ->where('is_archived', false)
            ->where('is_disposed', false);
    }

    public function scopeOutOfStock($query)
    {
        return $query->whereRaw('(quantity - reserved_quantity) <= 0')
            ->where('is_archived', false)
            ->where('is_disposed', false);
    }

    public function scopeInStock($query)
    {
        return $query->whereRaw('(quantity - reserved_quantity) > low_stock_threshold')
            ->where('is_archived', false)
            ->where('is_disposed', false);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeAvailable($query)
    {
        return $query->whereRaw('(quantity - reserved_quantity) > 0')
            ->where('is_archived', false)
            ->where('is_disposed', false);
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeNotDisposed($query)
    {
        return $query->where('is_disposed', false);
    }

    /**
     * ========== STOCK RESERVATION METHODS ==========
     */

    /**
     * Check if enough stock is available for reservation
     */
    public function hasAvailableStock($quantity)
    {
        return $this->available_quantity >= $quantity;
    }

    /**
     * Reserve a quantity of stock (for pending orders/transfers)
     * Uses database lock to prevent race conditions
     */
    public function reserveStock($quantity, $reservationType = 'online_order', $orderId = null, $transferId = null)
    {
        return DB::transaction(function () use ($quantity, $reservationType, $orderId, $transferId) {
            // Lock the row for update to prevent race conditions
            $lockedInventory = static::where('id', $this->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedInventory) {
                throw new Exception('Inventory item not found.');
            }

            if (!$lockedInventory->hasAvailableStock($quantity)) {
                throw new Exception(
                    "Insufficient stock to reserve {$quantity} units. Available: {$lockedInventory->available_quantity}"
                );
            }

            // Create reservation record
            $reservation = InventoryReservation::create([
                'branch_inventory_id' => $this->id,
                'order_id' => $orderId,
                'stock_transfer_id' => $transferId,
                'user_id' => auth()->id(),
                'quantity' => $quantity,
                'reservation_type' => $reservationType,
                'status' => 'active',
                'expires_at' => now()->addHours(
                    $reservationType === 'stock_transfer' ? 72 : 24
                ),
                'notes' => 'Auto-reserved for ' . $reservationType
            ]);

            // Update reserved quantity
            $lockedInventory->update([
                'reserved_quantity' => $lockedInventory->reserved_quantity + $quantity
            ]);

            return $reservation;
        });
    }

    /**
     * Confirm reservation and deduct actual stock (when order is confirmed/completed)
     */
    public function confirmReservation($quantity, $reservationId = null)
    {
        return DB::transaction(function () use ($quantity, $reservationId) {
            // Lock the row
            $lockedInventory = static::where('id', $this->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedInventory) {
                throw new Exception('Inventory item not found.');
            }

            if ($lockedInventory->reserved_quantity < $quantity) {
                throw new Exception(
                    "Cannot confirm reservation: only {$lockedInventory->reserved_quantity} units reserved."
                );
            }

            // If reservation ID provided, update reservation status
            if ($reservationId) {
                $reservation = InventoryReservation::where('id', $reservationId)
                    ->where('status', 'active')
                    ->first();

                if ($reservation) {
                    $reservation->update([
                        'status' => 'converted',
                        'converted_at' => now()
                    ]);
                }
            }

            // Deduct from quantity and release reservation
            $lockedInventory->update([
                'quantity' => $lockedInventory->quantity - $quantity,
                'reserved_quantity' => $lockedInventory->reserved_quantity - $quantity,
                'last_restocked_at' => $lockedInventory->quantity <= $quantity ? now() : $lockedInventory->last_restocked_at
            ]);

            // Create stock movement record
            StockMovement::create([
                'branch_id' => $lockedInventory->branch_id,
                'product_id' => $lockedInventory->product_id,
                'flavor_id' => $lockedInventory->flavor_id,
                'previous_quantity' => $lockedInventory->quantity,
                'new_quantity' => $lockedInventory->quantity - $quantity,
                'quantity_change' => -$quantity,
                'movement_type' => 'reservation_confirmed',
                'reference_type' => 'reservation',
                'reference_id' => $reservationId,
                'notes' => 'Reservation confirmed and stock deducted',
                'created_by' => auth()->id()
            ]);

            return true;
        });
    }

    /**
     * Release reservation without deducting stock (when order is cancelled/rejected/expired)
     */
    public function releaseReservation($quantity, $reservationId = null, $reason = 'cancelled')
    {
        return DB::transaction(function () use ($quantity, $reservationId, $reason) {
            // Lock the row
            $lockedInventory = static::where('id', $this->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedInventory) {
                throw new Exception('Inventory item not found.');
            }

            if ($lockedInventory->reserved_quantity < $quantity) {
                throw new Exception(
                    "Cannot release reservation: only {$lockedInventory->reserved_quantity} units reserved."
                );
            }

            // If reservation ID provided, update reservation status
            if ($reservationId) {
                $reservation = InventoryReservation::where('id', $reservationId)
                    ->where('status', 'active')
                    ->first();

                if ($reservation) {
                    $reservation->update([
                        'status' => $reason === 'expired' ? 'expired' : 'released',
                        'released_at' => now()
                    ]);
                }
            }

            // Release the reserved quantity
            $lockedInventory->update([
                'reserved_quantity' => $lockedInventory->reserved_quantity - $quantity
            ]);

            // Create stock movement record
            StockMovement::create([
                'branch_id' => $lockedInventory->branch_id,
                'product_id' => $lockedInventory->product_id,
                'flavor_id' => $lockedInventory->flavor_id,
                'previous_quantity' => $lockedInventory->quantity,
                'new_quantity' => $lockedInventory->quantity,
                'quantity_change' => 0,
                'movement_type' => 'reservation_released',
                'reference_type' => 'reservation',
                'reference_id' => $reservationId,
                'notes' => "Reservation released: {$reason}",
                'created_by' => auth()->id()
            ]);

            return true;
        });
    }

    /**
     * Get the total reserved quantity
     */
    public function getTotalReservedQuantity()
    {
        return $this->activeReservations()
            ->sum('quantity');
    }

    /**
     * Check if this inventory has active reservations
     */
    public function hasActiveReservations()
    {
        return $this->activeReservations()->exists();
    }

    /**
     * Get all active reservations for this inventory
     */
    public function getActiveReservations()
    {
        return $this->activeReservations()
            ->with(['user', 'order', 'stockTransfer'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Add stock to inventory
     */
    public function addStock($quantity, $notes = null)
    {
        return DB::transaction(function () use ($quantity, $notes) {
            $oldQuantity = $this->quantity;
            $newQuantity = $oldQuantity + $quantity;

            $this->update([
                'quantity' => $newQuantity,
                'last_restocked_at' => now(),
            ]);

            StockMovement::create([
                'branch_id' => $this->branch_id,
                'product_id' => $this->product_id,
                'flavor_id' => $this->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => $quantity,
                'movement_type' => 'stock_added',
                'notes' => $notes ?? 'Stock manually added',
                'created_by' => auth()->id()
            ]);

            return true;
        });
    }

    /**
     * Remove stock from inventory
     */
    public function removeStock($quantity, $reason = 'adjustment', $notes = null)
    {
        return DB::transaction(function () use ($quantity, $reason, $notes) {
            if ($this->available_quantity < $quantity) {
                throw new Exception("Insufficient stock. Available: {$this->available_quantity}");
            }

            $oldQuantity = $this->quantity;
            $newQuantity = $oldQuantity - $quantity;

            $this->update([
                'quantity' => $newQuantity,
            ]);

            StockMovement::create([
                'branch_id' => $this->branch_id,
                'product_id' => $this->product_id,
                'flavor_id' => $this->flavor_id,
                'previous_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'quantity_change' => -$quantity,
                'movement_type' => $reason,
                'notes' => $notes,
                'created_by' => auth()->id()
            ]);

            return true;
        });
    }

    /**
     * Check if stock is expiring soon
     */
    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiration_date) {
            return false;
        }

        return $this->expiration_date->between(now(), now()->addDays($days));
    }

    /**
     * Get the value of current stock
     */
    public function getStockValueAttribute()
    {
        return $this->quantity * ($this->product->price ?? 0);
    }

    /**
     * Get the value of available stock
     */
    public function getAvailableStockValueAttribute()
    {
        return $this->available_quantity * ($this->product->price ?? 0);
    }
}