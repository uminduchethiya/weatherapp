<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Weather Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body class="min-h-screen text-white bg-cover bg-no-repeat bg-center"
    style="background-image: url('{{ asset('images/dashboardimage.png') }}')">
    <div class="container mx-auto px-4 py-6">

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6 relative flex-wrap">
            <div class="w-full text-center">
                <h1 class="text-2xl md:text-4xl font-bold flex justify-center items-center gap-2 md:gap-3">
                    <img src="{{ asset('images/clear-sky.png') }}" alt="clear sky" class="w-8 md:w-10">
                    <span class="text-2xl md:text-4xl">Weather App</span>
                </h1>
            </div>
            <div class="absolute right-4 top-2 md:top-0">
                @if (Auth::check())
                    <a href="{{ route('logout') }}"
                        class="bg-white/20 hover:bg-white/30 text-sm md:text-base text-white font-semibold py-1.5 px-3 md:py-2 md:px-4 rounded-full transition">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="hidden md:inline">Logout</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-white/20 hover:bg-white/30 text-sm md:text-base text-white font-semibold py-1.5 px-3 md:py-2 md:px-4 rounded-full transition">
                        Login
                    </a>
                @endif
            </div>
        </div>

        <!-- Search -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-3 mb-6">
            <input type="text" name="search" placeholder="Enter a City"
                class="px-3 py-1.5 text-sm bg-black text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white w-56 sm:w-64 rounded-md" />
            <button
                class="bg-purple-600 hover:bg-blue-600 text-sm font-semibold py-1.5 px-5 rounded-md text-white transition">
                Add City
            </button>
        </div>

        <!-- Weather Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mt-4 justify-items-center">
            @forelse($weatherData as $weather)
                @php
                    $condition = strtolower($weather['weather_main']);
                    $backgroundMap = [
                        'clear' => 'card_background.png',
                        'clouds' => 'card_background2.png',
                        'rain' => 'card_background3.png',
                        'mist' => 'card_background4.png',
                        'snow' => 'card_background5.png',
                    ];
                    $backgroundImage = asset('images/' . ($backgroundMap[$condition] ?? 'card_background1.png'));
                @endphp

                <div onclick="openModalFromElement(this)" data-weather='@json($weather)'
                    class="rounded-xl overflow-hidden whitespace-nowrap shadow-lg lg:w-[500px] bg-white/10 cursor-pointer hover:scale-105 transition-all">

                    <div class="relative px-6 pt-6 pb-4 text-white bg-cover bg-center"
                        style="background-image: url('{{ $backgroundImage }}')">
                        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm z-0"></div>
                        <div class="flex justify-between items-start z-10 relative">
                            <div>
                                <h2 class="text-2xl font-bold">{{ $weather['city_name'] }}, {{ $weather['country'] }}
                                </h2>
                                <p class="text-sm opacity-90">{{ $weather['local_time'] }},
                                    {{ \Carbon\Carbon::now()->format('M j') }}</p>
                            </div>
                            <div class="text-right">
                                <h3 class="text-4xl font-bold">{{ $weather['temperature'] }}°C</h3>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 relative z-10">
                            <div class="flex items-center gap-2">
                                <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png"
                                    alt="icon" class="w-8 h-8" />
                                <p class="capitalize text-sm">{{ $weather['description'] }}</p>
                            </div>
                            <div class="text-sm text-right">
                                <p>Temp Min: {{ $weather['temp_min'] }}°C</p>
                                <p>Temp Max: {{ $weather['temp_max'] }}°C</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div
                        class="bg-gray-700 text-white md:px-6 px-2 py-4 w-full text-[10px] lg:text-sm grid grid-cols-5 gap-4 items-center">
                        <div>
                            <p><span class="font-semibold">Pressure:</span> {{ $weather['pressure'] }}hPa</p>
                            <p><span class="font-semibold">Humidity:</span> {{ $weather['humidity'] }}%</p>
                            <p><span class="font-semibold">Visibility:</span>
                                {{ number_format($weather['visibility'] / 1000, 1) }}km</p>
                        </div>
                        <div class="w-px h-full bg-white/20 mx-auto"></div>
                        <div class="flex flex-col justify-center items-center text-center">
                            <i class="fas fa-location-arrow text-lg mb-1 rotate-[{{ $weather['wind_deg'] }}deg]"></i>
                            <div class="flex gap-2">
                                <span>{{ $weather['wind_speed'] }}m/s</span>
                                <span>{{ $weather['wind_deg'] }}°</span>
                            </div>
                        </div>
                        <div class="w-px h-full bg-white/20 mx-auto"></div>
                        <div class="text-end w-full -ml-4">
                            <p><span class="font-semibold">Sunrise:</span> {{ $weather['sunrise'] }}</p>
                            <p><span class="font-semibold">Sunset:</span> {{ $weather['sunset'] }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <div class="bg-white/20 rounded-xl p-8 backdrop-blur-sm border border-white/20">
                        <i class="fas fa-exclamation-triangle text-4xl text-yellow-300 mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2">No Weather Data Available</h3>
                        <p class="opacity-80">Unable to fetch weather information. Please check your API configuration.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal -->
    <div id="weatherModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex justify-center items-center z-50 hidden">
        <div id="modalCard"
            class="rounded-xl overflow-hidden shadow-lg w-[90%] max-w-2xl text-white relative bg-cover bg-center">


            <button onclick="toggleModal(false)" class="absolute top-4 left-4  text-sm font-semibold z-10">
                ←
            </button>

            <div class="text-center py-6 px-4 bg-black/40 backdrop-blur-sm">
                <h2 id="modalCity" class="text-xl font-bold"></h2>
                <p id="modalDate" class="text-sm opacity-90"></p>

                <div class="flex items-center justify-center mt-4 gap-6">
                    <div class="flex flex-col items-center gap-1">
                        <img id="modalIcon" class="w-10 h-10" />
                        <p id="modalDescription" class="capitalize text-sm"></p>
                      </div>
                      <div class="w-px h-10 bg-white/20"></div>
                      <div class="text-right">
                        <div class="text-4xl font-bold" id="modalTemp"></div>
                        <div class="text-sm mt-1">
                          <p id="modalTempMin"></p>
                          <p id="modalTempMax"></p>
                        </div>
                      </div>
                </div>

            </div>

            <div class="bg-gray-800 text-white whitespace-nowrap px-6 py-6 grid grid-cols-3 gap-4 text-[10px] lg:text-sm ">
                <div>
                    <p><span class="font-semibold">Pressure:</span> <span id="modalPressure"></span> hPa</p>
                    <p><span class="font-semibold">Humidity:</span> <span id="modalHumidity"></span>%</p>
                    <p><span class="font-semibold">Visibility:</span> <span id="modalVisibility"></span> km</p>
                </div>

                <div class="text-center flex flex-col">
                    <i id="modalWindArrow" class="fas fa-location-arrow text-white mb-1"></i>
                    <div class="flex items-center justify-center  gap-2">
                        <p><span id="modalWindSpeed"></span> m/s</p>
                        <p><span id="modalWindDeg"></span>°</p>
                    </div>

                </div>

                <div class="text-end">
                    <p><span class="font-semibold">Sunrise:</span> <span id="modalSunrise"></span></p>
                    <p><span class="font-semibold">Sunset:</span> <span id="modalSunset"></span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(show) {
            const modal = document.getElementById('weatherModal');
            modal.classList.toggle('hidden', !show);
        }

        function openModalFromElement(el) {
            const data = el.getAttribute('data-weather');
            if (!data) return;
            try {
                const weather = JSON.parse(data);
                openModal(weather);
            } catch (e) {
                console.error("Failed to parse weather data", e);
            }
        }

        function openModal(weather) {
            document.getElementById('modalCity').textContent = `${weather.city_name}, ${weather.country}`;
            document.getElementById('modalDate').textContent =
                `${weather.local_time}, ${new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}`;
            document.getElementById('modalIcon').src = `https://openweathermap.org/img/wn/${weather.icon}@2x.png`;
            document.getElementById('modalTemp').textContent = `${weather.temperature}°C`;
            document.getElementById('modalDescription').textContent = weather.description;
            document.getElementById('modalTempMin').textContent = `Temp Min: ${weather.temp_min}°C`;
            document.getElementById('modalTempMax').textContent = `Temp Max: ${weather.temp_max}°C`;
            document.getElementById('modalPressure').textContent = weather.pressure;
            document.getElementById('modalHumidity').textContent = weather.humidity;
            document.getElementById('modalVisibility').textContent = (weather.visibility / 1000).toFixed(1);
            document.getElementById('modalWindArrow').style.transform = `rotate(${weather.wind_deg}deg)`;
            document.getElementById('modalWindSpeed').textContent = weather.wind_speed;
            document.getElementById('modalWindDeg').textContent = weather.wind_deg;
            document.getElementById('modalSunrise').textContent = weather.sunrise;
            document.getElementById('modalSunset').textContent = weather.sunset;

            // Set background image based on condition
            const condition = weather.weather_main.toLowerCase();
            const backgroundMap = {
                'clear': 'card_background.png',
                'clouds': 'card_background2.png',
                'rain': 'card_background3.png',
                'mist': 'card_background4.png',
                'snow': 'card_background5.png',
            };
            const backgroundImage = `/images/${backgroundMap[condition] || 'card_background1.png'}`;
            document.getElementById('modalCard').style.backgroundImage = `url('${backgroundImage}')`;

            toggleModal(true);
        }
    </script>
</body>

</html>
