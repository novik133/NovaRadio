@extends('admin.layout')

@section('title', $event->exists ? 'Edit Event' : 'Create Event')

@section('content')
<div class="content-header">
    <h1>{{ $event->exists ? 'Edit Event' : 'Create Event' }}</h1>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="content-body">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}" method="POST">
                @csrf
                @if($event->exists)
                    @method('PUT')
                @endif

                <div class="form-group mb-3">
                    <label for="title">Title *</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_date">Start Date *</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="end_date">End Date</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="venue">Venue</label>
                    <input type="text" name="venue" id="venue" class="form-control" value="{{ old('venue', $event->venue) }}">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $event->address) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $event->city) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="ticket_price">Ticket Price</label>
                            <input type="number" step="0.01" name="ticket_price" id="ticket_price" class="form-control" value="{{ old('ticket_price', $event->ticket_price) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="upcoming" {{ old('status', $event->status) === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ old('status', $event->status) === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status', $event->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
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

                <button type="submit" class="btn btn-primary">
                    {{ $event->exists ? 'Update' : 'Create' }} Event
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
