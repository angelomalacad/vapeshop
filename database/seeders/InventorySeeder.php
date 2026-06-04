<?php

namespace Database\Seeders;

use App\Models\BranchInventory;
use App\Models\Branch;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\StockMovement;
use App\Models\WarehouseInventory;
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
        $branchAdminPaciano = User::where('branch_id', $paciano->id)->where('role', 'branch_admin')->first();

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
        $relx = Product::where('name', 'Relx Classic')->first();
        $relxInfinity = Product::where('name', 'Relx Infinity')->first();
        
        // E-Liquids
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
        
        // Pod Systems & Hardware
        $caliburn = Product::where('name', 'Uwell Caliburn G2')->first();
        $xros = Product::where('name', 'Vaporesso XROS 3')->first();
        $oxva = Product::where('name', 'Oxva Xlim Pro')->first();
        $geekvape = Product::where('name', 'GeekVape Aegis Legend 2')->first();
        $voopoo = Product::where('name', 'VooPoo Drag 4')->first();
        $smok = Product::where('name', 'Smok RPM 5')->first();
        
        // Accessories
        $relxPods = Product::where('name', 'Relx Replacement Pods')->first();
        $caliburnCoils = Product::where('name', 'Caliburn G Coils (4-Pack)')->first();
        $batteries = Product::where('name', '18650 Battery')->first();
        $charger = Product::where('name', 'Dual Slot Battery Charger')->first();
        $dripTips = Product::where('name', '510 Drip Tips')->first();

        // =============================================
        // ========== SEED BRANCH INVENTORIES ==========
        // =============================================

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Seeding Branch Inventories...');
        $this->command->info('========================================');

        // ========== MAJADA BRANCH ==========
        $this->command->info('Seeding Majada Branch...');
        
        $this->createInventory($majada->id, $xUltra, $xUltra?->flavors->where('name', 'Purple Twilight')->first(), 50, 10, 20);
        $this->createInventory($majada->id, $xUltra, $xUltra?->flavors->where('name', 'Blueberry Ice')->first(), 35, 10, 20);
        $this->createInventory($majada->id, $xPro, $xPro?->flavors->where('name', 'Mango Ice')->first(), 30, 10, 15);
        $this->createInventory($majada->id, $xMax, $xMax?->flavors->where('name', 'Blue Razz')->first(), 25, 8, 15);
        $this->createInventory($majada->id, $slimbar, $slimbar?->flavors->where('name', 'Strawberry Banana')->first(), 60, 15, 25);
        $this->createInventory($majada->id, $slimbar, $slimbar?->flavors->where('name', 'Spearmint')->first(), 40, 15, 25);
        $this->createInventory($majada->id, $slimbarMax, $slimbarMax?->flavors->where('name', 'Mixed Berries')->first(), 35, 10, 20);
        $this->createInventory($majada->id, $dragbar, $dragbar?->flavors->where('name', 'Blue Razz')->first(), 55, 12, 25);
        $this->createInventory($majada->id, $dragbarF8000, $dragbarF8000?->flavors->where('name', 'Strawberry Kiwi')->first(), 30, 10, 20);
        $this->createInventory($majada->id, $flumPebble, $flumPebble?->flavors->where('name', 'Watermelon')->first(), 45, 12, 20);
        $this->createInventory($majada->id, $elfBar, $elfBar?->flavors->where('name', 'Blue Razz Lemonade')->first(), 50, 15, 25);
        $this->createInventory($majada->id, $lostMary, $lostMary?->flavors->where('name', 'Strawberry Pina Colada')->first(), 40, 10, 20);
        $this->createInventory($majada->id, $hqd, $hqd?->flavors->where('name', 'Strawberry')->first(), 35, 10, 15);
        $this->createInventory($majada->id, $relx, $relx?->flavors->where('name', 'Fresh Mint')->first(), 25, 8, 15);
        $this->createInventory($majada->id, $relx, $relx?->flavors->where('name', 'Watermelon')->first(), 20, 8, 15);
        $this->createInventory($majada->id, $relxInfinity, $relxInfinity?->flavors->where('name', 'Cool Mint')->first(), 15, 5, 10);
        
        // Hardware - Majada
        $this->createInventory($majada->id, $caliburn, null, 10, 3, 5);
        $this->createInventory($majada->id, $xros, null, 8, 3, 5);
        $this->createInventory($majada->id, $oxva, null, 12, 3, 5);
        $this->createInventory($majada->id, $geekvape, null, 5, 2, 3);
        $this->createInventory($majada->id, $voopoo, null, 6, 2, 3);
        $this->createInventory($majada->id, $smok, null, 8, 2, 4);
        
        // Accessories - Majada
        $this->createInventory($majada->id, $relxPods, null, 30, 10, 15);
        $this->createInventory($majada->id, $caliburnCoils, $caliburnCoils?->flavors->where('name', '0.8ohm Mesh')->first(), 40, 10, 20);
        $this->createInventory($majada->id, $batteries, null, 25, 8, 15);
        $this->createInventory($majada->id, $charger, null, 15, 5, 8);
        $this->createInventory($majada->id, $dripTips, null, 50, 15, 25);
        
        // E-Liquids - Majada
        $this->createInventory($majada->id, $dinnerLady, null, 30, 10, 20);
        $this->createInventory($majada->id, $naked, null, 25, 10, 20);
        $this->createInventory($majada->id, $coastal, null, 20, 8, 15);
        $this->createInventory($majada->id, $monster, null, 15, 5, 10);
        $this->createInventory($majada->id, $milkman, null, 18, 5, 10);
        $this->createInventory($majada->id, $pachamama, null, 22, 8, 15);
        $this->createInventory($majada->id, $ripe, null, 12, 5, 8);
        $this->createInventory($majada->id, $charlie, null, 16, 5, 10);
        $this->createInventory($majada->id, $sadboy, null, 20, 8, 12);
        $this->createInventory($majada->id, $glas, null, 14, 5, 10);
        $this->createInventory($majada->id, $twist, null, 25, 10, 15);
        $this->createInventory($majada->id, $juiceHead, null, 20, 8, 12);
        $this->createInventory($majada->id, $cloudNurdz, null, 22, 8, 15);

        // ========== ASIA 1 BRANCH ==========
        $this->command->info('Seeding Asia 1 Branch...');
        
        $this->createInventory($asia1->id, $xUltra, $xUltra?->flavors->where('name', 'Strawberry Watermelon')->first(), 45, 10, 20);
        $this->createInventory($asia1->id, $xUltra, $xUltra?->flavors->where('name', 'Mango Peach')->first(), 30, 10, 20);
        $this->createInventory($asia1->id, $xPro, $xPro?->flavors->where('name', 'Watermelon Ice')->first(), 25, 8, 15);
        $this->createInventory($asia1->id, $slimbarMax, $slimbarMax?->flavors->where('name', 'Mixed Berries')->first(), 35, 12, 20);
        $this->createInventory($asia1->id, $slimbar, $slimbar?->flavors->where('name', 'Lychee Ice')->first(), 40, 10, 20);
        $this->createInventory($asia1->id, $flumPebble, $flumPebble?->flavors->where('name', 'Aloe Grape')->first(), 60, 15, 25);
        $this->createInventory($asia1->id, $flumFloat, $flumFloat?->flavors->where('name', 'Strawberry Ice')->first(), 35, 10, 20);
        $this->createInventory($asia1->id, $dragbar, $dragbar?->flavors->where('name', 'Peach Mango')->first(), 30, 10, 20);
        $this->createInventory($asia1->id, $elfBar, $elfBar?->flavors->where('name', 'Watermelon')->first(), 45, 15, 25);
        $this->createInventory($asia1->id, $relxInfinity, $relxInfinity?->flavors->where('name', 'Cool Mint')->first(), 15, 5, 10);
        $this->createInventory($asia1->id, $caliburn, null, 8, 3, 5);
        $this->createInventory($asia1->id, $xros, null, 6, 2, 4);
        $this->createInventory($asia1->id, $relxPods, null, 20, 8, 12);
        $this->createInventory($asia1->id, $batteries, null, 15, 5, 10);
        $this->createInventory($asia1->id, $coastal, null, 20, 8, 15);
        $this->createInventory($asia1->id, $dinnerLady, null, 25, 10, 15);
        $this->createInventory($asia1->id, $naked, null, 20, 8, 12);
        $this->createInventory($asia1->id, $twist, null, 30, 10, 20);
        $this->createInventory($asia1->id, $juiceHead, null, 18, 5, 10);

        // ========== MCDC BRANCH ==========
        $this->command->info('Seeding MCDC Branch...');
        
        $this->createInventory($mcdc->id, $xUltra, $xUltra?->flavors->where('name', 'Grape Soda')->first(), 55, 10, 20);
        $this->createInventory($mcdc->id, $xUltra, $xUltra?->flavors->where('name', 'Cool Mint')->first(), 40, 10, 20);
        $this->createInventory($mcdc->id, $xMax, $xMax?->flavors->where('name', 'Peach Mango')->first(), 20, 5, 10);
        $this->createInventory($mcdc->id, $slimbar, $slimbar?->flavors->where('name', 'Spearmint')->first(), 70, 15, 25);
        $this->createInventory($mcdc->id, $slimbar, $slimbar?->flavors->where('name', 'Green Apple')->first(), 45, 10, 20);
        $this->createInventory($mcdc->id, $slimbarMax, $slimbarMax?->flavors->where('name', 'Menthol')->first(), 30, 10, 15);
        $this->createInventory($mcdc->id, $dragbarF8000, $dragbarF8000?->flavors->where('name', 'Grape Ice')->first(), 35, 10, 20);
        $this->createInventory($mcdc->id, $lostMary, $lostMary?->flavors->where('name', 'Blueberry')->first(), 30, 8, 15);
        $this->createInventory($mcdc->id, $relx, $relx?->flavors->where('name', 'Lychee')->first(), 30, 8, 15);
        $this->createInventory($mcdc->id, $relx, $relx?->flavors->where('name', 'Grape')->first(), 25, 8, 12);
        $this->createInventory($mcdc->id, $geekvape, null, 4, 2, 3);
        $this->createInventory($mcdc->id, $smok, null, 6, 2, 3);
        $this->createInventory($mcdc->id, $batteries, null, 20, 5, 10);
        $this->createInventory($mcdc->id, $dinnerLady, null, 28, 10, 15);
        $this->createInventory($mcdc->id, $monster, null, 20, 8, 12);
        $this->createInventory($mcdc->id, $sadboy, null, 25, 8, 15);
        $this->createInventory($mcdc->id, $pachamama, null, 18, 5, 10);

        // ========== PACIANO BRANCH (LOW STOCK) ==========
        $this->command->info('Seeding Paciano Branch (Low Stock Demo)...');
        
        $this->createInventory($paciano->id, $xUltra, $xUltra?->flavors->where('name', 'Pineapple Coconut')->first(), 40, 10, 20);
        $this->createInventory($paciano->id, $xUltra, $xUltra?->flavors->where('name', 'Strawberry Kiwi')->first(), 25, 10, 15);
        
        // LOW STOCK ITEMS
        $this->createInventory($paciano->id, $slimbar, $slimbar?->flavors->where('name', 'Vanilla Custard')->first(), 3, 10, 15);
        $this->createInventory($paciano->id, $slimbar, $slimbar?->flavors->where('name', 'Blueberry')->first(), 8, 10, 15);
        $this->createInventory($paciano->id, $slimbar, $slimbar?->flavors->where('name', 'Peach Ice')->first(), 5, 10, 15);
        
        $this->createInventory($paciano->id, $flumPebble, $flumPebble?->flavors->where('name', 'Watermelon')->first(), 50, 10, 20);
        $this->createInventory($paciano->id, $flumFloat, $flumFloat?->flavors->where('name', 'Mint')->first(), 6, 10, 15);
        $this->createInventory($paciano->id, $dragbar, $dragbar?->flavors->where('name', 'Strawberry Ice')->first(), 4, 10, 15);
        $this->createInventory($paciano->id, $relx, $relx?->flavors->where('name', 'Peach')->first(), 22, 8, 15);
        $this->createInventory($paciano->id, $relxInfinity, $relxInfinity?->flavors->where('name', 'Strawberry')->first(), 7, 5, 10);
        $this->createInventory($paciano->id, $caliburn, null, 3, 3, 5);
        $this->createInventory($paciano->id, $coastal, null, 8, 8, 12);
        $this->createInventory($paciano->id, $dinnerLady, null, 15, 10, 15);
        $this->createInventory($paciano->id, $charlie, null, 5, 5, 10);
        $this->createInventory($paciano->id, $cloudNurdz, null, 10, 8, 12);

        // ========== PACIANO V2 BRANCH ==========
        $this->command->info('Seeding Paciano V2 Branch...');
        
        $this->createInventory($pacianoV2->id, $xPro, $xPro?->flavors->where('name', 'Peach Ice Tea')->first(), 45, 10, 15);
        $this->createInventory($pacianoV2->id, $xPro, $xPro?->flavors->where('name', 'Double Apple')->first(), 30, 10, 15);
        $this->createInventory($pacianoV2->id, $xMax, $xMax?->flavors->where('name', 'Strawberry Ice')->first(), 25, 8, 15);
        $this->createInventory($pacianoV2->id, $slimbarMax, $slimbarMax?->flavors->where('name', 'Peach Mango')->first(), 30, 12, 20);
        $this->createInventory($pacianoV2->id, $slimbarMax, $slimbarMax?->flavors->where('name', 'Cotton Candy')->first(), 20, 10, 15);
        $this->createInventory($pacianoV2->id, $slimbar, $slimbar?->flavors->where('name', 'Pineapple Coconut')->first(), 35, 10, 20);
        $this->createInventory($pacianoV2->id, $flumPebble, $flumPebble?->flavors->where('name', 'Blue Razz Ice')->first(), 40, 12, 20);
        $this->createInventory($pacianoV2->id, $dragbar, $dragbar?->flavors->where('name', 'Watermelon')->first(), 30, 10, 15);
        $this->createInventory($pacianoV2->id, $hqd, $hqd?->flavors->where('name', 'Mango')->first(), 35, 10, 20);
        $this->createInventory($pacianoV2->id, $relxInfinity, $relxInfinity?->flavors->where('name', 'Mango')->first(), 20, 5, 10);
        $this->createInventory($pacianoV2->id, $relxInfinity, $relxInfinity?->flavors->where('name', 'Pineapple')->first(), 15, 5, 10);
        $this->createInventory($pacianoV2->id, $oxva, null, 10, 3, 5);
        $this->createInventory($pacianoV2->id, $xros, null, 8, 3, 5);
        $this->createInventory($pacianoV2->id, $voopoo, null, 5, 2, 3);
        $this->createInventory($pacianoV2->id, $relxPods, null, 25, 8, 12);
        $this->createInventory($pacianoV2->id, $charger, null, 10, 3, 5);
        $this->createInventory($pacianoV2->id, $dinnerLady, null, 35, 10, 20);
        $this->createInventory($pacianoV2->id, $naked, null, 30, 10, 20);
        $this->createInventory($pacianoV2->id, $coastal, null, 25, 8, 15);
        $this->createInventory($pacianoV2->id, $milkman, null, 22, 8, 12);
        $this->createInventory($pacianoV2->id, $glas, null, 20, 5, 10);
        $this->createInventory($pacianoV2->id, $ripe, null, 15, 5, 8);
        $this->createInventory($pacianoV2->id, $juiceHead, null, 25, 8, 15);
        $this->createInventory($pacianoV2->id, $cloudNurdz, null, 20, 8, 12);

        // =============================================
        // ========== STOCK TRANSFER DEMOS ==============
        // =============================================

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Creating Stock Transfer Demos...');
        $this->command->info('========================================');

        // Get flavors for transfers
        $slimbarFlavor = $slimbar ? $slimbar->flavors->where('name', 'Strawberry Banana')->first() : null;
        $xUltraFlavor = $xUltra ? $xUltra->flavors->where('name', 'Blueberry Ice')->first() : null;
        $relxFlavor = $relx ? $relx->flavors->where('name', 'Fresh Mint')->first() : null;
        $flumFlavor = $flumPebble ? $flumPebble->flavors->where('name', 'Aloe Grape')->first() : null;

        // PENDING TRANSFER
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
                'notes' => 'Urgent: Paciano branch running low on Strawberry Banana Slimbar.',
                'created_at' => now(),
            ]
        );

        if ($pendingTransfer) {
            $this->command->info('✓ Created PENDING transfer: Majada → Paciano (Slimbar x20)');
        }

        // APPROVED TRANSFER
        $approvedTransfer = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-APPR-001'],
            [
                'transfer_number' => 'TRF-APPR-001',
                'from_branch_id' => $majada->id,
                'to_branch_id' => $asia1->id,
                'product_id' => $xUltra ? $xUltra->id : null,
                'flavor_id' => $xUltraFlavor ? $xUltraFlavor->id : null,
                'quantity' => 15,
                'status' => 'approved',
                'requested_by' => $superAdmin ? $superAdmin->id : null,
                'approved_by' => $superAdmin ? $superAdmin->id : null,
                'approved_at' => now()->subDays(1),
                'notes' => 'Transfer approved by Super Admin.',
                'created_at' => now()->subDays(2),
            ]
        );

        if ($approvedTransfer) {
            $this->command->info('✓ Created APPROVED transfer: Majada → Asia1 (X-Vape Ultra x15)');
        }

        // COMPLETED TRANSFER
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
                'requested_by' => $superAdmin ? $superAdmin->id : null,
                'approved_by' => $superAdmin ? $superAdmin->id : null,
                'approved_at' => now()->subDays(3),
                'completed_at' => now()->subDays(2),
                'notes' => 'Transfer completed successfully.',
                'created_at' => now()->subDays(5),
            ]
        );

        if ($completedTransfer) {
            $this->command->info('✓ Created COMPLETED transfer: MCDC → Paciano V2 (Relx Classic x10)');
            
            // Update source inventory
            $sourceInventory = BranchInventory::where('branch_id', $mcdc->id)
                ->where('product_id', $relx ? $relx->id : null)
                ->where('flavor_id', $relxFlavor ? $relxFlavor->id : null)
                ->first();
                
            if ($sourceInventory) {
                $oldQty = $sourceInventory->quantity;
                $sourceInventory->update(['quantity' => $oldQty - 10]);
                
                StockMovement::create([
                    'branch_id' => $mcdc->id,
                    'product_id' => $relx ? $relx->id : null,
                    'flavor_id' => $relxFlavor ? $relxFlavor->id : null,
                    'previous_quantity' => $oldQty,
                    'new_quantity' => $oldQty - 10,
                    'quantity_change' => -10,
                    'movement_type' => 'transfer_out',
                    'reference_type' => 'transfer',
                    'reference_id' => $completedTransfer->id,
                    'notes' => 'Stock transferred out to Paciano V2',
                    'created_by' => $superAdmin ? $superAdmin->id : null,
                ]);
            }
            
            // Update destination inventory
            $destInventory = BranchInventory::where('branch_id', $pacianoV2->id)
                ->where('product_id', $relx ? $relx->id : null)
                ->where('flavor_id', $relxFlavor ? $relxFlavor->id : null)
                ->first();
                
            if ($destInventory) {
                $oldQty = $destInventory->quantity;
                $destInventory->update(['quantity' => $oldQty + 10, 'last_restocked_at' => now()]);
                
                StockMovement::create([
                    'branch_id' => $pacianoV2->id,
                    'product_id' => $relx ? $relx->id : null,
                    'flavor_id' => $relxFlavor ? $relxFlavor->id : null,
                    'previous_quantity' => $oldQty,
                    'new_quantity' => $oldQty + 10,
                    'quantity_change' => 10,
                    'movement_type' => 'transfer_in',
                    'reference_type' => 'transfer',
                    'reference_id' => $completedTransfer->id,
                    'notes' => 'Stock received from MCDC branch',
                    'created_by' => $superAdmin ? $superAdmin->id : null,
                ]);
            }
        }

        // CANCELLED TRANSFER
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
                'requested_by' => $superAdmin ? $superAdmin->id : null,
                'notes' => 'Cancelled due to insufficient stock at source branch.',
                'created_at' => now()->subDays(4),
            ]
        );

        if ($cancelledTransfer) {
            $this->command->info('✓ Created CANCELLED transfer: Asia1 → MCDC (Flum Pebble x25)');
        }

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✅ Seeding Completed Successfully!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('  🏪 Branch Inventory: ' . BranchInventory::count() . ' items seeded');
        $this->command->info('  📦 Stock Transfers: ' . StockTransfer::count() . ' transfers created');
        $this->command->info('  📊 Stock Movements: ' . StockMovement::count() . ' movements logged');
        $this->command->info('');
        $this->command->info('Low Stock Items (Paciano Branch):');
        $this->command->info('  ⚠️ Slimbar (Vanilla Custard) - Only 3 units left');
        $this->command->info('  ⚠️ Slimbar (Blueberry) - Only 8 units left');
        $this->command->info('  ⚠️ Slimbar (Peach Ice) - Only 5 units left');
        $this->command->info('  ⚠️ Flum Float (Mint) - Only 6 units left');
        $this->command->info('  ⚠️ Dragbar (Strawberry Ice) - Only 4 units left');
        $this->command->info('  ⚠️ RELX Infinity (Strawberry) - Only 7 units left');
        $this->command->info('  ⚠️ Uwell Caliburn G2 - Only 3 units left');
        $this->command->info('  ⚠️ Coastal Clouds - Only 8 units left');
        $this->command->info('  ⚠️ Charlie\'s Chalk Dust - Only 5 units left');
        $this->command->info('');
    }

    /**
     * Helper function to create inventory
     */
    private function createInventory($branchId, $product, $flavor, $quantity, $lowStockThreshold, $reorderPoint)
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
                'reserved_quantity' => 0,
                'low_stock_threshold' => $lowStockThreshold,
                'reorder_point' => $reorderPoint,
                'optimal_stock' => $reorderPoint * 2,
                'last_restocked_at' => now(),
            ]
        );
    }
}