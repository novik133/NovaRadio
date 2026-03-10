@extends('themes.default.layout')

@section('title', $event->title . ' - Events')
@section('description', $event->description ?? 'Event at ' . $event->venue)

@section('content')
<section class="event-hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 80px 0; color: white;">
    <div class="container">
        <div style="display: inline-block; background: {{ $event->status === 'upcoming' ? '#22c55e' : ($event->status === 'ongoing' ? '#f59e0b' : '#6b7280') }}; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 16px; text-transform: uppercase;">
            <i class="fas fa-calendar"></i> {{ $event->status }}
        </div>
        <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 16px; line-height: 1.2;">{{ $event->title }}</h1>
        <div style="display: flex; align-items: center; gap: 24px; color: #94a3b8; font-size: 16px; flex-wrap: wrap;">
            <span style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-calendar-alt"></i>
                {{ $event->start_date->format('F d, Y') }}
            </span>
            <span style="display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-clock"></i>
                {{ $event->start_date->format('H:i') }} - {{ $event->end_date?->format('H:i') ?? 'Late' }}
            </span>
            @if($event->venue)
                <span style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $event->venue }}{{ $event->city ? ', ' . $event->city : '' }}
                </span>
            @endif
        </div>
    </div>
</section>

<section class="event-content" style="padding: 60px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 60px;">
            {{-- Main Content --}}
            <div>
                @if($event->image)
                    <div style="border-radius: 16px; overflow: hidden; margin-bottom: 40px;">
                        <img src="{{ asset($event->image) }}" alt="{{ $event->title }}" style="width: 100%; height: auto;">
                    </div>
                @endif
                
                <div class="event-description" style="font-size: 18px; line-height: 1.8; color: var(--color-text);">
                    {!! nl2br(e($event->description)) !!}
                </div>
                
                @if($event->featuredDJ)
                    <div style="margin-top: 40px; padding: 24px; background: white; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 16px;">{{ __('frontend.events_page.featured_dj') }}</h3>
                        <div style="display: flex; align-items: center; gap: 16px;">
                            @if($event->featuredDJ->photo)
                                <img src="{{ asset($event->featuredDJ->photo) }}" alt="{{ $event->featuredDJ->name }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                            @else
                                <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="font-size: 32px; color: white;"></i>
                                </div>
                            @endif
                            <div>
                                <h4 style="font-size: 18px; font-weight: 600;">{{ $event->featuredDJ->name }}</h4>
                                @if($event->featuredDJ->role)
                                    <p style="color: var(--color-text-light);">{{ $event->featuredDJ->role }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            {{-- Sidebar --}}
            <div>
                {{-- Event Details --}}
                <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">{{ __('frontend.sections.details') }}</h3>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; color: var(--color-text-light); text-transform: uppercase; font-weight: 600;">{{ __('frontend.events_page.date') }}</label>
                        <p style="font-size: 16px; font-weight: 500;">{{ $event->start_date->format('l, F d, Y') }}</p>
                    </div>
                    
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 12px; color: var(--color-text-light); text-transform: uppercase; font-weight: 600;">{{ __('frontend.events_page.time') }}</label>
                        <p style="font-size: 16px; font-weight: 500;">{{ $event->start_date->format('H:i') }} - {{ $event->end_date?->format('H:i') ?? 'Late' }}</p>
                    </div>
                    
                    @if($event->venue)
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; color: var(--color-text-light); text-transform: uppercase; font-weight: 600;">{{ __('frontend.events_page.venue') }}</label>
                            <p style="font-size: 16px; font-weight: 500;">{{ $event->venue }}</p>
                        </div>
                    @endif
                    
                    @if($event->address)
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; color: var(--color-text-light); text-transform: uppercase; font-weight: 600;">{{ __('frontend.events_page.address') }}</label>
                            <p style="font-size: 16px; font-weight: 500;">{{ $event->address }}</p>
                        </div>
                    @endif
                    
                    @if($event->city)
                        <div style="margin-bottom: 16px;">
                            <label style="font-size: 12px; color: var(--color-text-light); text-transform: uppercase; font-weight: 600;">{{ __('admin.events.city') }}</label>
                            <p style="font-size: 16px; font-weight: 500;">{{ $event->city }}</p>
                        </div>
                    @endif
                    
                    @if($event->ticket_price)
                        <div style="margin-bottom: 20px;">
                            <label style="font-size: 12px; color: var(--color-text-light); text-transform: uppercase; font-weight: 600;">{{ __('frontend.events_page.price') }}</label>
                            <p style="font-size: 24px; font-weight: 700; color: var(--primary-color);">${{ number_format($event->ticket_price, 2) }}</p>
                        </div>
                    @endif
                    
                    @if($event->ticket_url)
                        <a href="{{ $event->ticket_url }}" target="_blank" style="display: block; width: 100%; padding: 16px; background: var(--primary-color); color: white; text-align: center; border-radius: 8px; font-weight: 600; text-decoration: none; margin-top: 16px;">
                            <i class="fas fa-ticket-alt"></i> {{ __('frontend.events_page.buy_tickets') }}
                        </a>
                    @endif
                </div>
                
                {{-- Share --}}
                <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px;">{{ __('frontend.articles.share') }}</h3>
                    <div style="display: flex; gap: 12px;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" style="width: 44px; height: 44px; background: #1877f2; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" style="width: 44px; height: 44px; background: #1da1f2; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($event->title . ' ' . url()->current()) }}" target="_blank" style="width: 44px; height: 44px; background: #25d366; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
