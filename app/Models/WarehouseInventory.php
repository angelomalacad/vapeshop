<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseInventory extends Model
{
    use HasFactory;

    protected $table = 'warehouse_inventories';

    protected $fillable = [
        'product_id',
        'flavor_id',
        'quantity',
        'low_stock_threshold',
        'reorder_point',
        'last_purchase_price',
        'last_restocked_at',
        'expiration_date'
    ];

    protected $casts = [
        'last_restocked_at' => 'datetime',
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'reorder_point' => 'integer',
        'last_purchase_price' => 'decimal:2',
        'expiration_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flavor()
    {
        return $this->belongsTo(ProductFlavor::class, 'flavor_id');
    }

    // ✅ ADD THIS
    public function getAvailableQuantityAttribute()
    {
        return $this->quantity;
    }

    public function isLowStock()
    {
        return $this->quantity <= $this->low_stock_threshold;
    }

    public function isOutOfStock()
    {
        return $this->quantity <= 0;
    }

    public function getTotalValueAttribute()
    {
        return $this->quantity * ($this->last_purchase_price ?? 0);
    }
}