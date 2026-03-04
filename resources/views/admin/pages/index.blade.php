@extends('admin.layout')

@section('title', 'Pages')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Pages</h2>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Page
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $page)
                <tr>
                    <td style="font-weight: 500;">{{ $page->title }}</td>
                    <td><code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ $page->slug }}</code></td>
                    <td>
                        <span class="badge badge-{{ $page->status }}">
                            {{ ucfirst($page->status) }}
                        </span>
                    </td>
                    <td style="color: #64748b; font-size: 13px;">
                        {{ $page->published_at?->format('M d, Y') ?? 'Not published' }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                        No pages found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($pages->hasPages())
    <div style="margin-top: 24px;">
        {{ $pages->links() }}
    </div>
@endif
@endsection
