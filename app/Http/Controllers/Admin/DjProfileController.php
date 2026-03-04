<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DjProfile;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DjProfileController extends Controller
{
    public function edit()
    {
        // Get the team member associated with the logged-in user
        $member = TeamMember::where('email', Auth::user()->email)->first();
        
        if (!$member) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No DJ profile found for your account.');
        }
        
        $djProfile = DjProfile::firstOrCreate(
            ['team_member_id' => $member->id],
            [
                'stage_name' => $member->name,
                'genre' => '',
                'biography' => $member->bio ?? '',
                'is_resident' => false,
            ]
        );
        
        return view('admin.dj-profile.edit', compact('member', 'djProfile'));
    }
    
    public function update(Request $request)
    {
        $member = TeamMember::where('email', Auth::user()->email)->firstOrFail();
        
        $validated = $request->validate([
            'stage_name' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'equipment' => 'nullable|string|max:500',
            'mixcloud_url' => 'nullable|url|max:255',
            'soundcloud_url' => 'nullable|url|max:255',
            'spotify_url' => 'nullable|url|max:255',
            'apple_music_url' => 'nullable|url|max:255',
            'years_experience' => 'nullable|integer|min:0',
            'top_tracks' => 'nullable|array',
        ]);
        
        // Update team member photo if provided
        if ($request->has('photo')) {
            $member->update(['photo' => $request->photo]);
        }
        
        // Update or create DJ profile
        DjProfile::updateOrCreate(
            ['team_member_id' => $member->id],
            $validated
        );
        
        return redirect()->route('admin.dj-profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
    
    public function uploadPhoto(Request $request)
    {
        $member = TeamMember::where('email', Auth::user()->email)->firstOrFail();
        
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);
        
        $file = $request->file('photo');
        $filename = 'dj_' . $member->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/images/team', $filename);
        
        $member->update(['photo' => str_replace('public/', 'storage/', $path)]);
        
        return response()->json([
            'success' => true,
            'url' => asset(str_replace('public/', 'storage/', $path))
        ]);
    }
}
