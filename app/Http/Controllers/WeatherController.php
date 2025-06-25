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
            $timezoneOffset = $data['timezone'] ?? 0;

            return [
                'city_name' => $data['name'] ?? 'Unknown',
                'country' => $data['sys']['country'] ?? '',
                'temperature' => round($data['main']['temp'] ?? 0, 1),
                'temp_min' => round($data['main']['temp_min'] ?? 0, 1),
                'temp_max' => round($data['main']['temp_max'] ?? 0, 1),
                'humidity' => $data['main']['humidity'] ?? 0,
                'pressure' => $data['main']['pressure'] ?? 0,
                'visibility' => $data['visibility'] ?? 0,
                'description' => $data['weather'][0]['description'] ?? 'Unknown',
                'weather_main' => $data['weather'][0]['main'] ?? 'Unknown',
                'icon' => $data['weather'][0]['icon'] ?? '01d',
                'wind_speed' => $data['wind']['speed'] ?? 0,
                'wind_deg' => $data['wind']['deg'] ?? 0,
                'wind_gust' => $data['wind']['gust'] ?? 0,
                'sunrise' => isset($data['sys']['sunrise']) ? gmdate('H:i', $data['sys']['sunrise'] + $timezoneOffset) : 'N/A',
                'sunset' => isset($data['sys']['sunset']) ? gmdate('H:i', $data['sys']['sunset'] + $timezoneOffset) : 'N/A',
                'timezone' => 'UTC' . ($timezoneOffset >= 0 ? '+' : '') . ($timezoneOffset / 3600),
                'local_time' => gmdate(' H:i', time() + $timezoneOffset),
                'city_id' => $data['id'] ?? null,
            ];
        })->toArray();

        return view('weather', compact('weatherData'));
    }

   
}
