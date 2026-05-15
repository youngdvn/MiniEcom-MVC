<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $items = ['Apple', 'Samsung', 'Xiaomi', 'Dell', 'Asus', 'Lenovo', 'Sony', 'Logitech'];

        foreach ($items as $name) {
            DB::table('brands')->insert([
                'brandname' => $name,
                'slug' => Str::slug($name),
                'description' => 'Thương hiệu công nghệ ' . $name,
                'thumbnail' => 'default.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
