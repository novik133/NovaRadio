<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Api\NowPlayingController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\DjProfileController;
use App\Http\Controllers\Install\InstallController;

// Installer Routes (only available when not installed)
Route::get('/install', [InstallController::class, 'index'])->name('install');
Route::post('/install', [InstallController::class, 'store']);

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/team', [TeamController::class, 'index'])->name('team');
Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');

// Articles
Route::get('/news', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/news/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/news/category/{slug}', [ArticleController::class, 'byCategory'])->name('articles.category');
Route::get('/news/tag/{slug}', [ArticleController::class, 'byTag'])->name('articles.tag');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// DJ Profiles
Route::get('/dj/{slug}', [TeamController::class, 'show'])->name('dj.show');

// API Routes
Route::get('/api/nowplaying', [NowPlayingController::class, 'index'])->name('api.nowplaying');

// PWA Routes
Route::get('/manifest.json', function () {
    return response()->file(public_path('pwa/manifest.json'));
})->name('pwa.manifest');

Route::get('/sw.js', function () {
    return response()->file(public_path('pwa/sw.js'), ['Content-Type' => 'application/javascript']);
})->name('pwa.sw');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('pages', AdminPageController::class);
        Route::resource('team', AdminTeamController::class);
        Route::post('/team/{member}/upload-photo', [AdminTeamController::class, 'uploadPhoto'])->name('team.upload-photo');
        Route::resource('schedule', AdminScheduleController::class);

        Route::get('/updates', [UpdateController::class, 'index'])->name('updates.index');
        Route::post('/updates/check', [UpdateController::class, 'check'])->name('updates.check');
        Route::post('/updates/install', [UpdateController::class, 'install'])->name('updates.install');
        
        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/upload-logo', [SettingsController::class, 'uploadLogo'])->name('settings.upload-logo');
        Route::post('/settings/upload-favicon', [SettingsController::class, 'uploadFavicon'])->name('settings.upload-favicon');
        Route::post('/settings/upload-hero', [SettingsController::class, 'uploadHero'])->name('settings.upload-hero');
        Route::get('/settings/clear-cache/{type}', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
        
        // Themes
        Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index');
        Route::post('/themes/upload', [ThemeController::class, 'upload'])->name('themes.upload');
        Route::post('/themes/{name}/activate', [ThemeController::class, 'activate'])->name('themes.activate');
        Route::get('/themes/{name}/preview', [ThemeController::class, 'preview'])->name('themes.preview');
        Route::delete('/themes/{name}', [ThemeController::class, 'delete'])->name('themes.delete');
        
        // Media
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
        Route::post('/media/folder', [MediaController::class, 'createFolder'])->name('media.create-folder');
        Route::post('/media/rename', [MediaController::class, 'rename'])->name('media.rename');
        Route::put('/media/{id}', [MediaController::class, 'update'])->name('media.update');
        Route::delete('/media', [MediaController::class, 'delete'])->name('media.delete');
        Route::get('/media/browse', [MediaController::class, 'browse'])->name('media.browse');
        // Articles
        Route::resource('articles', AdminArticleController::class);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('tags', TagController::class)->except(['show']);
        
        // Events
        Route::resource('events', AdminEventController::class);
        
        // DJ Profile (self-management)
        Route::get('/my-profile', [DjProfileController::class, 'edit'])->name('dj-profile.edit');
        Route::put('/my-profile', [DjProfileController::class, 'update'])->name('dj-profile.update');
        Route::post('/my-profile/upload-photo', [DjProfileController::class, 'uploadPhoto'])->name('dj-profile.upload-photo');
        
        // Admin Profile (for admin/editor users)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload-avatar');
    });
});
