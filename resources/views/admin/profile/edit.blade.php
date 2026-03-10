@extends('admin.layout')

@section('title', __('admin.profile.title'))

@section('content')
<div class="content-header">
    <h1>{{ __('admin.profile.title') }}</h1>
</div>

<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ __('admin.profile.title') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name">{{ __('admin.profile.name') }} *</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">{{ __('admin.profile.email') }} *</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">{{ __('admin.profile.phone') }}</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="timezone">{{ __('admin.profile.timezone') }}</label>
                            <select name="timezone" id="timezone" class="form-control">
                                <option value="UTC" {{ old('timezone', $user->timezone) === 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="Europe/Warsaw" {{ old('timezone', $user->timezone) === 'Europe/Warsaw' ? 'selected' : '' }}>Europe/Warsaw</option>
                                <option value="Europe/London" {{ old('timezone', $user->timezone) === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                <option value="America/New_York" {{ old('timezone', $user->timezone) === 'America/New_York' ? 'selected' : '' }}>America/New York</option>
                                <option value="America/Los_Angeles" {{ old('timezone', $user->timezone) === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los Angeles</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bio">{{ __('admin.profile.bio') }}</label>
                            <textarea name="bio" id="bio" class="form-control rich-editor" rows="4">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('admin.profile.update_profile') }}</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>{{ __('admin.profile.change_password') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="current_password">{{ __('admin.profile.current_password') }}</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">{{ __('admin.profile.new_password') }}</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation">{{ __('admin.profile.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning">{{ __('admin.profile.change_password') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('admin.profile.avatar') }}</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $user->avatar ? asset($user->avatar) : asset('images/default-avatar.png') }}" 
                         alt="Profile" 
                         class="rounded-circle mb-3" 
                         style="width: 120px; height: 120px; object-fit: cover;">
                    
                    <div class="form-group">
                        <label for="avatar-input">{{ __('admin.profile.upload_avatar') }}</label>
                        <input type="file" id="avatar-input" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>{{ __('admin.profile.account_info') }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>{{ __('admin.profile.role') }}:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>{{ __('admin.profile.member_since') }}:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    <p><strong>{{ __('admin.profile.status') }}:</strong> {{ $user->is_active ? __('admin.profile.active') : __('admin.profile.inactive') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('avatar-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('avatar', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route('admin.profile.upload-avatar') }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.rounded-circle').src = data.url;
            showToast('{{ __('admin.profile.avatar_updated') }}', 'success');
        } else {
            showToast('{{ __('admin.profile.avatar_upload_failed') }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('{{ __('admin.profile.avatar_upload_error') }}', 'error');
    });
});
</script>
@endpush
