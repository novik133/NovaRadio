<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        
        $grouped = [
            'general' => ['site_name', 'site_tagline', 'site_description', 'site_language', 'timezone'],
            'contact' => ['contact_email', 'contact_phone', 'contact_address', 'admin_name'],
            'social' => ['social_facebook', 'social_twitter', 'social_instagram', 'social_youtube', 'social_discord', 'social_tiktok'],
            'seo' => ['seo_meta_title', 'seo_meta_description', 'seo_keywords', 'google_analytics'],
            'appearance' => ['hero_image', 'favicon', 'logo_light', 'logo_dark', 'primary_color', 'secondary_color'],
            'streaming' => ['azuracast_enabled', 'station_name', 'stream_url', 'player_autoplay'],
            'advanced' => ['maintenance_mode', 'cache_enabled', 'debug_mode', 'registration_enabled'],
        ];
        
        return view('admin.settings.index', compact('settings', 'grouped'));
    }
    
    public function update(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            Setting::set($key, $value, 'string', 'general');
        }
        
        // Clear settings cache
        Cache::forget('settings');
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
    
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,svg|max:2048',
            'type' => 'required|in:light,dark'
        ]);
        
        $file = $request->file('logo');
        $filename = 'logo_' . $request->type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/logos', $filename);
        
        $key = 'logo_' . $request->type;
        Setting::set($key, str_replace('public/', 'storage/', $path), 'string', 'appearance');
        
        return response()->json([
            'success' => true,
            'url' => asset(str_replace('public/', 'storage/', $path))
        ]);
    }
    
    public function uploadFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|mimes:ico,png,svg|max:1024'
        ]);
        
        $file = $request->file('favicon');
        $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public', $filename);
        
        Setting::set('favicon', str_replace('public/', 'storage/', $path), 'string', 'appearance');
        
        return response()->json([
            'success' => true,
            'url' => asset(str_replace('public/', 'storage/', $path))
        ]);
    }
    
    public function uploadHero(Request $request)
    {
        $request->validate([
            'hero_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);
        
        $file = $request->file('hero_image');
        $filename = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/images/hero', $filename);
        
        Setting::set('hero_image', str_replace('public/', 'storage/', $path), 'string', 'appearance');
        
        return response()->json([
            'success' => true,
            'url' => asset(str_replace('public/', 'storage/', $path))
        ]);
    }
    
    public function clearCache($type)
    {
        switch($type) {
            case 'config':
                Cache::forget('settings');
                break;
            case 'view':
                break;
            case 'all':
                Cache::flush();
                break;
        }
        
        return redirect()->route('admin.settings.index', ['tab' => 'advanced'])
            ->with('success', ucfirst($type) . ' cache cleared!');
    }
}
