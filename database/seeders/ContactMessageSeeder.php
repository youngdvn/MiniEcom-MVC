<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['fullname' => 'Lê Minh Trí', 'email' => 'tri@gmail.com', 'phone' => '0988881111', 'message' => 'Shop có hỗ trợ trả góp laptop không?'],
            ['fullname' => 'Phạm Thu Hà', 'email' => 'ha@gmail.com', 'phone' => '0911222333', 'message' => 'Cho mình hỏi thời gian bảo hành điện thoại Samsung.'],
            ['fullname' => 'Hoàng Đức', 'email' => 'duc@gmail.com', 'phone' => null, 'message' => 'Mình cần tư vấn màn hình 27 inch cho thiết kế.'],
        ];

        foreach ($rows as $row) {
            DB::table('contact_messages')->insert([
                ...$row,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

