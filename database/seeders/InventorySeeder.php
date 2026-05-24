<?php

namespace Database\Seeders;

use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\User;
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

        // Get users
        $superAdmin = User::where('role', 'super_admin')->first();
        $branchAdminMajada = User::where('branch_id', $majada->id)->where('role', 'branch_admin')->first();
        $branchAdminPaciano = User::where('branch_id', $paciano->id)->where('role', 'branch_admin')->first();
        $branchAdminAsia1 = User::where('branch_id', $asia1->id)->where('role', 'branch_admin')->first();

        // =============================================
        // ========== GET ALL PRODUCTS BY NAME =========
        // =============================================

        // Existing products
        $xUltra = Product::where('name', 'X-Vape Ultra')->first();
        $xPro = Product::where('name', 'X-Vape Pro')->first();
        $slimbar = Product::where('name', 'Slimbar')->first();
        $slimbarMax = Product::where('name', 'Slimbar Max')->first();
        $flumPebble = Product::where('name', 'Flum Pebble')->first();
        $dragbar = Product::where('name', 'Dragbar B5000')->first();
        $relx = Product::where('name', 'Relx Classic')->first();
        $relxInfinity = Product::where('name', 'Relx Infinity')->first();

        // E-Liquids
        $dinnerLady = Product::where('name', 'Dinner Lady - Lemon Tart')->first();
        $naked = Product::where('name', 'Naked 100 - Hawaiian POG')->first();
        $coastal = Product::where('name', 'Coastal Clouds - Blueberry Banana')->first();

        // NEW PRODUCTS TO ADD TO INVENTORY
        // X-Vape Max
        $xMax = Product::where('name', 'X-Vape Max')->first();

        // Flum Float
        $flumFloat = Product::where('name', 'Flum Float')->first();

        // Dragbar F8000
        $dragbarF8000 = Product::where('name', 'Dragbar F8000')->first();

        // Elf Bar 600
        $elfBar = Product::where('name', 'Elf Bar 600')->first();

        // Lost Mary OS5000
        $lostMary = Product::where('name', 'Lost Mary OS5000')->first();

        // HQD Cuvie Plus
        $hqd = Product::where('name', 'HQD Cuvie Plus')->first();

        // New E-Liquids
        $monster = Product::where('name', 'Monster Vape Labs - Jam Monster')->first();
        $milkman = Product::where('name', 'The Milkman - Crumbleberry')->first();
        $pachamama = Product::where('name', 'Pachamama - Fuji Apple')->first();
        $ripe = Product::where('name', 'Ripe Vapes - VCT')->first();
        $charlie = Product::where('name', 'Charlie\'s Chalk Dust - PB&J')->first();
        $sadboy = Product::where('name', 'Sadboy - Butter Cookie')->first();
        $glas = Product::where('name', 'Glas Basix - Banana Cream Pie')->first();
        $twist = Product::where('name', 'Twist - Pink Punch No. 1')->first();
        $juiceHead = Product::where('name', 'Juice Head - Pineapple Grapefruit')->first();
        $cloudNurdz = Product::where('name', 'Cloud Nurdz - Watermelon Apple')->first();

        // Pod Systems (NEW)
        $caliburn = Product::where('name', 'Uwell Caliburn G2')->first();
        $xros = Product::where('name', 'Vaporesso XROS 3')->first();
        $oxva = Product::where('name', 'Oxva Xlim Pro')->first();

        // Box Mods (NEW)
        $geekvape = Product::where('name', 'GeekVape Aegis Legend 2')->first();
        $voopoo = Product::where('name', 'VooPoo Drag 4')->first();
        $smok = Product::where('name', 'Smok RPM 5')->first();

        // Accessories (NEW)
        $relxPods = Product::where('name', 'Relx Replacement Pods')->first();
        $caliburnCoils = Product::where('name', 'Caliburn G Coils (4-Pack)')->first();
        $batteries = Product::where('name', '18650 Battery')->first();
        $charger = Product::where('name', 'Dual Slot Battery Charger')->first();
        $dripTips = Product::where('name', '510 Drip Tips')->first();

        // =============================================
        // ========== MAJADA OUT BRANCH =================
        // =============================================

        $this->command->info('Seeding Majada Out Branch...');

        // X-Vape Ultra - Majada (Disposable: 2 year shelf life)
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Purple Twilight')->first() : null;
        $this->createInventory($majada->id, $xUltra, $flavor, 50, 0, 10, 20, 100, now()->addMonths(24));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Blueberry Ice')->first() : null;
        $this->createInventory($majada->id, $xUltra, $flavor, 35, 0, 10, 20, 80, now()->addMonths(22));

        // X-Vape Pro - Majada (Disposable: 2 year shelf life)
        $flavor = $xPro ? $xPro->flavors->where('name', 'Mango Ice')->first() : null;
        $this->createInventory($majada->id, $xPro, $flavor, 30, 0, 10, 15, 60, now()->addMonths(23));

        // X-Vape Max - Majada (Disposable: 2 year shelf life)
        $flavor = $xMax ? $xMax->flavors->where('name', 'Blue Razz')->first() : null;
        $this->createInventory($majada->id, $xMax, $flavor, 25, 0, 8, 15, 50, now()->addMonths(24));

        // Slimbar - Majada (Disposable: 2 year shelf life)
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Strawberry Banana')->first() : null;
        $this->createInventory($majada->id, $slimbar, $flavor, 60, 0, 15, 25, 120, now()->addMonths(20));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Spearmint')->first() : null;
        $this->createInventory($majada->id, $slimbar, $flavor, 40, 0, 15, 25, 80, now()->addMonths(21));

        // Slimbar Max - Majada (Disposable: 2 year shelf life)
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Mixed Berries')->first() : null;
        $this->createInventory($majada->id, $slimbarMax, $flavor, 35, 0, 10, 20, 70, now()->addMonths(22));

        // Dragbar - Majada (Disposable: 2 year shelf life)
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Blue Razz')->first() : null;
        $this->createInventory($majada->id, $dragbar, $flavor, 55, 0, 12, 25, 100, now()->addMonths(18));

        // Dragbar F8000 - Majada (Disposable: 2 year shelf life)
        $flavor = $dragbarF8000 ? $dragbarF8000->flavors->where('name', 'Strawberry Kiwi')->first() : null;
        $this->createInventory($majada->id, $dragbarF8000, $flavor, 30, 0, 10, 20, 60, now()->addMonths(23));

        // Flum Pebble - Majada (Disposable: 2 year shelf life)
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($majada->id, $flumPebble, $flavor, 45, 0, 12, 20, 90, now()->addMonths(19));

        // Elf Bar 600 - Majada (Disposable: 2 year shelf life)
        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Blue Razz Lemonade')->first() : null;
        $this->createInventory($majada->id, $elfBar, $flavor, 50, 0, 15, 25, 100, now()->addMonths(21));

        // Lost Mary - Majada (Disposable: 2 year shelf life)
        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Strawberry Pina Colada')->first() : null;
        $this->createInventory($majada->id, $lostMary, $flavor, 40, 0, 10, 20, 80, now()->addMonths(24));

        // HQD - Majada (Disposable: 2 year shelf life)
        $flavor = $hqd ? $hqd->flavors->where('name', 'Strawberry')->first() : null;
        $this->createInventory($majada->id, $hqd, $flavor, 35, 0, 10, 15, 70, now()->addMonths(20));

        // RELX Classic - Majada (Prefilled pods: 18 months shelf life) [citation:1]
        $flavor = $relx ? $relx->flavors->where('name', 'Fresh Mint')->first() : null;
        $this->createInventory($majada->id, $relx, $flavor, 25, 0, 8, 15, 50, now()->addMonths(18));

        $flavor = $relx ? $relx->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($majada->id, $relx, $flavor, 20, 0, 8, 15, 40, now()->addMonths(17));

        // RELX Infinity - Majada (Prefilled pods: 18 months shelf life) [citation:1]
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createInventory($majada->id, $relxInfinity, $flavor, 15, 0, 5, 10, 30, now()->addMonths(18));

        // Pod Systems - Majada (Hardware: 8+ years shelf life) [citation:4]
        $this->createInventory($majada->id, $caliburn, null, 10, 0, 3, 5, 15, now()->addYears(8));
        $this->createInventory($majada->id, $xros, null, 8, 0, 3, 5, 12, now()->addYears(8));
        $this->createInventory($majada->id, $oxva, null, 12, 0, 3, 5, 15, now()->addYears(8));

        // Box Mods - Majada (Hardware: 8+ years shelf life) [citation:4]
        $this->createInventory($majada->id, $geekvape, null, 5, 0, 2, 3, 8, now()->addYears(8));
        $this->createInventory($majada->id, $voopoo, null, 6, 0, 2, 3, 10, now()->addYears(8));
        $this->createInventory($majada->id, $smok, null, 8, 0, 2, 4, 12, now()->addYears(8));

        // Accessories - Majada (Coils/Accessories: 2+ years)
        $this->createInventory($majada->id, $relxPods, null, 30, 0, 10, 15, 60, now()->addMonths(24));
        $flavor = $caliburnCoils ? $caliburnCoils->flavors->where('name', '0.8ohm Mesh')->first() : null;
        $this->createInventory($majada->id, $caliburnCoils, $flavor, 40, 0, 10, 20, 80, now()->addYears(3));
        $this->createInventory($majada->id, $batteries, null, 25, 0, 8, 15, 50, now()->addYears(5));
        $this->createInventory($majada->id, $charger, null, 15, 0, 5, 8, 30, now()->addYears(8));
        $this->createInventory($majada->id, $dripTips, null, 50, 0, 15, 25, 100, now()->addYears(8));

        // E-Liquids - Majada (E-liquids: 1-2 year shelf life) [citation:6][citation:8]
        $this->createInventory($majada->id, $dinnerLady, null, 30, 0, 10, 20, 60, now()->addMonths(20));
        $this->createInventory($majada->id, $naked, null, 25, 0, 10, 20, 50, now()->addMonths(18));
        $this->createInventory($majada->id, $coastal, null, 20, 0, 8, 15, 40, now()->addMonths(22));
        $this->createInventory($majada->id, $monster, null, 15, 0, 5, 10, 30, now()->addMonths(19));
        $this->createInventory($majada->id, $milkman, null, 18, 0, 5, 10, 35, now()->addMonths(21));
        $this->createInventory($majada->id, $pachamama, null, 22, 0, 8, 15, 45, now()->addMonths(17));
        $this->createInventory($majada->id, $ripe, null, 12, 0, 5, 8, 25, now()->addMonths(23));
        $this->createInventory($majada->id, $charlie, null, 16, 0, 5, 10, 30, now()->addMonths(20));
        $this->createInventory($majada->id, $sadboy, null, 20, 0, 8, 12, 40, now()->addMonths(18));
        $this->createInventory($majada->id, $glas, null, 14, 0, 5, 10, 28, now()->addMonths(22));
        $this->createInventory($majada->id, $twist, null, 25, 0, 10, 15, 50, now()->addMonths(19));
        $this->createInventory($majada->id, $juiceHead, null, 20, 0, 8, 12, 40, now()->addMonths(21));
        $this->createInventory($majada->id, $cloudNurdz, null, 22, 0, 8, 15, 45, now()->addMonths(20));

        // =============================================
        // ========== ASIA 1 BRANCH ====================
        // =============================================

        $this->command->info('Seeding Asia 1 Branch...');

        // X-Vape Ultra - Asia1
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Strawberry Watermelon')->first() : null;
        $this->createInventory($asia1->id, $xUltra, $flavor, 45, 0, 10, 20, 90, now()->addMonths(24));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Mango Peach')->first() : null;
        $this->createInventory($asia1->id, $xUltra, $flavor, 30, 0, 10, 20, 60, now()->addMonths(22));

        // X-Vape Pro - Asia1
        $flavor = $xPro ? $xPro->flavors->where('name', 'Watermelon Ice')->first() : null;
        $this->createInventory($asia1->id, $xPro, $flavor, 25, 0, 8, 15, 50, now()->addMonths(23));

        // Slimbar Max - Asia1
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Mixed Berries')->first() : null;
        $this->createInventory($asia1->id, $slimbarMax, $flavor, 35, 0, 12, 20, 70, now()->addMonths(21));

        // Slimbar - Asia1
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Lychee Ice')->first() : null;
        $this->createInventory($asia1->id, $slimbar, $flavor, 40, 0, 10, 20, 80, now()->addMonths(20));

        // Flum Pebble - Asia1
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Aloe Grape')->first() : null;
        $this->createInventory($asia1->id, $flumPebble, $flavor, 60, 0, 15, 25, 120, now()->addMonths(19));

        // Flum Float - Asia1
        $flavor = $flumFloat ? $flumFloat->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createInventory($asia1->id, $flumFloat, $flavor, 35, 0, 10, 20, 70, now()->addMonths(22));

        // Dragbar - Asia1
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createInventory($asia1->id, $dragbar, $flavor, 30, 0, 10, 20, 60, now()->addMonths(18));

        // Elf Bar - Asia1
        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($asia1->id, $elfBar, $flavor, 45, 0, 15, 25, 90, now()->addMonths(21));

        // RELX Infinity - Asia1
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createInventory($asia1->id, $relxInfinity, $flavor, 15, 0, 5, 10, 30, now()->addMonths(18));

        // Pod Systems - Asia1 (Hardware)
        $this->createInventory($asia1->id, $caliburn, null, 8, 0, 3, 5, 12, now()->addYears(8));
        $this->createInventory($asia1->id, $xros, null, 6, 0, 2, 4, 10, now()->addYears(8));

        // Accessories - Asia1
        $this->createInventory($asia1->id, $relxPods, null, 20, 0, 8, 12, 40, now()->addMonths(24));
        $this->createInventory($asia1->id, $batteries, null, 15, 0, 5, 10, 30, now()->addYears(5));

        // E-Liquids - Asia1
        $this->createInventory($asia1->id, $coastal, null, 20, 0, 8, 15, 40, now()->addMonths(22));
        $this->createInventory($asia1->id, $dinnerLady, null, 25, 0, 10, 15, 50, now()->addMonths(20));
        $this->createInventory($asia1->id, $naked, null, 20, 0, 8, 12, 40, now()->addMonths(18));
        $this->createInventory($asia1->id, $twist, null, 30, 0, 10, 20, 60, now()->addMonths(19));
        $this->createInventory($asia1->id, $juiceHead, null, 18, 0, 5, 10, 35, now()->addMonths(21));

        // =============================================
        // ========== MCDC BRANCH ======================
        // =============================================

        $this->command->info('Seeding MCDC Branch...');

        // X-Vape Ultra - MCDC
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Grape Soda')->first() : null;
        $this->createInventory($mcdc->id, $xUltra, $flavor, 55, 0, 10, 20, 110, now()->addMonths(23));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createInventory($mcdc->id, $xUltra, $flavor, 40, 0, 10, 20, 80, now()->addMonths(24));

        // X-Vape Max - MCDC
        $flavor = $xMax ? $xMax->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createInventory($mcdc->id, $xMax, $flavor, 20, 0, 5, 10, 40, now()->addMonths(22));

        // Slimbar - MCDC
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Spearmint')->first() : null;
        $this->createInventory($mcdc->id, $slimbar, $flavor, 70, 0, 15, 25, 140, now()->addMonths(20));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Green Apple')->first() : null;
        $this->createInventory($mcdc->id, $slimbar, $flavor, 45, 0, 10, 20, 90, now()->addMonths(21));

        // Slimbar Max - MCDC
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Menthol')->first() : null;
        $this->createInventory($mcdc->id, $slimbarMax, $flavor, 30, 0, 10, 15, 60, now()->addMonths(19));

        // Dragbar F8000 - MCDC
        $flavor = $dragbarF8000 ? $dragbarF8000->flavors->where('name', 'Grape Ice')->first() : null;
        $this->createInventory($mcdc->id, $dragbarF8000, $flavor, 35, 0, 10, 20, 70, now()->addMonths(23));

        // Lost Mary - MCDC
        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Blueberry')->first() : null;
        $this->createInventory($mcdc->id, $lostMary, $flavor, 30, 0, 8, 15, 60, now()->addMonths(24));

        // RELX - MCDC
        $flavor = $relx ? $relx->flavors->where('name', 'Lychee')->first() : null;
        $this->createInventory($mcdc->id, $relx, $flavor, 30, 0, 8, 15, 60, now()->addMonths(18));

        $flavor = $relx ? $relx->flavors->where('name', 'Grape')->first() : null;
        $this->createInventory($mcdc->id, $relx, $flavor, 25, 0, 8, 12, 50, now()->addMonths(17));

        // Box Mods - MCDC (Hardware)
        $this->createInventory($mcdc->id, $geekvape, null, 4, 0, 2, 3, 8, now()->addYears(8));
        $this->createInventory($mcdc->id, $smok, null, 6, 0, 2, 3, 10, now()->addYears(8));

        // Accessories - MCDC
        $this->createInventory($mcdc->id, $batteries, null, 20, 0, 5, 10, 40, now()->addYears(5));

        // E-Liquids - MCDC
        $this->createInventory($mcdc->id, $dinnerLady, null, 28, 0, 10, 15, 55, now()->addMonths(20));
        $this->createInventory($mcdc->id, $monster, null, 20, 0, 8, 12, 40, now()->addMonths(19));
        $this->createInventory($mcdc->id, $sadboy, null, 25, 0, 8, 15, 50, now()->addMonths(21));
        $this->createInventory($mcdc->id, $pachamama, null, 18, 0, 5, 10, 35, now()->addMonths(22));

        // =============================================
        // ========== PACIANO BRANCH (LOW STOCK) =======
        // =============================================

        $this->command->info('Seeding Paciano Branch (Low Stock for Demo)...');

        // X-Vape Ultra - Paciano
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Pineapple Coconut')->first() : null;
        $this->createInventory($paciano->id, $xUltra, $flavor, 40, 0, 10, 20, 80, now()->addMonths(22));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Strawberry Kiwi')->first() : null;
        $this->createInventory($paciano->id, $xUltra, $flavor, 25, 0, 10, 15, 50, now()->addMonths(24));

        // Slimbar - Paciano (LOW STOCK - Near Expiry Demo)
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Vanilla Custard')->first() : null;
        $this->createInventory($paciano->id, $slimbar, $flavor, 3, 0, 10, 15, 60, now()->addMonths(1));  // EXPIRING SOON!

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Blueberry')->first() : null;
        $this->createInventory($paciano->id, $slimbar, $flavor, 8, 0, 10, 15, 50, now()->addMonths(2));   // EXPIRING SOON!

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Peach Ice')->first() : null;
        $this->createInventory($paciano->id, $slimbar, $flavor, 5, 0, 10, 15, 40, now()->addMonths(3));    // EXPIRING SOON!

        // Flum Pebble - Paciano
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($paciano->id, $flumPebble, $flavor, 50, 0, 10, 20, 100, now()->addMonths(20));

        // Flum Float - Paciano (LOW STOCK)
        $flavor = $flumFloat ? $flumFloat->flavors->where('name', 'Mint')->first() : null;
        $this->createInventory($paciano->id, $flumFloat, $flavor, 6, 0, 10, 15, 40, now()->addMonths(18));

        // Dragbar - Paciano (LOW STOCK)
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createInventory($paciano->id, $dragbar, $flavor, 4, 0, 10, 15, 50, now()->addMonths(16));

        // RELX - Paciano
        $flavor = $relx ? $relx->flavors->where('name', 'Peach')->first() : null;
        $this->createInventory($paciano->id, $relx, $flavor, 22, 0, 8, 15, 45, now()->addMonths(18));

        // RELX Infinity - Paciano (LOW STOCK)
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Strawberry')->first() : null;
        $this->createInventory($paciano->id, $relxInfinity, $flavor, 7, 0, 5, 10, 30, now()->addMonths(15));

        // Pod Systems - Paciano (LOW STOCK)
        $this->createInventory($paciano->id, $caliburn, null, 3, 0, 3, 5, 12, now()->addYears(8));

        // E-Liquids - Paciano (LOW STOCK for some)
        $this->createInventory($paciano->id, $coastal, null, 8, 0, 8, 12, 40, now()->addMonths(5));   // EXPIRING SOON!
        $this->createInventory($paciano->id, $dinnerLady, null, 15, 0, 10, 15, 50, now()->addMonths(20));
        $this->createInventory($paciano->id, $charlie, null, 5, 0, 5, 10, 30, now()->addMonths(4));    // EXPIRING SOON!
        $this->createInventory($paciano->id, $cloudNurdz, null, 10, 0, 8, 12, 40, now()->addMonths(18));

        // =============================================
        // ========== PACIANO V2 BRANCH ================
        // =============================================

        $this->command->info('Seeding Paciano V2 Branch...');

        // X-Vape Pro - Paciano V2
        $flavor = $xPro ? $xPro->flavors->where('name', 'Peach Ice Tea')->first() : null;
        $this->createInventory($pacianoV2->id, $xPro, $flavor, 45, 0, 10, 15, 90, now()->addMonths(23));

        $flavor = $xPro ? $xPro->flavors->where('name', 'Double Apple')->first() : null;
        $this->createInventory($pacianoV2->id, $xPro, $flavor, 30, 0, 10, 15, 60, now()->addMonths(24));

        // X-Vape Max - Paciano V2
        $flavor = $xMax ? $xMax->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createInventory($pacianoV2->id, $xMax, $flavor, 25, 0, 8, 15, 50, now()->addMonths(21));

        // Slimbar Max - Paciano V2
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $slimbarMax, $flavor, 30, 0, 12, 20, 60, now()->addMonths(22));

        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Cotton Candy')->first() : null;
        $this->createInventory($pacianoV2->id, $slimbarMax, $flavor, 20, 0, 10, 15, 45, now()->addMonths(20));

        // Slimbar - Paciano V2
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Pineapple Coconut')->first() : null;
        $this->createInventory($pacianoV2->id, $slimbar, $flavor, 35, 0, 10, 20, 70, now()->addMonths(19));

        // Flum Pebble - Paciano V2
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Blue Razz Ice')->first() : null;
        $this->createInventory($pacianoV2->id, $flumPebble, $flavor, 40, 0, 12, 20, 80, now()->addMonths(21));

        // Dragbar - Paciano V2
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($pacianoV2->id, $dragbar, $flavor, 30, 0, 10, 15, 60, now()->addMonths(17));

        // HQD - Paciano V2
        $flavor = $hqd ? $hqd->flavors->where('name', 'Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $hqd, $flavor, 35, 0, 10, 20, 70, now()->addMonths(20));

        // RELX Infinity - Paciano V2
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $relxInfinity, $flavor, 20, 0, 5, 10, 40, now()->addMonths(18));

        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Pineapple')->first() : null;
        $this->createInventory($pacianoV2->id, $relxInfinity, $flavor, 15, 0, 5, 10, 30, now()->addMonths(16));

        // Pod Systems - Paciano V2
        $this->createInventory($pacianoV2->id, $oxva, null, 10, 0, 3, 5, 15, now()->addYears(8));
        $this->createInventory($pacianoV2->id, $xros, null, 8, 0, 3, 5, 12, now()->addYears(8));

        // Box Mods - Paciano V2
        $this->createInventory($pacianoV2->id, $voopoo, null, 5, 0, 2, 3, 8, now()->addYears(8));

        // Accessories - Paciano V2
        $this->createInventory($pacianoV2->id, $relxPods, null, 25, 0, 8, 12, 50, now()->addMonths(24));
        $this->createInventory($pacianoV2->id, $charger, null, 10, 0, 3, 5, 20, now()->addYears(8));

        // E-Liquids - Paciano V2
        $this->createInventory($pacianoV2->id, $dinnerLady, null, 35, 0, 10, 20, 70, now()->addMonths(20));
        $this->createInventory($pacianoV2->id, $naked, null, 30, 0, 10, 20, 60, now()->addMonths(18));
        $this->createInventory($pacianoV2->id, $coastal, null, 25, 0, 8, 15, 50, now()->addMonths(22));
        $this->createInventory($pacianoV2->id, $milkman, null, 22, 0, 8, 12, 45, now()->addMonths(21));
        $this->createInventory($pacianoV2->id, $glas, null, 20, 0, 5, 10, 40, now()->addMonths(23));
        $this->createInventory($pacianoV2->id, $ripe, null, 15, 0, 5, 8, 30, now()->addMonths(19));
        $this->createInventory($pacianoV2->id, $juiceHead, null, 25, 0, 8, 15, 50, now()->addMonths(20));
        $this->createInventory($pacianoV2->id, $cloudNurdz, null, 20, 0, 8, 12, 45, now()->addMonths(18));

        // =============================================
        // ========== STOCK TRANSFER DEMOS ==============
        // =============================================

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Creating Stock Transfer Demos...');
        $this->command->info('========================================');

        // Get flavors for transfers
        $xUltraFlavor = $xUltra ? $xUltra->flavors->where('name', 'Purple Twilight')->first() : null;
        $slimbarFlavor = $slimbar ? $slimbar->flavors->where('name', 'Strawberry Banana')->first() : null;
        $vanillaFlavor = $slimbar ? $slimbar->flavors->where('name', 'Vanilla Custard')->first() : null;

        // ===== DEMO 1: PENDING TRANSFER (Majada to Paciano for Slimbar) =====
        $pendingTransfer = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-PEND-001'],
            [
                'transfer_number' => 'TRF-PEND-001',
                'from_branch_id' => $majada->id,
                'to_branch_id' => $paciano->id,
                'product_id' => $slimbar ? $slimbar->id : null,
                'flavor_id' => $slimbarFlavor ? $slimbarFlavor->id : null,
                'quantity' => 20,
                'status' => 'pending',
                'requested_by' => $branchAdminPaciano ? $branchAdminPaciano->id : ($superAdmin ? $superAdmin->id : null),
                'notes' => 'Urgent: Paciano branch running low on Strawberry Banana Slimbar. Customer demand is high.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        if ($pendingTransfer) {
            $this->command->info('✓ Created PENDING transfer: Majada → Paciano (Slimbar - Strawberry Banana x20)');

            $sourceInventory = BranchInventory::where('branch_id', $majada->id)
                ->where('product_id', $slimbar ? $slimbar->id : null)
                ->where('flavor_id', $slimbarFlavor ? $slimbarFlavor->id : null)
                ->first();

            if ($sourceInventory) {
                $sourceInventory->update([
                    'reserved_quantity' => $sourceInventory->reserved_quantity + 20
                ]);
                $this->command->info('  → Reserved 20 units at Majada branch');
            }
        }

        // ===== DEMO 2: APPROVED TRANSFER (Majada to Asia1 for X-Vape Ultra) =====
        $xUltraFlavor2 = $xUltra ? $xUltra->flavors->where('name', 'Blueberry Ice')->first() : null;

        $approvedTransfer = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-APPR-001'],
            [
                'transfer_number' => 'TRF-APPR-001',
                'from_branch_id' => $majada->id,
                'to_branch_id' => $asia1->id,
                'product_id' => $xUltra ? $xUltra->id : null,
                'flavor_id' => $xUltraFlavor2 ? $xUltraFlavor2->id : null,
                'quantity' => 15,
                'status' => 'approved',
                'requested_by' => $branchAdminAsia1 ? $branchAdminAsia1->id : ($superAdmin ? $superAdmin->id : null),
                'approved_by' => $superAdmin ? $superAdmin->id : null,
                'approved_at' => now()->subDays(1),
                'notes' => 'Transfer approved by Super Admin. Ready for completion.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ]
        );

        if ($approvedTransfer) {
            $this->command->info('✓ Created APPROVED transfer: Majada → Asia1 (X-Vape Ultra - Blueberry Ice x15)');

            $sourceInventory = BranchInventory::where('branch_id', $majada->id)
                ->where('product_id', $xUltra ? $xUltra->id : null)
                ->where('flavor_id', $xUltraFlavor2 ? $xUltraFlavor2->id : null)
                ->first();

            if ($sourceInventory) {
                $sourceInventory->update([
                    'reserved_quantity' => $sourceInventory->reserved_quantity + 15
                ]);
                $this->command->info('  → Reserved 15 units at Majada branch');
            }
        }

        // ===== DEMO 3: COMPLETED TRANSFER (MCDC to Paciano V2 for RELX) =====
        $relxFlavor = $relx ? $relx->flavors->where('name', 'Fresh Mint')->first() : null;

        $completedTransfer = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-COMP-001'],
            [
                'transfer_number' => 'TRF-COMP-001',
                'from_branch_id' => $mcdc->id,
                'to_branch_id' => $pacianoV2->id,
                'product_id' => $relx ? $relx->id : null,
                'flavor_id' => $relxFlavor ? $relxFlavor->id : null,
                'quantity' => 10,
                'status' => 'completed',
                'requested_by' => $branchAdminPaciano ? $branchAdminPaciano->id : ($superAdmin ? $superAdmin->id : null),
                'approved_by' => $superAdmin ? $superAdmin->id : null,
                'approved_at' => now()->subDays(3),
                'completed_at' => now()->subDays(2),
                'notes' => 'Transfer completed successfully. Stock moved to Paciano V2.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2),
            ]
        );

        if ($completedTransfer) {
            $this->command->info('✓ Created COMPLETED transfer: MCDC → Paciano V2 (Relx Classic - Fresh Mint x10)');

            $sourceInventory = BranchInventory::where('branch_id', $mcdc->id)
                ->where('product_id', $relx ? $relx->id : null)
                ->where('flavor_id', $relxFlavor ? $relxFlavor->id : null)
                ->first();

            $destInventory = BranchInventory::where('branch_id', $pacianoV2->id)
                ->where('product_id', $relx ? $relx->id : null)
                ->where('flavor_id', $relxFlavor ? $relxFlavor->id : null)
                ->first();

            if ($sourceInventory) {
                $sourceInventory->update([
                    'quantity' => $sourceInventory->quantity - 10,
                    'reserved_quantity' => max(0, $sourceInventory->reserved_quantity - 10)
                ]);
                $this->command->info('  → Deducted 10 units from MCDC branch');
            }

            if ($destInventory) {
                $destInventory->update([
                    'quantity' => $destInventory->quantity + 10,
                    'last_restocked_at' => now(),
                ]);
                $this->command->info('  → Added 10 units to Paciano V2 branch');
            }
        }

        // ===== DEMO 4: CANCELLED TRANSFER (Asia1 to MCDC) =====
        $flumFlavor = $flumPebble ? $flumPebble->flavors->where('name', 'Aloe Grape')->first() : null;

        $cancelledTransfer = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-CANC-001'],
            [
                'transfer_number' => 'TRF-CANC-001',
                'from_branch_id' => $asia1->id,
                'to_branch_id' => $mcdc->id,
                'product_id' => $flumPebble ? $flumPebble->id : null,
                'flavor_id' => $flumFlavor ? $flumFlavor->id : null,
                'quantity' => 25,
                'status' => 'cancelled',
                'requested_by' => $branchAdminAsia1 ? $branchAdminAsia1->id : ($superAdmin ? $superAdmin->id : null),
                'notes' => 'Cancelled due to insufficient stock at source branch.',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(3),
            ]
        );

        if ($cancelledTransfer) {
            $this->command->info('✓ Created CANCELLED transfer: Asia1 → MCDC (Flum Pebble - Aloe Grape x25)');
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Stock Transfer Demos Created!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Transfer Summary:');
        $this->command->info('  📋 PENDING:    1 transfer (Majada→Paciano for Slimbar x20)');
        $this->command->info('  ✅ APPROVED:   1 transfer (Majada→Asia1 for X-Vape Ultra x15)');
        $this->command->info('  ✔️ COMPLETED:  1 transfer (MCDC→Paciano V2 for Relx x10)');
        $this->command->info('  ❌ CANCELLED:  1 transfer (Asia1→MCDC for Flum Pebble x25)');
        $this->command->info('');
        $this->command->info('Low Stock & Expiring Soon Demo Items:');
        $this->command->info('  ⚠️ Paciano Branch: Slimbar (Vanilla Custard) - Only 3 units left, EXPIRES in 1 month');
        $this->command->info('  ⚠️ Paciano Branch: Slimbar (Blueberry) - Only 8 units left, EXPIRES in 2 months');
        $this->command->info('  ⚠️ Paciano Branch: Slimbar (Peach Ice) - Only 5 units left, EXPIRES in 3 months');
        $this->command->info('  ⚠️ Paciano Branch: Flum Float (Mint) - Only 6 units left');
        $this->command->info('  ⚠️ Paciano Branch: Dragbar (Strawberry Ice) - Only 4 units left');
        $this->command->info('  ⚠️ Paciano Branch: RELX Infinity (Strawberry) - Only 7 units left');
        $this->command->info('  ⚠️ Paciano Branch: Uwell Caliburn G2 - Only 3 units left');
        $this->command->info('  ⚠️ Paciano Branch: Coastal Clouds - Only 8 units left, EXPIRES in 5 months');
        $this->command->info('  ⚠️ Paciano Branch: Charlie\'s Chalk Dust - Only 5 units left, EXPIRES in 4 months');
        $this->command->info('');
        $this->command->info('✅ No negative stock issues! All quantities are valid.');
        $this->command->info('');
    }

    /**
     * Helper function to create inventory with expiration date
     */
    private function createInventory($branchId, $product, $flavor, $quantity, $reserved, $lowStockThreshold, $reorderPoint, $optimalStock, $expirationDate = null)
    {
        if (!$product) {
            return;
        }

        // If no expiration date is provided, set a default based on product type
        if (!$expirationDate) {
            if ($product->type === 'liquid') {
                $expirationDate = now()->addMonths(18); // E-liquids: 1.5 years default
            } elseif ($product->type === 'disposable') {
                $expirationDate = now()->addMonths(24); // Disposables: 2 years default
            } elseif ($product->type === 'pod-system' || $product->type === 'mod') {
                $expirationDate = now()->addYears(8); // Hardware: 8 years default
            } elseif ($product->type === 'accessory' || $product->type === 'coil') {
                $expirationDate = now()->addYears(3); // Accessories/coils: 3 years default
            } else {
                $expirationDate = now()->addYears(2); // Default fallback
            }
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
                'expiration_date' => $expirationDate,
            ]
        );
    }
}
