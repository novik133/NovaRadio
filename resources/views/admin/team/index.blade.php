@extends('admin.layout')

@section('title', __('admin.team.title'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">{{ __('admin.team.title') }}</h2>
    <a href="{{ route('admin.team.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> {{ __('admin.team.create') }}
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table>
        <thead>
            <tr>
                <th>{{ __('admin.team.photo') }}</th>
                <th>{{ __('admin.team.name') }}</th>
                <th>{{ __('admin.team.role') }}</th>
                <th>{{ __('admin.pages.status') }}</th>
                <th>{{ __('admin.actions.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
                <tr>
                    <td>
                        @if($member->photo)
                            <img src="{{ asset($member->photo) }}" alt="{{ $member->name }}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </td>
                    <td style="font-weight: 500;">{{ $member->name }}</td>
                    <td>{{ $member->role }}</td>
                    <td>
                        <span class="badge" style="background: {{ $member->status == 'active' ? '#dcfce7' : '#fee2e2' }}; color: {{ $member->status == 'active' ? '#166534' : '#991b1b' }};">
                            {{ $member->status == 'active' ? __('admin.profile.active') : __('admin.profile.inactive') }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('admin.team.edit', $member) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.team.destroy', $member) }}" method="POST" style="display: inline;" onsubmit="return confirm('{{ __('admin.actions.confirm_delete') }}');">
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
                    <td colspan="5" style="text-align: center; padding: 40px; color: #64748b;">
                        <i class="fas fa-users" style="font-size: 32px; margin-bottom: 12px; display: block;"></i>
                        {{ __('admin.team.no_members') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($members->hasPages())
    <div style="margin-top: 24px;">
        {{ $members->links() }}
    </div>
@endif
@endsection
