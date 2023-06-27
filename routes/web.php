<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ActorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ChangeEmailController;



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

    //STRIPE
    Route::post('/process-payment/{amount}/{movie}', [CartController::class, 'processPayment'])->name('process-payment');
    Route::get('/payment/success', [CartController::class, 'success'])->name('payment.success');
    Route::get('/payment/error', [CartController::class, 'error'])->name('payment.error');

    //zmiana hasła
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password.update');

    // zmiana emaila
    Route::get('/change-email', [ChangeEmailController::class, 'showChangeEmailForm'])->name('change-email');
    Route::post('/change-email', [ChangeEmailController::class, 'changeEmail'])->name('change-email');
});

Route::middleware(['auth', 'admin'])->group(function () {
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

    //ADMIN UZYTKOWNICY
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    //ADMIN ZAMÓWIENIA
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});







