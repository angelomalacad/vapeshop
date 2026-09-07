<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function showModal(Delivery $delivery)
    {
        $delivery->load(['order.items.product', 'driver']);
        return view('branch-admin.deliveries.show-modal', compact('delivery'));
    }
}