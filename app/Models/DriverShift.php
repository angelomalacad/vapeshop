<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'shift_date',
        'status',
        'start_time',
        'end_time',
        'notes',
        'assigned_by',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->shift_date->isToday();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('shift_date', today());
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('shift_date', $date);
    }
}