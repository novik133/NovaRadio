@extends('admin.layout')

@section('title', __('admin.pages.title'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">{{ __('admin.pages.title') }}</h2>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> {{ __('admin.pages.create') }}
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>{{ __('admin.pages.page_title') }}</th>
                <th>{{ __('admin.pages.slug') }}</th>
                <th>{{ __('admin.pages.status') }}</th>
                <th>{{ __('admin.pages.published_at') }}</th>
                <th>{{ __('admin.actions.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pages as $page)
                <tr>
                    <td style="font-weight: 500;">{{ $page->title }}</td>
                    <td><code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ $page->slug }}</code></td>
                    <td>
                        <span class="badge badge-{{ $page->status }}">
                            {{ __('admin.pages.' . $page->status) }}
                        </span>
                    </td>
                    <td style="color: #64748b; font-size: 13px;">
                        {{ $page->published_at?->format('M d, Y') ?? __('admin.pages.not_published') }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('admin.actions.confirm_delete') }}');">
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
                        {{ __('admin.pages.no_pages') }}
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
