@extends('themes.default.layout')

@section('title', $article->title)

@section('content')
<section class="article-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 80px 0 40px; color: white;">
    <div class="container">
        @if($article->category)
            <a href="{{ route('articles.category', $article->category->slug) }}" 
               style="display: inline-block; background: {{ $article->category->color }}; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; text-decoration: none; margin-bottom: 20px;">
                {{ $article->category->name }}
            </a>
        @endif
        <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 24px; line-height: 1.2;">{{ $article->title }}</h1>
        <div style="display: flex; align-items: center; gap: 24px; color: #94a3b8; font-size: 15px; flex-wrap: wrap;">
            <span style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-user-circle"></i>
                {{ $article->author->name }}
            </span>
            <span style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-calendar"></i>
                {{ $article->published_at->format('F d, Y') }}
            </span>
            <span style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-clock"></i>
                {{ $article->reading_time }} min read
            </span>
            <span style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-eye"></i>
                {{ number_format($article->views_count) }} views
            </span>
        </div>
    </div>
</section>

<section class="article-content" style="padding: 60px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 60px;">
            {{-- Main Content --}}
            <article>
                @if($article->featured_image)
                <div style="border-radius: 16px; overflow: hidden; margin-bottom: 40px;">
                    <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" style="width: 100%; height: auto;">
                </div>
                @endif
                
                <div class="article-body" style="font-size: 18px; line-height: 1.8; color: var(--color-text);">
                    {!! $article->content !!}
                </div>
                
                {{-- Tags --}}
                @if($article->tags->count() > 0)
                <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--color-border);">
                    <h4 style="font-size: 16px; margin-bottom: 16px;">{{ __('admin.articles.tags') }}:</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach($article->tags as $tag)
                            <span style="background: var(--color-bg-alt); padding: 8px 16px; border-radius: 20px; font-size: 14px;">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </article>
            
            {{-- Sidebar --}}
            <aside>
                {{-- Author Box --}}
                <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <h4 style="font-size: 16px; margin-bottom: 16px;">Written by</h4>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 56px; height: 56px; background: var(--color-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600;">{{ $article->author->name }}</div>
                            <div style="font-size: 14px; color: var(--color-text-light);">Staff Writer</div>
                        </div>
                    </div>
                </div>
                
                {{-- Related Articles --}}
                @if($related->count() > 0)
                <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <h4 style="font-size: 16px; margin-bottom: 16px;">{{ __('frontend.articles.related_articles') }}</h4>
                    @foreach($related as $rel)
                        <a href="{{ route('articles.show', $rel->slug) }}" style="display: block; padding: 12px 0; border-bottom: 1px solid var(--color-border); text-decoration: none; color: inherit;">
                            <div style="font-weight: 500; margin-bottom: 4px;">{{ $rel->title }}</div>
                            <div style="font-size: 13px; color: var(--color-text-light);">{{ $rel->published_at->format('M d, Y') }}</div>
                        </a>
                    @endforeach
                </div>
                @endif
            </aside>
        </div>
    </div>
</section>

<style>
.article-body p { margin-bottom: 1.5em; }
.article-body h2 { font-size: 28px; font-weight: 700; margin: 40px 0 20px; }
.article-body h3 { font-size: 22px; font-weight: 600; margin: 30px 0 15px; }
.article-body img { max-width: 100%; border-radius: 8px; margin: 20px 0; }
.article-body blockquote { border-left: 4px solid var(--color-primary); padding-left: 20px; margin: 20px 0; font-style: italic; color: var(--color-text-light); }
.article-body ul, .article-body ol { margin: 20px 0; padding-left: 30px; }
.article-body li { margin-bottom: 8px; }
</style>
@endsection
