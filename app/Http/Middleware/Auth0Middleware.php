<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Auth0Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            Log::info('Auth0Middleware: User not authenticated, redirecting to login', [
                'path' => $request->path(),
                'url' => $request->url(),
                'method' => $request->method()
            ]);

            // For API routes, return JSON response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}