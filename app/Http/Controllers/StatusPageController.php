<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\StatusPage;
use App\Models\Team;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    public function show($slug)
    {
        $statusPage = StatusPage::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $monitors = Monitor::where('team_id', $statusPage->team_id)
            ->where('show_on_status_page', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with(['logs' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(90))
                    ->orderBy('created_at', 'desc');
            }])
            ->get();

        // Group monitors by group name
        $groupedMonitors = $monitors->groupBy('group');

        // Calculate overall uptime
        $totalMonitors = $monitors->count();
        $upMonitors = $monitors->where('status', 'UP')->count();
        $overallStatus = $this->calculateOverallStatus($monitors);

        // Get recent incidents (last 30 days)
        $recentIncidents = \App\Models\Incident::whereIn('monitor_id', $monitors->pluck('id'))
            ->where('created_at', '>=', now()->subDays(30))
            ->with('monitor')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('status-page.show', compact(
            'statusPage',
            'groupedMonitors',
            'monitors',
            'totalMonitors',
            'upMonitors',
            'overallStatus',
            'recentIncidents'
        ));
    }

    private function calculateOverallStatus($monitors)
    {
        $downCount = $monitors->where('status', 'DOWN')->count();
        $maintenanceCount = $monitors->filter(fn($m) => $m->isInMaintenance())->count();
        $totalCount = $monitors->count();

        if ($totalCount === 0) {
            return 'unknown';
        }

        if ($downCount > 0) {
            return $downCount === $totalCount ? 'major_outage' : 'partial_outage';
        }

        if ($maintenanceCount > 0) {
            return 'maintenance';
        }

        return 'operational';
    }
}
