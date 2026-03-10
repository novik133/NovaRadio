@extends('admin.layout')

@section('title', __('admin.categories.title'))

@section('content')
<div class="page-header">
    <h1><i class="fas fa-folder"></i> {{ __('admin.categories.title') }}</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> {{ __('admin.categories.create') }}
    </a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>{{ __('admin.categories.name') }}</th>
                <th>{{ __('admin.categories.slug') }}</th>
                <th>{{ __('admin.categories.color') }}</th>
                <th>{{ __('admin.categories.order') }}</th>
                <th>{{ __('admin.actions.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 12px; height: 12px; border-radius: 50%; background: {{ $category->color }};"></span>
                        {{ $category->name }}
                    </span>
                </td>
                <td>{{ $category->slug }}</td>
                <td>
                    <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px;">{{ $category->color }}</code>
                </td>
                <td>{{ $category->order }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('{{ __('admin.actions.confirm_delete') }}');">
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
                <td colspan="5" class="empty-state">{{ __('admin.categories.no_categories') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
@endsection
