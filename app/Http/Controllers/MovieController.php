<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Actor;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        $categories = Category::all();
        $actors = Actor::all();


        return view('admin.movies', compact('movies', 'categories', 'actors'));
    }


    public function store(Request $request)
    {
        $movie = Movie::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'director' => $request->input('director'),
            'category_id' => $request->input('category'),
            'release_year' => $request->input('release_year'),
            'price' => $request->input('price'),
            'img_path' => $request->input('image'),
        ]);

        // Pobieramy ID wybranych aktorów z formularza
        $actorIds = $request->input('actors');

        // Jeśli wybrano jakichś aktorów
        if ($actorIds) {
            // Dodajemy wybranych aktorów do filmu
            $movie->actors()->attach($actorIds);
        }


        // Możesz również wykonać inne operacje po zapisie filmu, np. przekierowanie lub wyświetlenie komunikatu sukcesu.

        return redirect()->route('movies.index')->with('success', 'Film został dodany.');
    }

    public function edit($id)
    {
        $movie = Movie::find($id);
        $categories = Category::all(); // Zakładając, że masz model Category

        return view('admin.edit', compact('movie', 'categories'));
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        $movie->actors()->detach();
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Film został usunięty.');
    }



}
