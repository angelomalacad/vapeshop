<?php

namespace Database\Seeders;

use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Product;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // Get all branches
        $majada = Branch::where('code', 'MAJ')->first();
        $asia1 = Branch::where('code', 'AS1')->first();
        $mcdc = Branch::where('code', 'MCDC')->first();
        $paciano = Branch::where('code', 'PAC')->first();
        $pacianoV2 = Branch::where('code', 'PAC2')->first();

        if (!$majada) {
            $this->command->error('Branches not found! Please run BranchSeeder first.');
            return;
        }

        // =============================================
        // ========== GET ALL PRODUCTS BY NAME =========
        // =============================================

        $xUltra = Product::where('name', 'X-Vape Ultra')->first();
        $xPro = Product::where('name', 'X-Vape Pro')->first();
        $xMax = Product::where('name', 'X-Vape Max')->first();
        $slimbar = Product::where('name', 'Slimbar')->first();
        $slimbarMax = Product::where('name', 'Slimbar Max')->first();
        $flumPebble = Product::where('name', 'Flum Pebble')->first();
        $flumFloat = Product::where('name', 'Flum Float')->first();
        $dragbar = Product::where('name', 'Dragbar B5000')->first();
        $dragbarF8000 = Product::where('name', 'Dragbar F8000')->first();
        $elfBar = Product::where('name', 'Elf Bar 600')->first();
        $lostMary = Product::where('name', 'Lost Mary OS5000')->first();
        $hqd = Product::where('name', 'HQD Cuvie Plus')->first();
        $relx = Product::where('name', 'Relx Classic')->first();
        $relxInfinity = Product::where('name', 'Relx Infinity')->first();
        $caliburn = Product::where('name', 'Uwell Caliburn G2')->first();
        $xros = Product::where('name', 'Vaporesso XROS 3')->first();
        $oxva = Product::where('name', 'Oxva Xlim Pro')->first();

        // E-Liquids
        $dinnerLady = Product::where('name', 'Dinner Lady - Lemon Tart')->first();
        $naked = Product::where('name', 'Naked 100 - Hawaiian POG')->first();
        $monster = Product::where('name', 'Monster Vape Labs - Jam Monster')->first();
        $coastal = Product::where('name', 'Coastal Clouds - Blueberry Banana')->first();
        $milkman = Product::where('name', 'The Milkman - Crumbleberry')->first();
        $pachamama = Product::where('name', 'Pachamama - Fuji Apple')->first();
        $ripe = Product::where('name', 'Ripe Vapes - VCT')->first();
        $charlie = Product::where('name', 'Charlie\'s Chalk Dust - PB&J')->first();
        $sadboy = Product::where('name', 'Sadboy - Butter Cookie')->first();
        $glas = Product::where('name', 'Glas Basix - Banana Cream Pie')->first();
        $twist = Product::where('name', 'Twist - Pink Punch No. 1')->first();
        $juiceHead = Product::where('name', 'Juice Head - Pineapple Grapefruit')->first();
        $cloudNurdz = Product::where('name', 'Cloud Nurdz - Watermelon Apple')->first();

        // Box Mods & Accessories
        $geekvape = Product::where('name', 'GeekVape Aegis Legend 2')->first();
        $voopoo = Product::where('name', 'VooPoo Drag 4')->first();
        $smok = Product::where('name', 'Smok RPM 5')->first();
        $relxPods = Product::where('name', 'Relx Replacement Pods')->first();
        $caliburnCoils = Product::where('name', 'Caliburn G Coils (4-Pack)')->first();
        $batteries = Product::where('name', '18650 Battery')->first();
        $charger = Product::where('name', 'Dual Slot Battery Charger')->first();
        $dripTips = Product::where('name', '510 Drip Tips')->first();

        // =============================================
        // ========== MAJADA OUT BRANCH =================
        // =============================================

        $this->command->info('Seeding Majada Out Branch...');

        // X-Vape Ultra - Majada
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Purple Twilight')->first() : null;
        $this->createInventory($majada->id, $xUltra, $flavor, 50, 5, 10, 20, 100);

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Blueberry Ice')->first() : null;
        $this->createInventory($majada->id, $xUltra, $flavor, 35, 3, 10, 20, 80);

        // X-Vape Pro - Majada
        $flavor = $xPro ? $xPro->flavors->where('name', 'Mango Ice')->first() : null;
        $this->createInventory($majada->id, $xPro, $flavor, 30, 2, 10, 15, 60);

        // Slimbar - Majada
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Strawberry Banana')->first() : null;
        $this->createInventory($majada->id, $slimbar, $flavor, 60, 8, 15, 25, 120);

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Lychee Ice')->first() : null;
        $this->createInventory($majada->id, $slimbar, $flavor, 45, 4, 15, 25, 100);

        // Flum Pebble - Majada
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Aloe Grape')->first() : null;
        $this->createInventory($majada->id, $flumPebble, $flavor, 40, 6, 10, 20, 80);

        // Dragbar - Majada
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Blue Razz')->first() : null;
        $this->createInventory($majada->id, $dragbar, $flavor, 55, 7, 12, 25, 100);

        // RELX - Majada
        $flavor = $relx ? $relx->flavors->where('name', 'Fresh Mint')->first() : null;
        $this->createInventory($majada->id, $relx, $flavor, 25, 2, 8, 15, 50);

        $flavor = $relx ? $relx->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($majada->id, $relx, $flavor, 20, 1, 8, 15, 45);

        // E-Liquids - Majada
        $this->createInventory($majada->id, $dinnerLady, null, 30, 3, 10, 20, 60);
        $this->createInventory($majada->id, $naked, null, 25, 2, 10, 20, 50);
        $this->createInventory($majada->id, $coastal, null, 20, 1, 8, 15, 40);

        // Box Mods - Majada (low stock)
        $this->createInventory($majada->id, $geekvape, null, 5, 1, 3, 5, 15);
        $this->createInventory($majada->id, $voopoo, null, 3, 0, 3, 5, 10);

        // Accessories - Majada
        $this->createInventory($majada->id, $relxPods, null, 50, 5, 20, 30, 100);
        $this->createInventory($majada->id, $batteries, null, 30, 3, 10, 20, 60);
        $this->createInventory($majada->id, $charger, null, 15, 1, 5, 10, 30);

        // =============================================
        // ========== ASIA 1 BRANCH ====================
        // =============================================

        $this->command->info('Seeding Asia 1 Branch...');

        // X-Vape Ultra - Asia1
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Strawberry Watermelon')->first() : null;
        $this->createInventory($asia1->id, $xUltra, $flavor, 45, 4, 10, 20, 90);

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createInventory($asia1->id, $xUltra, $flavor, 38, 3, 10, 20, 75);

        // Slimbar Max - Asia1
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Mixed Berries')->first() : null;
        $this->createInventory($asia1->id, $slimbarMax, $flavor, 35, 4, 12, 20, 70);

        // Flum Float - Asia1
        $flavor = $flumFloat ? $flumFloat->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createInventory($asia1->id, $flumFloat, $flavor, 60, 8, 15, 25, 120);

        // Dragbar F8000 - Asia1
        $flavor = $dragbarF8000 ? $dragbarF8000->flavors->where('name', 'Strawberry Kiwi')->first() : null;
        $this->createInventory($asia1->id, $dragbarF8000, $flavor, 30, 2, 10, 15, 60);

        // Elf Bar - Asia1
        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Blue Razz Lemonade')->first() : null;
        $this->createInventory($asia1->id, $elfBar, $flavor, 80, 12, 20, 30, 150);

        // RELX Infinity - Asia1
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createInventory($asia1->id, $relxInfinity, $flavor, 15, 1, 5, 10, 30);

        // E-Liquids - Asia1
        $this->createInventory($asia1->id, $monster, null, 35, 4, 10, 20, 70);
        $this->createInventory($asia1->id, $milkman, null, 28, 2, 8, 15, 55);
        $this->createInventory($asia1->id, $pachamama, null, 22, 1, 8, 15, 45);

        // Pod Systems - Asia1
        $this->createInventory($asia1->id, $caliburn, null, 8, 1, 3, 5, 20);
        $this->createInventory($asia1->id, $xros, null, 6, 0, 3, 5, 15);

        // Accessories - Asia1
        $this->createInventory($asia1->id, $caliburnCoils, null, 60, 8, 15, 25, 120);
        $this->createInventory($asia1->id, $dripTips, null, 45, 5, 10, 20, 80);

        // =============================================
        // ========== MCDC BRANCH ======================
        // =============================================

        $this->command->info('Seeding MCDC Branch...');

        // X-Vape Ultra - MCDC
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Grape Soda')->first() : null;
        $this->createInventory($mcdc->id, $xUltra, $flavor, 55, 6, 10, 20, 110);

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Lush Ice')->first() : null;
        $this->createInventory($mcdc->id, $xUltra, $flavor, 42, 4, 10, 20, 85);

        // X-Vape Max - MCDC
        $flavor = $xMax ? $xMax->flavors->where('name', 'Blue Razz')->first() : null;
        $this->createInventory($mcdc->id, $xMax, $flavor, 25, 2, 8, 15, 50);

        // Slimbar - MCDC
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Spearmint')->first() : null;
        $this->createInventory($mcdc->id, $slimbar, $flavor, 70, 10, 15, 25, 140);

        // Lost Mary - MCDC
        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Strawberry Pina Colada')->first() : null;
        $this->createInventory($mcdc->id, $lostMary, $flavor, 35, 3, 10, 15, 70);

        // HQD - MCDC
        $flavor = $hqd ? $hqd->flavors->where('name', 'Mango')->first() : null;
        $this->createInventory($mcdc->id, $hqd, $flavor, 90, 15, 20, 30, 180);

        // RELX - MCDC
        $flavor = $relx ? $relx->flavors->where('name', 'Lychee')->first() : null;
        $this->createInventory($mcdc->id, $relx, $flavor, 30, 3, 8, 15, 60);

        $flavor = $relx ? $relx->flavors->where('name', 'Grape')->first() : null;
        $this->createInventory($mcdc->id, $relx, $flavor, 28, 2, 8, 15, 55);

        // E-Liquids - MCDC
        $this->createInventory($mcdc->id, $ripe, null, 18, 1, 5, 10, 35);
        $this->createInventory($mcdc->id, $charlie, null, 25, 2, 8, 15, 50);
        $this->createInventory($mcdc->id, $sadboy, null, 30, 3, 10, 20, 60);
        $this->createInventory($mcdc->id, $glas, null, 22, 1, 8, 15, 45);

        // Box Mods - MCDC
        $this->createInventory($mcdc->id, $smok, null, 7, 1, 3, 5, 15);

        // Accessories - MCDC
        $this->createInventory($mcdc->id, $relxPods, null, 45, 4, 20, 30, 90);
        $this->createInventory($mcdc->id, $batteries, null, 35, 4, 10, 20, 70);

        // =============================================
        // ========== PACIANO BRANCH ===================
        // =============================================

        $this->command->info('Seeding Paciano Branch...');

        // X-Vape Ultra - Paciano
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Pineapple Coconut')->first() : null;
        $this->createInventory($paciano->id, $xUltra, $flavor, 40, 3, 10, 20, 80);

        // Slimbar - Paciano (LOW STOCK - for demo alerts)
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Vanilla Custard')->first() : null;
        $this->createInventory($paciano->id, $slimbar, $flavor, 3, 1, 10, 15, 60);  // LOW STOCK!

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Blueberry')->first() : null;
        $this->createInventory($paciano->id, $slimbar, $flavor, 8, 2, 10, 15, 50);   // LOW STOCK!

        // Flum Pebble - Paciano
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($paciano->id, $flumPebble, $flavor, 50, 6, 10, 20, 100);

        // Dragbar - Paciano
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createInventory($paciano->id, $dragbar, $flavor, 35, 4, 12, 20, 70);

        // Elf Bar - Paciano
        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($paciano->id, $elfBar, $flavor, 65, 10, 20, 30, 130);

        // RELX - Paciano
        $flavor = $relx ? $relx->flavors->where('name', 'Peach')->first() : null;
        $this->createInventory($paciano->id, $relx, $flavor, 22, 2, 8, 15, 45);

        // E-Liquids - Paciano
        $this->createInventory($paciano->id, $twist, null, 40, 5, 10, 20, 80);
        $this->createInventory($paciano->id, $juiceHead, null, 35, 4, 10, 20, 70);
        $this->createInventory($paciano->id, $cloudNurdz, null, 28, 3, 8, 15, 55);

        // Pod Systems - Paciano
        $this->createInventory($paciano->id, $oxva, null, 10, 1, 3, 5, 20);

        // Accessories - Paciano
        $this->createInventory($paciano->id, $caliburnCoils, null, 55, 6, 15, 25, 110);
        $this->createInventory($paciano->id, $charger, null, 12, 1, 5, 10, 25);
        $this->createInventory($paciano->id, $dripTips, null, 35, 3, 10, 20, 70);

        // =============================================
        // ========== PACIANO V2 BRANCH ================
        // =============================================

        $this->command->info('Seeding Paciano V2 Branch...');

        // X-Vape Pro - Paciano V2
        $flavor = $xPro ? $xPro->flavors->where('name', 'Peach Ice Tea')->first() : null;
        $this->createInventory($pacianoV2->id, $xPro, $flavor, 45, 5, 10, 15, 90);

        // Slimbar Max - Paciano V2
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $slimbarMax, $flavor, 30, 3, 12, 20, 60);

        // Flum Pebble - Paciano V2
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $flumPebble, $flavor, 55, 7, 10, 20, 110);

        // Lost Mary - Paciano V2
        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Blueberry')->first() : null;
        $this->createInventory($pacianoV2->id, $lostMary, $flavor, 40, 4, 10, 15, 80);

        // RELX Infinity - Paciano V2
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $relxInfinity, $flavor, 20, 2, 5, 10, 40);

        // E-Liquids - Paciano V2
        $this->createInventory($pacianoV2->id, $dinnerLady, null, 35, 4, 10, 20, 70);
        $this->createInventory($pacianoV2->id, $naked, null, 30, 3, 10, 20, 60);
        $this->createInventory($pacianoV2->id, $monster, null, 25, 2, 8, 15, 50);
        $this->createInventory($pacianoV2->id, $coastal, null, 28, 3, 8, 15, 55);

        // Box Mods - Paciano V2
        $this->createInventory($pacianoV2->id, $geekvape, null, 4, 0, 3, 5, 12);

        // Accessories - Paciano V2
        $this->createInventory($pacianoV2->id, $relxPods, null, 60, 6, 20, 30, 120);
        $this->createInventory($pacianoV2->id, $batteries, null, 40, 5, 10, 20, 80);
        $this->createInventory($pacianoV2->id, $caliburnCoils, null, 50, 5, 15, 25, 100);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Inventory Seeding Completed!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Low Stock Demo Items:');
        $this->command->info('  - Paciano Branch: Slimbar (Vanilla Custard) - Only 3 units left');
        $this->command->info('  - Paciano Branch: Slimbar (Blueberry) - Only 8 units left');
        $this->command->info('');
    }

    /**
     * Helper function to create inventory
     */
    private function createInventory($branchId, $product, $flavor, $quantity, $reserved, $lowStockThreshold, $reorderPoint, $optimalStock)
    {
        if (!$product) {
            return;
        }

        BranchInventory::updateOrCreate(
            [
                'branch_id' => $branchId,
                'product_id' => $product->id,
                'flavor_id' => $flavor ? $flavor->id : null,
            ],
            [
                'quantity' => $quantity,
                'reserved_quantity' => $reserved,
                'low_stock_threshold' => $lowStockThreshold,
                'reorder_point' => $reorderPoint,
                'optimal_stock' => $optimalStock,
                'last_restocked_at' => now(),
                'last_purchase_price' => $product->cost,
            ]
        );
    }
}
