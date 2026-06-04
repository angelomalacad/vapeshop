<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\StockMovement;
use App\Models\WarehouseInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\GoogleDriveHelper;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['flavors']);
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $products = $query->orderBy('name')->paginate(15);
        
        $brands = Product::distinct()->pluck('brand');
        $categories = Product::distinct()->pluck('category');
        $types = Product::distinct()->pluck('type');
        
        return view('admin.products.index', compact('products', 'brands', 'categories', 'types'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

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

        if ($request->filled('image_url')) {
            $product->update([
                'image_url' => $request->image_url,
                'gdrive_file_id' => GoogleDriveHelper::extractFileId($request->image_url)
            ]);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

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

    public function show(Product $product)
    {
        $product->load(['flavors']);
        $branchInventories = BranchInventory::with('branch')
            ->where('product_id', $product->id)
            ->get();
        $totalStock = $branchInventories->sum('quantity');
        $branchesWithStock = $branchInventories->count();
        return view('admin.products.show', compact('product', 'branchInventories', 'totalStock', 'branchesWithStock'));
    }

    public function edit(Product $product)
    {
        $product->load('flavors');
        return view('admin.products.edit', compact('product'));
    }

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

        $brand = $request->brand;
        if ($brand === 'Other' && $request->filled('custom_brand')) {
            $brand = $request->custom_brand;
        }

        $category = $request->category;
        if ($category === 'New' && $request->filled('new_category')) {
            $category = $request->new_category;
        }

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

    public function destroy(Product $product)
    {
        $inventoryCount = BranchInventory::where('product_id', $product->id)->count();
        if ($inventoryCount > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete product that exists in inventory.');
        }
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->flavors()->delete();
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return redirect()->back()
            ->with('success', 'Product status updated successfully.');
    }

    // ========== ADD STOCK METHODS ==========

    /**
     * Show modal form to add stock to branch inventory (GET)
     */
    public function addStockToBranchForm(Product $product)
    {
        $branches = Branch::where('is_active', true)->get();
        return view('admin.products.modals.add-stock', compact('product', 'branches'));
    }

    /**
     * Process adding stock to branch inventory (POST) – returns JSON for AJAX
     */
    public function addStockToBranch(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'flavor_id' => 'nullable|exists:product_flavors,id',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if adding to Main Warehouse (direct addition)
        if ($request->branch_id == 'warehouse') {
            return $this->addToWarehouse($request, $product);
        }

        // Adding to branch - must check warehouse inventory first
        return $this->transferToBranch($request, $product);
    }

    /**
     * Add stock directly to warehouse inventory
     */
    private function addToWarehouse(Request $request, Product $product)
    {
        // Find or create warehouse inventory
        $inventory = WarehouseInventory::firstOrNew([
            'product_id' => $product->id,
            'flavor_id' => $request->flavor_id,
        ]);

        if (!$inventory->exists) {
            $inventory->quantity = 0;
            $inventory->low_stock_threshold = 10;
            $inventory->reorder_point = 20;
        }

        $oldQuantity = $inventory->quantity ?? 0;
        $newQuantity = $oldQuantity + $request->quantity;

        $inventory->quantity = $newQuantity;
        $inventory->last_restocked_at = now();
        
        if ($request->filled('purchase_price')) {
            $inventory->last_purchase_price = $request->purchase_price;
        }
        if ($request->filled('expiration_date')) {
            $inventory->expiration_date = $request->expiration_date;
        }
        
        $inventory->save();

        // Record stock movement
        StockMovement::create([
            'branch_id' => null,
            'product_id' => $product->id,
            'flavor_id' => $request->flavor_id,
            'previous_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'quantity_change' => $request->quantity,
            'movement_type' => 'purchase',
            'reference_type' => 'warehouse',
            'reference_id' => $inventory->id,
            'notes' => $request->notes ?: 'Added to warehouse via product management',
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Successfully added {$request->quantity} units to Main Warehouse."
        ]);
    }

    /**
     * Transfer stock from warehouse to branch
     */
    private function transferToBranch(Request $request, Product $product)
    {
        // Validate branch exists
        $branch = Branch::find($request->branch_id);
        if (!$branch) {
            return response()->json([
                'success' => false,
                'errors' => ['branch_id' => ['Selected branch does not exist.']]
            ], 422);
        }

        // Check if product exists in warehouse inventory
        $warehouseInventory = WarehouseInventory::where('product_id', $product->id)
            ->where('flavor_id', $request->flavor_id)
            ->first();

        if (!$warehouseInventory) {
            return response()->json([
                'success' => false,
                'errors' => ['quantity' => ["This product is not available in Main Warehouse. Please add stock to warehouse first."]]
            ], 422);
        }

        // Check if enough stock in warehouse
        if ($warehouseInventory->quantity < $request->quantity) {
            $available = $warehouseInventory->quantity;
            return response()->json([
                'success' => false,
                'errors' => ['quantity' => ["Insufficient warehouse stock. Available: {$available} units. Please add more stock to warehouse first."]]
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Deduct from warehouse
            $oldWarehouseQty = $warehouseInventory->quantity;
            $newWarehouseQty = $oldWarehouseQty - $request->quantity;
            $warehouseInventory->quantity = $newWarehouseQty;
            $warehouseInventory->save();

            // Record warehouse stock movement (outgoing)
            StockMovement::create([
                'branch_id' => null,
                'product_id' => $product->id,
                'flavor_id' => $request->flavor_id,
                'previous_quantity' => $oldWarehouseQty,
                'new_quantity' => $newWarehouseQty,
                'quantity_change' => -$request->quantity,
                'movement_type' => 'transfer_out',
                'reference_type' => 'warehouse_transfer',
                'reference_id' => $warehouseInventory->id,
                'notes' => $request->notes ?: "Transferred to {$branch->name} via product management",
                'created_by' => Auth::id(),
            ]);

            // Find or create branch inventory
            $branchInventory = BranchInventory::firstOrNew([
                'branch_id' => $request->branch_id,
                'product_id' => $product->id,
                'flavor_id' => $request->flavor_id,
            ]);

            if (!$branchInventory->exists) {
                $branchInventory->quantity = 0;
                $branchInventory->reserved_quantity = 0;
                $branchInventory->low_stock_threshold = 10;
                $branchInventory->reorder_point = 20;
                $branchInventory->optimal_stock = 50;
            }

            $oldBranchQty = $branchInventory->quantity;
            $newBranchQty = $oldBranchQty + $request->quantity;

            $branchInventory->quantity = $newBranchQty;
            $branchInventory->last_restocked_at = now();
            
            if ($request->filled('expiration_date')) {
                $branchInventory->expiration_date = $request->expiration_date;
            }
            
            $branchInventory->save();

            // Record branch stock movement (incoming)
            StockMovement::create([
                'branch_id' => $request->branch_id,
                'product_id' => $product->id,
                'flavor_id' => $request->flavor_id,
                'previous_quantity' => $oldBranchQty,
                'new_quantity' => $newBranchQty,
                'quantity_change' => $request->quantity,
                'movement_type' => 'transfer_in',
                'reference_type' => 'branch_transfer',
                'reference_id' => $branchInventory->id,
                'notes' => $request->notes ?: "Received from warehouse via product management",
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully transferred {$request->quantity} units from Main Warehouse to {$branch->name}."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Error processing transfer: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Return edit form as modal content (no layout).
     */
    public function editModal(Product $product)
    {
        $product->load('flavors');
        return view('admin.products.modals.edit', compact('product'));
    }

    /**
     * Return product details as modal content (no layout).
     */
    public function showModal(Product $product)
    {
        $product->load(['flavors']);
        $branchInventories = BranchInventory::with('branch')
            ->where('product_id', $product->id)
            ->get();
        return view('admin.products.modals.show', compact('product', 'branchInventories'));
    }
}