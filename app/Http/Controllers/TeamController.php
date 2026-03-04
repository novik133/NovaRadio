<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\DjProfile;
use App\Models\ScheduleShow;
use App\Services\SeoService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(private SeoService $seo) {}

    public function index()
    {
        $members = TeamMember::active()->ordered()->get();

        return view('themes.default.team', compact('members'))
            ->with($this->seo->forTeam());
    }
    
    public function show($slug)
    {
        $member = TeamMember::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();
        
        $dj = DjProfile::where('team_member_id', $member->id)->first();
        
        $shows = ScheduleShow::where('dj_id', $member->id)
            ->active()
            ->ordered()
            ->get();
        
        return view('themes.default.dj.show', compact('member', 'dj', 'shows'));
    }
}
