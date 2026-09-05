<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_number',
        'transfer_type',
        'from_branch_id',
        'to_branch_id',
        'product_id',
        'flavor_id',
        'quantity',
        'status',
        'requested_by',
        'approved_by',
        'approved_at',
        'completed_at',
        'received_by',
        'received_at',
        'notes',
        'rejection_reason',
        'rejected_by',
        'rejected_at',
        'cancelled_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transfer) {
            $transfer->transfer_number = 'TRF-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        });
    }

    public function fromBranch()
    {
        return $this->belongsTo(Branch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branch::class, 'to_branch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flavor()
    {
        return $this->belongsTo(ProductFlavor::class);
    }

    // Fix: Change from 'requester' to 'requestedBy' to match the query
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Optional: Add an alias if you want to keep using 'requester'
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // ADD THIS RELATIONSHIP - This is the missing one!
    public function reservations()
    {
        return $this->hasMany(InventoryReservation::class);
    }

    // Add receivedBy relationship too (you have received_by in fillable)
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    // Scopes for filtering
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    public function rejectedBy()
{
    return $this->belongsTo(User::class, 'rejected_by');
}
public function cancelledBy()
{
    return $this->belongsTo(User::class, 'cancelled_by');
}
}
