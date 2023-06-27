<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeEmailController extends Controller
{
    public function showChangeEmailForm()
    {
        return view('user.change-email');
    }

    public function changeEmail(Request $request)
    {
        $validatedData = $request->validate([
            'new_email' => 'required|email|unique:users,email',
        ], [
            'new_email.unique' => 'Podany adres e-mail jest już używany przez innego użytkownika.',
        ]);

        $user = Auth::user();
        $user->email = $request->new_email;
        $user->save();

        return redirect()->route('profile')->with('success', 'Adres e-mail został zmieniony.');
    }
}
