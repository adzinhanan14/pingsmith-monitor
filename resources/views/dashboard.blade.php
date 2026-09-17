<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard · Pingsmith Monitor</title>
    <style>
        .menu-item { transition: all 180ms ease; }
        .menu-item:hover { transform: translateX(2px); background: rgba(255,255,255,0.04); }
        .chart-bars span {
            display: block;
            width: 100%;
            border-radius: 9999px;
            background: linear-gradient(180deg, rgba(103,232,249,0.9), rgba(59,130,246,0.65));
            box-shadow: 0 0 18px rgba(34, 211, 238, 0.18);
        }
    </style>
</head>
<body class="min-h-screen bg-[#070b12] text-slate-100 antialiased">
<div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.16),_transparent_28%),radial-gradient(circle_at_right,_rgba(59,130,246,0.14),_transparent_28%)]"></div>

<main class="relative mx-auto max-w-[1500px] p-4 sm:p-6 lg:p-8">
    <div class="grid min-h-[calc(100vh-2rem)] gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="rounded-[26px] border border-white/10 bg-[#0d1727]/90 p-5 shadow-[0_24px_80px_rgba(14,116,144,0.16)] backdrop-blur-xl">
            <div class="mb-8 flex items-center gap-3">
                <div class="grid size-9 place-items-center rounded-2xl bg-gradient-to-br from-cyan-300 to-sky-500 text-sm font-black text-slate-950 shadow-[0_0_24px_rgba(34,211,238,0.38)]">P</div>
                <div>
                    <p class="text-[10px] font-bold tracking-[0.2em] text-cyan-300">PINGSMITH</p>
                    <p class="text-sm text-slate-400">Control Center</p>
                </div>
            </div>

            <nav class="space-y-2 text-[15px] text-slate-300">
                <a href="#" class="menu-item flex items-center justify-between gap-3 rounded-2xl border border-cyan-400/20 bg-cyan-400/10 px-3 py-2.5 font-medium text-cyan-200">
                    <span class="flex items-center gap-3"><span class="grid size-6 place-items-center rounded-lg bg-cyan-400/10 text-[11px]">◉</span> Overview</span>
                    <span class="rounded-full bg-cyan-400/15 px-2 py-0.5 text-[10px] text-cyan-200">Live</span>
                </a>
                <a href="#" class="menu-item flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-300"><span class="grid size-6 place-items-center rounded-lg bg-white/5 text-[11px]">◌</span> Monitors</a>
                <a href="#" class="menu-item flex items-center justify-between gap-3 rounded-2xl px-3 py-2.5 text-slate-300"><span class="flex items-center gap-3"><span class="grid size-6 place-items-center rounded-lg bg-white/5 text-[11px]">◌</span> Alerts</span><span class="rounded-full bg-white/5 px-2 py-0.5 text-[10px] text-slate-300">{{ $alertChannels->count() }}</span></a>
                <a href="#" class="menu-item flex items-center justify-between gap-3 rounded-2xl px-3 py-2.5 text-slate-300"><span class="flex items-center gap-3"><span class="grid size-6 place-items-center rounded-lg bg-white/5 text-[11px]">◌</span> Incidents</span><span class="rounded-full bg-rose-500/10 px-2 py-0.5 text-[10px] text-rose-300">{{ $recentIncidents->count() }}</span></a>
                <a href="#" class="menu-item flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-300"><span class="grid size-6 place-items-center rounded-lg bg-white/5 text-[11px]">◌</span> Settings</a>
            </nav>

            <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-slate-500">Status</p>
                <div class="mt-3 flex items-center gap-2 text-sm text-emerald-300">
                    <span class="inline-block size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    All systems operational
                </div>
            </div>
        </aside>

        <section class="rounded-[26px] border border-white/10 bg-[#0b1220]/60 p-4 shadow-[0_24px_80px_rgba(2,6,23,0.45)] backdrop-blur-xl sm:p-6">
            <div class="flex items-center justify-between gap-3 border-b border-white/10 pb-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-cyan-300">Overview</p>
                    <h1 class="mt-2 text-2xl font-bold text-white">{{ $team?->name ?? 'Workspace' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <div class="rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1.5 text-xs text-cyan-200">Live</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs text-slate-200 transition hover:border-cyan-400/40 hover:text-cyan-200">Logout</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-5 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mt-5 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ $errors->first() }}</div>
            @endif

            @if($team)
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach(['Total monitor' => $stats['total'], 'Active' => $stats['up'], 'Uptime' => $stats['uptime'].'%'] as $label => $value)
                        <div class="rounded-2xl border border-white/10 bg-[linear-gradient(180deg,rgba(15,23,42,0.96),rgba(15,23,42,0.82))] p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-slate-400">{{ $label }}</p>
                                <span class="grid size-8 place-items-center rounded-xl bg-cyan-400/10 text-cyan-300">●</span>
                            </div>
                            <p class="mt-4 text-3xl font-bold text-white">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-[1.5fr_0.9fr]">
                    <div class="rounded-2xl border border-white/10 bg-[#0f172a]/90 p-4">
                        <div class="mb-4 flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Performance trend</p>
                            <span class="text-xs text-emerald-300">+12.4%</span>
                        </div>
                        <div class="chart-bars flex h-28 items-end gap-2">
                            @foreach([28, 38, 52, 44, 66, 60, 78, 88, 82, 96, 90, 100] as $bar)
                                <span style="height: {{ $bar }}%;"></span>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0f172a]/90 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Error budget</p>
                        <p class="mt-3 text-3xl font-bold text-white">0.03%</p>
                        <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-800">
                            <div class="h-full w-[32%] rounded-full bg-gradient-to-r from-emerald-400 to-cyan-400"></div>
                        </div>
                        <p class="mt-3 text-sm text-slate-400">Healthy threshold</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 xl:grid-cols-[1.5fr_0.9fr]">
                    <div class="rounded-2xl border border-white/10 bg-[#0f172a]/90 p-4">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white">Monitors</h2>
                            <span class="rounded-full border border-cyan-400/20 bg-cyan-400/10 px-2.5 py-1 text-[10px] uppercase tracking-[0.18em] text-cyan-200">Live</span>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-white/10">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-[#0b1220] text-slate-400">
                                <tr>
                                    <th class="p-3 font-medium">Service</th>
                                    <th class="p-3 font-medium">Status</th>
                                    <th class="hidden p-3 font-medium md:table-cell">Latency</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($monitors->take(5) as $monitor)
                                    <tr class="border-t border-white/10">
                                        <td class="p-3">
                                            <div class="font-medium text-slate-100">{{ $monitor->name }}</div>
                                            <div class="mt-1 text-xs text-slate-500">{{ $monitor->url }}</div>
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-flex rounded-full px-2 py-1 text-[10px] font-medium {{ $monitor->status === 'UP' ? 'bg-emerald-500/10 text-emerald-300' : ($monitor->status === 'DOWN' ? 'bg-red-500/10 text-red-300' : 'bg-slate-700 text-slate-300') }}">
                                                {{ $monitor->status }}
                                            </span>
                                        </td>
                                        <td class="hidden p-3 text-slate-300 md:table-cell">{{ $monitor->last_latency_ms ? $monitor->last_latency_ms.' ms' : '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-4 text-center text-slate-500">No monitors created yet.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-2xl border border-white/10 bg-[#0f172a]/90 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Recent incidents</p>
                                <span class="text-xs text-slate-400">{{ $recentIncidents->count() }}</span>
                            </div>
                            @forelse($recentIncidents->take(3) as $incident)
                                <div class="mt-3 flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm">
                                    <span class="text-slate-200">{{ $incident->monitor?->name ?? 'Unknown monitor' }}</span>
                                    <span class="rounded-full bg-red-500/10 px-2 py-1 text-[10px] text-red-300">{{ $incident->status }}</span>
                                </div>
                            @empty
                                <div class="mt-3 rounded-xl border border-dashed border-white/10 bg-white/5 px-3 py-3 text-sm text-slate-400">No incidents yet.</div>
                            @endforelse
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-[#0f172a]/90 p-4">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Alerts</p>
                                <span class="text-xs text-slate-400">{{ $alertChannels->count() }}</span>
                            </div>
                            @forelse($alertChannels->take(3) as $channel)
                                <div class="mt-3 flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm">
                                    <span class="text-slate-200">{{ $channel->name }}</span>
                                    <span class="rounded-full {{ $channel->is_enabled ? 'bg-emerald-500/10 text-emerald-300' : 'bg-slate-700 text-slate-300' }} px-2 py-1 text-[10px]">
                                        {{ $channel->is_enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </div>
                            @empty
                                <div class="mt-3 rounded-xl border border-dashed border-white/10 bg-white/5 px-3 py-3 text-sm text-slate-400">No alert channels configured.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <section class="mt-6 rounded-[28px] border border-white/10 bg-[#0f172a]/80 p-8 text-center shadow-[0_20px_60px_rgba(15,23,42,0.7)]">
                    <h2 class="text-xl font-bold text-white">Belum ada workspace</h2>
                    <p class="mt-2 text-slate-400">Buat workspace melalui API terlebih dahulu, lalu kembali ke dashboard.</p>
                </section>
            @endif
        </section>
    </div>
</main>
</body>
</html>
