<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'driver_id',
        'driver_shift_id',  // ADDED - link to driver shift
        'tracking_number',
        'status',
        'delivery_address',
        'recipient_name',
        'recipient_phone',
        'latitude',
        'longitude',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'delivery_proof',
        'payment_proof',
        'notes',
        'driver_notes',
        'driver_latitude',
        'driver_longitude',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'driver_latitude' => 'decimal:8',
        'driver_longitude' => 'decimal:8',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the driver shift that this delivery belongs to
     */
    public function driverShift()
    {
        return $this->belongsTo(DriverShift::class, 'driver_shift_id');
    }

    // Helper: get status badge CSS
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-secondary',
            'assigned' => 'bg-info',
            'picked_up' => 'bg-primary',
            'in_transit' => 'bg-warning',
            'delivered' => 'bg-success',
            'failed' => 'bg-danger',
        ];
        return $classes[$this->status] ?? 'bg-secondary';
    }

    // Accessor for delivery proof URL
    public function getDeliveryProofUrlAttribute()
    {
        return $this->delivery_proof ? Storage::url($this->delivery_proof) : null;
    }

    // Accessor for payment proof URL
    public function getPaymentProofUrlAttribute()
    {
        return $this->payment_proof ? Storage::url($this->payment_proof) : null;
    }

    // Helper: check if delivery is completed
    public function isCompleted()
    {
        return $this->status === 'delivered';
    }

    // Helper: check if delivery is in progress
    public function isInProgress()
    {
        return in_array($this->status, ['assigned', 'picked_up', 'in_transit']);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeForDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    public function scopeForShift($query, $shiftId)
    {
        return $query->where('driver_shift_id', $shiftId);
    }
}