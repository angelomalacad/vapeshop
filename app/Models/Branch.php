<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'address', 'phone', 'email',
        'manager_name', 'latitude', 'longitude', 'is_active'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeProducts()
    {
        return $this->belongsToMany(Product::class, 'inventories')
                    ->wherePivot('quantity', '>', 0)
                    ->withPivot('quantity', 'low_stock_threshold')
                    ->withTimestamps();
    }

    public function getLowStockItemsAttribute()
    {
        return $this->inventories()
                    ->whereColumn('quantity', '<=', 'low_stock_threshold')
                    ->with('product')
                    ->get();
    }
}