<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->where('role', 0)->pluck('id');
        $products = DB::table('products')->pluck('id');

        foreach ($users as $userId) {
            foreach ($products->take(3) as $productId) {
                DB::table('wishlists')->insert([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

