<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Disposable Vapes', 'slug' => 'disposable-vapes', 'order' => 1],
            ['name' => 'Pod Systems', 'slug' => 'pod-systems', 'order' => 2],
            ['name' => 'Box Mods', 'slug' => 'box-mods', 'order' => 3],
            ['name' => 'E-Liquids', 'slug' => 'e-liquids', 'order' => 4],
            ['name' => 'Coils', 'slug' => 'coils', 'order' => 5],
            ['name' => 'Accessories', 'slug' => 'accessories', 'order' => 6],
            ['name' => 'Batteries', 'slug' => 'batteries', 'order' => 7],
            ['name' => 'Starter Kits', 'slug' => 'starter-kits', 'order' => 8],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}