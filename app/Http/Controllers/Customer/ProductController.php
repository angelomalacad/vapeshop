<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('is_active', true)->get();
        $selectedBranchId = $request->get('branch_id', 'all');
        
        $query = BranchInventory::with(['product', 'flavor'])
            ->where('is_archived', false);
        
        // Apply branch filter - if 'all', show products from all branches
        if ($selectedBranchId !== 'all') {
            $query->where('branch_id', $selectedBranchId);
        }
        
        $inventories = $query->get();
        
        // Group products by name (for flavor variants)
        $groupedProducts = [];
        foreach ($inventories as $inv) {
            $productName = $inv->product->name;
            if (!isset($groupedProducts[$productName])) {
                $groupedProducts[$productName] = collect();
            }
            $groupedProducts[$productName]->push([
                'inventory_id' => $inv->id,
                'product_id' => $inv->product_id,
                'branch_id' => $inv->branch_id,
                'branch_name' => $inv->branch->name,  // ADD THIS
                'flavor' => $inv->flavor->name ?? null,
                'price' => $inv->product->price,
                'available_quantity' => $inv->available_quantity,
                'image' => $inv->product->image ?? null,
            ]);
        }
        
        // DEBUG: Add this AFTER $groupedProducts is defined (remove after testing)
        \Log::info('Grouped Products:', ['count' => count($groupedProducts)]);
        
        // Get best sellers - simpler approach without GROUP BY issues
        $bestSellerIds = DB::select("
            SELECT oi.product_id, SUM(oi.quantity) as total_sold
            FROM order_items oi
            INNER JOIN orders o ON oi.order_id = o.id
            WHERE o.order_status = 'delivered'
            GROUP BY oi.product_id
            ORDER BY total_sold DESC
            LIMIT 8
        ");
        
        $bestSellerIdArray = array_column($bestSellerIds, 'product_id');
        
        $bestSellers = collect();
        if (!empty($bestSellerIdArray)) {
            $bestSellers = Product::whereIn('id', $bestSellerIdArray)->get();
            // Sort to maintain the order from the query
            $bestSellers = $bestSellers->sortBy(function($product) use ($bestSellerIdArray) {
                $key = array_search($product->id, $bestSellerIdArray);
                return $key !== false ? $key : PHP_INT_MAX;
            });
        }
        
        return view('customer.products.index', compact('branches', 'selectedBranchId', 'groupedProducts', 'bestSellers'));
    }
    
    public function byBranch(Request $request, Branch $branch)
    {
        return redirect()->route('customer.products.index', ['branch_id' => $branch->id]);
    }
}