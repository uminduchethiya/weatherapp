<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WeatherController;
use Auth0\Laravel\Controllers\LoginController;
use Auth0\Laravel\Controllers\LogoutController;
use Auth0\Laravel\Controllers\CallbackController;

// Redirect root to login to avoid state errors
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected weather routes
Route::middleware(['auth0'])->group(function () {
    Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
});

Route::middleware(['web'])->group(function () {
    Route::get('/login', LoginController::class)->name('login');
    Route::get('/callback', CallbackController::class)->name('auth0.callback');
    Route::get('/logout', LogoutController::class)->name('logout');
});