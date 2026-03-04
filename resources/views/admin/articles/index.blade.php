@extends('admin.layout')

@section('title', 'Articles')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-newspaper"></i> Articles</h1>
        <p style="color: var(--text-muted); margin-top: 4px;">Manage news and blog posts</p>
    </div>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Article
    </a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Category</th>
                <th>Author</th>
                <th>Status</th>
                <th>Views</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $article)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        @if($article->featured_image)
                            <img src="{{ $article->featured_image }}" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px;">
                        @else
                            <div style="width: 48px; height: 48px; background: var(--bg-light); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: var(--text-muted);"></i>
                            </div>
                        @endif
                        <div>
                            <div style="font-weight: 600;">{{ $article->title }}</div>
                            @if($article->is_featured)
                                <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 10px;">FEATURED</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if($article->category)
                        <span style="display: flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $article->category->color }};"></span>
                            {{ $article->category->name }}
                        </span>
                    @else
                        <span style="color: var(--text-muted);">-</span>
                    @endif
                </td>
                <td>{{ $article->author->name }}</td>
                <td>
                    @if($article->status === 'published')
                        <span class="badge badge-published">Published</span>
                    @elseif($article->status === 'draft')
                        <span class="badge badge-draft">Draft</span>
                    @else
                        <span class="badge" style="background: #f3f4f6; color: #6b7280;">Archived</span>
                    @endif
                </td>
                <td>{{ number_format($article->views_count) }}</td>
                <td>{{ $article->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" style="display: inline;" onsubmit="return confirm('Delete this article?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-state" style="padding: 48px; text-align: center;">
                    <i class="fas fa-newspaper" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
                    <h3>No articles yet</h3>
                    <p style="color: var(--text-muted);">Create your first article to get started</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $articles->links() }}
</div>
@endsection
