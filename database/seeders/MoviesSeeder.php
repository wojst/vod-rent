<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class MoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('csv/movies.csv');
        $data = File::get($path);
        $rows = explode("\n", $data);

        foreach ($rows as $row) {
            $values = str_getcsv($row, ';');

            // Dodawanie rekordu do tabeli movies
            DB::table('movies')->insert([
                'movie_id' => $values[0],
                'title' => $values[1],
                'description' => $values[2],
                'director' => $values[3],
                'category_id' => $values[4],
                'release_year' => $values[5],
                'rentals_count' => $values[6],
                'img_path' => $values[7],

            ]);
        }
    }
}
