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

         // Wywołanie procedury i pobranie wyniku
         $result = DB::select('CALL GetFavoriteActor(?)', [$user->user_id]);

         $favActor = '';
         if (!empty($result)) {
             $favActor = $result[0]->result;
         }

        // Przekazanie danych użytkownika, zamówień i wyniku procedury do widoku
        return view('user.profile', compact('user', 'orders', 'randomMovieFromLastCategory', 'categoryName', 'actors', 'favActor'));
    }

    public function index()
    {
        $users = User::all();
        return view('admin.users.users', compact('users'));
    }

    public function store(Request $request)
    {
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
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
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
