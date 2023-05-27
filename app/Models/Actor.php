<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    protected $table = 'actors';
    protected $primaryKey = 'actor_id'; // Nazwa kolumny będącej kluczem głównym w tabeli actors

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movies_actors_transfer', 'actor_id', 'movie_id')
            ->withPivot('actor_id', 'movie_id'); // Dodaj zdefiniowane kolumny pivot
    }
}

