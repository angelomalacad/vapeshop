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
                'branch_name' => $inv->branch->name,
                'flavor' => $inv->flavor->name ?? null,
                'price' => $inv->product->price,
                'available_quantity' => $inv->available_quantity,
                'image' => $inv->product->image ?? null,
            ]);
        }

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

    /**
     * Get product variants for AJAX request
     * This is used by the modal to load product variants dynamically
     */
    public function getVariants($productId)
    {
        try {
            $product = Product::findOrFail($productId);

            // Get all inventory items for this product across branches
            $variants = BranchInventory::with(['branch', 'flavor', 'product'])
                ->where('product_id', $productId)
                ->where('quantity', '>', 0) // Only show items with stock
                ->get()
                ->map(function($inventory) {
                    return [
                        'inventory_id' => $inventory->id,
                        'product_id' => $inventory->product_id,
                        'branch_id' => $inventory->branch_id,
                        'branch_name' => $inventory->branch->name ?? 'Unknown Branch',
                        'flavor' => $inventory->flavor->name ?? null,
                        'price' => $inventory->product->price,
                        'available_quantity' => $inventory->available_quantity,
                    ];
                });

            return response()->json([
                'success' => true,
                'variants' => $variants,
                'product_name' => $product->name
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching product variants: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load product variants',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
