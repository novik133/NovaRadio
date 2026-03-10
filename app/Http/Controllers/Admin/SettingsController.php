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
        
        // Only admin can change site language
        if (!auth()->user()->isAdmin()) {
            unset($data['site_language']);
        }
        
        // Map setting keys to their correct groups
        $groupMap = [
            'site_name' => 'general', 'site_tagline' => 'general', 'site_description' => 'general',
            'site_language' => 'general', 'timezone' => 'general',
            'contact_email' => 'contact', 'contact_phone' => 'contact',
            'contact_address' => 'contact', 'admin_name' => 'contact',
            'social_facebook' => 'social', 'social_twitter' => 'social',
            'social_instagram' => 'social', 'social_youtube' => 'social',
            'social_discord' => 'social', 'social_tiktok' => 'social',
            'seo_meta_title' => 'seo', 'seo_meta_description' => 'seo',
            'seo_keywords' => 'seo', 'google_analytics' => 'seo',
            'primary_color' => 'appearance', 'secondary_color' => 'appearance',
            'azuracast_enabled' => 'streaming', 'station_name' => 'streaming',
            'stream_url' => 'streaming', 'player_autoplay' => 'streaming',
            'maintenance_mode' => 'advanced', 'cache_enabled' => 'advanced',
            'debug_mode' => 'advanced', 'registration_enabled' => 'advanced',
        ];
        
        // Checkbox fields need special handling: if unchecked, they won't be in the request
        $checkboxFields = [
            'azuracast_enabled', 'player_autoplay',
            'maintenance_mode', 'cache_enabled', 'debug_mode', 'registration_enabled',
        ];
        
        // Set unchecked checkboxes to '0'
        foreach ($checkboxFields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = '0';
            }
        }
        
        foreach ($data as $key => $value) {
            $group = $groupMap[$key] ?? null;
            if ($group === null) {
                continue;
            }
            Setting::set($key, $value, null, $group);
        }
        
        // Clear all setting caches
        foreach (array_keys($data) as $key) {
            Cache::forget("setting.{$key}");
        }
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
