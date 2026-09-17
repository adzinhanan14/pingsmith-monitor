<?php

namespace App\Jobs;

use App\Models\Monitor;
use App\Services\MonitorCheckService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckMonitor implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(public int $monitorId) {}

    public function handle(MonitorCheckService $checker): void
    {
        $monitor = Monitor::find($this->monitorId);
        if ($monitor && $monitor->status !== 'PAUSED') {
            $checker->check($monitor);
        }
    }
}
