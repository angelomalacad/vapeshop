<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Check stock availability in a specific branch
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:products,id',
            'flavor_id' => 'nullable|exists:product_flavors,id',
        ]);

        $query = BranchInventory::where('branch_id', $request->branch_id)
            ->where('product_id', $request->product_id);

        if ($request->filled('flavor_id')) {
            $query->where('flavor_id', $request->flavor_id);
        }

        $inventory = $query->first();

        return response()->json([
            'success' => true,
            'available' => $inventory ? $inventory->available_quantity : 0,
            'message' => $inventory ? 'Stock available' : 'No stock found'
        ]);
    }

    /**
     * Check if a product/flavor already exists in the current branch inventory
     */
    public function check(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'flavor_id' => 'nullable|exists:product_flavors,id',
            ]);

            $branchId = Auth::user()->branch_id;
            
            $query = BranchInventory::where('branch_id', $branchId)
                ->where('product_id', $request->product_id);
            
            if ($request->filled('flavor_id')) {
                $query->where('flavor_id', $request->flavor_id);
            }
            
            $inventory = $query->first();
            
            return response()->json([
                'exists' => $inventory ? true : false,
                'quantity' => $inventory ? $inventory->quantity : 0,
                'available' => $inventory ? $inventory->available_quantity : 0,
                'threshold' => $inventory ? $inventory->low_stock_threshold : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'exists' => false,
                'quantity' => 0,
                'available' => 0,
            ], 500);
        }
    }
}