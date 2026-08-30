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
        'payment_proof',
        'delivery_type',
        'delivery_address',
        'delivery_date',
        'customer_name',
        'customer_phone',
        'notes',
        'estimated_delivery_time',
        // NEW fields for online ordering
        'customer_email',
        'city',
        'barangay',
        'other_barangay',
        'landmark',
        'gcash_reference',
        'order_status',      // new status column for online flow
        'admin_notes',
    ];

    protected $casts = [
        'estimated_delivery_time' => 'datetime',
        'order_status' => 'string',
    ];

    // Relationships
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

    // NEW: Delivery relationship (one-to-one)
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    // Helper: get readable order status
    public function getOrderStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'ready' => 'Ready',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'lalamove_pending' => 'bg-dark text-white',
        ];
        return $labels[$this->order_status] ?? ucfirst($this->order_status);
    }

    // Helper: get CSS class for status badge
    public function getOrderStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'processing' => 'bg-primary',
            'ready' => 'bg-success',
            'out_for_delivery' => 'bg-secondary',
            'delivered' => 'bg-dark',
            'cancelled' => 'bg-danger',
            'lalamove_pending' => 'bg-dark text-white',
        ];
        return $classes[$this->order_status] ?? 'bg-secondary';
    }
    public function getDeliveryDateAttribute($value)
{
    return $value ? \Carbon\Carbon::parse($value) : null;
}
    

}
