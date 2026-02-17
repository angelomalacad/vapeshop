<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories (optional - be careful!)
        // Category::truncate();
        
        $categories = [
            // Main Categories
            [
                'name' => 'Disposable Vapes',
                'description' => 'Ready-to-use vape devices, no maintenance needed',
                'is_active' => true,
                'order' => 1,
                'children' => [
                    ['name' => 'X Ultra Series', 'description' => 'X Ultra disposable vapes', 'is_active' => true],
                    ['name' => 'Slimbar Series', 'description' => 'Slim and portable disposables', 'is_active' => true],
                    ['name' => 'High Puff Count', 'description' => 'Disposables with 5000+ puffs', 'is_active' => true],
                    ['name' => 'Standard Disposables', 'description' => 'Regular disposable vapes', 'is_active' => true],
                ]
            ],
            [
                'name' => 'Pod Systems',
                'description' => 'Refillable pod vapes and cartridges',
                'is_active' => true,
                'order' => 2,
                'children' => [
                    ['name' => 'Relx Pods', 'description' => 'Relx compatible pods and devices', 'is_active' => true],
                    ['name' => 'Closed Pod Systems', 'description' => 'Pre-filled pod systems', 'is_active' => true],
                    ['name' => 'Open Pod Systems', 'description' => 'Refillable pod devices', 'is_active' => true],
                    ['name' => 'Pod Replacement', 'description' => 'Replacement pods and cartridges', 'is_active' => true],
                ]
            ],
            [
                'name' => 'E-Liquids',
                'description' => 'Vape juices and e-liquids',
                'is_active' => true,
                'order' => 3,
                'children' => [
                    ['name' => 'Fruit Flavors', 'description' => 'Mango, grape, apple, and more', 'is_active' => true],
                    ['name' => 'Menthol/Ice', 'description' => 'Cool and refreshing flavors', 'is_active' => true],
                    ['name' => 'Dessert Flavors', 'description' => 'Sweet and creamy flavors', 'is_active' => true],
                    ['name' => 'Tobacco Flavors', 'description' => 'Classic tobacco blends', 'is_active' => true],
                    ['name' => 'High Nicotine', 'description' => 'Salt nic and high strength', 'is_active' => true],
                ]
            ],
            [
                'name' => 'Coils & Atomizers',
                'description' => 'Replacement coils and rebuildable atomizers',
                'is_active' => true,
                'order' => 4,
                'children' => [
                    ['name' => 'Mesh Coils', 'description' => 'Mesh coil replacements', 'is_active' => true],
                    ['name' => 'Standard Coils', 'description' => 'Regular wire coils', 'is_active' => true],
                    ['name' => 'RDA', 'description' => 'Rebuildable dripping atomizers', 'is_active' => true],
                    ['name' => 'RTA', 'description' => 'Rebuildable tank atomizers', 'is_active' => true],
                ]
            ],
            [
                'name' => 'Mods & Devices',
                'description' => 'Advanced vape mods and batteries',
                'is_active' => true,
                'order' => 5,
                'children' => [
                    ['name' => 'Box Mods', 'description' => 'High wattage box mods', 'is_active' => true],
                    ['name' => 'Starter Kits', 'description' => 'Complete beginner kits', 'is_active' => true],
                    ['name' => 'Mechanical Mods', 'description' => 'Unregulated devices', 'is_active' => true],
                    ['name' => 'Squonk Mods', 'description' => 'Bottom-feeding devices', 'is_active' => true],
                ]
            ],
            [
                'name' => 'Accessories',
                'description' => 'Vape accessories and parts',
                'is_active' => true,
                'order' => 6,
                'children' => [
                    ['name' => 'Batteries', 'description' => '18650, 21700 batteries', 'is_active' => true],
                    ['name' => 'Chargers', 'description' => 'Battery chargers', 'is_active' => true],
                    ['name' => 'Drip Tips', 'description' => 'Replacement mouthpieces', 'is_active' => true],
                    ['name' => 'Tanks & Glass', 'description' => 'Replacement tanks and glass tubes', 'is_active' => true],
                    ['name' => 'Tools & Kits', 'description' => 'Building tools and accessories', 'is_active' => true],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            // Extract children data
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);
            
            // Create parent category
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                $categoryData
            );
            
            // Create child categories
            foreach ($children as $childData) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($childData['name'])],
                    array_merge($childData, [
                        'parent_id' => $parent->id,
                        'order' => $childData['order'] ?? 0,
                    ])
                );
            }
        }

        $this->command->info('Categories seeded successfully!');
    }
}