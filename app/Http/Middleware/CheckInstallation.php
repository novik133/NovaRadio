<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check for install routes
        if ($request->is('install') || $request->is('install/*')) {
            return $next($request);
        }

        // Check if .env exists and APP_INSTALLED is true
        $envExists = File::exists(base_path('.env'));
        $isInstalled = $envExists && env('APP_INSTALLED', false);

        // If not installed, redirect to installer
        if (!$isInstalled) {
            return redirect('/install');
        }

        return $next($request);
    }
}
