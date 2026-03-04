@extends('admin.layout')

@section('title', 'System Updates')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">System Updates</h2>
    <form method="POST" action="{{ route('admin.updates.check') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sync"></i> Check for Updates
        </button>
    </form>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px;">
    <div class="card" style="text-align: center;">
        <div style="font-size: 14px; color: #64748b; margin-bottom: 8px;">Current Version</div>
        <div style="font-size: 32px; font-weight: 800; color: #6366f1;">{{ $currentVersion }}</div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 14px; color: #64748b; margin-bottom: 8px;">Latest Check</div>
        <div style="font-size: 14px; color: #0f172a;">
            @if($latestCheck)
                {{ $latestCheck->created_at->diffForHumans() }}
            @else
                Never
            @endif
        </div>
    </div>
    <div class="card" style="text-align: center;">
        <div style="font-size: 14px; color: #64748b; margin-bottom: 8px;">Status</div>
        <div style="font-size: 14px; color: {{ $latestCheck?->status === 'available' ? '#ef4444' : '#10b981' }}; font-weight: 600;">
            @if($latestCheck?->status === 'available')
                Update Available
            @elseif($latestCheck?->status === 'success')
                Up to Date
            @else
                Unknown
            @endif
        </div>
    </div>
</div>

@if(session('update_available'))
    <div class="card" style="border: 2px solid #6366f1;">
        <h3 style="color: #6366f1; margin-bottom: 16px;">
            <i class="fas fa-download"></i> Update Available: v{{ session('version') }}
        </h3>
        <div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin-bottom: 16px; max-height: 300px; overflow-y: auto;">
            <pre style="white-space: pre-wrap; font-family: inherit; font-size: 14px; line-height: 1.6;">{{ session('changelog') }}</pre>
        </div>
        <form method="POST" action="{{ route('admin.updates.install') }}" onsubmit="return confirm('Install this update? The site will be temporarily unavailable.');">
            @csrf
            <input type="hidden" name="version" value="{{ session('version') }}">
            <input type="hidden" name="download_url" value="{{ session('download_url') }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-download"></i> Install Update
            </button>
            <a href="{{ session('html_url') }}" target="_blank" class="btn btn-secondary" style="margin-left: 8px;">
                <i class="fas fa-external-link-alt"></i> View on GitHub
            </a>
        </form>
    </div>
@endif

<div class="card">
    <h2 style="margin-bottom: 20px;">Update History</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Version</th>
                <th>Status</th>
                <th>Message</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $log)
                <tr>
                    <td style="font-size: 13px;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge" style="background: #e0e7ff; color: #3730a3;">
                            {{ ucfirst($log->type) }}
                        </span>
                    </td>
                    <td>{{ $log->version ?? '-' }}</td>
                    <td>
                        <span class="badge" style="background: {{ $log->status === 'success' ? '#dcfce7' : ($log->status === 'error' ? '#fee2e2' : '#fef3c7') }}; color: {{ $log->status === 'success' ? '#166534' : ($log->status === 'error' ? '#991b1b' : '#92400e') }};">
                            {{ ucfirst($log->status) }}
                        </span>
                    </td>
                    <td style="font-size: 13px; color: #64748b;">{{ $log->message }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-history" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                        No update history found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
