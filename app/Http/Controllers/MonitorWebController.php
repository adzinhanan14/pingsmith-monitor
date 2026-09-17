<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMonitorRequest;
use App\Models\Monitor;
use App\Services\MonitorCheckService;
use Illuminate\Http\Request;

class MonitorWebController extends Controller
{
    public function store(StoreMonitorRequest $request)
    {
        $team = $request->user()->teams()->firstOrFail();
        abort_unless($team->canManage($request->user()), 403);
        $team->monitors()->create($request->validated() + ['next_check_at' => now(), 'status' => 'PENDING']);

        return redirect()->route('dashboard')->with('success', 'Monitor berhasil ditambahkan.');
    }

    public function check(Request $request, Monitor $monitor, MonitorCheckService $checker)
    {
        $this->manage($request, $monitor);
        $checker->check($monitor);

        return back()->with('success', "Pengecekan {$monitor->name} selesai.");
    }

    public function togglePause(Request $request, Monitor $monitor)
    {
        $this->manage($request, $monitor);
        $monitor->update(['status' => $monitor->status === 'PAUSED' ? 'PENDING' : 'PAUSED', 'next_check_at' => now()]);

        return back()->with('success', "Monitor {$monitor->name} diperbarui.");
    }

    public function destroy(Request $request, Monitor $monitor)
    {
        $this->manage($request, $monitor);
        $monitor->delete();

        return back()->with('success', 'Monitor dihapus.');
    }

    private function manage(Request $request, Monitor $monitor): void
    {
        $team = $request->user()->teams()->whereKey($monitor->team_id)->first();
        abort_unless($team && $team->canManage($request->user()), 403);
    }
}
