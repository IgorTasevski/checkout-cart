<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skuData = [
            ['code' => 'A', 'amount' => 10, 'product_id' => 1],
            ['code' => 'B', 'amount' => 20, 'product_id' => 2],
            ['code' => 'C', 'amount' => 30, 'product_id' => 3],
            ['code' => 'D', 'amount' => 40, 'product_id' => 4],
            ['code' => 'E', 'amount' => 50, 'product_id' => 5],
        ];

        foreach ($skuData as $data) {
            $product = Product::find($data['product_id']);
            if ($product) {
                Sku::create([
                    'uuid'       => (string)Str::uuid(),
                    'code'       => $data['code'],
                    'product_id' => $product->id,
                    'amount'     => $data['amount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 1, // Hardcoded user ID for demo purposes
                    'updated_by' => 1, // Hardcoded user ID for demo purposes
                ]);
            } else {
                echo "Product with ID {$data['product_id']} not found";
            }
        }
    }
}
