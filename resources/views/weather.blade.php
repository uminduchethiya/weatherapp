<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .weather-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .weather-card.sunny {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .weather-card.cloudy {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .weather-card.rainy {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .weather-card.misty {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }
        .weather-card.clear {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                <i class="fas fa-cloud-sun mr-3"></i>Weather Dashboard
            </h1>
            <p class="text-xl text-white/80">Real-time weather information from around the world</p>
        </div>

        <!-- Refresh Button -->
        <div class="flex justify-center mb-6">
            <button onclick="refreshWeather()" class="bg-white/20 hover:bg-white/30 text-white font-semibold py-2 px-6 rounded-full backdrop-blur-sm border border-white/20 transition-all duration-300">
                <i class="fas fa-sync-alt mr-2"></i>Refresh Data
            </button>
        </div>

        <!-- Loading Spinner -->
        <div id="loading" class="hidden flex justify-center items-center py-8">
            <div class="loading-spinner"></div>
        </div>

        <!-- Weather Cards Grid -->
        <div id="weather-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($weatherData as $weather)
                <div class="weather-card {{ strtolower($weather['weather_main']) }} rounded-xl p-6 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <!-- City Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-xl font-bold">{{ $weather['city_name'] }}</h2>
                            @if($weather['country'])
                                <p class="text-sm opacity-80">{{ $weather['country'] }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png"
                                 alt="Weather icon"
                                 class="w-12 h-12">
                        </div>
                    </div>

                    <!-- Temperature -->
                    <div class="mb-4">
                        <div class="text-4xl font-bold mb-1">{{ $weather['temperature'] }}°C</div>
                        <div class="text-lg capitalize opacity-90">{{ $weather['weather_condition'] }}</div>
                    </div>

                    <!-- Additional Info -->
                    <div class="grid grid-cols-2 gap-2 text-sm opacity-80">
                        <div class="flex items-center">
                            <i class="fas fa-tint mr-2"></i>
                            <span>{{ $weather['humidity'] }}%</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-wind mr-2"></i>
                            <span>{{ $weather['wind_speed'] }} m/s</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-thermometer-half mr-2"></i>
                            <span>{{ $weather['pressure'] }} hPa</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $weather['local_time'] ?? 'N/A' }}</span>


                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <div class="bg-white/20 rounded-xl p-8 backdrop-blur-sm border border-white/20">
                        <i class="fas fa-exclamation-triangle text-4xl text-yellow-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-white mb-2">No Weather Data Available</h3>
                        <p class="text-white/80">Unable to fetch weather information. Please check your API configuration.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Cache Info -->
        <div class="text-center mt-8">
            <p class="text-white/60 text-sm">
                <i class="fas fa-info-circle mr-1"></i>
                Data is cached for 5 minutes to optimize performance
            </p>
        </div>
    </div>

    <script>
        function refreshWeather() {
            const loading = document.getElementById('loading');
            const weatherGrid = document.getElementById('weather-grid');

            loading.classList.remove('hidden');
            weatherGrid.style.opacity = '0.5';

            // Clear cache and reload
            fetch('/api/weather/clear-cache', { method: 'POST' })
                .then(() => {
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                })
                .catch(() => {
                    loading.classList.add('hidden');
                    weatherGrid.style.opacity = '1';
                });
        }

        // Auto-refresh every 5 minutes
        setInterval(() => {
            console.log('Auto-refreshing weather data...');
            refreshWeather();
        }, 300000); // 5 minutes in milliseconds

        // Add loading animation on page load
        window.addEventListener('load', function() {
            const cards = document.querySelectorAll('.weather-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>