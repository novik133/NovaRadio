@extends('themes.default.layout')

@section('title', 'News & Articles')

@section('content')
<section class="articles-hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 80px 0; color: white;">
    <div class="container">
        <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 16px;">Latest News</h1>
        <p style="font-size: 20px; color: #94a3b8;">Stay updated with the latest from NovaRadio</p>
    </div>
</section>

<section class="articles-content" style="padding: 60px 0;">
    <div class="container">
        {{-- Featured Article --}}
        @if($featured)
        <div class="featured-article" style="margin-bottom: 60px;">
            <a href="{{ route('articles.show', $featured->slug) }}" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; text-decoration: none; color: inherit;">
                <div style="border-radius: 16px; overflow: hidden; height: 400px;">
                    @if($featured->featured_image)
                        <img src="{{ $featured->featured_image }}" alt="{{ $featured->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: var(--color-bg-alt); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="font-size: 64px; color: var(--color-text-light);"></i>
                        </div>
                    @endif
                </div>
                <div style="display: flex; flex-direction: column; justify-content: center;">
                    <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                        @if($featured->category)
                            <span style="background: {{ $featured->category->color }}; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $featured->category->name }}
                            </span>
                        @endif
                        <span style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-star"></i> Featured
                        </span>
                    </div>
                    <h2 style="font-size: 32px; font-weight: 700; margin-bottom: 16px; color: var(--color-text);">{{ $featured->title }}</h2>
                    <p style="font-size: 18px; color: var(--color-text-light); line-height: 1.6; margin-bottom: 24px;">{{ $featured->excerpt }}</p>
                    <div style="display: flex; align-items: center; gap: 16px; color: var(--color-text-light); font-size: 14px;">
                        <span><i class="fas fa-user"></i> {{ $featured->author->name }}</span>
                        <span><i class="fas fa-calendar"></i> {{ $featured->published_at->format('M d, Y') }}</span>
                        <span><i class="fas fa-clock"></i> {{ $featured->reading_time }} min read</span>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Categories Filter --}}
        <div class="categories-filter" style="margin-bottom: 40px;">
            <a href="{{ route('articles.index') }}" class="btn {{ !request('category') ? 'btn-primary' : 'btn-secondary' }}">All</a>
            @foreach($categories as $cat)
                <a href="{{ route('articles.category', $cat->slug) }}" class="btn {{ request('category') == $cat->slug ? 'btn-primary' : 'btn-secondary' }}" style="margin-left: 8px;">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        {{-- Articles Grid --}}
        <div class="articles-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
            @foreach($articles as $article)
            <article class="article-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: transform 0.2s;">
                <a href="{{ route('articles.show', $article->slug) }}" style="text-decoration: none; color: inherit;">
                    <div style="height: 200px; overflow: hidden;">
                        @if($article->featured_image)
                            <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;">
                        @else
                            <div style="width: 100%; height: 100%; background: var(--color-bg-alt); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="font-size: 48px; color: var(--color-text-light);"></i>
                            </div>
                        @endif
                    </div>
                    <div style="padding: 24px;">
                        @if($article->category)
                            <span style="color: {{ $article->category->color }}; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                {{ $article->category->name }}
                            </span>
                        @endif
                        <h3 style="font-size: 20px; font-weight: 700; margin: 12px 0; color: var(--color-text); line-height: 1.4;">{{ $article->title }}</h3>
                        <p style="color: var(--color-text-light); font-size: 14px; line-height: 1.6; margin-bottom: 16px;">{{ Str::limit($article->excerpt, 100) }}</p>
                        <div style="display: flex; align-items: center; gap: 12px; color: var(--color-text-light); font-size: 13px;">
                            <span>{{ $article->published_at->format('M d') }}</span>
                            <span>•</span>
                            <span>{{ $article->reading_time }} min read</span>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="margin-top: 40px;">
            {{ $articles->links() }}
        </div>
    </div>
</section>
@endsection
