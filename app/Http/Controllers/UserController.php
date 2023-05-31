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
}
