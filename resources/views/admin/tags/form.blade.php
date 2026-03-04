@extends('admin.layout')

@section('title', $tag->id ? 'Edit Tag' : 'New Tag')

@section('content')
<div class="page-header">
    <h1>{{ $tag->id ? 'Edit Tag' : 'New Tag' }}</h1>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <form method="POST" action="{{ $tag->id ? route('admin.tags.update', $tag) : route('admin.tags.store') }}">
        @csrf
        @if($tag->id)
            @method('PUT')
        @endif
        
        <div class="form-row">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name', $tag->name) }}" required>
            </div>
            <div class="form-group">
                <label>Slug (optional)</label>
                <input type="text" name="slug" value="{{ old('slug', $tag->slug) }}">
            </div>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $tag->id ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
