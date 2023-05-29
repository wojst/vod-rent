<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user(); // Pobierz aktualnie zalogowanego użytkownika

        // Przekazanie danych użytkownika do widoku
        return view('user.profile', compact('user'));
    }
}
