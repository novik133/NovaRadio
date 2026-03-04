@extends('themes.default.layout')

@section('content')
<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-badge">
            <span class="live-dot"></span>
            Live Broadcasting
        </div>
        <h1>Your Soundtrack to Life</h1>
        <p class="hero-description">Experience the best music curated just for you. Listen live 24/7 and discover your next favorite song.</p>
        <div class="hero-buttons">
            <a href="#player" class="btn btn-primary">
                <i class="fas fa-play"></i> Start Listening
            </a>
            <a href="{{ route('page.show', 'about') }}" class="btn btn-secondary">Learn More</a>
        </div>
    </div>
</section>

<!-- Player Section -->
<section id="player" class="player-section">
    <div class="container">
        <div class="radio-player">
            @if($streamUrl)
                <div class="player-main">
                    <button id="play-button" class="play-button">
                        <i class="fas fa-play"></i>
                    </button>
                    <div class="track-info">
                        <div class="track-title">Loading...</div>
                        <div class="track-artist">Connecting to stream...</div>
                    </div>
                    <div class="volume-control">
                        <i class="fas fa-volume-up"></i>
                        <input type="range" id="volume-slider" class="volume-slider" min="0" max="100" value="80">
                    </div>
                </div>
                <div class="listeners">
                    <i class="fas fa-users"></i>
                    <span><span class="listeners-count">0</span> listeners tuned in</span>
                </div>
                <audio id="audio-player" preload="none">
                    <source src="{{ $streamUrl }}" type="audio/mpeg">
                </audio>
            @else
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-broadcast-tower" style="font-size: 48px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                    <p style="color: var(--color-text-muted);">Radio stream is currently unavailable. Please check back later.</p>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Recent Tracks -->
@if(!empty($recentTracks))
<section class="recent-tracks">
    <div class="container">
        <h2 class="section-title">Recently Played</h2>
        <div class="tracks-list">
            @foreach($recentTracks as $track)
                <div class="track-item">
                    <span class="track-time">{{ date('H:i', $track['played_at']) }}</span>
                    <div class="track-thumb">
                        @if(!empty($track['song']['art']))
                            <img src="{{ $track['song']['art'] }}" alt="">
                        @else
                            <i class="fas fa-music"></i>
                        @endif
                    </div>
                    <div class="track-details">
                        <div class="track-name">{{ $track['song']['title'] ?? 'Unknown' }}</div>
                        <div class="track-meta">{{ $track['song']['artist'] ?? 'Unknown Artist' }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Article -->
@if($featuredArticle)
<section class="featured-article-section" style="padding: 80px 0; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container">
        <h2 style="color: white; font-size: 32px; font-weight: 700; margin-bottom: 40px; text-align: center;">
            <i class="fas fa-newspaper" style="color: var(--color-secondary);"></i> Latest News
        </h2>
        <a href="{{ route('articles.show', $featuredArticle->slug) }}" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; text-decoration: none; color: inherit;">
            <div style="border-radius: 16px; overflow: hidden; height: 350px;">
                @if($featuredArticle->featured_image)
                    <img src="{{ $featuredArticle->featured_image }}" alt="{{ $featuredArticle->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>
            <div style="display: flex; flex-direction: column; justify-content: center;">
                @if($featuredArticle->category)
                    <span style="display: inline-block; background: {{ $featuredArticle->category->color }}; color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 16px; width: fit-content;">
                        {{ $featuredArticle->category->name }}
                    </span>
                @endif
                <h3 style="color: white; font-size: 28px; font-weight: 700; margin-bottom: 16px;">{{ $featuredArticle->title }}</h3>
                <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; margin-bottom: 24px;">{{ $featuredArticle->excerpt }}</p>
                <span style="color: var(--color-primary); font-weight: 600;">Read More <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>
    </div>
</section>
@endif

<!-- Recent Articles -->
@if($recentArticles->count() > 0)
<section style="padding: 60px 0;">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 class="section-title" style="margin: 0;">More News</h2>
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">View All</a>
        </div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            @foreach($recentArticles as $article)
                <a href="{{ route('articles.show', $article->slug) }}" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-decoration: none; color: inherit;">
                    <div style="height: 180px;">
                        @if($article->featured_image)
                            <img src="{{ $article->featured_image }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <div style="padding: 20px;">
                        @if($article->category)
                            <span style="color: {{ $article->category->color }}; font-size: 12px; font-weight: 600;">{{ $article->category->name }}</span>
                        @endif
                        <h4 style="margin: 8px 0; font-size: 18px; font-weight: 600;">{{ $article->title }}</h4>
                        <p style="color: #64748b; font-size: 14px;">{{ Str::limit($article->excerpt, 80) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Upcoming Events -->
@if($upcomingEvents->count() > 0)
<section style="padding: 80px 0; background: var(--color-bg-alt);">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <h2 class="section-title" style="margin: 0;"><i class="fas fa-calendar-check" style="color: var(--color-primary);"></i> Upcoming Events</h2>
            <a href="{{ route('events.index') }}" class="btn btn-secondary">View All Events</a>
        </div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
            @foreach($upcomingEvents as $event)
                <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                    <div style="height: 180px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); position: relative;">
                        @if($event->image)
                            <img src="{{ $event->image }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                        <div style="position: absolute; top: 16px; left: 16px; background: white; padding: 8px 16px; border-radius: 8px; text-align: center;">
                            <div style="font-size: 12px; color: var(--color-text-light); text-transform: uppercase;">{{ $event->start_date->format('M') }}</div>
                            <div style="font-size: 24px; font-weight: 700; color: var(--color-primary);">{{ $event->start_date->format('d') }}</div>
                        </div>
                    </div>
                    <div style="padding: 24px;">
                        <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">{{ $event->title }}</h4>
                        <p style="color: #64748b; margin-bottom: 16px;"><i class="fas fa-map-marker-alt"></i> {{ $event->venue }}, {{ $event->city }}</p>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary" style="width: 100%; text-align: center;">Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured DJs -->
@if($featuredDjs->count() > 0)
<section style="padding: 80px 0;">
    <div class="container">
        <h2 class="section-title" style="text-align: center; margin-bottom: 40px;">
            <i class="fas fa-users" style="color: var(--color-secondary);"></i> Resident DJs
        </h2>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
            @foreach($featuredDjs as $dj)
                <a href="{{ route('dj.show', $dj->slug) }}" style="text-align: center; text-decoration: none; color: inherit; padding: 24px; background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    @if($dj->photo)
                        <img src="{{ $dj->photo }}" alt="{{ $dj->name }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 16px; border: 3px solid var(--color-primary);">
                    @else
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--color-primary); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; color: white; font-size: 40px;">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <h4 style="font-size: 18px; font-weight: 700;">{{ $dj->djProfile->stage_name ?? $dj->name }}</h4>
                    <p style="color: var(--color-primary); font-size: 14px; font-weight: 500;">{{ $dj->djProfile->genre ?? $dj->role }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Why Listen With Us</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-music"></i>
                </div>
                <h3>Curated Playlists</h3>
                <p>Handpicked tracks by our expert DJs, updated daily with fresh music.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Live Shows</h3>
                <p>Exclusive live performances and interviews with emerging artists.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Anywhere Access</h3>
                <p>Listen on any device, anywhere in the world. 24/7 streaming.</p>
            </div>
        </div>
    </div>
</section>
@endsection
