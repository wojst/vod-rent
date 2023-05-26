<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class MoviesActorsTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('csv/moviesactorstransfer.csv');
        $data = File::get($path);
        $rows = explode("\n", $data);

        foreach ($rows as $row) {
            $values = str_getcsv($row, ';');

            // Dodawanie rekordu do tabeli movies
            DB::table('movies_actors_transfers')->insert([
                'movie_id' => $values[0],
                'actor_id' => $values[1],

            ]);
        }
    }
}
