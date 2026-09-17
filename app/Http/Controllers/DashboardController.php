<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $r)
    {
        $team = $r->user()->teams()->with('monitors')->first();
        if (! $team) {
            return view('dashboard-new', ['team' => null, 'monitors' => collect(), 'stats' => [], 'alertChannels' => collect(), 'recentIncidents' => collect()]);
        }

        $monitors = $team->monitors()
            ->when($r->search, fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->when($r->status, fn ($q, $v) => $q->where('status', $v))
            ->orderBy('sort_order')
            ->latest()
            ->get();
        $total = $team->monitors()->count();
        $up = $team->monitors()->where('status', 'UP')->count();
        $down = $team->monitors()->where('status', 'DOWN')->count();
        $alertChannels = $team->alertChannels()->latest()->get();
        $statusPage = \App\Models\StatusPage::where('team_id', $team->id)->first();
        $recentIncidents = $team->monitors()
            ->with('incidents')
            ->get()
            ->flatMap(fn ($monitor) => $monitor->incidents)
            ->sortByDesc('started_at')
            ->take(5)
            ->values();

        return view('dashboard-new', compact('team', 'monitors', 'alertChannels', 'recentIncidents', 'statusPage') + ['stats' => ['total' => $total, 'up' => $up, 'down' => $down, 'uptime' => $total ? round($up / $total * 100, 1) : 100]]);
    }
}
