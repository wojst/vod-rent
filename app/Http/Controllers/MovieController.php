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
        // dodawanie zdjęcia

        $validatedData = $request->validate([
            // walidacja pól formularza
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:400|dimensions:ratio=2/3',
        ], [
            'image.image' => 'Plik musi być obrazem.',
            'image.mimes' => 'Dozwolone formaty plików to: jpeg, png, jpg, gif.',
            'image.max' => 'Maksymalny rozmiar pliku to 400 kB.',
            'image.dimensions' => 'Proporcje obrazu muszą wynosić 2:3.',]);

        if ($request->hasFile('image')) {
            $path = 'img/movies/';
            $image = $request->file('image');
            $imageName = $path . $image->getClientOriginalName();
            $image->move(public_path('img/movies'), $imageName);
        } else {
            $imageName = null;
        }

        // tworzenie nowego filmu

        $movie = Movie::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'director' => $request->input('director'),
            'category_id' => $request->input('category'),
            'release_year' => $request->input('release_year'),
            'price' => $request->input('price'),
            'img_path' => $imageName,
        ]);

        $actorIds = $request->input('actors');

        // Jeśli wybrano jakichś aktorów
        if ($actorIds) {
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

        // Zmiana zdjęcia

        if ($request->hasFile('image')) {
            $validatedData = $request->validate([
                // walidacja innych pól formularza
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:400|dimensions:ratio=2/3',
            ], [
                'image.image' => 'Plik musi być obrazem.',
                'image.mimes' => 'Dozwolone formaty plików to: jpeg, png, jpg, gif.',
                'image.max' => 'Maksymalny rozmiar pliku to 400 kB.',
                'image.dimensions' => 'Proporcje obrazu muszą wynosić 2:3.',]);


                $path = 'img/movies/';
                $image = $request->file('image');
                $imageName = $path . $image->getClientOriginalName();
                $image->move(public_path('img/movies'), $imageName);

                $movie->img_path = $imageName;
        }

        $movie->title = $request->input('title');
        $movie->description = $request->input('description');
        $movie->director = $request->input('director');
        $movie->category_id = $request->input('category');
        $movie->release_year = $request->input('release_year');
        $movie->price = $request->input('price');


        $movie->save();

        $selectedActors = $request->input('actors', []);
        $movie->actors()->sync($selectedActors);

        return redirect()->route('movies.index', $movie->movies_id)->with('success', 'Film został zaktualizowany.');
    }


    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        // Usuwanie pliku ze zdjęciem
        if (!empty($movie->img_path)) {
            $filePath = public_path($movie->img_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $movie->actors()->detach();
        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Film został usunięty.');
    }

}
