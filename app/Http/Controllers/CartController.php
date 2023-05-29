<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function showCart()
    {
        $movies = Movie::with('category')->get();
        return view('cart', ['movies' => $movies]);
    }
}
