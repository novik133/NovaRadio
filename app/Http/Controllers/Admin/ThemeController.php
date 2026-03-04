<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Setting;
use ZipArchive;

class ThemeController extends Controller
{
    private $themesPath;
    private $activeTheme;
    
    public function __construct()
    {
        $this->themesPath = public_path('themes');
        $this->activeTheme = Setting::get('active_theme', 'default');
    }
    
    public function index()
    {
        $themes = $this->getAllThemes();
        $activeTheme = $this->activeTheme;
        
        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }
    
    public function upload(Request $request)
    {
        $request->validate([
            'theme' => 'required|file|mimes:zip|max:10240' // 10MB max
        ]);
        
        $file = $request->file('theme');
        $tempPath = storage_path('app/temp');
        
        // Create temp directory if not exists
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }
        
        // Extract ZIP
        $zip = new ZipArchive();
        $zipPath = $tempPath . '/' . $file->getClientOriginalName();
        $file->move($tempPath, $file->getClientOriginalName());
        
        if ($zip->open($zipPath) === true) {
            // Get theme folder name from first folder in zip
            $themeName = null;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);
                $parts = explode('/', $fileName);
                if (count($parts) > 1 && !$themeName) {
                    $themeName = $parts[0];
                    break;
                }
            }
            
            if (!$themeName) {
                $zip->close();
                File::delete($zipPath);
                return redirect()->back()->with('error', 'Invalid theme structure. Theme must be in a folder.');
            }
            
            // Check if theme already exists
            if (File::exists($this->themesPath . '/' . $themeName)) {
                $zip->close();
                File::delete($zipPath);
                return redirect()->back()->with('error', 'Theme "' . $themeName . '" already exists.');
            }
            
            // Extract theme
            $zip->extractTo($this->themesPath);
            $zip->close();
            File::delete($zipPath);
            
            // Validate theme structure
            $themePath = $this->themesPath . '/' . $themeName;
            if (!$this->isValidTheme($themePath)) {
                // Clean up invalid theme
                File::deleteDirectory($themePath);
                return redirect()->back()->with('error', 'Invalid theme structure. Missing required files (css/app.css or views/layout.blade.php).');
            }
            
            return redirect()->route('admin.themes.index')
                ->with('success', 'Theme "' . $themeName . '" installed successfully!');
        }
        
        File::delete($zipPath);
        return redirect()->back()->with('error', 'Could not open theme archive.');
    }
    
    public function activate($themeName)
    {
        $themePath = $this->themesPath . '/' . $themeName;
        
        if (!File::exists($themePath)) {
            return redirect()->back()->with('error', 'Theme not found.');
        }
        
        if (!$this->isValidTheme($themePath)) {
            return redirect()->back()->with('error', 'Invalid theme structure.');
        }
        
        // Save active theme to database
        Setting::set('active_theme', $themeName, 'string', 'appearance');
        
        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme "' . $themeName . '" activated!');
    }
    
    public function delete($themeName)
    {
        // Cannot delete active theme
        if ($themeName === $this->activeTheme) {
            return redirect()->back()->with('error', 'Cannot delete active theme. Please activate another theme first.');
        }
        
        // Cannot delete default theme
        if ($themeName === 'default') {
            return redirect()->back()->with('error', 'Cannot delete the default theme.');
        }
        
        $themePath = $this->themesPath . '/' . $themeName;
        
        if (!File::exists($themePath)) {
            return redirect()->back()->with('error', 'Theme not found.');
        }
        
        // Delete theme directory
        File::deleteDirectory($themePath);
        
        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme "' . $themeName . '" deleted successfully!');
    }
    
    public function preview($themeName)
    {
        $themePath = $this->themesPath . '/' . $themeName;
        
        if (!File::exists($themePath)) {
            abort(404, 'Theme not found');
        }
        
        // Return theme info for preview modal
        $info = $this->getThemeInfo($themeName);
        
        return response()->json($info);
    }
    
    private function getAllThemes()
    {
        $themes = [];
        
        if (!File::exists($this->themesPath)) {
            return $themes;
        }
        
        $directories = File::directories($this->themesPath);
        
        foreach ($directories as $dir) {
            $themeName = basename($dir);
            $themes[] = $this->getThemeInfo($themeName);
        }
        
        return $themes;
    }
    
    private function getThemeInfo($themeName)
    {
        $themePath = $this->themesPath . '/' . $themeName;
        $info = [
            'name' => $themeName,
            'active' => $themeName === $this->activeTheme,
            'path' => $themePath,
            'version' => '1.0',
            'author' => 'Unknown',
            'description' => 'No description available',
            'screenshot' => null,
            'has_css' => false,
            'has_js' => false,
        ];
        
        // Check for theme.json
        $jsonPath = $themePath . '/theme.json';
        if (File::exists($jsonPath)) {
            $json = json_decode(File::get($jsonPath), true);
            if ($json) {
                $info['version'] = $json['version'] ?? '1.0';
                $info['author'] = $json['author'] ?? 'Unknown';
                $info['description'] = $json['description'] ?? 'No description available';
            }
        }
        
        // Check for screenshot
        $screenshotExtensions = ['png', 'jpg', 'jpeg'];
        foreach ($screenshotExtensions as $ext) {
            $screenshotPath = $themePath . '/screenshot.' . $ext;
            if (File::exists($screenshotPath)) {
                $info['screenshot'] = asset('themes/' . $themeName . '/screenshot.' . $ext);
                break;
            }
        }
        
        // Check for CSS and JS
        $info['has_css'] = File::exists($themePath . '/css/app.css');
        $info['has_js'] = File::exists($themePath . '/js/app.js');
        
        return $info;
    }
    
    private function isValidTheme($themePath)
    {
        // A valid theme must have at least CSS or views
        $hasCss = File::exists($themePath . '/css/app.css');
        $hasViews = File::exists($themePath . '/views/layout.blade.php') || 
                    File::exists(resource_path('views/themes/' . basename($themePath) . '/layout.blade.php'));
        
        return $hasCss || $hasViews;
    }
}
