<?php

namespace App\Http\Controllers;

use App\Models\ScheduleShow;
use App\Services\SeoService;

class ScheduleController extends Controller
{
    public function __construct(private SeoService $seo) {}

    public function index()
    {
        $shows = ScheduleShow::active()->ordered()->get()->groupBy('day');
        $days = ScheduleShow::DAYS;

        return view('themes.default.schedule', compact('shows', 'days'))
            ->with($this->seo->forSchedule());
    }
}
