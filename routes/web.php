<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;


Route::get('/', [HomeController::class, 'homepage'])->name('homepage');

Route::get('/ourmovies', [HomeController::class, 'ourMovies'])->name('ourmovies');
Route::get('/searchMovies', [HomeController::class, 'searchMovies'])->name('searchMovies');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // tutaj inne trasy dostępne dla zalogowanych użytkowników
    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');

    Route::get('/summary/{movie_id}', [CartController::class, 'summary'])->name('summary');

    Route::get('/blik-payment', [CartController::class, 'showBlikPayment'])->name('blik-payment');
    Route::post('/process-blik-payment', [CartController::class, 'processBlikPayment'])->name('process-blik-payment');




});







