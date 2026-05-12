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
        'quantity',
        'low_stock_threshold',
        'reorder_point',
        'last_purchase_price',
        'last_restocked_at'
    ];

    protected $casts = [
        'last_restocked_at' => 'datetime',
        'quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'reorder_point' => 'integer',
        'last_purchase_price' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
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
        return $this->quantity * ($this->product->price ?? 0);
    }
}