<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing categories first
        Category::query()->delete();
        
        $categories = [
            ['name' => 'Disposables', 'is_active' => true],
            ['name' => 'Pod Systems', 'is_active' => true],
            ['name' => 'Mod Kits', 'is_active' => true],
            ['name' => 'E-Liquids', 'is_active' => true],
            ['name' => 'Coils', 'is_active' => true],
            ['name' => 'Accessories', 'is_active' => true],
            ['name' => 'Batteries', 'is_active' => true],
            ['name' => 'Chargers', 'is_active' => true],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'is_active' => $categoryData['is_active']
            ]);
        }
        
        echo "Categories seeded successfully!\n";
    }
}