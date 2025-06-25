                     Weather Dasboard Application(This Laravel Based Application)

 
 * Features
    Weather Inforamtion Web/API
    Authentication & Authorization

 * Technologies Used
    Laravel 12
    Auth0 Laravel SDK
    OpenWeatherMap API
    Tailwind CSS 
    Font Awesome

  * Setup Instruction
    1.Clone the repo (**development branch**)
    2.Composer Install
    3.npm Install 
    4.copy .env.example and rename it .env also write on the terminal php artisan key:generate
    5.Configure OpenWeather API
    6.Configure Auth0
    7.run this command
        *php php artisan serve --host=localhost
        *npm run dev

 * .env Configuration

    OpenWeather API
    OPENWEATHER_API_KEY=your_openweather_api_key
    OPENWEATHER_BASE_URL=http://api.openweathermap.org/data/2.5/weather

    Auth0 Settings
    AUTH0_DOMAIN=your-tenant.us.auth0.com
    AUTH0_CLIENT_ID=your_client_id
    AUTH0_CLIENT_SECRET=your_client_secret
    AUTH0_REDIRECT_URI=http://localhost:8000/callback
    AUTH0_LOGOUT_URL=http://localhost:8000
    AUTH0_AUDIENCE=https://your-tenant.us.auth0.com/api/v2/


 * Special Note: Auth0 & Localhost Configuration
    If your AUTH0_REDIRECT_URI and AUTH0_LOGOUT_UR are set to:
    AUTH0_REDIRECT_URI=http://localhost:8000/callback
    AUTH0_LOGOUT_URL=http://localhost:8000
    Then you must run php artisan serve --host=localhost otherwise php artisan serve

