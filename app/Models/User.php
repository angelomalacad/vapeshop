<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'branch_id',
        'phone', 'address', 'receive_notifications', 'fcm_token'
    ];

    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'driver_id');
    }

    // Role checks
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isBranchAdmin()
    {
        return $this->role === 'branch_admin';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function scopeBranchUsers($query, $branchId = null)
    {
        return $query->where('branch_id', $branchId ?? $this->branch_id)
                    ->whereIn('role', ['branch_admin', 'staff']);
    }
}