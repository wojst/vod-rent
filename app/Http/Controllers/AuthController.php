<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        // Walidacja danych logowania
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Sprawdzenie poprawności danych logowania
        if (Auth::attempt($credentials)) {
            // Pomyślne uwierzytelnienie użytkownika
            return redirect()->intended('/')->with('success', 'Zalogowano pomyślnie.');
        } else {
            // Błędne dane logowania
            return back()->withErrors([
                // 'email' => 'Podane dane logowania są nieprawidłowe.',
                'login' => 'Nieprawidłowe dane logowania.',
            ]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Podany adres e-mail jest już zajęty.',
            'password.confirmed' => 'Hasło i potwierdzenie hasła nie są identyczne.',
            'password.min:8' => 'Hasło musi zawierać przynajmniej 8 znaków.',
        ]);

        // Tworzenie nowego użytkownika
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Konto zostało utworzone. Możesz się teraz zalogować.');
    }
}

