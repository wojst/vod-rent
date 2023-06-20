<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Movie;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user(); // Pobierz aktualnie zalogowanego użytkownika
        $orders = $user->orders;
        $orders->load('movie');

        // Wywołanie procedury i pobranie wyniku
        $randomMovieFromLastCategory = DB::select('CALL GetMovieFromLastCategory(?)', [$user->user_id]);

        // Pobranie nazwy kategorii na podstawie ID kategorii z wyniku procedury
        $lastCategoryId = null;
        $categoryName = null;
        $actors = null;

        if (!empty($randomMovieFromLastCategory)) {
            $lastCategoryId = $randomMovieFromLastCategory[0]->category_id;
            $category = Category::find($lastCategoryId);
            $categoryName = $category->category_name;

            $randomMovieId = $randomMovieFromLastCategory[0]->movie_id;
            $movie = Movie::find($randomMovieId);
            $actors = $movie->actors()->get();
        }

        // Pobranie nazwy kategorii na podstawie ID kategorii z wyniku procedury
        $lastCategoryId = null;
        $categoryName = null;

        if (!empty($randomMovieFromLastCategory)) {
            $lastCategoryId = $randomMovieFromLastCategory[0]->category_id;
            $category = Category::find($lastCategoryId);
            $categoryName = $category->category_name;

            // Aktualizacja kolumny id_fav_category w tabeli users
            $user->id_fav_category = $categoryName;


            $randomMovieId = $randomMovieFromLastCategory[0]->movie_id;
            $movie = Movie::find($randomMovieId);
            $actors = $movie->actors()->get();
        }


        // Wywołanie procedury
        $resultCategory = DB::select('CALL GetFavoriteCategory(?)', [$user->user_id]);

        $favCategory = null;
        $favCategoryName = '';
        if (!empty($resultCategory)) {
            $favCategoryId = $resultCategory[0]->fav_category_id;
            $category = Category::find($favCategoryId);
            if ($category) {
                $favCategoryName = $category->category_name;
            }
        }



         // Wywołanie procedury i pobranie wyniku
         $result = DB::select('CALL GetFavoriteActor(?)', [$user->user_id]);

         $favActor = '';
         if (!empty($result)) {
             $favActor = $result[0]->result;
         }



         $resultMovie = DB::select('CALL GetFavoriteMovie(?)', [$user->user_id]);

         $favMovieTitle = '';

         if (!empty($resultMovie)) {
             $favMovieTitle = $resultMovie[0]->title;
         }



        // Przekazanie danych użytkownika, zamówień i wyniku procedury do widoku
        return view('user.profile', compact('user', 'orders', 'randomMovieFromLastCategory', 'categoryName', 'actors', 'favActor', 'favCategoryName', 'favMovieTitle'));
    }

    public function index()
    {
        $users = User::all();
        return view('admin.users.users', compact('users'));
    }

    public function store(Request $request)
    {
        // walidacja danych
        $validatedData = $request->validate([
            'email' => 'required|unique:users',
            'password' => 'min:8'
        ], ['email.unique' => 'Taki email już jest zajęty',
            'password.min' => 'Hasło musi zawierać 8 znaków'
        ]);

        //tworzenie nowego usera
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->admin_role = $request->has('admin_role');
        $user->orders_count = $request->input('orders_count');
        $user->loyalty_card = $request->has('loyalty_card');
        $user->save();


        return redirect()->route('users.index')->with('success', 'Użytkownik został dodany.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users',
        ], ['email.unique' => 'Taki email już jest zajęty'
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        // $user->email = $request->input('email');
        // if ($request->filled('password')) {
        //     $user->password = bcrypt($request->input('password'));
        // }
        $user->admin_role = $request->has('admin_role');
        $user->orders_count = $request->input('orders_count');
        $user->loyalty_card = $request->has('loyalty_card');
        $user->save();

        return redirect()->route('users.index')->with('success', 'Użytkownik został zaktualizowany.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Użytkownik został usunięty.');
    }
}
