<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductFlavor;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // =============================================
        // ========== DISPOSABLE VAPES =================
        // =============================================

        // 1. X-Vape Ultra
        $xUltra = Product::updateOrCreate(
            ['name' => 'X-Vape Ultra'],  // Changed from 'sku' to 'name'
            [
                'name' => 'X-Vape Ultra',
                'brand' => 'X-Vape',
                'description' => 'High-performance disposable vape with 10,000 puffs, 650mAh battery, and Type-C charging. Features smart display and adjustable airflow.',
                'category' => 'Ultra',
                'type' => 'disposable',
                'price' => 450.00,
                'cost' => 380.00,
                'puff_count' => 10000,
                'battery_capacity' => 650,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 10,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 1,
                'is_active' => 1,
            ]
        );

        $xUltra->flavors()->delete();
        $xUltraFlavors = [
            ['name' => 'Purple Twilight', 'code' => 'PT', 'category' => 'fruit', 'description' => 'Sweet grape and berry blend'],
            ['name' => 'Blueberry Ice', 'code' => 'BI', 'category' => 'fruit', 'description' => 'Fresh blueberries with cooling ice'],
            ['name' => 'Strawberry Watermelon', 'code' => 'SW', 'category' => 'fruit', 'description' => 'Juicy strawberry and watermelon mix'],
            ['name' => 'Mango Peach', 'code' => 'MP', 'category' => 'fruit', 'description' => 'Tropical mango and sweet peach'],
            ['name' => 'Cool Mint', 'code' => 'CM', 'category' => 'mint', 'description' => 'Refreshing minty blast'],
            ['name' => 'Grape Soda', 'code' => 'GS', 'category' => 'fruit', 'description' => 'Fizzy grape soda flavor'],
            ['name' => 'Lush Ice', 'code' => 'LI', 'category' => 'fruit', 'description' => 'Watermelon with icy finish'],
            ['name' => 'Cola Ice', 'code' => 'CI', 'category' => 'beverage', 'description' => 'Classic cola with ice'],
            ['name' => 'Strawberry Kiwi', 'code' => 'SK', 'category' => 'fruit', 'description' => 'Sweet strawberry and tangy kiwi'],
            ['name' => 'Pineapple Coconut', 'code' => 'PC', 'category' => 'fruit', 'description' => 'Tropical pineapple and coconut'],
        ];
        foreach ($xUltraFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $xUltra->id, 'is_active' => 1]));
        }

        // 2. X-Vape Pro
        $xPro = Product::updateOrCreate(
            ['name' => 'X-Vape Pro'],
            [
                'name' => 'X-Vape Pro',
                'brand' => 'X-Vape',
                'description' => 'Premium disposable with 15,000 puffs, 800mAh battery, and LED display.',
                'category' => 'Ultra',
                'type' => 'disposable',
                'price' => 550.00,
                'cost' => 460.00,
                'puff_count' => 15000,
                'battery_capacity' => 800,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 12,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 1,
                'is_active' => 1,
            ]
        );

        $xPro->flavors()->delete();
        $xProFlavors = [
            ['name' => 'Mango Ice', 'code' => 'MI', 'category' => 'fruit', 'description' => 'Sweet mango with icy finish'],
            ['name' => 'Peach Ice Tea', 'code' => 'PIT', 'category' => 'tea', 'description' => 'Peach flavored iced tea'],
            ['name' => 'Watermelon Ice', 'code' => 'WI', 'category' => 'fruit', 'description' => 'Fresh watermelon with cooling effect'],
            ['name' => 'Double Apple', 'code' => 'DA', 'category' => 'fruit', 'description' => 'Red and green apple blend'],
            ['name' => 'Grape Ice', 'code' => 'GI', 'category' => 'fruit', 'description' => 'Grape with icy finish'],
            ['name' => 'Strawberry Banana', 'code' => 'SB', 'category' => 'fruit', 'description' => 'Creamy strawberry banana'],
        ];
        foreach ($xProFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $xPro->id, 'is_active' => 1]));
        }

        // 3. X-Vape Max
        $xMax = Product::updateOrCreate(
            ['name' => 'X-Vape Max'],
            [
                'name' => 'X-Vape Max',
                'brand' => 'X-Vape',
                'description' => 'Ultimate disposable with 20,000 puffs, dual mesh coil, and digital screen.',
                'category' => 'Ultra',
                'type' => 'disposable',
                'price' => 650.00,
                'cost' => 540.00,
                'puff_count' => 20000,
                'battery_capacity' => 1000,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 15,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 1,
                'is_active' => 1,
            ]
        );

        $xMax->flavors()->delete();
        $xMaxFlavors = [
            ['name' => 'Blue Razz', 'code' => 'BR', 'category' => 'fruit', 'description' => 'Sweet blue raspberry candy'],
            ['name' => 'Peach Mango', 'code' => 'PM', 'category' => 'fruit', 'description' => 'Peach and mango tropical blend'],
            ['name' => 'Strawberry Ice', 'code' => 'SI', 'category' => 'fruit', 'description' => 'Strawberry with cooling ice'],
            ['name' => 'Menthol', 'code' => 'MT', 'category' => 'mint', 'description' => 'Strong menthol blast'],
        ];
        foreach ($xMaxFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $xMax->id, 'is_active' => 1]));
        }

        // 4. Slimbar
        $slimbar = Product::updateOrCreate(
            ['name' => 'Slimbar'],
            [
                'name' => 'Slimbar',
                'brand' => 'Slimbar',
                'description' => 'Sleek and portable disposable vape with 5,000 puffs. Perfect for on-the-go vaping.',
                'category' => 'Slimbar',
                'type' => 'disposable',
                'price' => 350.00,
                'cost' => 280.00,
                'puff_count' => 5000,
                'battery_capacity' => 500,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 8,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $slimbar->flavors()->delete();
        $slimbarFlavors = [
            ['name' => 'Strawberry Banana', 'code' => 'SB', 'category' => 'fruit', 'description' => 'Sweet strawberry and creamy banana'],
            ['name' => 'Pineapple Coconut', 'code' => 'PC', 'category' => 'fruit', 'description' => 'Tropical pineapple and coconut'],
            ['name' => 'Lychee Ice', 'code' => 'LI', 'category' => 'fruit', 'description' => 'Sweet lychee with icy finish'],
            ['name' => 'Green Apple', 'code' => 'GA', 'category' => 'fruit', 'description' => 'Tart green apple flavor'],
            ['name' => 'Spearmint', 'code' => 'SM', 'category' => 'mint', 'description' => 'Fresh spearmint'],
            ['name' => 'Vanilla Custard', 'code' => 'VC', 'category' => 'dessert', 'description' => 'Creamy vanilla custard'],
            ['name' => 'Blueberry', 'code' => 'BB', 'category' => 'fruit', 'description' => 'Fresh blueberry flavor'],
            ['name' => 'Peach Ice', 'code' => 'PI', 'category' => 'fruit', 'description' => 'Peach with cooling finish'],
        ];
        foreach ($slimbarFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $slimbar->id, 'is_active' => 1]));
        }

        // 5. Slimbar Max
        $slimbarMax = Product::updateOrCreate(
            ['name' => 'Slimbar Max'],
            [
                'name' => 'Slimbar Max',
                'brand' => 'Slimbar',
                'description' => 'Extended version with 8,000 puffs and larger battery.',
                'category' => 'Slimbar',
                'type' => 'disposable',
                'price' => 420.00,
                'cost' => 350.00,
                'puff_count' => 8000,
                'battery_capacity' => 600,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 10,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $slimbarMax->flavors()->delete();
        $slimbarMaxFlavors = [
            ['name' => 'Mixed Berries', 'code' => 'MB', 'category' => 'fruit', 'description' => 'Berry medley'],
            ['name' => 'Peach Mango', 'code' => 'PM', 'category' => 'fruit', 'description' => 'Peach and mango blend'],
            ['name' => 'Menthol', 'code' => 'MT', 'category' => 'mint', 'description' => 'Cool menthol'],
            ['name' => 'Cotton Candy', 'code' => 'CC', 'category' => 'dessert', 'description' => 'Sweet cotton candy'],
        ];
        foreach ($slimbarMaxFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $slimbarMax->id, 'is_active' => 1]));
        }

        // 6. Flum Pebble
        $flumPebble = Product::updateOrCreate(
            ['name' => 'Flum Pebble'],
            [
                'name' => 'Flum Pebble',
                'brand' => 'Flum',
                'description' => 'Compact disposable with 6,000 puffs. Ergonomic pebble design.',
                'category' => 'Disposable',
                'type' => 'disposable',
                'price' => 380.00,
                'cost' => 300.00,
                'puff_count' => 6000,
                'battery_capacity' => 600,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 8,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $flumPebble->flavors()->delete();
        $flumPebbleFlavors = [
            ['name' => 'Strawberry Banana', 'code' => 'SB', 'category' => 'fruit', 'description' => 'Strawberry banana smoothie'],
            ['name' => 'Aloe Grape', 'code' => 'AG', 'category' => 'fruit', 'description' => 'Aloe vera and grape'],
            ['name' => 'Blue Razz Ice', 'code' => 'BRI', 'category' => 'fruit', 'description' => 'Blue raspberry with ice'],
            ['name' => 'Peach Ice', 'code' => 'PI', 'category' => 'fruit', 'description' => 'Peach with cooling finish'],
            ['name' => 'Spearmint', 'code' => 'SM', 'category' => 'mint', 'description' => 'Fresh spearmint'],
            ['name' => 'Watermelon', 'code' => 'WM', 'category' => 'fruit', 'description' => 'Juicy watermelon'],
            ['name' => 'Mango', 'code' => 'MG', 'category' => 'fruit', 'description' => 'Sweet mango'],
            ['name' => 'Lychee', 'code' => 'LC', 'category' => 'fruit', 'description' => 'Sweet lychee'],
        ];
        foreach ($flumPebbleFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $flumPebble->id, 'is_active' => 1]));
        }

        // 7. Flum Float
        $flumFloat = Product::updateOrCreate(
            ['name' => 'Flum Float'],
            [
                'name' => 'Flum Float',
                'brand' => 'Flum',
                'description' => 'Smooth rounded design with 3,000 puffs. Perfect for beginners.',
                'category' => 'Disposable',
                'type' => 'disposable',
                'price' => 280.00,
                'cost' => 220.00,
                'puff_count' => 3000,
                'battery_capacity' => 450,
                'charging_type' => 'Micro-USB',
                'liquid_capacity' => 6,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $flumFloat->flavors()->delete();
        $flumFloatFlavors = [
            ['name' => 'Strawberry Ice', 'code' => 'SI', 'category' => 'fruit', 'description' => 'Strawberry with ice'],
            ['name' => 'Mint', 'code' => 'MN', 'category' => 'mint', 'description' => 'Fresh mint'],
            ['name' => 'Grape', 'code' => 'GP', 'category' => 'fruit', 'description' => 'Sweet grape'],
        ];
        foreach ($flumFloatFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $flumFloat->id, 'is_active' => 1]));
        }

        // 8. Dragbar B5000
        $dragbar = Product::updateOrCreate(
            ['name' => 'Dragbar B5000'],
            [
                'name' => 'Dragbar B5000',
                'brand' => 'ZOVOO',
                'description' => 'Popular box-type disposable. Mesh Coil delivers exquisite flavors.',
                'category' => 'Disposable',
                'type' => 'disposable',
                'price' => 400.00,
                'cost' => 330.00,
                'puff_count' => 5000,
                'battery_capacity' => 550,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 10,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $dragbar->flavors()->delete();
        $dragbarFlavors = [
            ['name' => 'Blue Razz', 'code' => 'BR', 'category' => 'fruit', 'description' => 'Blue raspberry candy'],
            ['name' => 'Strawberry Ice', 'code' => 'SI', 'category' => 'fruit', 'description' => 'Strawberry with ice'],
            ['name' => 'Mint', 'code' => 'MINT', 'category' => 'mint', 'description' => 'Fresh mint'],
            ['name' => 'Peach Mango', 'code' => 'PM', 'category' => 'fruit', 'description' => 'Peach and mango'],
            ['name' => 'Watermelon', 'code' => 'WM', 'category' => 'fruit', 'description' => 'Juicy watermelon'],
        ];
        foreach ($dragbarFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $dragbar->id, 'is_active' => 1]));
        }

        // 9. Dragbar F8000
        $dragbarF8000 = Product::updateOrCreate(
            ['name' => 'Dragbar F8000'],
            [
                'name' => 'Dragbar F8000',
                'brand' => 'ZOVOO',
                'description' => 'High-capacity disposable with 8,000 puffs and adjustable airflow.',
                'category' => 'Disposable',
                'type' => 'disposable',
                'price' => 480.00,
                'cost' => 400.00,
                'puff_count' => 8000,
                'battery_capacity' => 650,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 12,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $dragbarF8000->flavors()->delete();
        $dragbarF8000Flavors = [
            ['name' => 'Strawberry Kiwi', 'code' => 'SK', 'category' => 'fruit', 'description' => 'Strawberry and kiwi'],
            ['name' => 'Grape Ice', 'code' => 'GI', 'category' => 'fruit', 'description' => 'Grape with ice'],
            ['name' => 'Mango', 'code' => 'MG', 'category' => 'fruit', 'description' => 'Sweet mango'],
            ['name' => 'Cool Mint', 'code' => 'CM', 'category' => 'mint', 'description' => 'Cool mint'],
        ];
        foreach ($dragbarF8000Flavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $dragbarF8000->id, 'is_active' => 1]));
        }

        // 10. Elf Bar 600
        $elfBar = Product::updateOrCreate(
            ['name' => 'Elf Bar 600'],
            [
                'name' => 'Elf Bar 600',
                'brand' => 'Elf Bar',
                'description' => 'World-famous disposable with 600 puffs. Compact and reliable.',
                'category' => 'Disposable',
                'type' => 'disposable',
                'price' => 250.00,
                'cost' => 200.00,
                'puff_count' => 600,
                'battery_capacity' => 350,
                'charging_type' => 'Micro-USB',
                'liquid_capacity' => 2,
                'nicotine_strength' => '20mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $elfBar->flavors()->delete();
        $elfBarFlavors = [
            ['name' => 'Blue Razz Lemonade', 'code' => 'BRL', 'category' => 'fruit', 'description' => 'Blue raspberry lemonade'],
            ['name' => 'Strawberry Ice', 'code' => 'SI', 'category' => 'fruit', 'description' => 'Strawberry with ice'],
            ['name' => 'Watermelon', 'code' => 'WM', 'category' => 'fruit', 'description' => 'Juicy watermelon'],
            ['name' => 'Mint', 'code' => 'MN', 'category' => 'mint', 'description' => 'Fresh mint'],
        ];
        foreach ($elfBarFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $elfBar->id, 'is_active' => 1]));
        }

        // 11. Lost Mary OS5000
        $lostMary = Product::updateOrCreate(
            ['name' => 'Lost Mary OS5000'],
            [
                'name' => 'Lost Mary OS5000',
                'brand' => 'Lost Mary',
                'description' => 'Premium disposable from the makers of Elf Bar. 5,000 puffs.',
                'category' => 'Disposable',
                'type' => 'disposable',
                'price' => 450.00,
                'cost' => 370.00,
                'puff_count' => 5000,
                'battery_capacity' => 550,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 10,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $lostMary->flavors()->delete();
        $lostMaryFlavors = [
            ['name' => 'Strawberry Pina Colada', 'code' => 'SPC', 'category' => 'fruit', 'description' => 'Strawberry pina colada'],
            ['name' => 'Mary Dream', 'code' => 'MD', 'category' => 'dessert', 'description' => 'Creamy dessert blend'],
            ['name' => 'Blueberry', 'code' => 'BB', 'category' => 'fruit', 'description' => 'Fresh blueberry'],
            ['name' => 'Peach', 'code' => 'PC', 'category' => 'fruit', 'description' => 'Sweet peach'],
        ];
        foreach ($lostMaryFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $lostMary->id, 'is_active' => 1]));
        }

        // 12. HQD Cuvie Plus
        $hqd = Product::updateOrCreate(
            ['name' => 'HQD Cuvie Plus'],
            [
                'name' => 'HQD Cuvie Plus',
                'brand' => 'HQD',
                'description' => 'Classic disposable with 1,200 puffs. Simple and reliable.',
                'category' => 'Disposable',
                'type' => 'disposable',
                'price' => 220.00,
                'cost' => 180.00,
                'puff_count' => 1200,
                'battery_capacity' => 450,
                'charging_type' => 'Micro-USB',
                'liquid_capacity' => 3.5,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $hqd->flavors()->delete();
        $hqdFlavors = [
            ['name' => 'Strawberry', 'code' => 'SB', 'category' => 'fruit', 'description' => 'Sweet strawberry'],
            ['name' => 'Mango', 'code' => 'MG', 'category' => 'fruit', 'description' => 'Sweet mango'],
            ['name' => 'Mint', 'code' => 'MN', 'category' => 'mint', 'description' => 'Fresh mint'],
            ['name' => 'Grape', 'code' => 'GP', 'category' => 'fruit', 'description' => 'Sweet grape'],
        ];
        foreach ($hqdFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $hqd->id, 'is_active' => 1]));
        }

        // =============================================
        // ========== POD SYSTEMS ======================
        // =============================================

        // 13. RELX Classic
        $relx = Product::updateOrCreate(
            ['name' => 'Relx Classic'],
            [
                'name' => 'Relx Classic',
                'brand' => 'Relx',
                'description' => 'Popular pod system with smooth draw and long-lasting battery.',
                'category' => 'Relx',
                'type' => 'pod-system',
                'price' => 399.00,
                'cost' => 320.00,
                'puff_count' => null,
                'battery_capacity' => 350,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 1.9,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $relx->flavors()->delete();
        $relxFlavors = [
            ['name' => 'Fresh Mint', 'code' => 'FM', 'category' => 'mint', 'description' => 'Fresh mint flavor'],
            ['name' => 'Tobacco', 'code' => 'TB', 'category' => 'tobacco', 'description' => 'Classic tobacco'],
            ['name' => 'Watermelon', 'code' => 'WM', 'category' => 'fruit', 'description' => 'Juicy watermelon'],
            ['name' => 'Lychee', 'code' => 'LC', 'category' => 'fruit', 'description' => 'Sweet lychee'],
            ['name' => 'Grape', 'code' => 'GP', 'category' => 'fruit', 'description' => 'Sweet grape'],
            ['name' => 'Peach', 'code' => 'PC', 'category' => 'fruit', 'description' => 'Sweet peach'],
        ];
        foreach ($relxFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $relx->id, 'is_active' => 1]));
        }

        // 14. RELX Infinity
        $relxInfinity = Product::updateOrCreate(
            ['name' => 'Relx Infinity'],
            [
                'name' => 'Relx Infinity',
                'brand' => 'Relx',
                'description' => 'Upgraded pod system with better battery life and flavor delivery.',
                'category' => 'Relx',
                'type' => 'pod-system',
                'price' => 599.00,
                'cost' => 480.00,
                'puff_count' => null,
                'battery_capacity' => 500,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 2.5,
                'nicotine_strength' => '30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $relxInfinity->flavors()->delete();
        $relxInfinityFlavors = [
            ['name' => 'Cool Mint', 'code' => 'CM', 'category' => 'mint', 'description' => 'Cool mint'],
            ['name' => 'Strawberry', 'code' => 'SB', 'category' => 'fruit', 'description' => 'Sweet strawberry'],
            ['name' => 'Mango', 'code' => 'MG', 'category' => 'fruit', 'description' => 'Sweet mango'],
            ['name' => 'Pineapple', 'code' => 'PP', 'category' => 'fruit', 'description' => 'Sweet pineapple'],
        ];
        foreach ($relxInfinityFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $relxInfinity->id, 'is_active' => 1]));
        }

        // 15. Uwell Caliburn G2
        $caliburn = Product::updateOrCreate(
            ['name' => 'Uwell Caliburn G2'],
            [
                'name' => 'Uwell Caliburn G2',
                'brand' => 'Uwell',
                'description' => 'Top-rated pod system with replaceable coils and adjustable airflow.',
                'category' => 'Pod System',
                'type' => 'pod-system',
                'price' => 850.00,
                'cost' => 700.00,
                'puff_count' => null,
                'battery_capacity' => 750,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 2,
                'nicotine_strength' => '3mg-30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $caliburn->flavors()->delete();
        ProductFlavor::create(['product_id' => $caliburn->id, 'name' => 'Device Only', 'code' => 'DEV', 'category' => 'device', 'description' => 'Device only - pods sold separately', 'is_active' => 1]);

        // 16. Vaporesso XROS 3
        $xros = Product::updateOrCreate(
            ['name' => 'Vaporesso XROS 3'],
            [
                'name' => 'Vaporesso XROS 3',
                'brand' => 'Vaporesso',
                'description' => 'Popular pod system with SSS technology for leak-resistant design.',
                'category' => 'Pod System',
                'type' => 'pod-system',
                'price' => 899.00,
                'cost' => 750.00,
                'puff_count' => null,
                'battery_capacity' => 1000,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 2,
                'nicotine_strength' => '3mg-30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );

        $xros->flavors()->delete();
        ProductFlavor::create(['product_id' => $xros->id, 'name' => 'Device Only', 'code' => 'DEV', 'category' => 'device', 'description' => 'Device only - pods sold separately', 'is_active' => 1]);

        // 17. Oxva Xlim Pro
        $oxva = Product::updateOrCreate(
            ['name' => 'Oxva Xlim Pro'],
            [
                'name' => 'Oxva Xlim Pro',
                'brand' => 'Oxva',
                'description' => 'High-performance pod system with 1,000mAh battery and adjustable power.',
                'category' => 'Pod System',
                'type' => 'pod-system',
                'price' => 999.00,
                'cost' => 820.00,
                'puff_count' => null,
                'battery_capacity' => 1000,
                'charging_type' => 'Type-C',
                'liquid_capacity' => 2,
                'nicotine_strength' => '3mg-30mg',
                'adjustable_airflow' => 1,
                'smart_display' => 1,
                'is_active' => 1,
            ]
        );

        $oxva->flavors()->delete();
        ProductFlavor::create(['product_id' => $oxva->id, 'name' => 'Device Only', 'code' => 'DEV', 'category' => 'device', 'description' => 'Device only - pods sold separately', 'is_active' => 1]);

        // =============================================
        // ========== E-LIQUIDS (Juices) ===============
        // =============================================

        // 18. Dinner Lady - Lemon Tart
        $dinnerLady = Product::updateOrCreate(
            ['name' => 'Dinner Lady - Lemon Tart'],
            [
                'name' => 'Dinner Lady - Lemon Tart',
                'brand' => 'Dinner Lady',
                'description' => 'Award-winning dessert e-liquid. Tangy lemon curd with meringue and shortbread.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 500.00,
                'cost' => 420.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $dinnerLady->flavors()->delete();
        ProductFlavor::create(['product_id' => $dinnerLady->id, 'name' => 'Lemon Tart', 'code' => 'LT', 'category' => 'dessert', 'description' => 'Tangy lemon curd with meringue', 'is_active' => 1]);

        // 19. Naked 100 - Hawaiian POG
        $naked = Product::updateOrCreate(
            ['name' => 'Naked 100 - Hawaiian POG'],
            [
                'name' => 'Naked 100 - Hawaiian POG',
                'brand' => 'Naked 100',
                'description' => 'Premium E-liquid. Passion Fruit, Orange, Guava blend.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 450.00,
                'cost' => 380.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $naked->flavors()->delete();
        ProductFlavor::create(['product_id' => $naked->id, 'name' => 'Hawaiian POG', 'code' => 'POG', 'category' => 'fruit', 'description' => 'Passion fruit, orange, guava', 'is_active' => 1]);

        // 20. Monster Vape Labs - Jam Monster
        $monster = Product::updateOrCreate(
            ['name' => 'Monster Vape Labs - Jam Monster'],
            [
                'name' => 'Monster Vape Labs - Jam Monster',
                'brand' => 'Monster Vape Labs',
                'description' => 'Buttered toast with strawberry jam flavor.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 480.00,
                'cost' => 400.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $monster->flavors()->delete();
        ProductFlavor::create(['product_id' => $monster->id, 'name' => 'Strawberry Jam', 'code' => 'SJ', 'category' => 'dessert', 'description' => 'Buttered toast with strawberry jam', 'is_active' => 1]);

        // 21. Coastal Clouds - Blueberry Banana
        $coastal = Product::updateOrCreate(
            ['name' => 'Coastal Clouds - Blueberry Banana'],
            [
                'name' => 'Coastal Clouds - Blueberry Banana',
                'brand' => 'Coastal Clouds',
                'description' => 'Smooth blueberry and ripe banana blend.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 520.00,
                'cost' => 440.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $coastal->flavors()->delete();
        ProductFlavor::create(['product_id' => $coastal->id, 'name' => 'Blueberry Banana', 'code' => 'BB', 'category' => 'fruit', 'description' => 'Blueberry and banana blend', 'is_active' => 1]);

        // 22. The Milkman - Crumbleberry
        $milkman = Product::updateOrCreate(
            ['name' => 'The Milkman - Crumbleberry'],
            [
                'name' => 'The Milkman - Crumbleberry',
                'brand' => 'The Milkman',
                'description' => 'Fresh baked blueberry crumble with a scoop of vanilla ice cream.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 550.00,
                'cost' => 460.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $milkman->flavors()->delete();
        ProductFlavor::create(['product_id' => $milkman->id, 'name' => 'Crumbleberry', 'code' => 'CB', 'category' => 'dessert', 'description' => 'Blueberry crumble with vanilla ice cream', 'is_active' => 1]);

        // 23. Pachamama - Fuji Apple
        $pachamama = Product::updateOrCreate(
            ['name' => 'Pachamama - Fuji Apple'],
            [
                'name' => 'Pachamama - Fuji Apple',
                'brand' => 'Pachamama',
                'description' => 'Crisp Fuji apple, strawberry, and nectarine.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 490.00,
                'cost' => 410.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $pachamama->flavors()->delete();
        ProductFlavor::create(['product_id' => $pachamama->id, 'name' => 'Fuji Apple', 'code' => 'FA', 'category' => 'fruit', 'description' => 'Fuji apple, strawberry, nectarine', 'is_active' => 1]);

        // 24. Ripe Vapes - VCT
        $ripe = Product::updateOrCreate(
            ['name' => 'Ripe Vapes - VCT'],
            [
                'name' => 'Ripe Vapes - VCT',
                'brand' => 'Ripe Vapes',
                'description' => 'Vanilla custard tobacco. Rich and creamy.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 580.00,
                'cost' => 490.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $ripe->flavors()->delete();
        ProductFlavor::create(['product_id' => $ripe->id, 'name' => 'VCT', 'code' => 'VCT', 'category' => 'tobacco', 'description' => 'Vanilla custard tobacco', 'is_active' => 1]);

        // 25. Charlie's Chalk Dust - PB&J
        $charlie = Product::updateOrCreate(
            ['name' => 'Charlie\'s Chalk Dust - PB&J'],
            [
                'name' => 'Charlie\'s Chalk Dust - PB&J',
                'brand' => 'Charlie\'s Chalk Dust',
                'description' => 'Peanut butter and strawberry jam sandwich.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 530.00,
                'cost' => 450.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $charlie->flavors()->delete();
        ProductFlavor::create(['product_id' => $charlie->id, 'name' => 'PB&J', 'code' => 'PJ', 'category' => 'dessert', 'description' => 'Peanut butter and strawberry jam', 'is_active' => 1]);

        // 26. Sadboy - Butter Cookie
        $sadboy = Product::updateOrCreate(
            ['name' => 'Sadboy - Butter Cookie'],
            [
                'name' => 'Sadboy - Butter Cookie',
                'brand' => 'Sadboy',
                'description' => 'Butter cookie with lemon zest.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 510.00,
                'cost' => 430.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $sadboy->flavors()->delete();
        ProductFlavor::create(['product_id' => $sadboy->id, 'name' => 'Butter Cookie', 'code' => 'BC', 'category' => 'dessert', 'description' => 'Butter cookie with lemon zest', 'is_active' => 1]);

        // 27. Glas Basix - Banana Cream Pie
        $glas = Product::updateOrCreate(
            ['name' => 'Glas Basix - Banana Cream Pie'],
            [
                'name' => 'Glas Basix - Banana Cream Pie',
                'brand' => 'Glas Basix',
                'description' => 'Creamy banana pudding with vanilla wafers.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 540.00,
                'cost' => 460.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $glas->flavors()->delete();
        ProductFlavor::create(['product_id' => $glas->id, 'name' => 'Banana Cream Pie', 'code' => 'BCP', 'category' => 'dessert', 'description' => 'Banana pudding with vanilla wafers', 'is_active' => 1]);

        // 28. Twist - Pink Punch No. 1
        $twist = Product::updateOrCreate(
            ['name' => 'Twist - Pink Punch No. 1'],
            [
                'name' => 'Twist - Pink Punch No. 1',
                'brand' => 'Twist',
                'description' => 'Pink lemonade flavor. Sweet and tangy.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 470.00,
                'cost' => 390.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $twist->flavors()->delete();
        ProductFlavor::create(['product_id' => $twist->id, 'name' => 'Pink Punch', 'code' => 'PP', 'category' => 'beverage', 'description' => 'Pink lemonade', 'is_active' => 1]);

        // 29. Juice Head - Pineapple Grapefruit
        $juiceHead = Product::updateOrCreate(
            ['name' => 'Juice Head - Pineapple Grapefruit'],
            [
                'name' => 'Juice Head - Pineapple Grapefruit',
                'brand' => 'Juice Head',
                'description' => 'Fresh pineapple and grapefruit blend.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 490.00,
                'cost' => 410.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $juiceHead->flavors()->delete();
        ProductFlavor::create(['product_id' => $juiceHead->id, 'name' => 'Pineapple Grapefruit', 'code' => 'PG', 'category' => 'fruit', 'description' => 'Pineapple and grapefruit', 'is_active' => 1]);

        // 30. Cloud Nurdz - Watermelon Apple
        $cloudNurdz = Product::updateOrCreate(
            ['name' => 'Cloud Nurdz - Watermelon Apple'],
            [
                'name' => 'Cloud Nurdz - Watermelon Apple',
                'brand' => 'Cloud Nurdz',
                'description' => 'Sweet watermelon and tart green apple.',
                'category' => 'E-Liquid',
                'type' => 'liquid',
                'price' => 500.00,
                'cost' => 420.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => 60,
                'nicotine_strength' => '3mg,6mg',
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $cloudNurdz->flavors()->delete();
        ProductFlavor::create(['product_id' => $cloudNurdz->id, 'name' => 'Watermelon Apple', 'code' => 'WA', 'category' => 'fruit', 'description' => 'Watermelon and green apple', 'is_active' => 1]);

        // =============================================
        // ========== BOX MODS =========================
        // =============================================

        // 31. GeekVape Aegis Legend 2
        $geekvape = Product::updateOrCreate(
            ['name' => 'GeekVape Aegis Legend 2'],
            [
                'name' => 'GeekVape Aegis Legend 2',
                'brand' => 'GeekVape',
                'description' => 'Durable box mod with IP68 rating. 200W output.',
                'category' => 'Box Mod',
                'type' => 'mod',
                'price' => 2499.00,
                'cost' => 2000.00,
                'puff_count' => null,
                'battery_capacity' => 0,
                'charging_type' => 'Type-C',
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $geekvape->flavors()->delete();
        ProductFlavor::create(['product_id' => $geekvape->id, 'name' => 'Device Only', 'code' => 'DEV', 'category' => 'device', 'description' => 'Mod device only - batteries and tank sold separately', 'is_active' => 1]);

        // 32. VooPoo Drag 4
        $voopoo = Product::updateOrCreate(
            ['name' => 'VooPoo Drag 4'],
            [
                'name' => 'VooPoo Drag 4',
                'brand' => 'VooPoo',
                'description' => 'Powerful box mod with 177W max output and gene chip.',
                'category' => 'Box Mod',
                'type' => 'mod',
                'price' => 2299.00,
                'cost' => 1850.00,
                'puff_count' => null,
                'battery_capacity' => 0,
                'charging_type' => 'Type-C',
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 1,
                'is_active' => 1,
            ]
        );
        $voopoo->flavors()->delete();
        ProductFlavor::create(['product_id' => $voopoo->id, 'name' => 'Device Only', 'code' => 'DEV', 'category' => 'device', 'description' => 'Mod device only - batteries and tank sold separately', 'is_active' => 1]);

        // 33. Smok RPM 5
        $smok = Product::updateOrCreate(
            ['name' => 'Smok RPM 5'],
            [
                'name' => 'Smok RPM 5',
                'brand' => 'Smok',
                'description' => 'Pod mod with 80W output and large display.',
                'category' => 'Box Mod',
                'type' => 'mod',
                'price' => 1899.00,
                'cost' => 1500.00,
                'puff_count' => null,
                'battery_capacity' => 2000,
                'charging_type' => 'Type-C',
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $smok->flavors()->delete();
        ProductFlavor::create(['product_id' => $smok->id, 'name' => 'Device Only', 'code' => 'DEV', 'category' => 'device', 'description' => 'Pod mod device only - pods sold separately', 'is_active' => 1]);

        // =============================================
        // ========== ACCESSORIES & COILS ==============
        // =============================================

        // 34. Relx Replacement Pods
        $relxPods = Product::updateOrCreate(
            ['name' => 'Relx Replacement Pods'],
            [
                'name' => 'Relx Replacement Pods',
                'brand' => 'Relx',
                'description' => '3-pack replacement pods for Relx devices.',
                'category' => 'Accessories',
                'type' => 'accessory',
                'price' => 199.00,
                'cost' => 150.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $relxPods->flavors()->delete();
        ProductFlavor::create(['product_id' => $relxPods->id, 'name' => '3-Pack (Mixed)', 'code' => '3PK', 'category' => 'accessory', 'description' => '3 replacement pods', 'is_active' => 1]);

        // 35. Caliburn G Coils
        $caliburnCoils = Product::updateOrCreate(
            ['name' => 'Caliburn G Coils (4-Pack)'],
            [
                'name' => 'Caliburn G Coils (4-Pack)',
                'brand' => 'Uwell',
                'description' => 'Replacement coils for Caliburn G devices.',
                'category' => 'Coils',
                'type' => 'coil',
                'price' => 249.00,
                'cost' => 200.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $caliburnCoils->flavors()->delete();
        $caliburnCoilsFlavors = [
            ['name' => '0.8ohm Mesh', 'code' => '08M', 'category' => 'coil', 'description' => '0.8ohm mesh coil', 'is_active' => 1],
            ['name' => '1.0ohm Regular', 'code' => '10R', 'category' => 'coil', 'description' => '1.0ohm regular coil', 'is_active' => 1],
        ];
        foreach ($caliburnCoilsFlavors as $flavor) {
            ProductFlavor::create(array_merge($flavor, ['product_id' => $caliburnCoils->id, 'is_active' => 1]));
        }

        // 36. 18650 Battery
        $batteries = Product::updateOrCreate(
            ['name' => '18650 Battery'],
            [
                'name' => '18650 Battery',
                'brand' => 'Samsung',
                'description' => 'High-drain 18650 battery for box mods. 2500mAh.',
                'category' => 'Accessories',
                'type' => 'accessory',
                'price' => 299.00,
                'cost' => 250.00,
                'puff_count' => null,
                'battery_capacity' => 2500,
                'charging_type' => null,
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $batteries->flavors()->delete();
        ProductFlavor::create(['product_id' => $batteries->id, 'name' => '25R 2500mAh', 'code' => '25R', 'category' => 'battery', 'description' => '18650 2500mAh battery', 'is_active' => 1]);

        // 37. Dual Slot Battery Charger
        $charger = Product::updateOrCreate(
            ['name' => 'Dual Slot Battery Charger'],
            [
                'name' => 'Dual Slot Battery Charger',
                'brand' => 'Nitecore',
                'description' => 'Smart charger for 18650/21700 batteries.',
                'category' => 'Accessories',
                'type' => 'accessory',
                'price' => 399.00,
                'cost' => 320.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $charger->flavors()->delete();
        ProductFlavor::create(['product_id' => $charger->id, 'name' => '2-Slot Charger', 'code' => '2SC', 'category' => 'accessory', 'description' => 'Dual slot smart charger', 'is_active' => 1]);

        // 38. 510 Drip Tips
        $dripTips = Product::updateOrCreate(
            ['name' => '510 Drip Tips'],
            [
                'name' => '510 Drip Tips',
                'brand' => 'Various',
                'description' => 'Resin 510 drip tips, assorted colors.',
                'category' => 'Accessories',
                'type' => 'accessory',
                'price' => 99.00,
                'cost' => 70.00,
                'puff_count' => null,
                'battery_capacity' => null,
                'charging_type' => null,
                'liquid_capacity' => null,
                'nicotine_strength' => null,
                'adjustable_airflow' => 0,
                'smart_display' => 0,
                'is_active' => 1,
            ]
        );
        $dripTips->flavors()->delete();
        ProductFlavor::create(['product_id' => $dripTips->id, 'name' => 'Assorted Colors', 'code' => 'ASST', 'category' => 'accessory', 'description' => 'Assorted resin drip tips', 'is_active' => 1]);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Products Seeded Successfully!');
        $this->command->info('========================================');
        $this->command->info('Total Products: ' . Product::count());
        $this->command->info('Total Flavors: ' . ProductFlavor::count());
        $this->command->info('');
    }
}
