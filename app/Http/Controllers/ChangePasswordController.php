<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('user.change-password');
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.min' => 'Nowe hasło musi mieć co najmniej :min znaków.',
            'new_password.confirmed' => 'Potwierdzenie nowego hasła nie pasuje.',
        ]);

        $user = Auth::user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->route('profile')->with('success', 'Hasło zostało zmienione.');
        } else {
            return back()->withErrors(['current_password' => 'Podane obecne hasło jest nieprawidłowe.'])->withInput();

        }
    }
}
