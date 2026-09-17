<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{{ $statusPage->title }} - Status</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        secondary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        },
                        accent: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        .status-indicator {
            animation: pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-glow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }
        .uptime-bar {
            transition: all 0.3s ease;
        }
        .uptime-bar:hover {
            transform: scaleY(1.05);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100 antialiased">
    <!-- Background decoration -->
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(99,102,241,0.15),_transparent_50%),radial-gradient(circle_at_bottom_left,_rgba(20,184,166,0.1),_transparent_50%)]"></div>

    <div class="relative">
        <!-- Header -->
        <header class="border-b border-white/10 bg-slate-900/50 backdrop-blur-xl">
            <div class="mx-auto max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        @if($statusPage->logo_url)
                            <img src="{{ $statusPage->logo_url }}" alt="Logo" class="h-10 w-10 rounded-xl">
                        @else
                            <div class="grid h-12 w-12 place-items-center rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-lg font-black text-white shadow-lg shadow-primary-500/50">
                                {{ substr($statusPage->title, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $statusPage->title }}</h1>
                            @if($statusPage->description)
                                <p class="text-sm text-slate-400">{{ $statusPage->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusConfig = [
                                'operational' => ['text' => 'All Systems Operational', 'color' => 'secondary', 'icon' => '✓'],
                                'maintenance' => ['text' => 'Under Maintenance', 'color' => 'accent', 'icon' => '⚙'],
                                'partial_outage' => ['text' => 'Partial Outage', 'color' => 'accent', 'icon' => '⚠'],
                                'major_outage' => ['text' => 'Major Outage', 'color' => 'red', 'icon' => '✕'],
                                'unknown' => ['text' => 'Unknown', 'color' => 'slate', 'icon' => '?'],
                            ];
                            $currentStatus = $statusConfig[$overallStatus] ?? $statusConfig['unknown'];
                        @endphp
                        <div class="flex items-center gap-2 rounded-full border border-{{ $currentStatus['color'] }}-500/30 bg-{{ $currentStatus['color'] }}-500/10 px-4 py-2">
                            <span class="status-indicator inline-block h-2.5 w-2.5 rounded-full bg-{{ $currentStatus['color'] }}-400"></span>
                            <span class="text-sm font-medium text-{{ $currentStatus['color'] }}-300">{{ $currentStatus['text'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="mb-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Services</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ $totalMonitors }}</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary-500/10 text-primary-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Services Online</p>
                            <p class="mt-2 text-3xl font-bold text-secondary-400">{{ $upMonitors }}</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-secondary-500/10 text-secondary-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Uptime Rate</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ $totalMonitors > 0 ? round(($upMonitors / $totalMonitors) * 100, 1) : 0 }}%</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-accent-500/10 text-accent-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monitors List -->
            <div class="space-y-6">
                @if($groupedMonitors->isEmpty())
                    @foreach($monitors as $monitor)
                        @include('status-page.partials.monitor-card', ['monitor' => $monitor])
                    @endforeach
                @else
                    @foreach($groupedMonitors as $groupName => $groupMonitors)
                        <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                            <h2 class="mb-4 text-lg font-semibold text-white">
                                {{ $groupName ?? 'Ungrouped Services' }}
                            </h2>
                            <div class="space-y-3">
                                @foreach($groupMonitors as $monitor)
                                    @include('status-page.partials.monitor-row', ['monitor' => $monitor])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Recent Incidents -->
            @if($recentIncidents->isNotEmpty())
                <div class="mt-8 rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <h2 class="mb-4 text-lg font-semibold text-white">Recent Incidents (Last 30 Days)</h2>
                    <div class="space-y-3">
                        @foreach($recentIncidents as $incident)
                            <div class="flex items-start justify-between rounded-xl border border-white/10 bg-slate-900/50 p-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $incident->status === 'RESOLVED' ? 'bg-secondary-500/10 text-secondary-300' : 'bg-red-500/10 text-red-300' }}">
                                            {{ $incident->status }}
                                        </span>
                                        <h3 class="font-medium text-white">{{ $incident->monitor?->name ?? 'Unknown Monitor' }}</h3>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-400">{{ $incident->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Footer -->
            <footer class="mt-12 border-t border-white/10 pt-8 text-center text-sm text-slate-500">
                <p>Powered by Pingsmith Monitor</p>
                <p class="mt-2">Last updated: {{ now()->format('M d, Y H:i:s') }} UTC</p>
            </footer>
        </main>
    </div>
</body>
</html>
