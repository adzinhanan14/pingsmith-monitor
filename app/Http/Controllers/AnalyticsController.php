<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use App\Models\PingLog;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $team = Team::where('owner_id', $user->id)->first();

        if (!$team) {
            return redirect()->route('dashboard')->with('error', 'No team found');
        }

        $period = $request->get('period', '7d'); // 24h, 7d, 30d, 90d
        $dateRange = $this->getDateRange($period);

        $monitors = Monitor::where('team_id', $team->id)->get();

        // Uptime statistics
        $uptimeStats = $this->calculateUptimeStats($team->id, $dateRange);

        // Response time trends
        $responseTimeTrends = $this->getResponseTimeTrends($team->id, $dateRange, $period);

        // Incidents by monitor
        $incidentsByMonitor = $this->getIncidentsByMonitor($team->id, $dateRange);

        // Availability heatmap data
        $availabilityData = $this->getAvailabilityHeatmap($team->id, $dateRange);

        return view('analytics.index', compact(
            'team',
            'monitors',
            'period',
            'uptimeStats',
            'responseTimeTrends',
            'incidentsByMonitor',
            'availabilityData'
        ));
    }

    private function getDateRange($period)
    {
        return match ($period) {
            '24h' => now()->subHours(24),
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            default => now()->subDays(7),
        };
    }

    private function calculateUptimeStats($teamId, $dateRange)
    {
        $monitors = Monitor::where('team_id', $teamId)->pluck('id');

        $totalChecks = PingLog::whereIn('monitor_id', $monitors)
            ->where('checked_at', '>=', $dateRange)
            ->count();

        $successfulChecks = PingLog::whereIn('monitor_id', $monitors)
            ->where('checked_at', '>=', $dateRange)
            ->where('is_success', true)
            ->count();

        $uptime = $totalChecks > 0 ? round(($successfulChecks / $totalChecks) * 100, 2) : 0;

        $avgResponseTime = PingLog::whereIn('monitor_id', $monitors)
            ->where('checked_at', '>=', $dateRange)
            ->where('is_success', true)
            ->avg('latency_ms');

        return [
            'uptime_percentage' => $uptime,
            'total_checks' => $totalChecks,
            'successful_checks' => $successfulChecks,
            'failed_checks' => $totalChecks - $successfulChecks,
            'avg_response_time' => round($avgResponseTime ?? 0, 2),
        ];
    }

    private function getResponseTimeTrends($teamId, $dateRange, $period)
    {
        $monitors = Monitor::where('team_id', $teamId)->pluck('id');
        $driver = DB::connection()->getDriverName();

        $groupBy = match ($period) {
            '24h' => match ($driver) {
                'pgsql' => "to_char(checked_at, 'YYYY-MM-DD HH24:00')",
                'mysql' => "DATE_FORMAT(checked_at, '%Y-%m-%d %H:00')",
                default => "strftime('%Y-%m-%d %H:00', checked_at)",
            },
            default => match ($driver) {
                'pgsql' => "to_char(checked_at, 'YYYY-MM-DD')",
                'mysql' => "DATE(checked_at)",
                default => "date(checked_at)",
            },
        };

        $trends = PingLog::whereIn('monitor_id', $monitors)
            ->where('checked_at', '>=', $dateRange)
            ->where('is_success', true)
            ->select(
                DB::raw("$groupBy as period"),
                DB::raw('AVG(latency_ms) as avg_latency'),
                DB::raw('MIN(latency_ms) as min_latency'),
                DB::raw('MAX(latency_ms) as max_latency'),
                DB::raw('COUNT(*) as check_count')
            )
            ->groupBy(DB::raw($groupBy))
            ->orderBy('period')
            ->get();

        return $trends;
    }

    private function getIncidentsByMonitor($teamId, $dateRange)
    {
        return Monitor::where('team_id', $teamId)
            ->whereHas('incidents', function ($query) use ($dateRange) {
                $query->where('started_at', '>=', $dateRange);
            })
            ->withCount(['incidents' => function ($query) use ($dateRange) {
                $query->where('started_at', '>=', $dateRange);
            }])
            ->orderByDesc('incidents_count')
            ->limit(10)
            ->get();
    }

    private function getAvailabilityHeatmap($teamId, $dateRange)
    {
        $monitors = Monitor::where('team_id', $teamId)->pluck('id');
        $driver = DB::connection()->getDriverName();

        $dateExpr = match ($driver) {
            'pgsql' => "to_char(checked_at, 'YYYY-MM-DD')",
            'mysql' => "DATE(checked_at)",
            default => "date(checked_at)",
        };

        $heatmapData = PingLog::whereIn('monitor_id', $monitors)
            ->where('checked_at', '>=', $dateRange)
            ->select(
                DB::raw("{$dateExpr} as date"),
                DB::raw('CAST(AVG(CASE WHEN is_success THEN 100 ELSE 0 END) as INTEGER) as uptime_percentage')
            )
            ->groupBy(DB::raw($dateExpr))
            ->orderBy('date')
            ->get();

        return $heatmapData;
    }
}
