<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\CardException;

class CartController extends Controller
{
    public function summary($movie_id)
    {
        $movie = Movie::find($movie_id);
        $amount = $movie->price;

        return view('summary', compact('movie', 'amount'));
    }

    public function processPayment(Request $request, $amount, Movie $movie)
    {
        $amount = $movie->price;
        if (auth()->user()->loyalty_card) {
            $amount *= 0.9; // Obniżenie ceny o 10% dla posiadaczy karty stałego klienta
        }

        $transactionCode = $this->generateTransactionCode();

        Stripe::setApiKey('sk_test_51NDBgPDXAO4dSNxMc6e4OkwI6HJRJXBg7AvpaiKT8zROpe7WbIbgQUKaLvBJCqw7vdefzoj4JpBDA114nIFlxZ2q00aIpYsGxO');

        // Przetwarzanie płatności
        try {
            Charge::create([
                'amount' => $amount*100,
                'currency' => 'pln',
                'source' => $request->stripeToken,
                'description' => $movie->title
            ]);

            // Jeśli płatność jest udana, zapisz rekord do bazy danych

            $order = Order::create([
                'user_id' => auth()->user()->user_id,
                'movie_id' => $movie->movie_id,
                'rent_start' => now()->addHours(2),
                'rent_end' => now()->addHours(26),
                'cost' => $amount,
                'code' => $transactionCode,
                'payment_status' => 'succeed'
            ]);

            // Zwiększanie liczby zamówień użytkownika
            DB::table('users')
                ->where('user_id', auth()->user()->user_id)
                ->increment('orders_count');

            // Sprawdzenie liczby zamówień użytkownika
            $userOrdersCount = DB::table('users')
                ->where('user_id', auth()->user()->user_id)
                ->value('orders_count');

            // Aktualizacja wartości loyalty_card
            if ($userOrdersCount >= 10) {
                DB::table('users')
                    ->where('user_id', auth()->user()->user_id)
                    ->update(['loyalty_card' => true]);
            }

            // Zwiększanie wartości rentals_count filmu
            DB::table('movies')
                ->where('movie_id', $movie->movie_id)
                ->increment('rentals_count');

            return redirect()->route('payment.success');
        } catch (CardException $e) {

            $order = Order::create([
                'user_id' => auth()->user()->user_id,
                'movie_id' => $movie->movie_id,
                'rent_start' => now()->addHours(2),
                'rent_end' => now()->addHours(2),
                'cost' => $amount,
                'code' => null,
                'payment_status' => 'failed'
            ]);

            return redirect()->route('payment.error');
        } catch (\Exception $e) {

            $order = Order::create([
                'user_id' => auth()->user()->user_id,
                'movie_id' => $movie->movie_id,
                'rent_start' => now()->addHours(2),
                'rent_end' => now()->addHours(2),
                'cost' => $amount,
                'code' => null,
                'payment_status' => 'failed'
            ]);

            return redirect()->route('payment.error');
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

    public function error()
    {
        return view('payment.error');
    }

    public function success()
    {
        return view('payment.success');
    }
}
