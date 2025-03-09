<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Chạy Seeder để tạo tài khoản admin.
     *
     * @return void
     */
    public function run()
    {
        // Kiểm tra xem admin đã tồn tại chưa để tránh tạo trùng
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin@1234'),
                'role' => 'admin', // Đảm bảo cột role tồn tại trong bảng users
                'status' => 1,     // 1 là active, theo Enum Status
            ]);
        }

        if (!User::where('email', 'ngocha@gmail.com')->exists()) {
            User::create([
                'name' => 'Ha',
                'email' => 'ngocha@gmail.com',
                'password' => Hash::make('admin@1234'),
                'role' => 'user', // Đảm bảo cột role tồn tại trong bảng users
                'status' => 1,     // 1 là active, theo Enum Status
            ]);
        }
    }
}
