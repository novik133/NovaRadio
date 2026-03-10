@extends('admin.layout')

@section('title', $tag->id ? __('admin.tags.edit') : __('admin.tags.create'))

@section('content')
<div class="page-header">
    <h1>{{ $tag->id ? __('admin.tags.edit') : __('admin.tags.create') }}</h1>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">{{ __('admin.actions.back') }}</a>
</div>

<div class="card">
    <form method="POST" action="{{ $tag->id ? route('admin.tags.update', $tag) : route('admin.tags.store') }}">
        @csrf
        @if($tag->id)
            @method('PUT')
        @endif
        
        <div class="form-row">
            <div class="form-group">
                <label>{{ __('admin.tags.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $tag->name) }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('admin.tags.slug_optional') }}</label>
                <input type="text" name="slug" value="{{ old('slug', $tag->slug) }}">
            </div>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $tag->id ? __('admin.actions.update') : __('admin.actions.create') }}
            </button>
            <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">{{ __('admin.actions.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
