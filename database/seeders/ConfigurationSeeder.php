<?php

namespace Database\Seeders;

use App\Models\Configuration;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the products, hardcoded for demo purposes
        $firstProduct = Product::find(1);
        $secondProduct = Product::find(2);
        $thirdProduct = Product::find(3);
        $fourthProduct = Product::find(4);
        $fifthProduct = Product::find(5);

        if (!$firstProduct || !$secondProduct || !$thirdProduct || !$fourthProduct || !$fifthProduct) {
            return;
        }

        // Multipriced: Buy 3 for a special price
        Configuration::create([
            'uuid' => (string)Str::uuid(),
            'product_id' => $firstProduct->id,
            'rule_type' => 'multipriced',
            'rule_details' => json_encode([
                'quantity' => 3,
                'special_price' => ($firstProduct->price * 3) - 350,
            ]),
            'conditions' => json_encode([]),
            'actions' => json_encode([
                'discount' => 350,
            ]),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Buy N Get 1 Free
        Configuration::create([
            'uuid' => (string)Str::uuid(),
            'product_id' => $secondProduct->id,
            'rule_type' => 'buy_n_get_1_free',
            'rule_details' => json_encode([
                'quantity' => 2,
            ]),
            'conditions' => json_encode([]),
            'actions' => json_encode([
                'free_item' => 1,
            ]),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Meal Deal: D + E for a special price
        Configuration::create([
            'uuid' => (string)Str::uuid(),
            'product_id' => null, // No specific product, applies to both D and E
            'rule_type' => 'meal_deal',
            'rule_details' => json_encode([
                'items' => ['D', 'E'],
                'special_price' => ($fourthProduct->price + $fifthProduct->price) - 350, // Combined special price
            ]),
            'conditions' => json_encode([
                'products' => ['D', 'E'],
            ]),
            'actions' => json_encode([
                'discount' => 350,
            ]),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Meal Deal: B + C for a special price (Inactive promotion)
        Configuration::create([
            'uuid' => (string)Str::uuid(),
            'product_id' => null,
            'rule_type' => 'meal_deal',
            'rule_details' => json_encode([
                'items' => ['B', 'C'],
                'special_price' => ($secondProduct->price + $thirdProduct->price) - 450, // Combined special price
            ]),
            'conditions' => json_encode([
                'products' => ['B', 'C'],
            ]),
            'actions' => json_encode([
                'discount' => 450,
            ]),
            'active' => false, // Inactive promotion
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
