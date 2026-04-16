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

        // First, clear any existing stock movements or transfers for clean demo
        // (Optional: Uncomment if you want clean slate)
        // StockTransfer::truncate();
        
        // =============================================
        // ========== MAJADA OUT BRANCH =================
        // =============================================
        
        $this->command->info('Seeding Majada Out Branch...');
        
        // X-Vape Ultra - Majada
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Purple Twilight')->first() : null;
        $this->createInventory($majada->id, $xUltra, $flavor, 50, 0, 10, 20, 100);
        
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Blueberry Ice')->first() : null;
        $this->createInventory($majada->id, $xUltra, $flavor, 35, 0, 10, 20, 80);
        
        // X-Vape Pro - Majada
        $flavor = $xPro ? $xPro->flavors->where('name', 'Mango Ice')->first() : null;
        $this->createInventory($majada->id, $xPro, $flavor, 30, 0, 10, 15, 60);
        
        // Slimbar - Majada (HAS PLENTY OF STOCK)
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Strawberry Banana')->first() : null;
        $this->createInventory($majada->id, $slimbar, $flavor, 60, 0, 15, 25, 120);
        
        // Dragbar - Majada
        $flavor = $dragbar ? $dragbar->flavors->where('name', 'Blue Razz')->first() : null;
        $this->createInventory($majada->id, $dragbar, $flavor, 55, 0, 12, 25, 100);
        
        // RELX - Majada
        $flavor = $relx ? $relx->flavors->where('name', 'Fresh Mint')->first() : null;
        $this->createInventory($majada->id, $relx, $flavor, 25, 0, 8, 15, 50);
        
        // E-Liquids - Majada
        $this->createInventory($majada->id, $dinnerLady, null, 30, 0, 10, 20, 60);
        $this->createInventory($majada->id, $naked, null, 25, 0, 10, 20, 50);

        // =============================================
        // ========== ASIA 1 BRANCH ====================
        // =============================================
        
        $this->command->info('Seeding Asia 1 Branch...');
        
        // X-Vape Ultra - Asia1
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Strawberry Watermelon')->first() : null;
        $this->createInventory($asia1->id, $xUltra, $flavor, 45, 0, 10, 20, 90);
        
        // Slimbar Max - Asia1
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Mixed Berries')->first() : null;
        $this->createInventory($asia1->id, $slimbarMax, $flavor, 35, 0, 12, 20, 70);
        
        // Flum Pebble - Asia1
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Aloe Grape')->first() : null;
        $this->createInventory($asia1->id, $flumPebble, $flavor, 60, 0, 15, 25, 120);
        
        // RELX Infinity - Asia1
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Cool Mint')->first() : null;
        $this->createInventory($asia1->id, $relxInfinity, $flavor, 15, 0, 5, 10, 30);
        
        // E-Liquids - Asia1
        $this->createInventory($asia1->id, $coastal, null, 20, 0, 8, 15, 40);

        // =============================================
        // ========== MCDC BRANCH ======================
        // =============================================
        
        $this->command->info('Seeding MCDC Branch...');
        
        // X-Vape Ultra - MCDC
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Grape Soda')->first() : null;
        $this->createInventory($mcdc->id, $xUltra, $flavor, 55, 0, 10, 20, 110);
        
        // Slimbar - MCDC
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Spearmint')->first() : null;
        $this->createInventory($mcdc->id, $slimbar, $flavor, 70, 0, 15, 25, 140);
        
        // RELX - MCDC
        $flavor = $relx ? $relx->flavors->where('name', 'Lychee')->first() : null;
        $this->createInventory($mcdc->id, $relx, $flavor, 30, 0, 8, 15, 60);

        // =============================================
        // ========== PACIANO BRANCH (LOW STOCK) =======
        // =============================================
        
        $this->command->info('Seeding Paciano Branch (Low Stock for Demo)...');
        
        // X-Vape Ultra - Paciano
        $flavor = $xUltra ? $xUltra->flavors->where('name', 'Pineapple Coconut')->first() : null;
        $this->createInventory($paciano->id, $xUltra, $flavor, 40, 0, 10, 20, 80);
        
        // Slimbar - Paciano (LOW STOCK - NO RESERVED STOCK to avoid negative)
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Vanilla Custard')->first() : null;
        $this->createInventory($paciano->id, $slimbar, $flavor, 3, 0, 10, 15, 60);  // LOW STOCK! (3 units, 0 reserved)
        
        $flavor = $slimbar ? $slimbar->flavors->where('name', 'Blueberry')->first() : null;
        $this->createInventory($paciano->id, $slimbar, $flavor, 8, 0, 10, 15, 50);   // LOW STOCK! (8 units, 0 reserved)
        
        // Flum Pebble - Paciano
        $flavor = $flumPebble ? $flumPebble->flavors->where('name', 'Watermelon')->first() : null;
        $this->createInventory($paciano->id, $flumPebble, $flavor, 50, 0, 10, 20, 100);
        
        // RELX - Paciano
        $flavor = $relx ? $relx->flavors->where('name', 'Peach')->first() : null;
        $this->createInventory($paciano->id, $relx, $flavor, 22, 0, 8, 15, 45);

        // =============================================
        // ========== PACIANO V2 BRANCH ================
        // =============================================
        
        $this->command->info('Seeding Paciano V2 Branch...');
        
        // X-Vape Pro - Paciano V2
        $flavor = $xPro ? $xPro->flavors->where('name', 'Peach Ice Tea')->first() : null;
        $this->createInventory($pacianoV2->id, $xPro, $flavor, 45, 0, 10, 15, 90);
        
        // Slimbar Max - Paciano V2
        $flavor = $slimbarMax ? $slimbarMax->flavors->where('name', 'Peach Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $slimbarMax, $flavor, 30, 0, 12, 20, 60);
        
        // RELX Infinity - Paciano V2
        $flavor = $relxInfinity ? $relxInfinity->flavors->where('name', 'Mango')->first() : null;
        $this->createInventory($pacianoV2->id, $relxInfinity, $flavor, 20, 0, 5, 10, 40);
        
        // E-Liquids - Paciano V2
        $this->createInventory($pacianoV2->id, $dinnerLady, null, 35, 0, 10, 20, 70);
        $this->createInventory($pacianoV2->id, $naked, null, 30, 0, 10, 20, 60);

        // =============================================
        // ========== STOCK TRANSFER DEMOS ==============
        // =============================================
        
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Creating Stock Transfer Demos...');
        $this->command->info('========================================');
        
        // Get flavors for transfers
        $xUltraFlavor = $xUltra ? $xUltra->flavors->where('name', 'Purple Twilight')->first() : null;
        $slimbarFlavor = $slimbar ? $slimbar->flavors->where('name', 'Strawberry Banana')->first() : null; // Using Majada's stock
        $vanillaFlavor = $slimbar ? $slimbar->flavors->where('name', 'Vanilla Custard')->first() : null;
        
        // ===== DEMO 1: PENDING TRANSFER (Majada to Paciano for Slimbar) =====
        // This demonstrates a transfer request from branch with stock to branch with low stock
        
        $pendingTransfer = StockTransfer::updateOrCreate(
            ['transfer_number' => 'TRF-PEND-001'],
            [
                'transfer_number' => 'TRF-PEND-001',
                'from_branch_id' => $majada->id,  // Majada has plenty of stock (60 units)
                'to_branch_id' => $paciano->id,   // Paciano is low on stock (3 units)
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
            
            // Reserve stock at source branch (Majada)
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
            
            // Update inventory for completed transfer
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
        $this->command->info('Low Stock Demo Items:');
        $this->command->info('  ⚠️ Paciano Branch: Slimbar (Vanilla Custard) - Only 3 units left');
        $this->command->info('  ⚠️ Paciano Branch: Slimbar (Blueberry) - Only 8 units left');
        $this->command->info('');
        $this->command->info('✅ No negative stock issues! All quantities are valid.');
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