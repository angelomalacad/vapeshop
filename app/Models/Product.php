<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'description', 'brand', 'category', 'type',
        'price', 'cost', 'puff_count', 'battery_capacity', 'charging_type',
        'liquid_capacity', 'nicotine_strength', 'adjustable_airflow',
        'smart_display', 'image', 'images', 'image_url', 'gdrive_file_id', 'is_active'
    ];

    protected $casts = [
        'images' => 'array',
        'adjustable_airflow' => 'boolean',
        'smart_display' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function flavors()
    {
        return $this->hasMany(ProductFlavor::class);
    }

    public function branchInventories()
    {
        return $this->hasMany(BranchInventory::class);
    }
}