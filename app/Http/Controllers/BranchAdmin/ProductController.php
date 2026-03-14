<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductFlavor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('is_active', true)
            ->with('flavors')
            ->orderBy('name')
            ->paginate(20);
        
        return view('branch-admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('branch-admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'nicotine_strength' => 'nullable|string|max:50',
            'puff_count' => 'nullable|integer',
            'battery_capacity' => 'nullable|integer',
            'charging_type' => 'nullable|string|max:50',
            'liquid_capacity' => 'nullable|numeric',
            'adjustable_airflow' => 'nullable|boolean',
            'smart_display' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'flavors' => 'nullable|array',
            'flavors.*.name' => 'required_with:flavors|string|max:255',
            'flavors.*.code' => 'nullable|string|max:50',
            'flavors.*.category' => 'nullable|string|max:50',
        ]);

        // Handle brand
        $brand = $request->brand;
        if ($brand === 'Other' && $request->filled('custom_brand')) {
            $brand = $request->custom_brand;
        }

        // Handle category
        $category = $request->category;
        if ($category === 'New' && $request->filled('new_category')) {
            $category = $request->new_category;
        }

        // Create product
        $product = Product::create([
            'name' => $request->name,
            'brand' => $brand,
            'description' => $request->description,
            'category' => $category,
            'type' => $request->type,
            'price' => $request->price,
            'cost' => $request->cost,
            'nicotine_strength' => $request->nicotine_strength,
            'puff_count' => $request->puff_count,
            'battery_capacity' => $request->battery_capacity,
            'charging_type' => $request->charging_type,
            'liquid_capacity' => $request->liquid_capacity,
            'adjustable_airflow' => $request->has('adjustable_airflow'),
            'smart_display' => $request->has('smart_display'),
            'is_active' => true,
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

        // Handle flavors and collect flavor IDs
        $flavorIds = [];
        if ($request->has('flavors')) {
            foreach ($request->flavors as $flavorData) {
                if (!empty($flavorData['name'])) {
                    $flavor = ProductFlavor::create([
                        'product_id' => $product->id,
                        'name' => $flavorData['name'],
                        'code' => $flavorData['code'] ?? null,
                        'category' => $flavorData['category'] ?? null,
                        'is_active' => true,
                    ]);
                    $flavorIds[] = $flavor->id;
                }
            }
        }

        // Add to branch inventory
        $branchId = Auth::user()->branch_id;
        
        // If there are flavors, create inventory for each flavor
        if (count($flavorIds) > 0) {
            foreach ($flavorIds as $flavorId) {
                \App\Models\BranchInventory::create([
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                    'flavor_id' => $flavorId, // This sets the flavor_id!
                    'quantity' => $request->stock_quantity,
                    'reserved_quantity' => 0,
                    'low_stock_threshold' => $request->low_stock_threshold ?? 10,
                    'reorder_point' => 10,
                    'optimal_stock' => 30,
                    'last_restocked_at' => now(),
                ]);
            }
        } else {
            // No flavors, create inventory without flavor_id
            \App\Models\BranchInventory::create([
                'branch_id' => $branchId,
                'product_id' => $product->id,
                'flavor_id' => null,
                'quantity' => $request->stock_quantity,
                'reserved_quantity' => 0,
                'low_stock_threshold' => $request->low_stock_threshold ?? 10,
                'reorder_point' => 10,
                'optimal_stock' => 30,
                'last_restocked_at' => now(),
            ]);
        }

        return redirect()->route('branch-admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('branch-admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('branch-admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Similar validation for update
        // ... (you can add update logic later)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if product is in any inventory
        $inventoryCount = \App\Models\BranchInventory::where('product_id', $product->id)->count();
        
        if ($inventoryCount > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete product that exists in inventory.');
        }

        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('branch-admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}