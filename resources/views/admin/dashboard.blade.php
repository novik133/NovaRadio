@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #6366f1; margin-bottom: 8px;">
            <i class="fas fa-file-alt" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\Page::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">Pages</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #ec4899; margin-bottom: 8px;">
            <i class="fas fa-users" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\TeamMember::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">Team Members</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #06b6d4; margin-bottom: 8px;">
            <i class="fas fa-calendar" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\ScheduleShow::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">Shows</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 36px; font-weight: 800; color: #10b981; margin-bottom: 8px;">
            <i class="fas fa-broadcast-tower" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
            {{ \App\Models\User::count() }}
        </div>
        <div style="color: #64748b; font-size: 14px;">Users</div>
    </div>
</div>

<div class="card">
    <h2>Welcome to NovaRadio Admin</h2>
    <p style="color: #64748b; line-height: 1.6;">
        Manage your internet radio station from this dashboard. You can edit pages, manage your team,
        view schedule, and check for system updates.
    </p>
</div>
@endsection
