<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user(); // Pobierz aktualnie zalogowanego użytkownika
        $orders = $user->orders;
        $orders->load('movie');


        // Przekazanie danych użytkownika do widoku
        return view('user.profile', compact('user', 'orders'));
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
