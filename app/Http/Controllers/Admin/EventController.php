<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DjProfile;
use App\Models\Event;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('featuredDj')
            ->orderBy('start_date', 'desc')
            ->paginate(20);
        
        return view('admin.events.index', compact('events'));
    }
    
    public function create()
    {
        $event = new Event();
        $djs = TeamMember::where('status', 'active')->orderBy('name')->get();
        
        return view('admin.events.form', compact('event', 'djs'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'venue' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'ticket_price' => 'nullable|numeric|min:0',
            'ticket_url' => 'nullable|url|max:500',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'featured_dj_id' => 'nullable|exists:team_members,id',
            'djs' => 'nullable|array',
            'djs.*' => 'exists:team_members,id',
        ]);
        
        $event = Event::create($validated);
        
        if (!empty($validated['djs'])) {
            $event->djs()->sync($validated['djs']);
        }
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }
    
    public function edit(Event $event)
    {
        $djs = TeamMember::where('status', 'active')->orderBy('name')->get();
        $event->load('djs');
        
        return view('admin.events.form', compact('event', 'djs'));
    }
    
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'venue' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'ticket_price' => 'nullable|numeric|min:0',
            'ticket_url' => 'nullable|url|max:500',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'featured_dj_id' => 'nullable|exists:team_members,id',
            'djs' => 'nullable|array',
            'djs.*' => 'exists:team_members,id',
        ]);
        
        $event->update($validated);
        
        if (isset($validated['djs'])) {
            $event->djs()->sync($validated['djs'] ?? []);
        }
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }
    
    public function destroy(Event $event)
    {
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
