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
        $path = storage_path('csv/movies.csv'); // Ścieżka do pliku CSV zawierającego informacje o filmach
        $data = File::get($path); // Wczytanie zawartości pliku CSV do zmiennej $data
        $rows = explode("\n", $data); // Podział danych na wiersze na podstawie separatora nowej linii ("\n")

        foreach ($rows as $row) {  // dla wszystkich linijek w pliku .csv
            $values = str_getcsv($row, ';'); // Podział wiersza na poszczególne wartości na podstawie separatora ";"

            // Dodawanie rekordu do tabeli movies
            DB::table('movies')->insert([
                'movie_id' => $values[0],
                'title' => $values[1],
                'description' => $values[2],
                'director' => $values[3],
                'category_id' => $values[4],
                'release_year' => $values[5],
                'price' => $values[6],
                'rentals_count' => $values[7],
                'img_path' => $values[8],

            ]);
        }
    }
}
