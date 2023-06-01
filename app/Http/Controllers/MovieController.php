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


        return view('admin.movies.movies', compact('movies', 'categories', 'actors'));
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

        $actorIds = $request->input('actors');

        // Jeśli wybrano jakichś aktorów
        if ($actorIds) {
            // Dodajemy wybranych aktorów do filmu
            $movie->actors()->attach($actorIds);
        }

        return redirect()->route('movies.index')->with('success', 'Film został dodany.');
    }

    public function edit($id)
    {
        $movie = Movie::find($id);
        $categories = Category::all();
        $actors = Actor::all();

        $selectedActors = $movie->actors->pluck('actor_id')->toArray();

        return view('admin.movies.edit', compact('movie', 'categories', 'actors', 'selectedActors'));
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $movie->title = $request->input('title');
        $movie->description = $request->input('description');
        $movie->director = $request->input('director');
        $movie->category_id = $request->input('category');
        $movie->release_year = $request->input('release_year');
        $movie->price = $request->input('price');
        $movie->img_path = $request->input('image');

        $movie->save();

        $selectedActors = $request->input('actors', []);
        $movie->actors()->sync($selectedActors);

        return redirect()->route('movies.index', $movie->movies_id)->with('success', 'Film został zaktualizowany.');
    }


    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        $movie->actors()->detach();
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Film został usunięty.');
    }

}
