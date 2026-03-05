@extends('admin.layout')

@section('title', 'Schedule')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Schedule</h2>
    <a href="{{ route('admin.schedule.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Show
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 24px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@foreach($days as $dayKey => $dayName)
    <div style="margin-bottom: 32px;">
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #1e293b;">
            <i class="fas fa-calendar-day"></i> {{ $dayName }}
        </h3>
        
        @if(isset($shows[$dayKey]) && $shows[$dayKey]->count())
            <div class="card" style="padding: 0; overflow: hidden;">
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Title</th>
                            <th>Host</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shows[$dayKey]->sortBy('start_time') as $show)
                        <tr>
                            <td style="font-weight: 500; white-space: nowrap;">
                                <i class="fas fa-clock" style="color: #64748b; margin-right: 4px;"></i>
                                {{ $show->start_time }} - {{ $show->end_time }}
                            </td>
                            <td style="font-weight: 500;">{{ $show->title }}</td>
                            <td style="color: #64748b;">{{ $show->host ?? 'N/A' }}</td>
                            <td style="color: #64748b; font-size: 13px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $show->description ?? '-' }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $show->status === 'active' ? 'published' : 'draft' }}">
                                    {{ ucfirst($show->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('admin.schedule.edit', $show) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedule.destroy', $show) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this show?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card" style="padding: 40px; text-align: center;">
                <i class="fas fa-calendar-times" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px;"></i>
                <p style="color: #64748b; margin: 0;">No shows scheduled for {{ $dayName }}</p>
            </div>
        @endif
    </div>
@endforeach
@endsection
