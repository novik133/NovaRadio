@extends('admin.layout')

@section('title', $show->exists ? __('admin.schedule.edit') : __('admin.schedule.create'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">{{ $show->exists ? __('admin.schedule.edit') : __('admin.schedule.create') }}</h2>
    <a href="{{ route('admin.schedule.index') }}" class="btn btn-secondary">
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
    <form action="{{ $show->exists ? route('admin.schedule.update', $show) : route('admin.schedule.store') }}" method="POST">
        @csrf
        @if($show->exists)
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="title">{{ __('admin.schedule.show_name') }} <span style="color: #ef4444;">*</span></label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $show->title) }}" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="day">{{ __('admin.schedule.day') }} <span style="color: #ef4444;">*</span></label>
                <select name="day" id="day" class="form-control" required>
                    @foreach($days as $key => $name)
                        <option value="{{ $key }}" {{ old('day', $show->day) === $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status">{{ __('admin.schedule.status') }}</label>
                <select name="status" id="status" class="form-control">
                    <option value="active" {{ old('status', $show->status) === 'active' ? 'selected' : '' }}>{{ __('admin.schedule.active') }}</option>
                    <option value="inactive" {{ old('status', $show->status) === 'inactive' ? 'selected' : '' }}>{{ __('admin.schedule.inactive') }}</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="start_time">{{ __('admin.schedule.start_time') }} <span style="color: #ef4444;">*</span></label>
                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $show->start_time) }}" required>
            </div>
            <div class="form-group">
                <label for="end_time">{{ __('admin.schedule.end_time') }} <span style="color: #ef4444;">*</span></label>
                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $show->end_time) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="host">{{ __('admin.schedule.host') }}</label>
            <select name="host" id="host" class="form-control">
                <option value="">{{ __('admin.schedule.select_host') }}</option>
                @foreach($hosts as $member)
                    <option value="{{ $member->name }}" {{ old('host', $show->host) === $member->name ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">{{ __('admin.schedule.description') }}</label>
            <textarea name="description" id="description" class="form-control rich-editor" rows="4">{{ old('description', $show->description) }}</textarea>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ $show->exists ? __('admin.actions.update') : __('admin.actions.create') }}
            </button>
            <a href="{{ route('admin.schedule.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> {{ __('admin.actions.cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection
