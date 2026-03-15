<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\BranchInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\GoogleDriveHelper;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['flavors']);
        
        // Search by name or SKU
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by active status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $products = $query->orderBy('name')->paginate(15);
        
        // Get unique brands and categories for filters
        $brands = Product::distinct()->pluck('brand');
        $categories = Product::distinct()->pluck('category');
        $types = Product::distinct()->pluck('type');
        
        return view('admin.products.index', compact('products', 'brands', 'categories', 'types'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product.
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
            'nicotine_strength' => 'nullable|string|max:50',
            'puff_count' => 'nullable|integer',
            'battery_capacity' => 'nullable|integer',
            'charging_type' => 'nullable|string|max:50',
            'liquid_capacity' => 'nullable|numeric',
            'adjustable_airflow' => 'nullable|boolean',
            'smart_display' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url|max:500',
            'flavors' => 'nullable|array',
            'flavors.*.name' => 'required_with:flavors|string|max:255',
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

        // Handle Google Drive image
        if ($request->filled('image_url')) {
            $product->update([
                'image_url' => $request->image_url,
                'gdrive_file_id' => GoogleDriveHelper::extractFileId($request->image_url)
            ]);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

        // Handle flavors
        if ($request->has('flavors')) {
            foreach ($request->flavors as $flavorData) {
                if (!empty($flavorData['name'])) {
                    $product->flavors()->create([
                        'name' => $flavorData['name'],
                        'code' => $flavorData['code'] ?? null,
                        'category' => $flavorData['category'] ?? null,
                        'is_active' => true,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['flavors']);
        
        // Get inventory across all branches
        $branchInventories = BranchInventory::with('branch')
            ->where('product_id', $product->id)
            ->get();
        
        $totalStock = $branchInventories->sum('quantity');
        $branchesWithStock = $branchInventories->count();
        
        return view('admin.products.show', compact('product', 'branchInventories', 'totalStock', 'branchesWithStock'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $product->load('flavors');
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'nicotine_strength' => 'nullable|string|max:50',
            'puff_count' => 'nullable|integer',
            'battery_capacity' => 'nullable|integer',
            'charging_type' => 'nullable|string|max:50',
            'liquid_capacity' => 'nullable|numeric',
            'adjustable_airflow' => 'nullable|boolean',
            'smart_display' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url|max:500',
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

        // Update product
        $product->update([
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
        ]);

        // Handle Google Drive image
        if ($request->filled('image_url')) {
            $product->update([
                'image_url' => $request->image_url,
                'gdrive_file_id' => GoogleDriveHelper::extractFileId($request->image_url)
            ]);
        } elseif ($request->has('remove_image')) {
            $product->update([
                'image_url' => null,
                'gdrive_file_id' => null
            ]);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        // Check if product is in any inventory
        $inventoryCount = BranchInventory::where('product_id', $product->id)->count();
        
        if ($inventoryCount > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete product that exists in inventory.');
        }

        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete flavors
        $product->flavors()->delete();
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product active status.
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        return redirect()->back()
            ->with('success', 'Product status updated successfully.');
    }
}