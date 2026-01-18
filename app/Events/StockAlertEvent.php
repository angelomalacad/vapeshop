<?php

namespace App\Events;

use App\Models\StockAlert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockAlertEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alert;
    public $branchId;

    public function __construct(StockAlert $alert)
    {
        $this->alert = $alert;
        $this->branchId = $alert->branch_id;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('stock-alerts.' . $this->branchId),
            new PrivateChannel('admin.stock-alerts'),
        ];
    }

    public function broadcastAs()
    {
        return 'stock.alert';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->alert->id,
            'product_name' => $this->alert->inventory->product->name,
            'branch_name' => $this->alert->branch->name,
            'alert_type' => $this->alert->alert_type,
            'current_quantity' => $this->alert->current_quantity,
            'threshold' => $this->alert->threshold_quantity,
            'created_at' => $this->alert->created_at->diffForHumans(),
        ];
    }
}