@extends('themes.default.layout')

@section('title', 'Events & Gigs')

@section('content')
<section class="events-hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 80px 0; color: white;">
    <div class="container">
        <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 16px;">Events & Gigs</h1>
        <p style="font-size: 20px; color: #94a3b8;">Upcoming shows and live performances</p>
    </div>
</section>

<section class="events-content" style="padding: 60px 0;">
    <div class="container">
        {{-- Ongoing Events --}}
        @if($ongoing->count() > 0)
        <div style="margin-bottom: 60px;">
            <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 30px; color: #22c55e;">
                <i class="fas fa-broadcast-tower"></i> Live Now
            </h2>
            <div class="events-grid" style="display: grid; gap: 24px;">
                @foreach($ongoing as $event)
                <div style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-radius: 16px; padding: 24px; border-left: 4px solid #22c55e;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <span style="background: #22c55e; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">LIVE</span>
                            <h3 style="font-size: 24px; font-weight: 700; margin: 12px 0;">{{ $event->title }}</h3>
                            <p style="color: var(--color-text-light); margin-bottom: 16px;">{{ $event->venue }} • {{ $event->city }}</p>
                        </div>
                        @if($event->featuredDj)
                        <div style="text-align: center;">
                            @if($event->featuredDj->photo)
                                <img src="{{ $event->featuredDj->photo }}" alt="{{ $event->featuredDj->name }}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                            @endif
                            <div style="font-size: 12px; margin-top: 4px;">{{ $event->featuredDj->name }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- Upcoming Events --}}
        <div style="margin-bottom: 60px;">
            <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 30px;">Upcoming Events</h2>
            @if($upcoming->count() > 0)
            <div class="events-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 24px;">
                @foreach($upcoming as $event)
                <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div style="height: 200px; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); display: flex; align-items: center; justify-content: center;">
                        @if($event->image)
                            <img src="{{ $event->image }}" alt="{{ $event->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-calendar-alt" style="font-size: 64px; color: white; opacity: 0.5;"></i>
                        @endif
                    </div>
                    <div style="padding: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span style="color: var(--color-primary); font-weight: 600; font-size: 14px;">
                                {{ $event->start_date->format('M d, Y') }}
                            </span>
                            @if($event->is_free)
                                <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">FREE</span>
                            @else
                                <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${{ $event->ticket_price }}</span>
                            @endif
                        </div>
                        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">{{ $event->title }}</h3>
                        <p style="color: var(--color-text-light); margin-bottom: 16px;">
                            <i class="fas fa-map-marker-alt"></i> {{ $event->venue }}, {{ $event->city }}
                        </p>
                        <div style="display: flex; gap: 12px;">
                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary" style="flex: 1; text-align: center;">Details</a>
                            @if($event->ticket_url)
                                <a href="{{ $event->ticket_url }}" target="_blank" class="btn btn-secondary" style="flex: 1; text-align: center;">Get Tickets</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="text-align: center; padding: 60px; background: var(--bg-light); border-radius: 16px;">
                <i class="fas fa-calendar" style="font-size: 48px; color: var(--text-muted); margin-bottom: 16px;"></i>
                <h3>No upcoming events</h3>
                <p style="color: var(--text-muted);">Check back soon for new events!</p>
            </div>
            @endif
        </div>
        
        {{-- Past Events --}}
        @if($past->count() > 0)
        <div>
            <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 30px; color: var(--text-muted);">Past Events</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; opacity: 0.7;">
                @foreach($past as $event)
                <div style="background: white; border-radius: 12px; padding: 20px;">
                    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">
                        {{ $event->start_date->format('M d, Y') }}
                    </div>
                    <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">{{ $event->title }}</h4>
                    <p style="font-size: 13px; color: var(--text-muted);">
                        <i class="fas fa-map-marker-alt"></i> {{ $event->venue }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
