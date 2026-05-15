<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $userId = (int) DB::table('users')->orderBy('id')->value('id');

        $posts = [
            [
                'title' => 'Top 5 điện thoại pin trâu đáng mua năm nay',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Hướng dẫn chọn laptop cho sinh viên công nghệ',
                'image' => 'https://images.unsplash.com/photo-1517336714739-489689fd1ca8?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'So sánh iPad Air và Galaxy Tab cho công việc',
                'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Kinh nghiệm chọn tai nghe chống ồn cho văn phòng',
                'image' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=1400&q=80',
            ],
            [
                'title' => 'Mẹo bảo quản pin thiết bị công nghệ bền hơn',
                'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?auto=format&fit=crop&w=1400&q=80',
            ],
        ];

        foreach ($posts as $item) {
            $slug = Str::slug($item['title']);
            DB::table('posts')->updateOrInsert(
                ['slug' => $slug],
                [
                    'title' => $item['title'],
                    'userid' => $userId,
                    'image' => $item['image'],
                    'content' => 'Nội dung tư vấn và đánh giá sản phẩm công nghệ dành cho người dùng phổ thông.',
                    'status' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
