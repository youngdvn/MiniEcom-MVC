<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->select('id')->get();

        foreach ($products as $product) {
            DB::table('product_images')->insert([
                [
                    'product_id' => $product->id,
                    'image' => 'default.png',
                    'sort' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'product_id' => $product->id,
                    'image' => 'default.png',
                    'sort' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}

