<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\SeoService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(private SeoService $seo) {}

    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        return view('themes.default.page', compact('page'))
            ->with($this->seo->forPage($page));
    }
}
