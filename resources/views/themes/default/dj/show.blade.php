@extends('themes.default.layout')

@section('title', $dj->stage_name ?? $dj->teamMember->name)

@section('content')
@php
$member = $dj->teamMember;
@endphp

<section class="dj-hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 100px 0 60px; color: white; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background: url('{{ $member->photo ? asset($member->photo) : '' }}') center/cover;"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 40px; align-items: center;">
            <div>
                @if($member->photo)
                    <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; border: 4px solid var(--color-primary); box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
                @else
                    <div style="width: 200px; height: 200px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 80px;">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <div>
                @if($dj->is_resident)
                    <span style="display: inline-block; background: var(--color-primary); color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 16px;">
                        <i class="fas fa-star"></i> Resident DJ
                    </span>
                @endif
                <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 8px;">{{ $dj->stage_name ?? $member->name }}</h1>
                <p style="font-size: 20px; color: #94a3b8; margin-bottom: 16px;">{{ $member->role }}</p>
                @if($dj->genre)
                    <span style="display: inline-block; background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 20px; font-size: 14px;">
                        <i class="fas fa-music"></i> {{ $dj->genre }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="dj-content" style="padding: 60px 0;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 60px;">
            {{-- Main Content --}}
            <div>
                @if($dj->biography)
                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 20px;">Biography</h2>
                    <div style="font-size: 16px; line-height: 1.8; color: var(--color-text);">
                        {!! nl2br(e($dj->biography)) !!}
                    </div>
                </div>
                @endif
                
                @if($dj->equipment)
                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 20px;">Equipment</h2>
                    <p style="color: var(--color-text-light);">{{ $dj->equipment }}</p>
                </div>
                @endif
                
                @if($dj->top_tracks)
                <div style="margin-bottom: 40px;">
                    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 20px;">Top Tracks</h2>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($dj->top_tracks as $track)
                            <div style="display: flex; align-items: center; gap: 16px; padding: 16px; background: var(--color-bg-alt); border-radius: 12px;">
                                <div style="width: 48px; height: 48px; background: var(--color-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-music"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $track['title'] ?? $track }}</div>
                                    @if(is_array($track) && isset($track['artist']))
                                        <div style="font-size: 14px; color: var(--color-text-light);">{{ $track['artist'] }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                {{-- Shows Schedule --}}
                @if($shows->count() > 0)
                <div>
                    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 20px;">Show Schedule</h2>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach($shows as $show)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                <div>
                                    <div style="font-weight: 600; margin-bottom: 4px;">{{ $show->title }}</div>
                                    <div style="font-size: 14px; color: var(--color-text-light);">
                                        <i class="fas fa-clock"></i> {{ $show->start_time }} - {{ $show->end_time }}
                                    </div>
                                </div>
                                <span style="background: var(--color-primary); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    {{ $show->day }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            {{-- Sidebar --}}
            <aside>
                {{-- Social Links --}}
                <div style="background: white; border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <h4 style="font-size: 16px; margin-bottom: 16px;">Follow {{ $dj->stage_name ?? $member->name }}</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @if($member->social_twitter)
                            <a href="{{ $member->social_twitter }}" target="_blank" style="width: 40px; height: 40px; background: #1da1f2; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        @if($member->social_instagram)
                            <a href="{{ $member->social_instagram }}" target="_blank" style="width: 40px; height: 40px; background: #e4405f; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if($member->social_facebook)
                            <a href="{{ $member->social_facebook }}" target="_blank" style="width: 40px; height: 40px; background: #1877f2; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-facebook"></i>
                            </a>
                        @endif
                        @if($dj->soundcloud_url)
                            <a href="{{ $dj->soundcloud_url }}" target="_blank" style="width: 40px; height: 40px; background: #ff5500; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-soundcloud"></i>
                            </a>
                        @endif
                        @if($dj->mixcloud_url)
                            <a href="{{ $dj->mixcloud_url }}" target="_blank" style="width: 40px; height: 40px; background: #5000ff; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-cloud"></i>
                            </a>
                        @endif
                        @if($dj->spotify_url)
                            <a href="{{ $dj->spotify_url }}" target="_blank" style="width: 40px; height: 40px; background: #1db954; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-spotify"></i>
                            </a>
                        @endif
                    </div>
                </div>
                
                {{-- Stats --}}
                <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <h4 style="font-size: 16px; margin-bottom: 16px;">DJ Stats</h4>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @if($dj->years_experience)
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--color-text-light);">Experience</span>
                                <span style="font-weight: 600;">{{ $dj->years_experience }} years</span>
                            </div>
                        @endif
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--color-text-light);">Shows</span>
                            <span style="font-weight: 600;">{{ $shows->count() }} per week</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
