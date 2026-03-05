@extends('admin.layout')

@section('title', $page->id ? 'Edit Page' : 'Create Page')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">{{ $page->id ? 'Edit Page' : 'Create Page' }}</h2>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ $page->id ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
        @csrf
        @if($page->id)
            @method('PUT')
        @endif

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" required>
        </div>

        <div class="form-group">
            <label>Slug (optional)</label>
            <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="Leave empty to auto-generate">
        </div>

        <div class="form-group">
            <label>Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
        </div>

        <div class="form-group">
            <label>Meta Description</label>
            <textarea name="meta_description" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Content</label>
            <textarea name="content" class="rich-editor" rows="15" required>{{ old('content', $page->content) }}</textarea>
        </div>

        <div class="form-group">
            <label>Featured Image</label>
            <div class="image-picker">
                <input type="hidden" name="featured_image" id="featured_image" value="{{ old('featured_image', $page->featured_image) }}">
                <div class="image-preview" id="image-preview" onclick="openMediaPickerForImage()">
                    @if($page->featured_image)
                        <img src="{{ $page->featured_image }}" alt="Featured">
                    @else
                        <div class="no-image"><i class="fas fa-image"></i> Click to select image</div>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openMediaPickerForImage()">
                    <i class="fas fa-folder-open"></i> Browse Media
                </button>
                @if($page->featured_image)
                    <button type="button" class="btn btn-danger btn-sm" onclick="clearFeaturedImage()">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="draft" {{ (old('status', $page->status) == 'draft') ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ (old('status', $page->status) == 'published') ? 'selected' : '' }}>Published</option>
                <option value="private" {{ (old('status', $page->status) == 'private') ? 'selected' : '' }}>Private</option>
            </select>
        </div>

        <div class="form-group">
            <label>Published At</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $page->published_at?->format('Y-m-d\\TH:i')) }}">
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $page->id ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection


@push('scripts')
<script>
function openMediaPickerForImage() {
    window.openMediaPicker(function(url, id) {
        document.getElementById('featured_image').value = url.replace('{{ url('/') }}/', '');
        document.getElementById('image-preview').innerHTML = '<img src="' + url + '" alt="Featured">';
    });
}

function clearFeaturedImage() {
    document.getElementById('featured_image').value = '';
    document.getElementById('image-preview').innerHTML = '<div class="no-image"><i class="fas fa-image"></i> Click to select image</div>';
}
</script>
@endpush
