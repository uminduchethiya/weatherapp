<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key')
            ?? throw new \RuntimeException('OpenWeather API key not configured.');

        $this->baseUrl = config('services.openweather.endpoint');
    }

    /**
     * Get weather data for all city codes.
     * Cached for 5 minutes to reduce API calls.
     *
     * @return array
     */
    public function getWeatherData(): array
    {
        return Cache::remember('weather_data', 60, function () {
            Log::info('Cache miss — fetching weather data from API');
            $cityCodes = $this->extractCityCodes();
            if (empty($cityCodes)) {
                Log::warning('No city codes found for weather data.');
                return [];
            }

            $weatherData = [];
            foreach ($cityCodes as $cityId) {
                try {
                    $response = Http::timeout(10)->retry(2, 500)->get($this->baseUrl, [
                        'id' => $cityId,
                        'units' => 'metric',
                        'appid' => $this->apiKey,
                    ]);

                    if ($response->successful()) {
                        $weatherData[] = $response->json();
                    } else {
                        Log::error("Failed to fetch weather for city ID {$cityId}: HTTP {$response->status()}");
                    }
                } catch (\Exception $e) {
                    Log::error("Exception fetching weather for city ID {$cityId}: {$e->getMessage()}");
                }

                usleep(200000);
            }

            return $weatherData;
        });
    }

    /**
     * Extract city codes from JSON file.
     *
     * @return array
     */
    private function extractCityCodes(): array
    {
        $paths = [
            storage_path('app/cities.json'),
            public_path('cities.json'),
            base_path('cities.json'),
            resource_path('cities.json'),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                $content = file_get_contents($path);
                $json = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($json['List'])) {
                    return array_column($json['List'], 'CityCode');
                }
            }
        }

        return [];
    }
}
