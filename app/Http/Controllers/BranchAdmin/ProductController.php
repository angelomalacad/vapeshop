<?php

namespace App\Http\Controllers\BranchAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->with('category')
            ->orderBy('name')
            ->paginate(20);
            
        return view('branch-admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('branch-admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'type' => 'required|in:disposable,pod,mod,liquid,coil,accessory',
        ]);
        
        $product = Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'type' => $request->type,
            'is_active' => true,
        ]);
        
        // Add to branch inventory
        $branchId = Auth::user()->branch_id;
        
        \App\Models\Inventory::create([
            'branch_id' => $branchId,
            'product_id' => $product->id,
            'quantity' => $request->stock_quantity,
            'low_stock_threshold' => 5,
            'optimal_stock_level' => 20,
        ]);
        
        return redirect()->route('branch-admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('branch-admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('branch-admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:disposable,pod,mod,liquid,coil,accessory',
        ]);
        
        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'type' => $request->type,
        ]);
        
        return redirect()->route('branch-admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => false]);
        
        return redirect()->route('branch-admin.products.index')
            ->with('success', 'Product deactivated successfully.');
    }
    
    public function uploadImage(Request $request)
    {
        // This will be implemented later with your ImageProcessor
        return response()->json(['message' => 'Image upload not implemented yet']);
    }
}