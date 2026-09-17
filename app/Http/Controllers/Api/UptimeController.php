<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Monitor;
use App\Models\Team;
use Illuminate\Http\Request;

class UptimeController extends Controller
{
    public function summary(Request $request, Team $team)
    {
        abort_unless($team->roleFor($request->user()), 403);

        $monitors = $team->monitors()->get();
        $total = $monitors->count();
        $up = $monitors->where('status', 'UP')->count();
        $down = $monitors->where('status', 'DOWN')->count();
        $paused = $monitors->where('status', 'PAUSED')->count();
        $avgLatency = $monitors->whereNotNull('last_latency_ms')->avg('last_latency_ms') ?: 0;

        return response()->json([
            'total' => $total,
            'up' => $up,
            'down' => $down,
            'paused' => $paused,
            'uptime' => $total > 0 ? (float) round(($up / $total) * 100, 1) : 100.0,
            'average_latency_ms' => (float) round((float) $avgLatency, 1),
        ]);
    }

    public function incidents(Request $request, Team $team)
    {
        abort_unless($team->roleFor($request->user()), 403);

        $incidents = $team->monitors()
            ->with('incidents.monitor')
            ->get()
            ->flatMap(fn (Monitor $monitor) => $monitor->incidents)
            ->sortByDesc('started_at')
            ->values();

        return response()->json($incidents->map(fn ($item) => [
            'id' => $item->id,
            'monitor_id' => $item->monitor_id,
            'monitor_name' => $item->monitor?->name,
            'status' => $item->status,
            'failure_reason' => $item->failure_reason,
            'started_at' => $item->started_at,
            'resolved_at' => $item->resolved_at,
        ])->all());
    }
}
