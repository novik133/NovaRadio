<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleShow;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $shows = ScheduleShow::ordered()->get()->groupBy('day');
        $days = ScheduleShow::DAYS;
        $hosts = TeamMember::active()->get();
        
        return view('admin.schedule.index', compact('shows', 'days', 'hosts'));
    }

    public function create()
    {
        $days = ScheduleShow::DAYS;
        $hosts = TeamMember::active()->get();
        $show = new ScheduleShow();
        return view('admin.schedule.form', compact('show', 'days', 'hosts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'host' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        ScheduleShow::create($validated);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Show added successfully.');
    }

    public function edit(ScheduleShow $schedule)
    {
        $days = ScheduleShow::DAYS;
        $hosts = TeamMember::active()->get();
        $show = $schedule;
        return view('admin.schedule.form', compact('show', 'days', 'hosts'));
    }

    public function update(Request $request, ScheduleShow $schedule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'host' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $schedule->update($validated);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Show updated successfully.');
    }

    public function destroy(ScheduleShow $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedule.index')
            ->with('success', 'Show deleted successfully.');
    }
}
