@extends('admin.layout')

@section('title', $article->id ? 'Edit Article' : 'Create Article')

@section('content')
<div class="page-header">
    <h1>{{ $article->id ? 'Edit Article' : 'Create Article' }}</h1>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ $article->id ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
        @csrf
        @if($article->id)
            @method('PUT')
        @endif
        
        <div class="form-row">
            <div class="form-group" style="flex: 2;">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="draft" {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $article->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <option value="">No Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $article->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Published At</label>
                <input type="datetime-local" name="published_at" 
                       value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="form-group checkbox-group" style="align-self: end;">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    Featured Article
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Excerpt</label>
            <textarea name="excerpt" rows="2" placeholder="Short summary (auto-generated if empty)">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>
        
        <div class="form-group">
            <label>Content</label>
            <textarea name="content" class="rich-editor" rows="15" required>{{ old('content', $article->content) }}</textarea>
        </div>
        
        <div class="form-group">
            <label>Featured Image</label>
            <div class="image-picker">
                <input type="hidden" name="featured_image" id="featured_image" 
                       value="{{ old('featured_image', $article->featured_image) }}">
                <div class="image-preview" id="image-preview" onclick="openMediaPickerForImage()">
                    @if($article->featured_image)
                        <img src="{{ asset($article->featured_image) }}" alt="Featured">
                    @else
                        <div class="no-image"><i class="fas fa-image"></i> Click to select image</div>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openMediaPickerForImage()">
                    <i class="fas fa-folder-open"></i> Browse Media
                </button>
            </div>
        </div>
        
        <div class="form-group">
            <label>Tags</label>
            <div class="tags-selector">
                @foreach($tags as $tag)
                    <label class="tag-checkbox">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                               {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <span>{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $article->id ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.tags-selector {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag-checkbox {
    cursor: pointer;
}

.tag-checkbox input {
    display: none;
}

.tag-checkbox span {
    display: block;
    padding: 6px 12px;
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 20px;
    font-size: 13px;
    transition: all 0.2s;
}

.tag-checkbox input:checked + span {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.image-picker {
    margin-top: 8px;
}
.image-preview {
    width: 300px;
    height: 180px;
    border: 2px dashed var(--border-color);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    cursor: pointer;
    overflow: hidden;
    background: var(--bg-light);
}
.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.no-image {
    text-align: center;
    color: var(--text-muted);
}
.no-image i {
    font-size: 48px;
    margin-bottom: 8px;
    display: block;
}
</style>

@push('scripts')
<script>
function openMediaPickerForImage() {
    window.openMediaPicker(function(url, id) {
        document.getElementById('featured_image').value = url.replace('{{ url('/') }}/', '');
        document.getElementById('image-preview').innerHTML = '<img src="' + url + '" alt="Featured">';
    });
}
</script>
@endpush
@endsection
