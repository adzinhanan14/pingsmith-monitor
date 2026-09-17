<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMonitorRequest;
use App\Http\Requests\UpdateMonitorRequest;
use App\Http\Resources\IncidentResource;
use App\Http\Resources\MonitorResource;
use App\Http\Resources\PingLogResource;
use App\Models\Monitor;
use App\Models\Team;
use App\Services\MonitorCheckService;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    private function team(Request $r, Team $team): Team
    {
        abort_unless($team->roleFor($r->user()), 403);

        return $team;
    }

    private function manage(Request $r, Team $t): void
    {
        abort_unless($t->canManage($r->user()), 403);
    }

    public function index(Request $r, Team $team)
    {
        $this->team($r, $team);
        $q = $team->monitors()->when($r->search, fn ($q, $v) => $q->where(fn ($q) => $q->where('name', 'like', "%{$v}%")->orWhere('url', 'like', "%{$v}%")))->when($r->status, fn ($q, $v) => $q->where('status', $v));

        return MonitorResource::collection($q->latest()->paginate());
    }

    public function store(StoreMonitorRequest $r, Team $team)
    {
        $this->manage($r, $team);
        $m = $team->monitors()->create($r->validated() + ['next_check_at' => now()]);

        return new MonitorResource($m);
    }

    public function show(Request $r, Team $team, Monitor $monitor)
    {
        $this->team($r, $team);
        abort_unless($monitor->team_id === $team->id, 404);

        return new MonitorResource($monitor);
    }

    public function update(UpdateMonitorRequest $r, Team $team, Monitor $monitor)
    {
        $this->manage($r, $team);
        abort_unless($monitor->team_id === $team->id, 404);
        $monitor->update($r->validated());

        return new MonitorResource($monitor);
    }

    public function destroy(Request $r, Team $team, Monitor $monitor)
    {
        $this->manage($r, $team);
        abort_unless($monitor->team_id === $team->id, 404);
        $monitor->delete();

        return response()->noContent();
    }

    public function test(Request $r, Team $team, Monitor $monitor, MonitorCheckService $s)
    {
        $this->manage($r, $team);
        abort_unless($monitor->team_id === $team->id, 404);

        return new PingLogResource($s->check($monitor));
    }

    public function logs(Request $r, Team $team, Monitor $monitor)
    {
        $this->team($r, $team);
        abort_unless($monitor->team_id === $team->id, 404);

        return PingLogResource::collection($monitor->logs()->latest('checked_at')->paginate());
    }

    public function incidents(Request $r, Team $team, Monitor $monitor)
    {
        $this->team($r, $team);
        abort_unless($monitor->team_id === $team->id, 404);

        return IncidentResource::collection($monitor->incidents()->latest('started_at')->paginate());
    }
}
