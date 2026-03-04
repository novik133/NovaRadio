<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.articles.index', compact('articles'));
    }
    
    public function create()
    {
        $categories = Category::ordered()->get();
        $tags = Tag::orderBy('name')->get();
        $article = new Article();
        
        return view('admin.articles.form', compact('article', 'categories', 'tags'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        
        $validated['author_id'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured');
        
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }
        
        $article = Article::create($validated);
        
        if (!empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully!');
    }
    
    public function edit(Article $article)
    {
        $categories = Category::ordered()->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('admin.articles.form', compact('article', 'categories', 'tags'));
    }
    
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);
        
        $validated['is_featured'] = $request->boolean('is_featured');
        
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }
        
        $article->update($validated);
        
        if (isset($validated['tags'])) {
            $article->tags()->sync($validated['tags'] ?? []);
        }
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully!');
    }
    
    public function destroy(Article $article)
    {
        $article->delete();
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted successfully!');
    }
}
