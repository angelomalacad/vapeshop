<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFlavor extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'name', 'code', 'description', 'category', 'is_active', 'expiration_date',
        'shelf_life_months'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expiration_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function branchInventories()
    {
        return $this->hasMany(BranchInventory::class);
    }
}