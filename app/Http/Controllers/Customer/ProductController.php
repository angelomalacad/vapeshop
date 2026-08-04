<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('is_active', true)->get();
        $selectedBranchId = $request->get('branch_id', 'all');

        $query = BranchInventory::with(['product', 'flavor'])->where('is_archived', false);

        if ($selectedBranchId !== 'all') {
            $query->where('branch_id', $selectedBranchId);
        }

        $inventories = $query->get();

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
                'category' => $inv->product->category,
            ]);
        }

        // Best Sellers
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
            $bestSellers = $bestSellers->sortBy(function($product) use ($bestSellerIdArray) {
                $key = array_search($product->id, $bestSellerIdArray);
                return $key !== false ? $key : PHP_INT_MAX;
            });
        }

        return view('customer.products.index', compact('branches', 'selectedBranchId', 'groupedProducts', 'bestSellers'));
    }

    public function getVariants($productId)
    {
        try {
            $product = Product::findOrFail($productId);

            // 1. Get the user's assigned branch
            $userBranchId = null;
            if (Auth::check() && Auth::user()->barangay) {
                $servingBranch = DB::table('branch_barangay')
                    ->where('barangay_name', Auth::user()->barangay)->first();
                if ($servingBranch) {
                    $userBranchId = $servingBranch->branch_id;
                }
            }

            // 2. Get ALL inventory items with stock across ALL branches
            $allVariants = BranchInventory::with(['branch', 'flavor'])
                ->where('product_id', $productId)
                ->get();

            // 3. Define Proximity Chain based on your geography
            $proximityMap = [
                1 => [2, 5, 4, 3], // Asia 1 (Main) -> MCDC -> Majada Out -> V2 -> Paciano
                2 => [1, 5, 4, 3], // MCDC -> Asia 1 -> Majada Out -> V2 -> Paciano
                3 => [4, 5, 2, 1], // Paciano -> V2 -> Majada Out -> MCDC -> Asia 1
                4 => [3, 5, 2, 1], // V2 -> Paciano -> Majada Out -> MCDC -> Asia 1
                5 => [2, 1, 4, 3], // Majada Out -> MCDC -> Asia 1 -> V2 -> Paciano
            ];

            // 4. Deduplicate by Flavor Name AND Apply Proximity Logic
            $finalVariants = [];
            foreach ($allVariants as $inventory) {
                $flavorName = $inventory->flavor->name ?? 'Standard';
                
                // If we haven't added this flavor yet, add it
                if (!isset($finalVariants[$flavorName])) {
                    $finalVariants[$flavorName] = [
                        'inventory_id' => $inventory->id,
                        'product_id' => $inventory->product_id,
                        'branch_id' => $inventory->branch_id,
                        'branch_name' => $inventory->branch->name,
                        'flavor' => $flavorName,
                        'price' => $inventory->product->price,
                        'available_quantity' => $inventory->available_quantity,
                    ];
                } else {
                    // We already have this flavor. Check if the NEW one is a closer backup.
                    $current = $finalVariants[$flavorName];
                    $currentBranchId = $current['branch_id'];
                    $newBranchId = $inventory->branch_id;

                    // If current is out of stock, we ALWAYS swap to any branch with stock.
                    if ($current['available_quantity'] <= 0 && $inventory->available_quantity > 0) {
                        $finalVariants[$flavorName] = [
                            'inventory_id' => $inventory->id,
                            'product_id' => $inventory->product_id,
                            'branch_id' => $inventory->branch_id,
                            'branch_name' => $inventory->branch->name,
                            'flavor' => $flavorName,
                            'price' => $inventory->product->price,
                            'available_quantity' => $inventory->available_quantity,
                        ];
                    } 
                    // If they both have stock, compare proximity to the user's assigned branch.
                    elseif ($current['available_quantity'] > 0 && $inventory->available_quantity > 0) {
                        // Check if we have a proximity map for the user
                        if ($userBranchId && isset($proximityMap[$userBranchId])) {
                            $priorityList = $proximityMap[$userBranchId];
                            
                            // Find index of current branch and new branch in the priority list
                            $currentPriority = array_search($currentBranchId, $priorityList);
                            $newPriority = array_search($newBranchId, $priorityList);

                            // If new branch is closer (lower index), and not false, swap it
                            if ($newPriority !== false && ($currentPriority === false || $newPriority < $currentPriority)) {
                                $finalVariants[$flavorName] = [
                                    'inventory_id' => $inventory->id,
                                    'product_id' => $inventory->product_id,
                                    'branch_id' => $inventory->branch_id,
                                    'branch_name' => $inventory->branch->name,
                                    'flavor' => $flavorName,
                                    'price' => $inventory->product->price,
                                    'available_quantity' => $inventory->available_quantity,
                                ];
                            }
                        }
                    }
                }
            }

            // 5. Calculate Total Unique Flavors for the Frontend
            $uniqueFlavorCount = count($finalVariants);

            return response()->json([
                'success' => true,
                'variants' => array_values($finalVariants),
                'product_name' => $product->name,
                'unique_flavor_count' => $uniqueFlavorCount
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load product variants'], 500);
        }
    }
}