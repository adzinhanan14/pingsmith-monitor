<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <title>Analytics · Pingsmith Monitor</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        },
                        secondary: {
                            500: '#14b8a6',
                            600: '#0d9488',
                        },
                        accent: {
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100">
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top,_rgba(99,102,241,0.15),_transparent_50%),radial-gradient(circle_at_bottom,_rgba(20,184,166,0.1),_transparent_50%)]"></div>

    <div class="relative">
        <!-- Header -->
        <header class="border-b border-white/10 bg-slate-900/50 backdrop-blur-xl">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Analytics Dashboard</h1>
                        <p class="mt-1 text-sm text-slate-400">{{ $team->name }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <select class="rounded-xl border border-white/10 bg-slate-800 px-4 py-2 text-sm text-white" onchange="window.location.href='?period='+this.value">
                            <option value="24h" {{ $period === '24h' ? 'selected' : '' }}>Last 24 Hours</option>
                            <option value="7d" {{ $period === '7d' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30d" {{ $period === '30d' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="90d" {{ $period === '90d' ? 'selected' : '' }}>Last 90 Days</option>
                        </select>
                        <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/10 bg-slate-800 px-4 py-2 text-sm text-white transition hover:bg-slate-700">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="mb-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Uptime</p>
                            <p class="mt-2 text-3xl font-bold text-secondary-400">{{ $uptimeStats['uptime_percentage'] }}%</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-secondary-500/10">
                            <svg class="h-6 w-6 text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">{{ $uptimeStats['successful_checks'] }} / {{ $uptimeStats['total_checks'] }} checks</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Avg Response</p>
                            <p class="mt-2 text-3xl font-bold text-primary-400">{{ $uptimeStats['avg_response_time'] }} ms</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-primary-500/10">
                            <svg class="h-6 w-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Average latency</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Checks</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ number_format($uptimeStats['total_checks']) }}</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-accent-500/10">
                            <svg class="h-6 w-6 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Monitoring requests</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Failed Checks</p>
                            <p class="mt-2 text-3xl font-bold text-red-400">{{ number_format($uptimeStats['failed_checks']) }}</p>
                        </div>
                        <div class="grid h-12 w-12 place-items-center rounded-xl bg-red-500/10">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Downtime events</p>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="mb-8 grid gap-6 lg:grid-cols-2">
                <!-- Response Time Trend -->
                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <h2 class="mb-4 text-lg font-semibold text-white">Response Time Trend</h2>
                    <div class="chart-container">
                        <canvas id="responseTimeChart"></canvas>
                    </div>
                </div>

                <!-- Availability Heatmap -->
                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <h2 class="mb-4 text-lg font-semibold text-white">Daily Availability</h2>
                    <div class="chart-container">
                        <canvas id="availabilityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Incidents Table -->
            @if($incidentsByMonitor->isNotEmpty())
                <div class="rounded-2xl border border-white/10 bg-slate-800/50 p-6 backdrop-blur-xl">
                    <h2 class="mb-4 text-lg font-semibold text-white">Incidents by Monitor</h2>
                    <div class="overflow-hidden rounded-xl border border-white/10">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-900 text-slate-400">
                                <tr>
                                    <th class="p-4 font-medium">Monitor</th>
                                    <th class="p-4 font-medium">URL</th>
                                    <th class="p-4 font-medium text-right">Incidents</th>
                                    <th class="p-4 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($incidentsByMonitor as $monitor)
                                    <tr class="transition hover:bg-slate-900/50">
                                        <td class="p-4 font-medium text-white">{{ $monitor->name }}</td>
                                        <td class="p-4 text-slate-400">{{ $monitor->url }}</td>
                                        <td class="p-4 text-right font-semibold text-red-400">{{ $monitor->incidents_count }}</td>
                                        <td class="p-4">
                                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $monitor->status === 'UP' ? 'bg-secondary-500/10 text-secondary-300' : 'bg-red-500/10 text-red-300' }}">
                                                {{ $monitor->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <script>
        // Response Time Chart
        const responseTimeCtx = document.getElementById('responseTimeChart').getContext('2d');
        new Chart(responseTimeCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($responseTimeTrends->pluck('period')) !!},
                datasets: [{
                    label: 'Avg Response Time (ms)',
                    data: {!! json_encode($responseTimeTrends->pluck('avg_latency')) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });

        // Availability Chart
        const availabilityCtx = document.getElementById('availabilityChart').getContext('2d');
        new Chart(availabilityCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($availabilityData->pluck('date')) !!},
                datasets: [{
                    label: 'Uptime %',
                    data: {!! json_encode($availabilityData->pluck('uptime_percentage')) !!},
                    backgroundColor: function(context) {
                        const value = context.parsed.y;
                        if (value === 100) return '#14b8a6';
                        if (value >= 95) return '#f59e0b';
                        return '#ef4444';
                    },
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.05)'
                        },
                        ticks: {
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
