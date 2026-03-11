<?php
// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductFlavor;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // X-Vape Ultra
        $xUltra = Product::create([
            'name' => 'X-Vape Ultra',
            'sku' => 'X-ULTRA-10000',
            'description' => '10,000-puff rechargeable pod system featuring 10ml of e-liquid, a 650mAh battery, Type-C charging, and a smart display. Pocket-sized device designed for flavor with adjustable airflow and detachable cartridge system.',
            'brand' => 'X-Vape',
            'category' => 'Ultra',
            'type' => 'pod-system',
            'price' => 650.00,
            'cost' => 450.00,
            'puff_count' => 10000,
            'battery_capacity' => 650,
            'charging_type' => 'Type-C',
            'liquid_capacity' => 10,
            'nicotine_strength' => '10mg',
            'adjustable_airflow' => true,
            'smart_display' => true,
            'is_active' => true,
        ]);

        // X-Vape Ultra Flavors
        $ultraFlavors = [
            ['name' => 'Purple Twilight', 'code' => 'PT', 'category' => 'fruit', 'description' => 'Grapes'],
            ['name' => 'Wild Fragrance', 'code' => 'WF', 'category' => 'fruit', 'description' => 'Mixed Berries'],
            ['name' => 'Morning Garden', 'code' => 'MG', 'category' => 'fruit', 'description' => 'Strawberry'],
            ['name' => 'Summer Dew', 'code' => 'SD', 'category' => 'fruit', 'description' => 'Watermelon'],
            ['name' => 'Bubble Dream', 'code' => 'BD', 'category' => 'fruit', 'description' => 'Bubblegum'],
            ['name' => 'Violet Stream', 'code' => 'VS', 'category' => 'fruit', 'description' => 'Blackcurrant'],
            ['name' => 'Cold Breeze', 'code' => 'CB', 'category' => 'mint', 'description' => 'Menthol'],
            ['name' => 'Cloud Spring', 'code' => 'CS', 'category' => 'beverage', 'description' => 'Yakult'],
            ['name' => 'Purple Burst', 'code' => 'PB', 'category' => 'fruit', 'description' => 'Grape'],
            ['name' => 'Violet Eclipse', 'code' => 'VE', 'category' => 'dessert', 'description' => 'Taro Ice Cream'],
            ['name' => 'Blue Burst', 'code' => 'BB', 'category' => 'fruit', 'description' => 'Blueberry Ice'],
            ['name' => 'Golden Sunburst', 'code' => 'GS', 'category' => 'beverage', 'description' => 'Yakult'],
            ['name' => 'Rosy Breeze', 'code' => 'RB', 'category' => 'fruit', 'description' => 'Lychee Ice'],
            ['name' => 'Red Tropic Bubble', 'code' => 'RTB', 'category' => 'fruit', 'description' => 'Watermelon Bubble Gum'],
            ['name' => 'Red Tropic', 'code' => 'RT', 'category' => 'fruit', 'description' => 'Watermelon Ice'],
            ['name' => 'Gold Slice', 'code' => 'GOS', 'category' => 'fruit', 'description' => 'Mango Ice'],
            ['name' => 'Icy Heart', 'code' => 'IH', 'category' => 'fruit', 'description' => 'Strawberry Ice'],
            ['name' => 'Mystery Burst', 'code' => 'MB', 'category' => 'fruit', 'description' => 'Mixed Berries'],
            ['name' => 'Amber', 'code' => 'AM', 'category' => 'tobacco', 'description' => 'Tobacco'],
            ['name' => 'Purple Haze', 'code' => 'PH', 'category' => 'fruit', 'description' => 'Bubble Gum'],
        ];

        foreach ($ultraFlavors as $flavor) {
            $xUltra->flavors()->create($flavor);
        }

        // Slimbar
        $slimbar = Product::create([
            'name' => 'Slimbar',
            'sku' => 'SLIMBAR-POD',
            'description' => 'Sleek and portable pod system with various flavors.',
            'brand' => 'Slimbar',
            'category' => 'Slimbar',
            'type' => 'pod-system',
            'price' => 450.00,
            'cost' => 300.00,
            'is_active' => true,
        ]);

        // Slimbar Flavors (common flavors)
        $slimbarFlavors = [
            ['name' => 'Tangy Grape', 'code' => 'TG', 'category' => 'fruit'],
            ['name' => 'Ruby Raspberry', 'code' => 'RR', 'category' => 'fruit'],
            ['name' => 'Green Grape', 'code' => 'GG', 'category' => 'fruit'],
            ['name' => 'Fresh Peach', 'code' => 'FP', 'category' => 'fruit'],
            ['name' => 'Mango Orange', 'code' => 'MO', 'category' => 'fruit'],
            ['name' => 'Menthol', 'code' => 'MT', 'category' => 'mint'],
            ['name' => 'Lime Ice', 'code' => 'LI', 'category' => 'mint'],
            ['name' => 'Jasmine Longjing Tea', 'code' => 'JLT', 'category' => 'tea'],
            ['name' => 'Oolong Ice Tea', 'code' => 'OIT', 'category' => 'tea'],
            ['name' => 'Taro Scoop', 'code' => 'TS', 'category' => 'dessert'],
            ['name' => 'Lemon Zest', 'code' => 'LZ', 'category' => 'citrus'],
            ['name' => 'Dark Sparkle', 'code' => 'DS', 'category' => 'beverage'],
        ];

        foreach ($slimbarFlavors as $flavor) {
            $slimbar->flavors()->create($flavor);
        }

        // Relx
        $relx = Product::create([
            'name' => 'Relx',
            'sku' => 'RELX-POD',
            'description' => 'Popular pod system with various flavors.',
            'brand' => 'Relx',
            'category' => 'Relx',
            'type' => 'pod-system',
            'price' => 550.00,
            'cost' => 400.00,
            'is_active' => true,
        ]);

        // Relx Flavors
        $relxFlavors = [
            ['name' => 'Tangy Grape', 'code' => 'TG', 'category' => 'fruit'],
            ['name' => 'Ruby Raspberry', 'code' => 'RR', 'category' => 'fruit'],
            ['name' => 'Watermelon Ice', 'code' => 'WI', 'category' => 'fruit'],
            ['name' => 'Menthol', 'code' => 'MT', 'category' => 'mint'],
            ['name' => 'Lime Ice', 'code' => 'LI', 'category' => 'mint'],
            ['name' => 'Jasmine Longjing Tea', 'code' => 'JLT', 'category' => 'tea'],
            ['name' => 'Oolong Ice Tea', 'code' => 'OIT', 'category' => 'tea'],
            ['name' => 'Taro Scoop', 'code' => 'TS', 'category' => 'dessert'],
            ['name' => 'Lemon Zest', 'code' => 'LZ', 'category' => 'citrus'],
            ['name' => 'Dark Sparkle', 'code' => 'DS', 'category' => 'beverage'],
        ];

        foreach ($relxFlavors as $flavor) {
            $relx->flavors()->create($flavor);
        }

        $this->command->info('Products and flavors seeded successfully!');
    }
}