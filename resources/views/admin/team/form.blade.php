@extends('admin.layout')

@section('title', $member->id ? __('admin.team.edit') : __('admin.team.create'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">{{ $member->id ? __('admin.team.edit') : __('admin.team.create') }}</h2>
    <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> {{ __('admin.actions.back') }}
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ $member->id ? route('admin.team.update', $member) : route('admin.team.store') }}">
        @csrf
        @if($member->id)
            @method('PUT')
        @endif

        <div class="form-group">
            <label>{{ __('admin.team.name') }}</label>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" required>
        </div>

        <div class="form-group">
            <label>{{ __('admin.team.role') }}</label>
            <input type="text" name="role" value="{{ old('role', $member->role) }}" required placeholder="{{ __('admin.team.role_placeholder') }}">
        </div>

        <div class="form-group">
            <label>{{ __('admin.team.bio') }}</label>
            <textarea name="bio" class="rich-editor" rows="4">{{ old('bio', $member->bio) }}</textarea>
        </div>

        <div class="form-group">
            <label>{{ __('admin.team.photo') }}</label>
            <div class="image-picker">
                <input type="hidden" name="photo" id="photo" value="{{ old('photo', $member->photo) }}">
                <div class="image-preview" id="photo-preview" onclick="openMediaPickerForPhoto()">
                    @if($member->photo)
                        <img src="{{ asset($member->photo) }}" alt="Photo">
                    @else
                        <div class="no-image"><i class="fas fa-user"></i> {{ __('admin.team.click_to_select') }}</div>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openMediaPickerForPhoto()">
                    <i class="fas fa-folder-open"></i> {{ __('admin.team.browse_media') }}
                </button>
                @if($member->photo)
                    <button type="button" class="btn btn-danger btn-sm" onclick="clearPhoto()">
                        <i class="fas fa-trash"></i> {{ __('admin.team.remove') }}
                    </button>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label>{{ __('admin.team.email') }}</label>
            <input type="email" name="email" value="{{ old('email', $member->email) }}">
        </div>

        <div class="form-group">
            <label>{{ __('admin.team.twitter_url') }}</label>
            <input type="text" name="social_twitter" value="{{ old('social_twitter', $member->social_twitter) }}">
        </div>

        <div class="form-group">
            <label>{{ __('admin.team.instagram_url') }}</label>
            <input type="text" name="social_instagram" value="{{ old('social_instagram', $member->social_instagram) }}">
        </div>

        <div class="form-group">
            <label>{{ __('admin.team.status') }}</label>
            <select name="status">
                <option value="active" {{ (old('status', $member->status) == 'active') ? 'selected' : '' }}>{{ __('admin.team.is_active') }}</option>
                <option value="inactive" {{ (old('status', $member->status) == 'inactive') ? 'selected' : '' }}>{{ __('admin.team.inactive') }}</option>
            </select>
        </div>

        <div style="display: flex; gap: 12px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $member->id ? __('admin.actions.update') : __('admin.actions.create') }}
            </button>
            <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">{{ __('admin.actions.cancel') }}</a>
        </div>
    </form>
</div>

<style>
.image-picker {
    margin-top: 8px;
}
.image-preview {
    width: 150px;
    height: 150px;
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
function openMediaPickerForPhoto() {
    window.openMediaPicker(function(url, id) {
        document.getElementById('photo').value = url.replace('{{ url('/') }}/', '');
        document.getElementById('photo-preview').innerHTML = '<img src="' + url + '" alt="Photo">';
    });
}

function clearPhoto() {
    document.getElementById('photo').value = '';
    document.getElementById('photo-preview').innerHTML = '<div class="no-image"><i class="fas fa-user"></i> {{ __('admin.team.click_to_select') }}</div>';
}
</script>
@endpush
@endsection
