<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id', 'product_id', 'quantity', 'low_stock_threshold',
        'optimal_stock_level', 'reserved_quantity', 'last_purchase_price',
        'last_restocked_at'
    ];

    protected $casts = [
        'last_restocked_at' => 'datetime'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockAlerts()
    {
        return $this->hasMany(StockAlert::class);
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }

    public function isLowStock()
    {
        return $this->available_quantity <= $this->low_stock_threshold;
    }

    public function isOutOfStock()
    {
        return $this->available_quantity <= 0;
    }

    public function updateStock($quantity, $type = 'sale', $notes = null)
    {
        $oldQuantity = $this->quantity;
        
        if ($type === 'sale' || $type === 'out') {
            $this->decrement('quantity', $quantity);
        } elseif ($type === 'purchase' || $type === 'in') {
            $this->increment('quantity', $quantity);
            $this->last_restocked_at = now();
        } elseif ($type === 'adjustment') {
            $this->quantity = $quantity;
        }

        $this->save();

        // Create stock movement record
        StockMovement::create([
            'inventory_id' => $this->id,
            'branch_id' => $this->branch_id,
            'product_id' => $this->product_id,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $this->quantity,
            'quantity_change' => $this->quantity - $oldQuantity,
            'movement_type' => $type,
            'notes' => $notes,
        ]);

        // Check for stock alerts
        $this->checkStockAlerts();
    }

    private function checkStockAlerts()
    {
        if ($this->isOutOfStock()) {
            $this->createAlert('out_of_stock');
        } elseif ($this->isLowStock()) {
            $this->createAlert('low_stock');
        }
    }

    private function createAlert($type)
    {
        StockAlert::create([
            'inventory_id' => $this->id,
            'branch_id' => $this->branch_id,
            'alert_type' => $type,
            'current_quantity' => $this->available_quantity,
            'threshold_quantity' => $type === 'out_of_stock' ? 0 : $this->low_stock_threshold,
        ]);
    }
}