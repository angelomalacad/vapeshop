<?php

namespace Database\Seeders;

use App\Models\WarehouseInventory;
use App\Models\Product;
use App\Models\ProductFlavor;
use Illuminate\Database\Seeder;

class WarehouseInventorySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Seeding Warehouse Inventories...');
        $this->command->info('========================================');

        // Get all products
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

        // Pod Systems
        $relx = Product::where('name', 'Relx Classic')->first();
        $relxInfinity = Product::where('name', 'Relx Infinity')->first();
        $caliburn = Product::where('name', 'Uwell Caliburn G2')->first();
        $xros = Product::where('name', 'Vaporesso XROS 3')->first();
        $oxva = Product::where('name', 'Oxva Xlim Pro')->first();

        // Box Mods
        $geekvape = Product::where('name', 'GeekVape Aegis Legend 2')->first();
        $voopoo = Product::where('name', 'VooPoo Drag 4')->first();
        $smok = Product::where('name', 'Smok RPM 5')->first();

        // E-Liquids (Popular in PH)
        $dinnerLady = Product::where('name', 'Dinner Lady - Lemon Tart')->first();
        $naked = Product::where('name', 'Naked 100 - Hawaiian POG')->first();
        $coastal = Product::where('name', 'Coastal Clouds - Blueberry Banana')->first();
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

        // Accessories
        $relxPods = Product::where('name', 'Relx Replacement Pods')->first();
        $caliburnCoils = Product::where('name', 'Caliburn G Coils (4-Pack)')->first();
        $batteries = Product::where('name', '18650 Battery')->first();
        $charger = Product::where('name', 'Dual Slot Battery Charger')->first();
        $dripTips = Product::where('name', '510 Drip Tips')->first();

        // =============================================
        // ========== WAREHOUSE INVENTORY ==============
        // =============================================

        // ===== DISPOSABLE VAPES =====

        // X-Vape Ultra (Best seller in PH)
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Purple Twilight')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 500, 20, 100, 380.00, now()->addMonths(24));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Blueberry Ice')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 450, 20, 100, 380.00, now()->addMonths(23));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Strawberry Watermelon')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 480, 20, 100, 380.00, now()->addMonths(24));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Mango Peach')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 400, 20, 100, 380.00, now()->addMonths(22));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 350, 20, 100, 380.00, now()->addMonths(24));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Grape Soda')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 420, 20, 100, 380.00, now()->addMonths(23));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Strawberry Kiwi')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 380, 20, 100, 380.00, now()->addMonths(24));

        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Pineapple Coconut')->first() : null;
        $this->createWarehouseInventory($xUltra, $flavor, 360, 20, 100, 380.00, now()->addMonths(22));

        // X-Vape Pro
        $flavor = $xPro ? $xPro->flavors->where('name', 'Mango Ice')->first() : null;
        $this->createWarehouseInventory($xPro, $flavor, 300, 15, 80, 460.00, now()->addMonths(24));

        $flavor = $xPro ? $xPro->flavors->where('name', 'Peach Ice Tea')->first() : null;
        $this->createWarehouseInventory($xPro, $flavor, 280, 15, 80, 460.00, now()->addMonths(23));

        $flavor = $xPro ? $xPro->flavors->where('name', 'Watermelon Ice')->first() : null;
        $this->createWarehouseInventory($xPro, $flavor, 320, 15, 80, 460.00, now()->addMonths(24));

        $flavor = $xPro ? $xPro->flavors->where('name', 'Double Apple')->first() : null;
        $this->createWarehouseInventory($xPro, $flavor, 250, 15, 80, 460.00, now()->addMonths(22));

        $flavor = $xPro ? $xPro->flavors->where('name', 'Strawberry Banana')->first() : null;
        $this->createWarehouseInventory($xPro, $flavor, 290, 15, 80, 460.00, now()->addMonths(24));

        // X-Vape Max (Premium)
        $flavor = $xMax ? $xMax->flavors->where('name', 'Blue Razz')->first() : null;
        $this->createWarehouseInventory($xMax, $flavor, 200, 10, 50, 540.00, now()->addMonths(24));

        $flavor = $xMax ? $xMax->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createWarehouseInventory($xMax, $flavor, 180, 10, 50, 540.00, now()->addMonths(23));

        $flavor = $xMax ? $xMax->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createWarehouseInventory($xMax, $flavor, 220, 10, 50, 540.00, now()->addMonths(24));

        $flavor = $xMax ? $xMax->flavors->where('name', 'Menthol')->first() : null;
        $this->createWarehouseInventory($xMax, $flavor, 150, 10, 50, 540.00, now()->addMonths(22));

        // Slimbar (Budget friendly, high volume)
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Strawberry Banana')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 800, 30, 150, 280.00, now()->addMonths(20));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Pineapple Coconut')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 650, 30, 150, 280.00, now()->addMonths(21));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Lychee Ice')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 700, 30, 150, 280.00, now()->addMonths(20));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Green Apple')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 550, 30, 150, 280.00, now()->addMonths(22));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Spearmint')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 600, 30, 150, 280.00, now()->addMonths(20));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Vanilla Custard')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 400, 30, 150, 280.00, now()->addMonths(19));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Blueberry')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 500, 30, 150, 280.00, now()->addMonths(21));

        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Peach Ice')->first() : null;
        $this->createWarehouseInventory($slimbar, $flavor, 580, 30, 150, 280.00, now()->addMonths(20));

        // Slimbar Max
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Mixed Berries')->first() : null;
        $this->createWarehouseInventory($slimbarMax, $flavor, 450, 20, 100, 350.00, now()->addMonths(22));

        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createWarehouseInventory($slimbarMax, $flavor, 400, 20, 100, 350.00, now()->addMonths(21));

        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Menthol')->first() : null;
        $this->createWarehouseInventory($slimbarMax, $flavor, 350, 20, 100, 350.00, now()->addMonths(22));

        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Cotton Candy')->first() : null;
        $this->createWarehouseInventory($slimbarMax, $flavor, 300, 20, 100, 350.00, now()->addMonths(20));

        // Flum Pebble (Popular in PH)
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Strawberry Banana')->first() : null;
        $this->createWarehouseInventory($flumPebble, $flavor, 350, 15, 80, 300.00, now()->addMonths(19));

        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Aloe Grape')->first() : null;
        $this->createWarehouseInventory($flumPebble, $flavor, 400, 15, 80, 300.00, now()->addMonths(20));

        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Blue Razz Ice')->first() : null;
        $this->createWarehouseInventory($flumPebble, $flavor, 380, 15, 80, 300.00, now()->addMonths(19));

        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Peach Ice')->first() : null;
        $this->createWarehouseInventory($flumPebble, $flavor, 320, 15, 80, 300.00, now()->addMonths(21));

        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Watermelon')->first() : null;
        $this->createWarehouseInventory($flumPebble, $flavor, 450, 15, 80, 300.00, now()->addMonths(20));

        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Mango')->first() : null;
        $this->createWarehouseInventory($flumPebble, $flavor, 300, 15, 80, 300.00, now()->addMonths(19));

        // Flum Float
        $flavor = $flumFloat ? $flumFloat->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createWarehouseInventory($flumFloat, $flavor, 250, 15, 60, 220.00, now()->addMonths(18));

        $flavor = $flumFloat ? $flumFloat->flavors->where('name', 'Mint')->first() : null;
        $this->createWarehouseInventory($flumFloat, $flavor, 200, 15, 60, 220.00, now()->addMonths(19));

        $flavor = $flumFloat ? $flumFloat->flavors->where('name', 'Grape')->first() : null;
        $this->createWarehouseInventory($flumFloat, $flavor, 280, 15, 60, 220.00, now()->addMonths(18));

        // Dragbar B5000
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Blue Razz')->first() : null;
        $this->createWarehouseInventory($dragbar, $flavor, 350, 15, 80, 330.00, now()->addMonths(18));

        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createWarehouseInventory($dragbar, $flavor, 300, 15, 80, 330.00, now()->addMonths(17));

        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createWarehouseInventory($dragbar, $flavor, 280, 15, 80, 330.00, now()->addMonths(18));

        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Watermelon')->first() : null;
        $this->createWarehouseInventory($dragbar, $flavor, 320, 15, 80, 330.00, now()->addMonths(17));

        // Dragbar F8000
        $flavor = $dragbarF8000 ? $dragbarF8000->flavors->where('name', 'Strawberry Kiwi')->first() : null;
        $this->createWarehouseInventory($dragbarF8000, $flavor, 250, 10, 60, 400.00, now()->addMonths(23));

        $flavor = $dragbarF8000 ? $dragbarF8000->flavors->where('name', 'Grape Ice')->first() : null;
        $this->createWarehouseInventory($dragbarF8000, $flavor, 220, 10, 60, 400.00, now()->addMonths(22));

        $flavor = $dragbarF8000 ? $dragbarF8000->flavors->where('name', 'Mango')->first() : null;
        $this->createWarehouseInventory($dragbarF8000, $flavor, 200, 10, 60, 400.00, now()->addMonths(23));

        // Elf Bar 600 (Entry level)
        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Blue Razz Lemonade')->first() : null;
        $this->createWarehouseInventory($elfBar, $flavor, 500, 25, 120, 200.00, now()->addMonths(15));

        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Strawberry Ice')->first() : null;
        $this->createWarehouseInventory($elfBar, $flavor, 450, 25, 120, 200.00, now()->addMonths(14));

        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Watermelon')->first() : null;
        $this->createWarehouseInventory($elfBar, $flavor, 480, 25, 120, 200.00, now()->addMonths(15));

        $flavor = $elfBar ? $elfBar->flavors->where('name', 'Mint')->first() : null;
        $this->createWarehouseInventory($elfBar, $flavor, 400, 25, 120, 200.00, now()->addMonths(14));

        // Lost Mary OS5000
        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Strawberry Pina Colada')->first() : null;
        $this->createWarehouseInventory($lostMary, $flavor, 300, 15, 70, 370.00, now()->addMonths(24));

        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Mary Dream')->first() : null;
        $this->createWarehouseInventory($lostMary, $flavor, 250, 15, 70, 370.00, now()->addMonths(23));

        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Blueberry')->first() : null;
        $this->createWarehouseInventory($lostMary, $flavor, 280, 15, 70, 370.00, now()->addMonths(24));

        $flavor = $lostMary ? $lostMary->flavors->where('name', 'Peach')->first() : null;
        $this->createWarehouseInventory($lostMary, $flavor, 220, 15, 70, 370.00, now()->addMonths(22));

        // HQD Cuvie Plus
        $flavor = $hqd ? $hqd->flavors->where('name', 'Strawberry')->first() : null;
        $this->createWarehouseInventory($hqd, $flavor, 350, 20, 100, 180.00, now()->addMonths(16));

        $flavor = $hqd ? $hqd->flavors->where('name', 'Mango')->first() : null;
        $this->createWarehouseInventory($hqd, $flavor, 300, 20, 100, 180.00, now()->addMonths(15));

        $flavor = $hqd ? $hqd->flavors->where('name', 'Mint')->first() : null;
        $this->createWarehouseInventory($hqd, $flavor, 280, 20, 100, 180.00, now()->addMonths(16));

        $flavor = $hqd ? $hqd->flavors->where('name', 'Grape')->first() : null;
        $this->createWarehouseInventory($hqd, $flavor, 320, 20, 100, 180.00, now()->addMonths(15));

        // ===== POD SYSTEMS =====

        // RELX Classic (Very popular in PH)
        $flavor = $relx ? $relx->flavors->where('name', 'Fresh Mint')->first() : null;
        $this->createWarehouseInventory($relx, $flavor, 200, 10, 50, 320.00, now()->addMonths(18));

        $flavor = $relx ? $relx->flavors->where('name', 'Watermelon')->first() : null;
        $this->createWarehouseInventory($relx, $flavor, 180, 10, 50, 320.00, now()->addMonths(17));

        $flavor = $relx ? $relx->flavors->where('name', 'Lychee')->first() : null;
        $this->createWarehouseInventory($relx, $flavor, 150, 10, 50, 320.00, now()->addMonths(18));

        $flavor = $relx ? $relx->flavors->where('name', 'Grape')->first() : null;
        $this->createWarehouseInventory($relx, $flavor, 160, 10, 50, 320.00, now()->addMonths(17));

        $flavor = $relx ? $relx->flavors->where('name', 'Peach')->first() : null;
        $this->createWarehouseInventory($relx, $flavor, 140, 10, 50, 320.00, now()->addMonths(16));

        // RELX Infinity
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createWarehouseInventory($relxInfinity, $flavor, 150, 8, 40, 480.00, now()->addMonths(18));

        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Strawberry')->first() : null;
        $this->createWarehouseInventory($relxInfinity, $flavor, 120, 8, 40, 480.00, now()->addMonths(17));

        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Mango')->first() : null;
        $this->createWarehouseInventory($relxInfinity, $flavor, 130, 8, 40, 480.00, now()->addMonths(18));

        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Pineapple')->first() : null;
        $this->createWarehouseInventory($relxInfinity, $flavor, 100, 8, 40, 480.00, now()->addMonths(16));

        // Uwell Caliburn G2 (Hardware - long shelf life)
        $this->createWarehouseInventory($caliburn, null, 80, 5, 20, 700.00, now()->addYears(8));

        // Vaporesso XROS 3
        $this->createWarehouseInventory($xros, null, 75, 5, 20, 750.00, now()->addYears(8));

        // Oxva Xlim Pro
        $this->createWarehouseInventory($oxva, null, 60, 5, 20, 820.00, now()->addYears(8));

        // ===== BOX MODS =====

        // GeekVape Aegis Legend 2
        $this->createWarehouseInventory($geekvape, null, 40, 3, 15, 2000.00, now()->addYears(8));

        // VooPoo Drag 4
        $this->createWarehouseInventory($voopoo, null, 35, 3, 15, 1850.00, now()->addYears(8));

        // Smok RPM 5
        $this->createWarehouseInventory($smok, null, 50, 3, 15, 1500.00, now()->addYears(8));

        // ===== E-LIQUIDS (Highest volume items) =====

        // Dinner Lady - Lemon Tart (Best seller)
        $this->createWarehouseInventory($dinnerLady, null, 600, 30, 150, 420.00, now()->addMonths(20));

        // Naked 100 - Hawaiian POG
        $this->createWarehouseInventory($naked, null, 550, 30, 150, 380.00, now()->addMonths(18));

        // Coastal Clouds - Blueberry Banana
        $this->createWarehouseInventory($coastal, null, 500, 25, 120, 440.00, now()->addMonths(22));

        // Monster Vape Labs - Jam Monster
        $this->createWarehouseInventory($monster, null, 400, 20, 100, 400.00, now()->addMonths(19));

        // The Milkman - Crumbleberry
        $this->createWarehouseInventory($milkman, null, 350, 20, 100, 460.00, now()->addMonths(21));

        // Pachamama - Fuji Apple
        $this->createWarehouseInventory($pachamama, null, 380, 20, 100, 410.00, now()->addMonths(17));

        // Ripe Vapes - VCT
        $this->createWarehouseInventory($ripe, null, 300, 15, 80, 490.00, now()->addMonths(23));

        // Charlie's Chalk Dust - PB&J
        $this->createWarehouseInventory($charlie, null, 320, 15, 80, 450.00, now()->addMonths(20));

        // Sadboy - Butter Cookie
        $this->createWarehouseInventory($sadboy, null, 360, 20, 100, 430.00, now()->addMonths(18));

        // Glas Basix - Banana Cream Pie
        $this->createWarehouseInventory($glas, null, 340, 15, 80, 460.00, now()->addMonths(22));

        // Twist - Pink Punch No. 1
        $this->createWarehouseInventory($twist, null, 420, 25, 120, 390.00, now()->addMonths(19));

        // Juice Head - Pineapple Grapefruit
        $this->createWarehouseInventory($juiceHead, null, 380, 20, 100, 410.00, now()->addMonths(21));

        // Cloud Nurdz - Watermelon Apple
        $this->createWarehouseInventory($cloudNurdz, null, 400, 20, 100, 420.00, now()->addMonths(20));

        // ===== ACCESSORIES =====

        // RELX Replacement Pods
        $this->createWarehouseInventory($relxPods, null, 500, 30, 150, 150.00, now()->addMonths(24));

        // Caliburn G Coils - 0.8ohm Mesh
        $flavor = $caliburnCoils ? $caliburnCoils->flavors->where('name', '0.8ohm Mesh')->first() : null;
        $this->createWarehouseInventory($caliburnCoils, $flavor, 400, 20, 100, 200.00, now()->addYears(3));

        // Caliburn G Coils - 1.0ohm Regular
        $flavor = $caliburnCoils ? $caliburnCoils->flavors->where('name', '1.0ohm Regular')->first() : null;
        $this->createWarehouseInventory($caliburnCoils, $flavor, 350, 20, 100, 200.00, now()->addYears(3));

        // 18650 Batteries
        $this->createWarehouseInventory($batteries, null, 300, 15, 80, 250.00, now()->addYears(5));

        // Dual Slot Battery Charger
        $this->createWarehouseInventory($charger, null, 150, 8, 40, 320.00, now()->addYears(8));

        // 510 Drip Tips
        $this->createWarehouseInventory($dripTips, null, 400, 20, 100, 70.00, now()->addYears(8));

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Warehouse Inventory Seeded Successfully!');
        $this->command->info('========================================');
        $this->command->info('Total Warehouse Records: ' . WarehouseInventory::count());
        $this->command->info('');

        // Summary by product category
        $disposableCount = WarehouseInventory::whereHas('product', function($q) {
            $q->where('type', 'disposable');
        })->count();

        $liquidCount = WarehouseInventory::whereHas('product', function($q) {
            $q->where('type', 'liquid');
        })->count();

        $hardwareCount = WarehouseInventory::whereHas('product', function($q) {
            $q->whereIn('type', ['pod-system', 'mod', 'accessory', 'coil']);
        })->count();

        $this->command->info('📦 Warehouse Stock Summary:');
        $this->command->info("   • Disposable Vapes: {$disposableCount} variants");
        $this->command->info("   • E-Liquids: {$liquidCount} variants");
        $this->command->info("   • Hardware/Accessories: {$hardwareCount} variants");
        $this->command->info('');
    }

    /**
     * Helper function to create warehouse inventory
     */
    private function createWarehouseInventory($product, $flavor, $quantity, $lowStockThreshold, $reorderPoint, $lastPurchasePrice, $expirationDate = null)
    {
        if (!$product) {
            return;
        }

        // If no expiration date is provided, set a default based on product type
        if (!$expirationDate) {
            if ($product->type === 'liquid') {
                $expirationDate = now()->addMonths(18);
            } elseif ($product->type === 'disposable') {
                $expirationDate = now()->addMonths(24);
            } elseif ($product->type === 'pod-system' || $product->type === 'mod') {
                $expirationDate = now()->addYears(8);
            } elseif ($product->type === 'accessory' || $product->type === 'coil') {
                $expirationDate = now()->addYears(3);
            } else {
                $expirationDate = now()->addYears(2);
            }
        }

        WarehouseInventory::updateOrCreate(
            [
                'product_id' => $product->id,
                'flavor_id' => $flavor ? $flavor->id : null,
            ],
            [
                'quantity' => $quantity,
                'low_stock_threshold' => $lowStockThreshold,
                'reorder_point' => $reorderPoint,
                'last_purchase_price' => $lastPurchasePrice,
                'last_restocked_at' => now(),
                'expiration_date' => $expirationDate,
            ]
        );
    }
}
