<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    protected $table = 'actors'; // Nazwa tabeli w bazie danych, którą mapuje model
    protected $primaryKey = 'actor_id'; // Nazwa kolumny będącej kluczem głównym w tabeli actors
    protected $fillable = ['actor_name']; // Pola, które mogą być wypełniane masowo przy tworzeniu modelu
    public $timestamps = false; // Wyłączenie automatycznego ustawiania pól created_at i updated_at


    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movies_actors_transfer', 'actor_id', 'movie_id')
            ->withPivot('actor_id', 'movie_id');
    }
}

