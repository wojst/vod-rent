<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class HomeController extends Controller
{
    public function index()
    {
        $movies = Movie::with('actors')->inRandomOrder()->limit(4)->get();

        return view('welcome', compact('movies'));
    }

    public function welcome()
    {
        // You can optionally add logic specific to the welcome page here
        return $this->index();
    }
}
