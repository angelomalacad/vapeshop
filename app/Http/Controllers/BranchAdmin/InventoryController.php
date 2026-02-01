<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;
        
        $inventory = Inventory::where('branch_id', $branchId)
            ->with('product')
            ->orderBy('quantity', 'asc')
            ->paginate(20);
            
        return view('branch-admin.inventory.index', compact('inventory'));
    }

    public function create()
    {
        $branchId = Auth::user()->branch_id;
        
        $existingProductIds = Inventory::where('branch_id', $branchId)
            ->pluck('product_id')
            ->toArray();
            
        $products = Product::where('is_active', true)
            ->whereNotIn('id', $existingProductIds)
            ->get();
            
        return view('branch-admin.inventory.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'optimal_stock_level' => 'required|integer|min:1',
        ]);
        
        $branchId = Auth::user()->branch_id;
        
        Inventory::create([
            'branch_id' => $branchId,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'low_stock_threshold' => $request->low_stock_threshold,
            'optimal_stock_level' => $request->optimal_stock_level,
        ]);
        
        return redirect()->route('branch-admin.inventory.index')
            ->with('success', 'Product added to inventory successfully.');
    }
}