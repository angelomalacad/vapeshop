<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'product_id', 'flavor_id', 'previous_quantity',
        'new_quantity', 'quantity_change', 'movement_type',
        'reference_type', 'reference_id', 'notes', 'created_by'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function flavor()
    {
        return $this->belongsTo(ProductFlavor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}