<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        foreach ([
            'order_items',
            'orders',
            'wishlists',
            'contact_messages',
            'customers',
            'product_variants',
            'product_images',
            'products',
            'posts',
            'coupons',
            'banners',
            'users',
            'brands',
            'categories',
        ] as $table) {
            DB::table($table)->truncate();
        }
        Schema::enableForeignKeyConstraints();

        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            ProductImageSeeder::class,
            PostSeeder::class,
            BannerSeeder::class,
            CouponSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            WishlistSeeder::class,
            ContactMessageSeeder::class,
        ]);
    }
}
