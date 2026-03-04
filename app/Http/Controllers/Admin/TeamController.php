<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $members = TeamMember::ordered()->paginate(20);
        return view('admin.team.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:team_members',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        TeamMember::create($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member created successfully');
    }

    public function edit(TeamMember $member)
    {
        return view('admin.team.edit', compact('member'));
    }

    public function update(Request $request, TeamMember $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:team_members,slug,' . $member->id,
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $member->update($validated);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully');
    }

    public function destroy(TeamMember $member)
    {
        $member->delete();
        return redirect()->route('admin.team.index')->with('success', 'Team member deleted successfully');
    }
    
    public function uploadPhoto(Request $request, TeamMember $member)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);
        
        $file = $request->file('photo');
        $filename = 'team_' . $member->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/images/team', $filename);
        
        $member->update(['photo' => str_replace('public/', 'storage/', $path)]);
        
        return response()->json([
            'success' => true,
            'url' => asset(str_replace('public/', 'storage/', $path))
        ]);
    }
}
