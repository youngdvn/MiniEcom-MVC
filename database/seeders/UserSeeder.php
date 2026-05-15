<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin',
                'fullname' => 'Quản trị hệ thống',
                'email' => 'admin@mini-ecom.vn',
                'role' => 1,
            ],
            [
                'username' => 'client01',
                'fullname' => 'Nguyễn Văn Công Nghệ',
                'email' => 'client01@mini-ecom.vn',
                'role' => 0,
            ],
            [
                'username' => 'client02',
                'fullname' => 'Trần Thị Mua Sắm',
                'email' => 'client02@mini-ecom.vn',
                'role' => 0,
            ],
            [
                'username' => 'staff01',
                'fullname' => 'Nhân viên Hỗ trợ',
                'email' => 'staff01@mini-ecom.vn',
                'role' => 1,
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                ...$user,
                'password' => Hash::make('123456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
