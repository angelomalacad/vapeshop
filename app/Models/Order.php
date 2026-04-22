<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_number',
        'user_id',
        'branch_id',
        'subtotal',
        'tax',
        'delivery_fee',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'delivery_type',
        'delivery_address',
        'customer_name',
        'customer_phone',
        'notes',
        'estimated_delivery_time'
    ];
    
    protected $casts = [
        'estimated_delivery_time' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}