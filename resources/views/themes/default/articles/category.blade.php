@extends('themes.default.layout')

@section('title', $category->name . ' - News')
@section('description', $category->description ?? 'Articles in ' . $category->name)

@section('content')
<section class="category-hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 80px 0; color: white;">
    <div class="container">
        <div style="display: inline-block; background: {{ $category->color }}; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 16px;">
            <i class="fas fa-folder"></i> Category
        </div>
        <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 16px;">{{ $category->name }}</h1>
        @if($category->description)
            <p style="font-size: 20px; color: #94a3b8; max-width: 600px;">{{ $category->description }}</p>
        @endif
    </div>
</section>

<section class="articles-content" style="padding: 60px 0;">
    <div class="container">
        {{-- Categories Filter --}}
        <div class="categories-filter" style="margin-bottom: 40px;">
            <a href="{{ route('articles.index') }}" class="btn btn-secondary">All</a>
            @foreach($categories as $cat)
                <a href="{{ route('articles.category', $cat->slug) }}" class="btn {{ $cat->id === $category->id ? 'btn-primary' : 'btn-secondary' }}" style="margin-left: 8px;">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        {{-- Articles Grid --}}
        @if($articles->count())
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 32px;">
                @foreach($articles as $article)
                    <article style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                        <a href="{{ route('articles.show', $article->slug) }}" style="text-decoration: none; color: inherit;">
                            <div style="height: 200px; overflow: hidden;">
                                @if($article->featured_image)
                                    <img src="{{ asset($article->featured_image) }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-newspaper" style="font-size: 48px; color: white;"></i>
                                    </div>
                                @endif
                            </div>
                            <div style="padding: 24px;">
                                <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                                    @if($article->category)
                                        <span style="background: {{ $article->category->color }}20; color: {{ $article->category->color }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                            {{ $article->category->name }}
                                        </span>
                                    @endif
                                </div>
                                <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 12px; color: var(--color-text); line-height: 1.4;">{{ $article->title }}</h3>
                                <p style="font-size: 15px; color: var(--color-text-light); line-height: 1.6; margin-bottom: 16px;">{{ Str::limit(strip_tags($article->excerpt ?? $article->content), 100) }}</p>
                                <div style="display: flex; align-items: center; gap: 16px; color: var(--color-text-light); font-size: 13px;">
                                    <span><i class="fas fa-user"></i> {{ $article->author->name }}</span>
                                    <span><i class="fas fa-calendar"></i> {{ $article->published_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
            
            <div style="margin-top: 40px;">
                {{ $articles->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 16px;">
                <i class="fas fa-inbox" style="font-size: 64px; color: var(--color-text-light); margin-bottom: 16px;"></i>
                <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 8px;">No articles yet</h3>
                <p style="color: var(--color-text-light);">Articles in this category will appear here.</p>
            </div>
        @endif
    </div>
</section>
@endsection
