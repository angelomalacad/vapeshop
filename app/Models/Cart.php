<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'inventory_id',
        'quantity',
    ];

    public function inventory()
    {
        return $this->belongsTo(BranchInventory::class, 'inventory_id');
    }
}