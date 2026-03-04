@extends('admin.layout')

@section('title', 'Tags')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-tags"></i> Tags</h1>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Tag
    </a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
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
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" style="display: inline;" onsubmit="return confirm('Delete?');">
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
                <td colspan="3" class="empty-state">No tags</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $tags->links() }}
</div>
@endsection
