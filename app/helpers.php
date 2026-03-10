<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get a setting value from the database with caching
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        try {
            // Check if database is configured and accessible
            if (!config('database.connections.mysql.database')) {
                return $default;
            }

            return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
                $setting = Setting::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            // If database is not accessible, return default
            return $default;
        }
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings as a collection
     *
     * @return \Illuminate\Support\Collection
     */
    function settings()
    {
        try {
            // Check if database is configured and accessible
            if (!config('database.connections.mysql.database')) {
                return collect([]);
            }

            return Cache::remember('settings.all', 3600, function () {
                return Setting::all()->pluck('value', 'key');
            });
        } catch (\Exception $e) {
            // If database is not accessible, return empty collection
            return collect([]);
        }
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Get a theme asset URL
     *
     * @param string $path
     * @return string
     */
    function theme_asset(string $path)
    {
        $theme = setting('active_theme', 'default');
        return asset("themes/{$theme}/{$path}");
    }
}

if (!function_exists('active_theme')) {
    /**
     * Get the active theme name
     *
     * @return string
     */
    function active_theme()
    {
        return setting('active_theme', 'default');
    }
}
