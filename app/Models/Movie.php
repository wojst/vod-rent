<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    protected $primaryKey = 'movie_id'; // Nazwa kolumny będącej kluczem głównym w tabeli movies

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'movies_actors_transfer', 'movie_id', 'actor_id')
            ->withPivot('actor_id', 'movie_id'); // Dodaj zdefiniowane kolumny pivot
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

