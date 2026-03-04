<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $featured = Article::published()
            ->featured()
            ->with(['category', 'author'])
            ->latest()
            ->first();
        
        $categories = Category::ordered()->get();
        
        return view('themes.default.articles.index', compact('articles', 'featured', 'categories'));
    }
    
    public function show($slug)
    {
        $article = Article::published()
            ->with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();
        
        $article->incrementViews();
        
        $related = Article::published()
            ->where('id', '!=', $article->id)
            ->where(function($q) use ($article) {
                if ($article->category_id) {
                    $q->where('category_id', $article->category_id);
                }
            })
            ->take(3)
            ->get();
        
        return view('themes.default.articles.show', compact('article', 'related'));
    }
    
    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $articles = Article::published()
            ->where('category_id', $category->id)
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $categories = Category::withCount('articles')->ordered()->get();
        $tags = \App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->take(20)->get();
        
        return view('themes.default.articles.category', compact('articles', 'category', 'categories', 'tags'));
    }
    
    public function byTag($slug)
    {
        $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
        
        $articles = Article::published()
            ->whereHas('tags', function($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            })
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        $categories = Category::withCount('articles')->ordered()->get();
        $tags = \App\Models\Tag::withCount('articles')->orderBy('articles_count', 'desc')->take(20)->get();
        
        return view('themes.default.articles.tag', compact('articles', 'tag', 'categories', 'tags'));
    }
}
