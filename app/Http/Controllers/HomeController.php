<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

class HomeController extends Controller
{
    public function index()
    {
        $movies = Movie::with('actors')->inRandomOrder()->limit(4)->get();

        return view('homepage', compact('movies'));
    }


    public function boot()
    {
        Paginator::useBootstrap();
    }


    public function homepage()
    {

        return $this->index();
    }


    public function ourMovies()
    {
        $movies = Movie::paginate(8);
        $categories = Category::all();
        return view('ourmovies', compact('movies', 'categories'));
    }


    public function searchMovies(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');

        $movies = Movie::query();

        if ($search) {
            $movies->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhereHas('actors', function ($query) use ($search) {
                        $query->where('actor_name', 'like', "%$search%");
                    });
            });
        }

        if ($category) {
            $movies->whereHas('category', function ($query) use ($category) {
                $query->where('category_id', $category);
            });
        }

        $movies = $movies->with('category')->paginate(8);

        $categories = Category::all();

        return view('ourmovies', compact('movies', 'categories'));
    }



}





