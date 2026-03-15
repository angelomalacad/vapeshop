<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'code', 
        'address', 
        'phone', 
        'contact_number',
        'email',
        'manager_name', 
        'opening_date',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'opening_date' => 'date',
    ];

    /**
     * Get the users for this branch.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the inventories for this branch.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the stock movements for this branch.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the orders for this branch.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the transfers from this branch.
     */
    public function transfersFrom()
    {
        return $this->hasMany(StockTransfer::class, 'from_branch_id');
    }

    /**
     * Get the transfers to this branch.
     */
    public function transfersTo()
    {
        return $this->hasMany(StockTransfer::class, 'to_branch_id');
    }

    /**
     * Get active products for this branch.
     */
    public function activeProducts()
    {
        return $this->belongsToMany(Product::class, 'inventories')
                    ->wherePivot('quantity', '>', 0)
                    ->withPivot('quantity', 'low_stock_threshold')
                    ->withTimestamps();
    }

    /**
     * Get low stock items for this branch.
     */
    public function getLowStockItemsAttribute()
    {
        return $this->inventories()
                    ->whereColumn('quantity', '<=', 'low_stock_threshold')
                    ->with('product')
                    ->get();
    }
}