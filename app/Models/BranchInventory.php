<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class BranchInventory extends Model
{
    use HasFactory;

    protected $table = 'branch_inventories';

    protected $fillable = [
        'branch_id', 'product_id', 'flavor_id', 'quantity', 'reserved_quantity',
        'low_stock_threshold', 'reorder_point', 'optimal_stock',
        'last_purchase_price', 'last_restocked_at', 'expiration_date', 'is_archived'
    ];

    protected $casts = [
        'last_restocked_at' => 'datetime',
        'expiration_date' => 'date',
    ];

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

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }

    public function getIsLowStockAttribute()
    {
        return $this->available_quantity <= $this->low_stock_threshold;
    }

    /**
     * Scope a query to only include low stock items.
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('(quantity - reserved_quantity) <= low_stock_threshold');
    }

    /**
     * Scope a query to only include out of stock items.
     */
    public function scopeOutOfStock($query)
    {
        return $query->whereRaw('(quantity - reserved_quantity) <= 0');
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // ========== NEW STOCK RESERVATION METHODS ==========

    /**
     * Reserve a quantity of stock (for pending orders)
     */
    public function reserve($quantity)
    {
        if ($this->available_quantity < $quantity) {
            throw new Exception("Insufficient stock to reserve {$quantity} units. Available: {$this->available_quantity}");
        }
        $this->increment('reserved_quantity', $quantity);
    }

    /**
     * Confirm reservation and deduct actual stock (when order is confirmed)
     */
    public function confirmReservation($quantity)
    {
        if ($this->reserved_quantity < $quantity) {
            throw new Exception("Cannot confirm reservation: only {$this->reserved_quantity} units reserved.");
        }
        $this->decrement('reserved_quantity', $quantity);
        $this->decrement('quantity', $quantity);
    }

    /**
     * Release reservation without deducting stock (when order is cancelled/rejected)
     */
    public function releaseReservation($quantity)
    {
        if ($this->reserved_quantity < $quantity) {
            throw new Exception("Cannot release reservation: only {$this->reserved_quantity} units reserved.");
        }
        $this->decrement('reserved_quantity', $quantity);
    }
}