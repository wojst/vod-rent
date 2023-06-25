<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dodanie użytkownika z rolą administratora
        DB::table('users')->insert([
            'admin_role' => true,
            'name' => 'admin0',
            'email' => 'admin0@example.com',
            'password' => Hash::make('admin123'),
            'orders_count' => 0,
            'loyalty_card' => false,
            'id_fav_category' => null,
        ]);

        // Dodanie nowego użytkownika, który nie jest administratorem
        DB::table('users')->insert([
            'admin_role' => false,
            'name' => 'user1',
            'email' => 'user1@example.com',
            'password' => Hash::make('user123'),
            'orders_count' => 0,
            'loyalty_card' => false,
            'id_fav_category' => null,
        ]);
    }
}
