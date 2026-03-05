@extends('admin.layout')

@section('title', 'My DJ Profile')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-user-circle"></i> My DJ Profile</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <form method="POST" action="{{ route('admin.dj-profile.update') }}">
        @csrf
        @method('PUT')
        
        <div class="profile-header" style="display: grid; grid-template-columns: 200px 1fr; gap: 32px; margin-bottom: 32px;">
            <div class="photo-section" style="text-align: center;">
                <h4 style="margin-bottom: 16px;">Profile Photo</h4>
                <div class="photo-preview" id="photo-preview" style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; margin: 0 auto 16px; border: 3px solid var(--primary-color); cursor: pointer;" onclick="openMediaPickerForPhoto()">
                    @if($member->photo)
                        <img src="{{ asset($member->photo) }}" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: var(--bg-light); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 48px; color: var(--text-muted);"></i>
                        </div>
                    @endif
                </div>
                <input type="hidden" name="photo" id="photo" value="{{ old('photo', $member->photo) }}">
                <button type="button" class="btn btn-secondary btn-sm" onclick="openMediaPickerForPhoto()">
                    <i class="fas fa-camera"></i> Change Photo
                </button>
                <div style="margin-top: 12px;">
                    <input type="file" id="photo-upload" accept="image/*" style="display: none;">
                    <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('photo-upload').click()">
                        <i class="fas fa-upload"></i> Upload New
                    </button>
                </div>
            </div>
            
            <div class="info-section">
                <h3 style="margin-bottom: 8px;">{{ $member->name }}</h3>
                <p style="color: var(--text-muted); margin-bottom: 16px;">{{ $member->role }}</p>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Stage Name</label>
                        <input type="text" name="stage_name" value="{{ old('stage_name', $djProfile->stage_name) }}" placeholder="Your DJ name">
                    </div>
                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" value="{{ old('genre', $djProfile->genre) }}" placeholder="e.g. House, Techno, Jazz">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Years of Experience</label>
                        <input type="number" name="years_experience" value="{{ old('years_experience', $djProfile->years_experience) }}" min="0">
                    </div>
                    <div class="form-group checkbox-group" style="align-self: end;">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_resident" value="1" {{ old('is_resident', $djProfile->is_resident) ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            Resident DJ
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label>Biography</label>
            <textarea name="biography" rows="6" class="rich-editor" placeholder="Tell your story...">{{ old('biography', $djProfile->biography) }}</textarea>
        </div>
        
        <div class="form-group">
            <label>Equipment</label>
            <input type="text" name="equipment" value="{{ old('equipment', $djProfile->equipment) }}" placeholder="e.g. Pioneer CDJ-3000, DJM-900">
        </div>
        
        <h4 style="margin: 32px 0 16px; padding-top: 24px; border-top: 1px solid var(--border-color);">Social Links</h4>
        
        <div class="form-row">
            <div class="form-group">
                <label><i class="fab fa-soundcloud" style="color: #ff5500;"></i> SoundCloud</label>
                <input type="url" name="soundcloud_url" value="{{ old('soundcloud_url', $djProfile->soundcloud_url) }}" placeholder="https://soundcloud.com/yourname">
            </div>
            <div class="form-group">
                <label><i class="fas fa-cloud" style="color: #5000ff;"></i> Mixcloud</label>
                <input type="url" name="mixcloud_url" value="{{ old('mixcloud_url', $djProfile->mixcloud_url) }}" placeholder="https://mixcloud.com/yourname">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label><i class="fab fa-spotify" style="color: #1db954;"></i> Spotify</label>
                <input type="url" name="spotify_url" value="{{ old('spotify_url', $djProfile->spotify_url) }}" placeholder="https://open.spotify.com/artist/...">
            </div>
            <div class="form-group">
                <label><i class="fab fa-apple" style="color: #fa57c1;"></i> Apple Music</label>
                <input type="url" name="apple_music_url" value="{{ old('apple_music_url', $djProfile->apple_music_url) }}" placeholder="https://music.apple.com/artist/...">
            </div>
        </div>
        
        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Profile
            </button>
            <a href="{{ route('dj.show', $member->slug) }}" target="_blank" class="btn btn-secondary">
                <i class="fas fa-external-link-alt"></i> View Public Profile
            </a>
        </div>
    </form>
</div>

<style>
.checkbox-group {
    margin-bottom: 16px;
}
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
}
.checkbox-label input {
    display: none;
}
.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.checkbox-label input:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}
.checkbox-label input:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-size: 12px;
}
</style>

@push('scripts')
<script>
// Photo upload via Media Manager
function openMediaPickerForPhoto() {
    if (window.openMediaPicker) {
        window.openMediaPicker(function(url) {
            document.getElementById('photo').value = url;
            document.getElementById('photo-preview').innerHTML = '<img src="' + url + '" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">';
        });
    } else {
        // Direct file upload fallback
        document.getElementById('photo-upload').click();
    }
}

// Direct photo upload
document.getElementById('photo-upload')?.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('photo', file);
    
    try {
        const response = await fetch('{{ route("admin.dj-profile.upload-photo") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            document.getElementById('photo').value = data.url.replace(window.location.origin + '/', '');
            document.getElementById('photo-preview').innerHTML = '<img src="' + data.url + '" alt="Photo" style="width: 100%; height: 100%; object-fit: cover;">';
            showToast('Photo uploaded successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to upload photo', 'error');
        }
    } catch (err) {
        console.error('Upload failed:', err);
        showToast('Upload failed. Please try again.', 'error');
    }
});
</script>
@endpush
@endsection
