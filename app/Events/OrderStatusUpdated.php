<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $userId;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->userId = $order->user_id;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('orders.' . $this->userId),
            new PrivateChannel('branch.orders.' . $this->order->branch_id),
        ];
    }

    public function broadcastAs()
    {
        return 'order.updated';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'message' => 'Your order status has been updated to ' . ucfirst($this->order->status),
            'updated_at' => $this->order->updated_at->format('M d, Y h:i A'),
        ];
    }
}