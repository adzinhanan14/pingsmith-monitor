@php
    $isUp = $monitor->status === 'UP';
    $isDown = $monitor->status === 'DOWN';
    $isMaintenance = $monitor->isInMaintenance();
    
    // Calculate uptime for last 90 days
    $logs = $monitor->logs;
    $totalLogs = $logs->count();
    $upLogs = $logs->where('is_up', true)->count();
    $uptimePercentage = $totalLogs > 0 ? round(($upLogs / $totalLogs) * 100, 2) : 0;
@endphp

<div class="flex items-center justify-between rounded-xl border border-white/5 bg-slate-900/30 p-4 transition hover:border-white/10 hover:bg-slate-900/50">
    <div class="flex flex-1 items-center gap-4">
        <!-- Status indicator -->
        <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $isMaintenance ? 'bg-accent-500/10' : ($isUp ? 'bg-secondary-500/10' : 'bg-red-500/10') }}">
            @if($isMaintenance)
                <svg class="h-5 w-5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            @elseif($isUp)
                <svg class="h-5 w-5 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            @else
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            @endif
        </div>

        <!-- Monitor info -->
        <div class="flex-1">
            <h3 class="font-medium text-white">{{ $monitor->name }}</h3>
            <p class="text-sm text-slate-400">{{ $monitor->url }}</p>
        </div>

        <!-- Uptime percentage -->
        <div class="text-right">
            <p class="text-lg font-semibold {{ $uptimePercentage >= 99 ? 'text-secondary-400' : ($uptimePercentage >= 95 ? 'text-accent-400' : 'text-red-400') }}">
                {{ $uptimePercentage }}%
            </p>
            <p class="text-xs text-slate-500">90-day uptime</p>
        </div>

        <!-- Latency -->
        @if($monitor->last_latency_ms && !$isMaintenance)
            <div class="text-right">
                <p class="text-lg font-semibold text-slate-300">{{ $monitor->last_latency_ms }} ms</p>
                <p class="text-xs text-slate-500">Response time</p>
            </div>
        @endif

        <!-- Status badge -->
        <div>
            @if($isMaintenance)
                <span class="inline-flex rounded-full bg-accent-500/10 px-3 py-1.5 text-xs font-medium text-accent-300">
                    Maintenance
                </span>
            @elseif($isUp)
                <span class="inline-flex rounded-full bg-secondary-500/10 px-3 py-1.5 text-xs font-medium text-secondary-300">
                    Operational
                </span>
            @else
                <span class="inline-flex rounded-full bg-red-500/10 px-3 py-1.5 text-xs font-medium text-red-300">
                    Down
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Uptime history bar (90 days) -->
<div class="mt-2 flex gap-0.5 px-4">
    @php
        $days = 90;
        $dailyLogs = $logs->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
    @endphp
    @for($i = $days - 1; $i >= 0; $i--)
        @php
            $date = now()->subDays($i)->format('Y-m-d');
            $dayLogs = $dailyLogs->get($date, collect());
            $dayTotal = $dayLogs->count();
            $dayUp = $dayLogs->where('is_up', true)->count();
            $dayUptime = $dayTotal > 0 ? ($dayUp / $dayTotal) * 100 : 100;
            
            $colorClass = match(true) {
                $dayTotal === 0 => 'bg-slate-700',
                $dayUptime === 100 => 'bg-secondary-500',
                $dayUptime >= 95 => 'bg-accent-500',
                $dayUptime >= 80 => 'bg-orange-500',
                default => 'bg-red-500',
            };
        @endphp
        <div class="uptime-bar h-8 flex-1 rounded-sm {{ $colorClass }}" 
             title="{{ $date }}: {{ round($dayUptime, 1) }}% uptime"></div>
    @endfor
</div>
