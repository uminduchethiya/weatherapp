<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        $rawWeatherData = $this->weatherService->getWeatherData();

        $weatherData = collect($rawWeatherData)->map(function ($data) {
            return [
                'city_name' => $data['name'] ?? 'Unknown',
                'temperature' => round($data['main']['temp'] ?? 0, 1),
                'weather_condition' => $data['weather'][0]['description'] ?? 'Unknown',
                'weather_main' => $data['weather'][0]['main'] ?? 'Unknown',
                'humidity' => $data['main']['humidity'] ?? 0,
                'pressure' => $data['main']['pressure'] ?? 0,
                'wind_speed' => $data['wind']['speed'] ?? 0,
                'country' => $data['sys']['country'] ?? '',
                'icon' => $data['weather'][0]['icon'] ?? '01d',
                'city_id' => $data['id'] ?? null,
                'local_time' => isset($data['timezone']) && is_numeric($data['timezone'])
                    ? gmdate('H:i', time() + $data['timezone'])
                    : 'N/A',
            ];
        })->toArray();

        return view('weather', compact('weatherData'));
    }

    public function clearCache()
    {
        Cache::forget('weather_data');
        Cache::forget('weather_last_error');
        return response()->json(['message' => 'Cache cleared']);
    }

    public function api()
    {
        $rawData = $this->weatherService->getWeatherData();

        $weatherData = collect($rawData)->map(function ($data) {
            return [
                'city_name' => $data['name'] ?? 'Unknown',
                'temperature' => round($data['main']['temp'] ?? 0, 1),
                'weather_condition' => $data['weather'][0]['description'] ?? 'Unknown',
                'weather_main' => $data['weather'][0]['main'] ?? 'Unknown',
                'humidity' => $data['main']['humidity'] ?? 0,
                'pressure' => $data['main']['pressure'] ?? 0,
                'wind_speed' => $data['wind']['speed'] ?? 0,
                'country' => $data['sys']['country'] ?? '',
                'icon' => $data['weather'][0]['icon'] ?? '01d',
                'city_id' => $data['id'] ?? null,
                'local_time' => isset($data['timezone']) && is_numeric($data['timezone'])
                    ? gmdate('H:i', time() + $data['timezone'])
                    : 'N/A',
            ];
        })->toArray();

        return response()->json([
            'data' => $weatherData,
            'cached' => Cache::has('weather_data'),
            'error' => Cache::get('weather_last_error', 'No error'),
        ]);
    }
}
