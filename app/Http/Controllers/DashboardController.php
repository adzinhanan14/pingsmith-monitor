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
        $alertChannels = $team->alertChannels()->latest()->get();
        $recentIncidents = $team->monitors()
            ->with('incidents')
            ->get()
            ->flatMap(fn ($monitor) => $monitor->incidents)
            ->sortByDesc('started_at')
            ->take(5)
            ->values();

        return view('dashboard-new', compact('team', 'monitors', 'alertChannels', 'recentIncidents') + ['stats' => ['total' => $total, 'up' => $up, 'uptime' => $total ? round($up / $total * 100, 1) : 100]]);
    }
}
