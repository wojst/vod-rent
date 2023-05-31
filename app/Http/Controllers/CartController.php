<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function summary($movie_id)
    {
        $movie = Movie::find($movie_id);

        return view('summary', compact('movie'));
    }

    public function showBlikPayment()
    {
        return view('blik-payment');
    }

    public function processBlikPayment(Request $request)
    {
        $blikCode = $request->input('blik_code');

        // Sprawdź, czy wpisany kod BLIK składa się z 6 cyfr
        if (strlen($blikCode) === 6 && ctype_digit($blikCode)) {
            // Kod BLIK jest poprawny

            // Generuj losowy 3-znakowy kod transakcji
            $transactionCode = $this->generateTransactionCode();

            // Wykonaj odpowiednie działania (np. zapisz płatność w bazie danych, zaktualizuj status zamówienia itp.)

            Session::flash('success', 'Płatność BLIK została pomyślnie przetworzona.');
            Session::flash('transaction_code', $transactionCode);

            return redirect()->route('profile');
        } else {
            // Kod BLIK jest niepoprawny
            // Możesz przekierować użytkownika na stronę błędu lub wyświetlić odpowiednie komunikaty

            Session::flash('error', 'Transakcja BLIK nieudana.');

            return redirect()->route('blik-payment');
        }
    }

    private function generateTransactionCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < 3; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }
}

