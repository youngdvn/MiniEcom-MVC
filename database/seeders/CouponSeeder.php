<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'code' => 'TECH10',
                'type' => 'percent',
                'value' => 10,
                'max_discount' => 500000,
                'min_order' => 3000000,
                'usage_limit' => 200,
            ],
            [
                'code' => 'FREESHIP200',
                'type' => 'fixed',
                'value' => 200000,
                'max_discount' => null,
                'min_order' => 5000000,
                'usage_limit' => 100,
            ],
            [
                'code' => 'LAPTOP15',
                'type' => 'percent',
                'value' => 15,
                'max_discount' => 1500000,
                'min_order' => 15000000,
                'usage_limit' => 50,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('coupons')->insert([
                ...$row,
                'used_count' => 0,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addMonths(2),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

