<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Update the Calamba delivery fee.
     */
    public function updateDeliveryFee(Request $request)
    {
        $request->validate([
            'calamba_delivery_fee' => 'required|numeric|min:0|max:9999',
        ]);

        Setting::set('calamba_delivery_fee', $request->input('calamba_delivery_fee'));

        return response()->json([
            'success' => true,
            'message' => 'Delivery fee updated to ₱' . number_format((float) $request->input('calamba_delivery_fee'), 2) . '.',
        ]);
    }
}
