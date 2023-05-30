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

    public function payment()
    {
        // Przekierowanie do integracji płatności (np. Stripe)
        // kod odpowiedzialny za przekierowanie do integracji płatności

        // Przykład przekierowania do Stripe Checkout
        return redirect()->away('https://checkout.stripe.com/...');
    }

}
