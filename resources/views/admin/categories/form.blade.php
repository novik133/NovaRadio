@extends('admin.layout')

@section('title', $category->id ? __('admin.categories.edit') : __('admin.categories.create'))

@section('content')
<div class="page-header">
    <h1>{{ $category->id ? __('admin.categories.edit') : __('admin.categories.create') }}</h1>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">{{ __('admin.actions.back') }}</a>
</div>

<div class="card">
    <form method="POST" action="{{ $category->id ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if($category->id)
            @method('PUT')
        @endif
        
        <div class="form-row">
            <div class="form-group">
                <label>{{ __('admin.categories.name') }}</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required>
            </div>
            <div class="form-group">
                <label>{{ __('admin.categories.slug_optional') }}</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>{{ __('admin.categories.color') }}</label>
                <div class="color-picker-wrapper">
                    <input type="color" name="color" value="{{ old('color', $category->color ?? '#6366f1') }}">
                    <input type="text" name="color_text" value="{{ old('color', $category->color ?? '#6366f1') }}" class="color-input">
                </div>
            </div>
            <div class="form-group">
                <label>{{ __('admin.categories.order') }}</label>
                <input type="number" name="order" value="{{ old('order', $category->order ?? 0) }}">
            </div>
        </div>
        
        <div class="form-group">
            <label>{{ __('admin.categories.description') }}</label>
            <textarea name="description" class="rich-editor" rows="3">{{ old('description', $category->description) }}</textarea>
        </div>
        
        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $category->id ? __('admin.actions.update') : __('admin.actions.create') }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">{{ __('admin.actions.cancel') }}</a>
        </div>
    </form>
</div>

<style>
.color-picker-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}
input[type="color"] {
    width: 50px;
    height: 40px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.color-input {
    width: 120px;
}
</style>

<script>
document.querySelector('input[type="color"]').addEventListener('input', function() {
    this.nextElementSibling.value = this.value;
});
</script>
@endsection
