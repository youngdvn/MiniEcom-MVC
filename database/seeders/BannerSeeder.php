<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'title' => 'Siêu sale công nghệ tháng này',
                'subtitle' => 'Giảm đến 30% cho điện thoại, laptop và phụ kiện',
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1800&q=80',
                'link' => '/san-pham',
                'sort_order' => 1,
            ],
            [
                'title' => 'Laptop văn phòng cấu hình mạnh',
                'subtitle' => 'Ưu đãi trả góp 0% và quà tặng chính hãng',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=1800&q=80',
                'link' => '/san-pham',
                'sort_order' => 2,
            ],
            [
                'title' => 'Phụ kiện chính hãng giá tốt',
                'subtitle' => 'Tai nghe, chuột, bàn phím, sạc dự phòng',
                'image' => 'https://images.unsplash.com/photo-1580894908361-967195033215?auto=format&fit=crop&w=1800&q=80',
                'link' => '/san-pham',
                'sort_order' => 3,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('banners')->insert([
                ...$row,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

