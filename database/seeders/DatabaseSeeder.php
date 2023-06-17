<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ActorsSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(MoviesSeeder::class);
        $this->call(MoviesActorsTransferSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(OrdersSeeder::class);
    }
}
