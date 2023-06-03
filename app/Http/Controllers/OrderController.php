<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Movie;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $users = User::all();
        $movies = Movie::all();
        return view('admin.orders.orders', compact('orders', 'users', 'movies'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'movie_id' => 'required',
            'rent_start' => 'required',
            'rent_end' => 'required',
            'cost' => 'required',
            'code' => 'required',
        ]);

        Order::create($validatedData);

        return redirect()->route('orders.index')->with('success', 'Zamówienie zostało dodane.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Zamówienie zostało usunięte.');
    }
}
