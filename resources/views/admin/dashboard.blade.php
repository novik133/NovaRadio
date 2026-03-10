@extends('admin.layout')

@section('title', __('admin.dashboard.title'))

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <div class="card" style="text-align: center; padding: 28px 24px;">
        <div style="font-size: 32px; font-weight: 700; font-family: 'Roboto Mono', monospace; color: var(--primary-color); margin-bottom: 8px;">
            {{ \App\Models\Page::count() }}
        </div>
        <div style="color: var(--text-muted); font-size: 13px; font-family: 'Roboto Mono', monospace; text-transform: uppercase; letter-spacing: 0.05em;">
            <i class="fas fa-file-alt" style="margin-right: 6px;"></i>{{ __('admin.dashboard.pages') }}
        </div>
    </div>
    <div class="card" style="text-align: center; padding: 28px 24px;">
        <div style="font-size: 32px; font-weight: 700; font-family: 'Roboto Mono', monospace; color: #ec4899; margin-bottom: 8px;">
            {{ \App\Models\TeamMember::count() }}
        </div>
        <div style="color: var(--text-muted); font-size: 13px; font-family: 'Roboto Mono', monospace; text-transform: uppercase; letter-spacing: 0.05em;">
            <i class="fas fa-users" style="margin-right: 6px;"></i>{{ __('admin.dashboard.team_members') }}
        </div>
    </div>
    <div class="card" style="text-align: center; padding: 28px 24px;">
        <div style="font-size: 32px; font-weight: 700; font-family: 'Roboto Mono', monospace; color: #06b6d4; margin-bottom: 8px;">
            {{ \App\Models\ScheduleShow::count() }}
        </div>
        <div style="color: var(--text-muted); font-size: 13px; font-family: 'Roboto Mono', monospace; text-transform: uppercase; letter-spacing: 0.05em;">
            <i class="fas fa-calendar" style="margin-right: 6px;"></i>{{ __('admin.dashboard.shows') }}
        </div>
    </div>
    <div class="card" style="text-align: center; padding: 28px 24px;">
        <div style="font-size: 32px; font-weight: 700; font-family: 'Roboto Mono', monospace; color: #10b981; margin-bottom: 8px;">
            {{ \App\Models\User::count() }}
        </div>
        <div style="color: var(--text-muted); font-size: 13px; font-family: 'Roboto Mono', monospace; text-transform: uppercase; letter-spacing: 0.05em;">
            <i class="fas fa-user-shield" style="margin-right: 6px;"></i>{{ __('admin.dashboard.users') }}
        </div>
    </div>
</div>

<div class="card">
    <h2>{{ __('admin.dashboard.welcome_title') }}</h2>
    <p style="color: var(--text-muted); line-height: 1.6; font-size: 14px;">
        {{ __('admin.dashboard.welcome_text') }}
    </p>
</div>
@endsection
