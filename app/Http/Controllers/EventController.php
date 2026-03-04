<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $upcoming = Event::upcoming()->with('featuredDj')->take(6)->get();
        $ongoing = Event::ongoing()->with('featuredDj')->get();
        $past = Event::where('status', 'completed')
            ->orderBy('start_date', 'desc')
            ->take(6)
            ->get();
        
        return view('themes.default.events.index', compact('upcoming', 'ongoing', 'past'));
    }
    
    public function show($id)
    {
        $event = Event::with(['featuredDj', 'djs'])->findOrFail($id);
        
        return view('themes.default.events.show', compact('event'));
    }
}
