@extends('admin.layout')

@section('title', 'Schedule')

@section('content')
<div class="content-header">
    <h1>Schedule</h1>
    <a href="{{ route('admin.schedule.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Show
    </a>
</div>

<div class="content-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @foreach($days as $dayKey => $dayName)
                <h3 class="mt-4 mb-3">{{ $dayName }}</h3>
                @if(isset($shows[$dayKey]) && $shows[$dayKey]->count())
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Title</th>
                                <th>Host</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shows[$dayKey]->sortBy('start_time') as $show)
                            <tr>
                                <td>{{ $show->start_time }} - {{ $show->end_time }}</td>
                                <td>{{ $show->title }}</td>
                                <td>{{ $show->host ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ $show->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($show->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.schedule.edit', $show) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedule.destroy', $show) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this show?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No shows scheduled</p>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
