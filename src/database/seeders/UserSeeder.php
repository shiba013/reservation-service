<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '一般ユーザ',
                'email' => 'user@example.com',
                'password' => Hash::make('user1234'),
                'email_verified_at' => now(),
                'role' => 1,
            ],
            [
                'name' => '一般ユーザ1',
                'email' => 'user1@example.com',
                'password' => Hash::make('user0987'),
                'email_verified_at' => now(),
                'role' => 1,
            ],
            [
                'name' => 'お店のオーナー',
                'email' => 'owner@example.com',
                'password' => Hash::make('owner1234'),
                'email_verified_at' => now(),
                'role' => 2,
            ],
            [
                'name' => 'お店のオーナー1',
                'email' => 'owner1@example.com',
                'password' => Hash::make('owner0987'),
                'email_verified_at' => now(),
                'role' => 2,
            ],
            [
                'name' => '管理者',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin1234'),
                'email_verified_at' => now(),
                'role' => 3,
            ],
        ]);
    }
}
