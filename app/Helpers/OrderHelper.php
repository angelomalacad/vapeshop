<?php

namespace App\Helpers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderHelper
{
    /**
     * Get the count of processing orders for the logged-in user.
     * Only counts orders with statuses that are still "in progress" —
     * excludes cancelled, delivered, and rejected orders.
     */
    public static function getProcessingCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        return Order::where('user_id', Auth::id())
            ->whereIn('order_status', [
                'pending',
                'confirmed',
                'processing',
                'ready',
                'out_for_delivery',
            ])
            ->count();
    }
}