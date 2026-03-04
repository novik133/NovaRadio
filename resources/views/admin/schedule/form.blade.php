@extends('admin.layout')

@section('title', $show->exists ? 'Edit Show' : 'Add Show')

@section('content')
<div class="content-header">
    <h1>{{ $show->exists ? 'Edit Show' : 'Add Show' }}</h1>
    <a href="{{ route('admin.schedule.index') }}" class="btn btn-secondary">
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
            <form action="{{ $show->exists ? route('admin.schedule.update', $show) : route('admin.schedule.store') }}" method="POST">
                @csrf
                @if($show->exists)
                    @method('PUT')
                @endif

                <div class="form-group mb-3">
                    <label for="title">Title *</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $show->title) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="day">Day *</label>
                            <select name="day" id="day" class="form-control" required>
                                @foreach($days as $key => $name)
                                    <option value="{{ $key }}" {{ old('day', $show->day) === $key ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status', $show->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $show->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="start_time">Start Time *</label>
                            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $show->start_time) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="end_time">End Time *</label>
                            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $show->end_time) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="host">Host</label>
                    <select name="host" id="host" class="form-control">
                        <option value="">Select Host</option>
                        @foreach($hosts as $member)
                            <option value="{{ $member->name }}" {{ old('host', $show->host) === $member->name ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $show->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ $show->exists ? 'Update' : 'Create' }} Show
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
