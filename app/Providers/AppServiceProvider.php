<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\AzuraCastService::class);
        $this->app->singleton(\App\Services\UpdateService::class);
        $this->app->singleton(\App\Services\SeoService::class);
    }

    public function boot(): void
    {
        try {
            \Illuminate\Support\Facades\View::share('siteName', \App\Models\Setting::get('site_name', 'NovaRadio'));
            \Illuminate\Support\Facades\View::share('siteTagline', \App\Models\Setting::get('site_tagline', 'Internet Radio'));
            \Illuminate\Support\Facades\View::share('siteLogo', \App\Models\Setting::get('site_logo'));
        } catch (\Exception $e) {
            // Database not configured yet - use defaults
            \Illuminate\Support\Facades\View::share('siteName', 'NovaRadio');
            \Illuminate\Support\Facades\View::share('siteTagline', 'Internet Radio');
            \Illuminate\Support\Facades\View::share('siteLogo', null);
        }
    }
}
