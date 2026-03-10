@extends('admin.layout')

@section('title', __('admin.dashboard.title'))

@section('content')
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #6366f1; margin-bottom: 8px;">
            <i class="fas fa-file-alt" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\Page::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">{{ __('admin.dashboard.pages') }}</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #ec4899; margin-bottom: 8px;">
            <i class="fas fa-users" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\TeamMember::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">{{ __('admin.dashboard.team_members') }}</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #06b6d4; margin-bottom: 8px;">
            <i class="fas fa-calendar" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\ScheduleShow::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">{{ __('admin.dashboard.shows') }}</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #10b981; margin-bottom: 8px;">
            <i class="fas fa-broadcast-tower" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\User::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">{{ __('admin.dashboard.users') }}</div>
    </div>
</div>

<div class="card">
    <h2>{{ __('admin.dashboard.welcome_title') }}</h2>
    <p style="color: #64748b; line-height: 1.6;">
        {{ __('admin.dashboard.welcome_text') }}
    </p>
</div>
@endsection
