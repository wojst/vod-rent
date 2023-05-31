<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function summary($movie_id)
    {
        $movie = Movie::find($movie_id);

        return view('summary', ['movie' => $movie]);
    }

    public function showBlikPayment($movie_id)
    {
        $movie = Movie::find($movie_id);

        return view('blik-payment', ['movie' => $movie, 'movie_id' => $movie_id]);
    }

    public function processBlikPayment(Request $request)
    {
        $blikCode = $request->input('blik_code');
        $movie_id = $request->input('movie_id');

        // Sprawdź, czy wpisany kod BLIK składa się z 6 cyfr
        if (strlen($blikCode) === 6 && ctype_digit($blikCode)) {
            // Kod BLIK jest poprawny

            // Generuj losowy 3-znakowy kod transakcji
            $transactionCode = $this->generateTransactionCode();

            $movie = Movie::find($movie_id);

            // Sprawdź, czy znaleziono film
            if ($movie) {
                // Tworzenie nowego order
                $order = Order::create([
                    'user_id' => auth()->user()->user_id,
                    'movie_id' => $movie->movie_id,
                    'rent_start' => now(),
                    'rent_end' => now()->addHours(24),
                    'cost' => $movie->price,
                    'code' => $transactionCode
                ]);

                // Zwiększanie liczby zamówień użytkownika
                DB::table('users')
                ->where('user_id', auth()->user()->user_id)
                ->increment('orders_count');

                // Zwiększanie wartości rentals_count filmu
                DB::table('movies')
                    ->where('movie_id', $movie->movie_id)
                    ->increment('rentals_count');


                Session::flash('success', 'Płatność BLIK została pomyślnie przetworzona.');
                Session::flash('transaction_code', $transactionCode);

                return redirect()->route('profile');
            } else {
                // Film o podanym movie_id nie został znaleziony
                Session::flash('error', 'Film nie został znaleziony.');

                return redirect()->route('homepage');
            }
        } else {
            // Kod BLIK jest niepoprawny
            // Możesz przekierować użytkownika na stronę błędu lub wyświetlić odpowiednie komunikaty

            Session::flash('error', 'Transakcja BLIK nieudana.');

            return redirect()->route('blik-payment', ['movie_id' => $movie_id]);
        }
    }

    private function generateTransactionCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }
}
