<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ActorController;


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

    Route::get('/blik-payment/{movie_id}', [CartController::class, 'showBlikPayment'])->name('blik-payment');
    Route::post('/process-blik-payment', [CartController::class, 'processBlikPayment'])->name('process-blik-payment');

    //ADMIN FILMY

    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::post('/movies', [MovieController::class, 'store'])->name('movies.store');
    //edytowanie
    Route::get('/movies/{movie}/edit', [MovieController::class, 'edit'])->name('movies.edit');
    Route::put('/movies/{movie}', [MovieController::class, 'update'])->name('movies.update');
    //usuwanie
    Route::delete('/movies/{movie}', [MovieController::class, 'destroy'])->name('movies.destroy');

    //ADMIN KATEGORIE

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    //ADMIN AKTORZY

    Route::get('/actors', [ActorController::class, 'index'])->name('actors.index');
    Route::post('/actors', [ActorController::class, 'store'])->name('actors.store');
    Route::delete('/actors/{id}', [ActorController::class, 'destroy'])->name('actors.destroy');
    Route::get('/actors/{id}/edit', [ActorController::class, 'edit'])->name('actors.edit');
    Route::put('/actors/{id}', [ActorController::class, 'update'])->name('actors.update');












});







