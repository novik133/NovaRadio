<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\TeamMember;
use App\Models\ScheduleShow;
use App\Models\Article;
use App\Models\Event;
use App\Services\AzuraCastService;
use App\Services\SeoService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private AzuraCastService $azuraCast,
        private SeoService $seo
    ) {}

    public function index()
    {
        $nowPlaying = $this->azuraCast->getNowPlaying();
        $streamUrl = $this->azuraCast->getStreamUrl();
        $recentTracks = $this->azuraCast->getRecentTracks(5);
        $listeners = $this->azuraCast->getListenersCount();
        
        // Check if stream is live
        $isLive = !empty($nowPlaying) && isset($nowPlaying['now_playing']);
        
        // Articles
        $featuredArticle = Article::published()
            ->featured()
            ->with('category')
            ->latest()
            ->first();
        
        $recentArticles = Article::published()
            ->with('category')
            ->latest()
            ->take(3)
            ->get();
        
        // Events
        $upcomingEvents = Event::upcoming()
            ->with('featuredDj')
            ->take(3)
            ->get();
        
        // DJs
        $featuredDjs = TeamMember::active()
            ->whereHas('djProfile', fn($q) => $q->where('is_resident', true))
            ->with('djProfile')
            ->take(4)
            ->get();
        
        // Today's schedule
        $todayShows = ScheduleShow::forDay(strtolower(now()->format('l')))
            ->active()
            ->ordered()
            ->get();

        return view('themes.default.home', compact(
            'nowPlaying',
            'streamUrl',
            'recentTracks',
            'listeners',
            'isLive',
            'featuredArticle',
            'recentArticles',
            'upcomingEvents',
            'featuredDjs',
            'todayShows'
        ))->with($this->seo->forHome());
    }
}
