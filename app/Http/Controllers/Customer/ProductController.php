<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchInventory;
use App\Models\Product;
use App\Helpers\GoogleDriveHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('is_active', true)->get();
        $selectedBranchId = $request->get('branch_id', 'all');

        // ✅ Added withoutGlobalScopes to ensure all branches' stock is fetched
        $query = BranchInventory::withoutGlobalScopes()->with(['product', 'flavor'])->where('is_archived', false);

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

            // ✅ Build the correct image URL — supports Google Drive URL OR local storage file
            $imageUrl = null;
            if ($inv->product) {
                if (!empty($inv->product->image_url)) {
                    $imageUrl = GoogleDriveHelper::getDirectImageUrl($inv->product->image_url);
                } elseif (!empty($inv->product->image)) {
                    $imageUrl = Storage::url($inv->product->image);
                }
            }

            $groupedProducts[$productName]->push([
                'inventory_id' => $inv->id,
                'product_id' => $inv->product_id,
                'branch_id' => $inv->branch_id,
                'branch_name' => $inv->branch->name,
                'flavor' => $inv->flavor->name ?? null,
                'flavor_id' => $inv->flavor->id ?? null,
                'price' => $inv->product->price,
                'available_quantity' => $inv->available_quantity,
                'image' => $imageUrl,   // ✅ full URL, ready for <img src="">
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
            $bestSellers = $bestSellers->sortBy(function ($product) use ($bestSellerIdArray) {
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

            // 1. Get the user's assigned branch via barangay mapping
            $userBranchId = null;
            if (Auth::check() && Auth::user()->barangay) {
                $servingBranch = DB::table('branch_barangay')
                    ->where('barangay_name', Auth::user()->barangay)->first();
                if ($servingBranch) {
                    $userBranchId = $servingBranch->branch_id;
                }
            }

            // 2. Get ALL inventory items across ALL branches (bypassing global scopes)
            $allVariants = BranchInventory::withoutGlobalScopes()->with(['branch', 'flavor'])
                ->where('product_id', $productId)
                ->get();

            if ($allVariants->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'This product is not available in any branch inventory.']);
            }

            // 3. Define Proximity Chain (Passing Logic)
            $proximityMap = [
                1 => [2, 5, 4, 3],
                2 => [1, 5, 4, 3],
                3 => [4, 5, 2, 1],
                4 => [3, 5, 2, 1],
                5 => [2, 1, 4, 3],
            ];

            // 4. Group by Flavor
            $groupedVariants = [];
            foreach ($allVariants as $inventory) {
                $flavorName = $inventory->flavor->name ?? 'Standard';
                
                if (!isset($groupedVariants[$flavorName])) {
                    $groupedVariants[$flavorName] = [
                        'flavor' => $flavorName,
                        'total_available_quantity' => 0,
                        'branches' => []
                    ];
                }
                
                $groupedVariants[$flavorName]['total_available_quantity'] += $inventory->available_quantity;
                
                $groupedVariants[$flavorName]['branches'][] = [
                    'inventory_id' => $inventory->id,
                    'branch_id' => $inventory->branch_id,
                    'branch_name' => $inventory->branch->name ?? 'Unknown Branch',
                    'available_quantity' => $inventory->available_quantity,
                    'user_branch_id' => $userBranchId, // Pass this down for the UI
                ];
            }

            // 5. Sort branches by proximity (PASSING LOGIC FIXED)
            $finalVariants = [];
            foreach ($groupedVariants as $flavorName => $data) {
                $branches = $data['branches'];
                
                usort($branches, function ($a, $b) use ($proximityMap, $userBranchId) {
                    $aId = $a['branch_id'];
                    $bId = $b['branch_id'];

                    if ($userBranchId) {
                        // User's own branch always first
                        if ($aId == $userBranchId) return -1;
                        if ($bId == $userBranchId) return 1;
                        
                        $aPriority = array_search($aId, $proximityMap[$userBranchId] ?? []);
                        $bPriority = array_search($bId, $proximityMap[$userBranchId] ?? []);

                        // ✅ FIX: Both are in the map, sort by priority
                        if ($aPriority !== false && $bPriority !== false) {
                            return $aPriority - $bPriority;
                        }
                        
                        // ✅ FIX: Only A is in the map, A wins
                        if ($aPriority !== false && $bPriority === false) {
                            return -1; 
                        }
                        
                        // ✅ FIX: Only B is in the map, B wins
                        if ($aPriority === false && $bPriority !== false) {
                            return 1;
                        }
                    }
                    
                    // Fallback to highest stock if neither is in the map
                    return $b['available_quantity'] - $a['available_quantity'];
                });

                $primaryBranch = $branches[0];

                $finalVariants[] = [
                    'inventory_id' => $primaryBranch['inventory_id'], 
                    'product_id' => $productId,
                    'branch_id' => $primaryBranch['branch_id'],
                    'branch_name' => $primaryBranch['branch_name'],
                    'flavor' => $flavorName,
                    'price' => $product->price,
                    'available_quantity' => $data['total_available_quantity'], // ✅ TOTAL across all branches
                    'user_branch_id' => $userBranchId,
                    'branches' => $branches, // ✅ Send all branches to JS
                ];
            }

            // 6. Image & Description logic
            $imageUrl = null;
            if (!empty($product->image_url)) {
                $imageUrl = GoogleDriveHelper::getDirectImageUrl($product->image_url);
            } elseif (!empty($product->image)) {
                $imageUrl = Storage::url($product->image);
            }

            $description = $product->description;
            if (empty($description)) {
                $parts = [];
                if ($product->brand) $parts[] = $product->brand;
                if ($product->category) $parts[] = $product->category;
                if ($product->type) $parts[] = ucfirst(str_replace('-', ' ', $product->type));
                $description = !empty($parts) ? implode(' • ', $parts) : null;
            }

            return response()->json([
                'success' => true,
                'variants' => $finalVariants,
                'product_name' => $product->name,
                'unique_flavor_count' => count($finalVariants),
                'product_info' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $imageUrl,
                    'description' => $description,
                    'brand' => $product->brand ?? null,
                    'category' => $product->category ?? null,
                    'type' => $product->type ?? null,
                    'nicotine_strength' => $product->nicotine_strength ?? null,
                    'puff_count' => $product->puff_count ?? null,
                    'battery_capacity' => $product->battery_capacity ?? null,
                    'liquid_capacity' => $product->liquid_capacity ?? null,
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching variants: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load product variants.'], 500);
        }
    }
}