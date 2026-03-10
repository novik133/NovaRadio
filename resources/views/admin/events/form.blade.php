@extends('admin.layout')

@section('title', $event->exists ? __('admin.events.edit') : __('admin.events.create'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">{{ $event->exists ? __('admin.events.edit') : __('admin.events.create') }}</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> {{ __('admin.actions.back') }}
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 24px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}" method="POST">
        @csrf
        @if($event->exists)
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="title">{{ __('admin.events.event_title') }} <span style="color: #ef4444;">*</span></label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $event->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">{{ __('admin.events.description') }}</label>
            <textarea name="description" id="description" class="form-control rich-editor" rows="4">{{ old('description', $event->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">{{ __('admin.events.event_image') }}</label>
            <div class="image-picker">
                <input type="hidden" name="image" id="image" value="{{ old('image', $event->image) }}">
                <div class="image-preview" id="image-preview" onclick="openMediaPickerForImage()">
                    @if($event->image)
                        <img src="{{ asset($event->image) }}" alt="Event Image">
                    @else
                        <div class="no-image"><i class="fas fa-image"></i> {{ __('admin.events.click_to_select') }}</div>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openMediaPickerForImage()">
                    <i class="fas fa-folder-open"></i> {{ __('admin.events.browse_media') }}
                </button>
                @if($event->image)
                    <button type="button" class="btn btn-danger btn-sm" onclick="clearImage()">
                        <i class="fas fa-trash"></i> {{ __('admin.events.remove') }}
                    </button>
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="start_date">{{ __('admin.events.start_date') }} <span style="color: #ef4444;">*</span></label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required>
            </div>
            <div class="form-group">
                <label for="end_date">{{ __('admin.events.end_date') }}</label>
                <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="venue">{{ __('admin.events.venue') }}</label>
            <input type="text" name="venue" id="venue" class="form-control" value="{{ old('venue', $event->venue) }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="address">{{ __('admin.events.address') }}</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $event->address) }}">
            </div>
            <div class="form-group">
                <label for="city">{{ __('admin.events.city') }}</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $event->city) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="ticket_price">{{ __('admin.events.ticket_price') }}</label>
                <input type="number" step="0.01" name="ticket_price" id="ticket_price" class="form-control" value="{{ old('ticket_price', $event->ticket_price) }}" placeholder="0.00">
            </div>
            <div class="form-group">
                <label for="ticket_url">{{ __('admin.events.ticket_url') }}</label>
                <input type="url" name="ticket_url" id="ticket_url" class="form-control" value="{{ old('ticket_url', $event->ticket_url) }}" placeholder="https://...">
            </div>
            <div class="form-group">
                <label for="status">{{ __('admin.events.status') }}</label>
                <select name="status" id="status" class="form-control">
                    <option value="upcoming" {{ old('status', $event->status) === 'upcoming' ? 'selected' : '' }}>{{ __('admin.events.upcoming') }}</option>
                    <option value="ongoing" {{ old('status', $event->status) === 'ongoing' ? 'selected' : '' }}>{{ __('admin.events.ongoing') }}</option>
                    <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>{{ __('admin.events.completed') }}</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="featured_dj_id">{{ __('admin.events.featured_dj') }}</label>
            <select name="featured_dj_id" id="featured_dj_id" class="form-control">
                <option value="">{{ __('admin.events.select_dj') }}</option>
                @foreach($djs as $dj)
                    <option value="{{ $dj->id }}" {{ old('featured_dj_id', $event->featured_dj_id) == $dj->id ? 'selected' : '' }}>
                        {{ $dj->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $event->exists ? __('admin.actions.update') : __('admin.actions.create') }}
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('admin.actions.cancel') }}
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function openMediaPickerForImage() {
    window.openMediaPicker(function(url, id) {
        document.getElementById('image').value = url.replace('{{ url('/') }}/', '');
        document.getElementById('image-preview').innerHTML = '<img src="' + url + '" alt="Event Image">';
    });
}

function clearImage() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').innerHTML = '<div class="no-image"><i class="fas fa-image"></i> {{ __('admin.events.click_to_select') }}</div>';
}
</script>
@endpush
@endsection
