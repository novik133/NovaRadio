@extends('admin.layout')

@section('title', 'Events')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Events</h2>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Event
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 24px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="card" style="padding: 0; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Date</th>
                <th>Venue</th>
                <th>City</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
                <tr>
                    <td>
                        @if($event->image)
                            <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                        @else
                            <div style="width: 60px; height: 60px; border-radius: 8px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight: 500;">{{ $event->title }}</td>
                    <td style="white-space: nowrap;">
                        <i class="fas fa-calendar" style="color: #64748b; margin-right: 4px;"></i>
                        {{ $event->start_date->format('M d, Y') }}
                        <br>
                        <small style="color: #64748b;">
                            <i class="fas fa-clock" style="margin-right: 4px;"></i>
                            {{ $event->start_date->format('H:i') }}
                        </small>
                    </td>
                    <td style="color: #64748b;">{{ $event->venue ?? 'N/A' }}</td>
                    <td style="color: #64748b;">{{ $event->city ?? 'N/A' }}</td>
                    <td>
                        @php
                            $badgeClass = match($event->status) {
                                'upcoming' => 'published',
                                'ongoing' => 'draft',
                                'completed' => 'archived',
                                'cancelled' => 'archived',
                                default => 'draft'
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeClass }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('events.show', $event->id) }}" target="_blank" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-calendar-times" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                        No events found. <a href="{{ route('admin.events.create') }}" style="color: var(--primary-color);">Create one</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($events->hasPages())
    <div style="margin-top: 24px;">
        {{ $events->links() }}
    </div>
@endif
@endsection
