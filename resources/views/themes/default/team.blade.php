@extends('themes.default.layout')

@section('content')
<div class="page-header" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 120px 0 60px; text-align: center;">
    <div class="container">
        <h1 style="font-size: 48px; font-weight: 800;">Our Team</h1>
        <p style="font-size: 18px; opacity: 0.9; max-width: 600px; margin: 16px auto 0;">Meet the talented DJs and staff behind NovaRadio</p>
    </div>
</div>

<div style="padding: 80px 0; background: var(--color-bg-alt);">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px;">
            @forelse($members as $member)
                <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: var(--shadow); text-align: center; padding: 40px 32px;">
                    @if($member->photo)
                        <img src="{{ $member->photo }}" alt="{{ $member->name }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 24px;">
                    @else
                        <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: white; font-size: 48px;">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    <h3 style="font-size: 22px; font-weight: 700; margin-bottom: 8px;">{{ $member->name }}</h3>
                    <p style="color: var(--color-primary); font-weight: 600; margin-bottom: 16px;">{{ $member->role }}</p>
                    <p style="color: var(--color-text-muted); font-size: 15px; line-height: 1.6;">{{ $member->bio }}</p>
                    @if($member->social_twitter || $member->social_instagram)
                        <div style="display: flex; gap: 12px; justify-content: center; margin-top: 20px;">
                            @if($member->social_twitter)
                                <a href="{{ $member->social_twitter }}" style="width: 40px; height: 40px; background: var(--color-bg-alt); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--color-text); text-decoration: none;">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if($member->social_instagram)
                                <a href="{{ $member->social_instagram }}" style="width: 40px; height: 40px; background: var(--color-bg-alt); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--color-text); text-decoration: none;">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 60px;">
                    <i class="fas fa-users" style="font-size: 48px; color: var(--color-text-muted); margin-bottom: 16px;"></i>
                    <p style="color: var(--color-text-muted);">No team members added yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
