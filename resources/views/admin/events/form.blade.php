@extends('admin.layout')

@section('title', $event->exists ? 'Edit Event' : 'Create Event')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">{{ $event->exists ? 'Edit Event' : 'Create Event' }}</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
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
            <label for="title">Title <span style="color: #ef4444;">*</span></label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $event->title) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $event->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Event Image</label>
            <div class="image-picker">
                <input type="hidden" name="image" id="image" value="{{ old('image', $event->image) }}">
                <div class="image-preview" id="image-preview" onclick="openMediaPickerForImage()">
                    @if($event->image)
                        <img src="{{ asset($event->image) }}" alt="Event Image">
                    @else
                        <div class="no-image"><i class="fas fa-image"></i> Click to select image</div>
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openMediaPickerForImage()">
                    <i class="fas fa-folder-open"></i> Browse Media
                </button>
                @if($event->image)
                    <button type="button" class="btn btn-danger btn-sm" onclick="clearImage()">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="start_date">Start Date <span style="color: #ef4444;">*</span></label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        <div class="form-group">
            <label for="venue">Venue</label>
            <input type="text" name="venue" id="venue" class="form-control" value="{{ old('venue', $event->venue) }}">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $event->address) }}">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $event->city) }}">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="ticket_price">Ticket Price</label>
                <input type="number" step="0.01" name="ticket_price" id="ticket_price" class="form-control" value="{{ old('ticket_price', $event->ticket_price) }}" placeholder="0.00">
            </div>
            <div class="form-group">
                <label for="ticket_url">Ticket URL</label>
                <input type="url" name="ticket_url" id="ticket_url" class="form-control" value="{{ old('ticket_url', $event->ticket_url) }}" placeholder="https://...">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="upcoming" {{ old('status', $event->status) === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ old('status', $event->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="featured_dj_id">Featured DJ</label>
            <select name="featured_dj_id" id="featured_dj_id" class="form-control">
                <option value="">Select DJ</option>
                @foreach($djs as $dj)
                    <option value="{{ $dj->id }}" {{ old('featured_dj_id', $event->featured_dj_id) == $dj->id ? 'selected' : '' }}>
                        {{ $dj->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $event->exists ? 'Update' : 'Create' }} Event
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function openMediaPickerForImage() {
    const picker = window.open('{{ route("admin.media.index") }}?picker=1&type=image', 'media-picker', 'width=900,height=600');
    window.mediaPickerCallback = function(url, name) {
        document.getElementById('image').value = url.replace('{{ url('/') }}/', '');
        document.getElementById('image-preview').innerHTML = '<img src="' + url + '" alt="Event Image">';
        picker.close();
    };
}

function clearImage() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').innerHTML = '<div class="no-image"><i class="fas fa-image"></i> Click to select image</div>';
}
</script>
@endpush
@endsection
