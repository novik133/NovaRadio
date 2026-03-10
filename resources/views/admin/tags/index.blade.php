@extends('admin.layout')

@section('title', __('admin.tags.title'))

@section('content')
<div class="page-header">
    <h1><i class="fas fa-tags"></i> {{ __('admin.tags.title') }}</h1>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> {{ __('admin.tags.create') }}
    </a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>{{ __('admin.tags.name') }}</th>
                <th>{{ __('admin.tags.slug') }}</th>
                <th>{{ __('admin.actions.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tags as $tag)
            <tr>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td>
                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" style="display: inline;" onsubmit="return confirm('{{ __('admin.actions.confirm_delete') }}');">
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
                <td colspan="3" class="empty-state">{{ __('admin.tags.no_tags') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $tags->links() }}
</div>
@endsection
