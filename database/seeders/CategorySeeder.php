<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Điện thoại',
            'Laptop',
            'Máy tính bảng',
            'Tai nghe',
            'Đồng hồ thông minh',
            'Chuột - Bàn phím',
            'Màn hình',
            'Phụ kiện công nghệ',
        ];

        foreach ($items as $name) {
            DB::table('categories')->insert([
                'catename' => $name,
                'slug' => Str::slug($name),
                'description' => 'Danh mục ' . $name,
                'thumbnail' => 'default.png',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
