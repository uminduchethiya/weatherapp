<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\WeatherController;
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');

// API routes for weather
Route::prefix('api')->group(function () {
    Route::get('/weather', [WeatherController::class, 'api'])->name('api.weather');
    Route::post('/weather/clear-cache', [WeatherController::class, 'clearCache'])->name('api.weather.clear-cache');
});
