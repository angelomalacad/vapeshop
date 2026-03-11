<?php
// app/Notifications/LowStockNotification.php

namespace App\Notifications;

use App\Models\BranchInventory;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $inventory;

    public function __construct(BranchInventory $inventory)
    {
        $this->inventory = $inventory;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        $product = $this->inventory->product->name;
        $flavor = $this->inventory->flavor ? ' - ' . $this->inventory->flavor->name : '';
        $branch = $this->inventory->branch->name;
        
        return (new MailMessage)
            ->subject('⚠️ Low Stock Alert - ' . $product . $flavor)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The following item is running low on stock:')
            ->line('**Product:** ' . $product . $flavor)
            ->line('**Branch:** ' . $branch)
            ->line('**Current Stock:** ' . $this->inventory->available_quantity)
            ->line('**Threshold:** ' . $this->inventory->low_stock_threshold)
            ->action('View Inventory', url('/branch-admin/inventory'))
            ->line('Please reorder or transfer stock as needed.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'inventory_id' => $this->inventory->id,
            'branch_id' => $this->inventory->branch_id,
            'product_id' => $this->inventory->product_id,
            'flavor_id' => $this->inventory->flavor_id,
            'product_name' => $this->inventory->product->name,
            'flavor_name' => $this->inventory->flavor->name ?? null,
            'branch_name' => $this->inventory->branch->name,
            'current_stock' => $this->inventory->available_quantity,
            'threshold' => $this->inventory->low_stock_threshold,
            'type' => 'low_stock',
            'message' => 'Low stock: ' . $this->inventory->product->name 
                . ($this->inventory->flavor ? ' - ' . $this->inventory->flavor->name : '')
                . ' at ' . $this->inventory->branch->name
                . ' (' . $this->inventory->available_quantity . ' left)',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'inventory_id' => $this->inventory->id,
            'message' => 'Low stock: ' . $this->inventory->product->name 
                . ($this->inventory->flavor ? ' - ' . $this->inventory->flavor->name : '')
                . ' at ' . $this->inventory->branch->name,
            'current_stock' => $this->inventory->available_quantity,
        ]);
    }
}