<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    protected $primaryKey = 'movie_id';
    protected $fillable = ['title', 'description', 'director', 'category_id', 'release_year', 'price', 'img_path'];

    public $timestamps = false;


    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'movies_actors_transfer', 'movie_id', 'actor_id')
            ->withPivot('actor_id', 'movie_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'movie_id', 'movie_id');
    }

    public function actorsTransfer()
    {
        return $this->hasMany(MoviesActorsTransfer::class, 'movie_id')->cascadeDelete();
    }

}

