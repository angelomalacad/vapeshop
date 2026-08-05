<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'inventory_id',  // ADD THIS
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];
    public function inventory()
    {
        return $this->belongsTo(BranchInventory::class, 'inventory_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}