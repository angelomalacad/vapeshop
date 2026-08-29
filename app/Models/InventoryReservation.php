<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryReservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_inventory_id',
        'order_id',
        'stock_transfer_id',
        'user_id',
        'quantity',
        'reservation_type',
        'status',
        'expires_at',
        'released_at',
        'converted_at',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'released_at' => 'datetime',
        'converted_at' => 'datetime',
        'quantity' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
        'released_at',
        'converted_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the branch inventory that owns the reservation.
     */
    public function branchInventory()
    {
        return $this->belongsTo(BranchInventory::class);
    }

    /**
     * Get the order associated with the reservation.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the stock transfer associated with the reservation.
     */
    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    /**
     * Get the user who made the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active reservations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include expired reservations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '<', now());
    }

    /**
     * Scope a query to only include reservations of a specific type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('reservation_type', $type);
    }

    /**
     * Scope a query to only include reservations that are not expired.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    /**
     * Check if the reservation is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->status === 'active' && $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if the reservation is still active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if the reservation has been converted to a sale.
     *
     * @return bool
     */
    public function isConverted()
    {
        return $this->status === 'converted';
    }

    /**
     * Get the time remaining before expiration.
     *
     * @return \Carbon\CarbonInterval|null
     */
    public function timeRemaining()
    {
        if (!$this->expires_at || $this->status !== 'active') {
            return null;
        }
        
        return now()->diff($this->expires_at);
    }

    /**
     * Get the status badge color.
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'success',
            'converted' => 'primary',
            'released' => 'warning',
            'expired' => 'danger',
            'cancelled' => 'secondary',
            default => 'gray',
        };
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get the reservation type label.
     *
     * @return string
     */
    public function getReservationTypeLabelAttribute()
    {
        return match($this->reservation_type) {
            'online_order' => 'Online Order',
            'stock_transfer' => 'Stock Transfer',
            'pickup' => 'Pickup',
            default => ucfirst($this->reservation_type),
        };
    }

    /**
     * Get a human-readable expiration time.
     *
     * @return string
     */
    public function getExpiresAtDisplayAttribute()
    {
        if (!$this->expires_at) {
            return 'Never';
        }
        
        return $this->expires_at->diffForHumans();
    }

    /**
     * Relationship: Get the product through the branch inventory.
     */
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            BranchInventory::class,
            'id', // Foreign key on branch_inventories table
            'id', // Foreign key on products table
            'branch_inventory_id', // Local key on inventory_reservations table
            'product_id' // Local key on branch_inventories table
        );
    }

    /**
     * Relationship: Get the flavor through the branch inventory.
     */
    public function flavor()
    {
        return $this->hasOneThrough(
            ProductFlavor::class,
            BranchInventory::class,
            'id', // Foreign key on branch_inventories table
            'id', // Foreign key on product_flavors table
            'branch_inventory_id', // Local key on inventory_reservations table
            'flavor_id' // Local key on branch_inventories table
        );
    }
}