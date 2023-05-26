<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('csv/categories.csv');
        $data = File::get($path);
        $rows = explode("\n", $data);

        foreach ($rows as $row) {
            $values = str_getcsv($row);

            // Dodawanie rekordu do tabeli movies
            DB::table('categories')->insert([
                'category_id' => $values[0],
                'category_name' => $values[1],

            ]);
        }
    }
}
