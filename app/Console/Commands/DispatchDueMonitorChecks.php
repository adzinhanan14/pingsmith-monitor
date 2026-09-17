<?php

namespace App\Console\Commands;

use App\Jobs\CheckMonitor;
use App\Models\Monitor;
use Illuminate\Console\Command;

class DispatchDueMonitorChecks extends Command
{
    protected $signature = 'monitors:dispatch-due';

    protected $description = 'Queue checks for monitors that are due';

    public function handle(): int
    {
        $count = 0;
        Monitor::where('status', '!=', 'PAUSED')->where(fn ($q) => $q->whereNull('next_check_at')->orWhere('next_check_at', '<=', now()))->select('id')->chunkById(500, function ($rows) use (&$count) {
            foreach ($rows as $m) {
                CheckMonitor::dispatch($m->id);
                $count++;
            }
        });
        $this->info("Queued {$count} monitor checks.");

        return self::SUCCESS;
    }
}
