@extends('themes.default.layout')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 120px 0 60px; text-align: center;">
    <div class="container">
        <h1 style="font-size: 48px; font-weight: 800;">{{ __('frontend.schedule_page.title') }}</h1>
        <p style="font-size: 18px; opacity: 0.9; max-width: 600px; margin: 16px auto 0;">{{ __('frontend.schedule_page.subtitle') }}</p>
    </div>
</div>

<div style="padding: 80px 0;">
    <div class="container">
        @forelse($days as $key => $day)
            @if(isset($shows[$key]) && $shows[$key]->count() > 0)
                <div style="margin-bottom: 48px;">
                    <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 24px; color: var(--color-primary); text-transform: capitalize;">
                        <i class="far fa-calendar-alt" style="margin-right: 12px;"></i>{{ $day }}
                    </h2>
                    <div style="display: grid; gap: 16px;">
                        @foreach($shows[$key] as $show)
                            <div style="display: flex; align-items: center; gap: 24px; background: var(--color-bg-alt); padding: 24px; border-radius: 16px;">
                                <div style="text-align: center; min-width: 80px;">
                                    <div style="font-size: 14px; color: var(--color-text-muted); font-weight: 600;">{{ $show->start_time->format('H:i') }}</div>
                                    <div style="font-size: 12px; color: var(--color-text-muted);">to</div>
                                    <div style="font-size: 14px; color: var(--color-text-muted); font-weight: 600;">{{ $show->end_time->format('H:i') }}</div>
                                </div>
                                <div style="flex: 1;">
                                    <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">{{ $show->title }}</h3>
                                    @if($show->host)
                                        <p style="color: var(--color-primary); font-size: 14px; font-weight: 500; margin-bottom: 8px;">
                                            <i class="fas fa-microphone" style="margin-right: 8px;"></i>Hosted by {{ $show->host }}
                                        </p>
                                    @endif
                                    @if($show->description)
                                        <p style="color: var(--color-text-muted); font-size: 15px;">{{ $show->description }}</p>
                                    @endif
                                </div>
                                @if($show->image)
                                    <img src="{{ $show->image }}" alt="{{ $show->title }}" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
            <div style="text-align: center; padding: 60px;">
                <i class="fas fa-calendar" style="font-size: 48px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                <p style="color: var(--color-text-muted);">{{ __('frontend.schedule_page.no_shows') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
