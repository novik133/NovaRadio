<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Clear cache to ensure fresh value is read
            \Illuminate\Support\Facades\Cache::forget('setting.site_language');
            
            $locale = Setting::where('key', 'site_language')->value('value');

            if ($locale && in_array($locale, ['en', 'pl', 'es', 'fr'])) {
                app()->setLocale($locale);
                // Also set the Laravel application locale for translations
                config(['app.locale' => $locale]);
            }
        } catch (\Exception $e) {
            // Database may not be available (e.g., during install)
        }

        return $next($request);
    }
}
