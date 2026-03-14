<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get flavors for a specific product
     */
    public function flavors($productId)
    {
        $product = Product::with('flavors')->findOrFail($productId);
        
        return response()->json($product->flavors);
    }
}