<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->where('role', 0)->orderBy('id')->get();

        foreach ($users as $index => $user) {
            DB::table('customers')->insert([
                'user_id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => '0900000' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'address' => 'Số ' . (10 + $index) . ' Nguyễn Trãi, Hà Nội',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

