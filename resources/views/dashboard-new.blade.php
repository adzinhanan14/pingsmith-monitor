<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Dashboard · Pingsmith Monitor</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81',
                        },
                        secondary: {
                            50: '#f0fdfa', 100: '#ccfbf1', 200: '#99f6e4', 300: '#5eead4',
                            400: '#2dd4bf', 500: '#14b8a6', 600: '#0d9488', 700: '#0f766e',
                            800: '#115e59', 900: '#134e4a',
                        },
                        accent: {
                            50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d',
                            400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309',
                            800: '#92400e', 900: '#78350f',
                        },
                    }
                }
            }
        }
    </script>
    <style>
        .menu-item { transition: all 200ms cubic-bezier(0.4, 0, 0.2, 1); }
        .menu-item:hover { transform: translateX(3px); }
        .card-hover { transition: all 200ms ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2); }
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-ring {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.1); }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-slate-100 antialiased">
    <!-- Background effects -->
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_30%_20%,_rgba(99,102,241,0.15),_transparent_40%),radial-gradient(circle_at_70%_60%,_rgba(20,184,166,0.12),_transparent_40%),radial-gradient(circle_at_50%_90%,_rgba(245,158,11,0.08),_transparent_40%)]"></div>
    
    <div class="relative">
        <main class="mx-auto max-w-[1600px] p-4 sm:p-6 lg:p-8">
            <div class="grid min-h-[calc(100vh-2rem)] gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
                <!-- Sidebar -->
                <aside class="rounded-3xl border border-white/10 bg-slate-800/50 p-6 shadow-2xl backdrop-blur-xl">
                    <!-- Logo -->
                    <div class="mb-8 flex items-center gap-3">
                        <div class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 text-base font-black text-white shadow-lg shadow-primary-500/50">
                            P
                        </div>
                        <div>
                            <p class="text-xs font-bold tracking-wider text-primary-300">PINGSMITH</p>
                            <p class="text-sm text-slate-400">Monitor Pro</p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="space-y-2 text-sm">
                        <a href="{{ route('dashboard') }}" class="menu-item flex items-center justify-between gap-3 rounded-2xl border border-primary-500/30 bg-primary-500/10 px-4 py-3 font-semibold text-primary-200 shadow-sm">
                            <span class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </span>
                            <span class="rounded-full bg-primary-500/20 px-2 py-0.5 text-[10px] font-medium text-primary-300">LIVE</span>
                        </a>

                        <a href="#monitors-section" onclick="scrollToSection('monitors-section'); return false;" class="menu-item flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-300 hover:bg-slate-700/50 hover:text-white">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Monitors
                            <span class="ml-auto rounded-full bg-slate-700 px-2 py-0.5 text-[10px] font-semibold text-slate-200">{{ $monitors->count() }}</span>
                        </a>

                        @if($team)
                        <a href="{{ route('analytics.index') }}" class="menu-item flex items-center gap-3 rounded-2xl px-4 py-3 text-slate-300 hover:bg-slate-700/50 hover:text-white">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Analytics
                        </a>
                        @endif

                        <a href="#alerts-section" onclick="scrollToSection('alerts-section'); return false;" class="menu-item flex items-center justify-between gap-3 rounded-2xl px-4 py-3 text-slate-300 hover:bg-slate-700/50 hover:text-white">
                            <span class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                Alerts
                            </span>
                            <span class="rounded-full bg-slate-700 px-2 py-0.5 text-[10px] font-semibold text-slate-200">{{ $alertChannels->count() }}</span>
                        </a>

                        <a href="#incidents-section" onclick="scrollToSection('incidents-section'); return false;" class="menu-item flex items-center justify-between gap-3 rounded-2xl px-4 py-3 text-slate-300 hover:bg-slate-700/50 hover:text-white">
                            <span class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Incidents
                            </span>
                            @if($recentIncidents->count() > 0)
                                <span class="rounded-full bg-red-500/20 px-2 py-0.5 text-[10px] font-semibold text-red-300">{{ $recentIncidents->count() }}</span>
                            @else
                                <span class="rounded-full bg-slate-700 px-2 py-0.5 text-[10px] font-semibold text-slate-400">0</span>
                            @endif
                        </a>
                    </nav>

                    <!-- Dynamic & Clickable System Status Indicator -->
                    @php
                        $statusUrl = isset($statusPage) ? route('status.show', $statusPage->slug) : route('status.show', 'demo-status');
                        $isDown = isset($stats['down']) && $stats['down'] > 0;
                    @endphp
                    <a href="{{ $statusUrl }}" target="_blank" title="View Public Status Page" class="mt-8 block rounded-2xl border border-white/10 bg-slate-900/50 p-4 transition hover:border-white/20 hover:bg-slate-900/80">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">System Status</p>
                            <svg class="h-3.5 w-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </div>
                        <div class="mt-3 flex items-center gap-2.5">
                            <div class="relative">
                                <span class="absolute inline-flex h-3 w-3 animate-ping rounded-full {{ $isDown ? 'bg-red-400' : 'bg-secondary-400' }} opacity-75"></span>
                                <span class="relative inline-flex h-3 w-3 rounded-full {{ $isDown ? 'bg-red-500' : 'bg-secondary-400' }}"></span>
                            </div>
                            <span class="text-sm font-medium {{ $isDown ? 'text-red-300' : 'text-secondary-300' }}">
                                {{ $isDown ? $stats['down'] . ' Service(s) Down' : 'All Systems Operational' }}
                            </span>
                        </div>
                    </a>

                    <!-- User Menu -->
                    <div class="mt-auto pt-6">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="menu-item flex w-full items-center gap-3 rounded-2xl border border-white/10 bg-slate-900/30 px-4 py-3 text-sm text-slate-300 hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Main Content -->
                <section class="rounded-3xl border border-white/10 bg-slate-800/30 p-6 shadow-2xl backdrop-blur-xl">
                    <!-- Header -->
                    <div class="mb-6 flex items-center justify-between border-b border-white/10 pb-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-primary-300">Dashboard</p>
                            <h1 class="mt-1 text-3xl font-bold text-white">{{ $team?->name ?? 'Welcome' }}</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="rounded-full border border-primary-500/30 bg-primary-500/10 px-4 py-2 text-xs font-medium text-primary-300">
                                <span class="pulse-ring inline-block mr-1.5 h-1.5 w-1.5 rounded-full bg-primary-400"></span>
                                LIVE
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 rounded-2xl border border-secondary-500/30 bg-secondary-500/10 px-5 py-4 text-sm text-secondary-200">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 px-5 py-4 text-sm text-red-200">
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    @if($team)
                        <!-- Stats Cards -->
                        <div class="mb-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="card-hover rounded-2xl border border-white/10 bg-gradient-to-br from-primary-500/10 to-primary-600/5 p-6 backdrop-blur-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-400">Total Monitors</p>
                                        <p class="mt-3 text-4xl font-bold text-white">{{ $stats['total'] }}</p>
                                    </div>
                                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-primary-500/20 text-primary-300">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-2 text-xs text-slate-400">
                                    <span class="flex items-center gap-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-secondary-400"></span>
                                        {{ $stats['up'] }} active
                                    </span>
                                </div>
                            </div>

                            <div class="card-hover rounded-2xl border border-white/10 bg-gradient-to-br from-secondary-500/10 to-secondary-600/5 p-6 backdrop-blur-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-400">Services Online</p>
                                        <p class="mt-3 text-4xl font-bold text-secondary-300">{{ $stats['up'] }}</p>
                                    </div>
                                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-secondary-500/20 text-secondary-300">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-700">
                                    <div class="h-full rounded-full bg-gradient-to-r from-secondary-500 to-secondary-400" style="width: {{ $stats['total'] > 0 ? ($stats['up'] / $stats['total']) * 100 : 0 }}%"></div>
                                </div>
                            </div>

                            <div class="card-hover rounded-2xl border border-white/10 bg-gradient-to-br from-accent-500/10 to-accent-600/5 p-6 backdrop-blur-sm">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-slate-400">Uptime Rate</p>
                                        <p class="mt-3 text-4xl font-bold text-white">{{ $stats['uptime'] }}%</p>
                                    </div>
                                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-accent-500/20 text-accent-300">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-4 text-xs text-slate-400">{{ $stats['total'] > 0 ? 'Excellent performance' : 'No data yet' }}</p>
                            </div>
                        </div>

                        <!-- Main Content Grid -->
                        <div class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
                            <!-- Monitors Section -->
                            <div id="monitors-section" class="rounded-2xl border border-white/10 bg-slate-900/50 p-6">
                                <div class="mb-5 flex items-center justify-between">
                                    <h2 class="text-xl font-semibold text-white">Active Monitors</h2>
                                    <button onclick="document.getElementById('addMonitorForm').classList.toggle('hidden')" class="rounded-xl bg-primary-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-600">
                                        + Add Monitor
                                    </button>
                                </div>

                                <!-- Add Monitor Form -->
                                <div id="addMonitorForm" class="mb-6 hidden rounded-xl border border-white/10 bg-slate-800/50 p-5">
                                    <h3 class="mb-4 font-medium text-white">Create New Monitor</h3>
                                    <form method="POST" action="{{ route('web.monitors.store') }}" class="space-y-4">
                                        @csrf
                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-300">Monitor Name</label>
                                                <input type="text" name="name" required class="w-full rounded-lg border border-white/10 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20" placeholder="My Website">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-300">Type</label>
                                                <select name="type" required class="w-full rounded-lg border border-white/10 bg-slate-900 px-4 py-2.5 text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                                    <option value="http">HTTP/HTTPS</option>
                                                    <option value="ping">Ping</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="mb-2 block text-sm font-medium text-slate-300">URL</label>
                                            <input type="url" name="url" required class="w-full rounded-lg border border-white/10 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20" placeholder="https://example.com">
                                        </div>
                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-300">Check Interval (seconds)</label>
                                                <input type="number" name="interval_seconds" value="60" min="30" required class="w-full rounded-lg border border-white/10 bg-slate-900 px-4 py-2.5 text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                            </div>
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-slate-300">Timeout (seconds)</label>
                                                <input type="number" name="timeout_seconds" value="10" min="5" max="60" required class="w-full rounded-lg border border-white/10 bg-slate-900 px-4 py-2.5 text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button type="submit" class="rounded-lg bg-primary-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-primary-600">
                                                Create Monitor
                                            </button>
                                            <button type="button" onclick="document.getElementById('addMonitorForm').classList.add('hidden')" class="rounded-lg border border-white/10 bg-slate-800 px-6 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-slate-700">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Monitors List -->
                                <div class="space-y-3">
                                    @forelse($monitors as $monitor)
                                        <div class="rounded-xl border border-white/10 bg-slate-800/30 p-4 transition hover:border-white/20 hover:bg-slate-800/50">
                                            <div class="flex items-center justify-between">
                                                <div class="flex flex-1 items-center gap-4">
                                                    <div class="grid h-10 w-10 place-items-center rounded-lg {{ $monitor->status === 'UP' ? 'bg-secondary-500/20 text-secondary-400' : ($monitor->status === 'DOWN' ? 'bg-red-500/20 text-red-400' : 'bg-slate-700 text-slate-400') }}">
                                                        @if($monitor->status === 'UP')
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        @elseif($monitor->status === 'DOWN')
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="font-semibold text-white truncate">{{ $monitor->name }}</h3>
                                                        <p class="text-sm text-slate-400 truncate">{{ $monitor->url }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    @if($monitor->last_latency_ms)
                                                        <div class="text-right">
                                                            <p class="text-sm font-semibold text-white">{{ $monitor->last_latency_ms }}ms</p>
                                                            <p class="text-xs text-slate-500">Latency</p>
                                                        </div>
                                                    @endif
                                                    <span class="inline-flex rounded-full px-3 py-1.5 text-xs font-medium {{ $monitor->status === 'UP' ? 'bg-secondary-500/10 text-secondary-300' : ($monitor->status === 'DOWN' ? 'bg-red-500/10 text-red-300' : 'bg-slate-700 text-slate-300') }}">
                                                        {{ $monitor->status }}
                                                    </span>
                                                    <form method="POST" action="{{ route('web.monitors.check', $monitor) }}" class="inline">
                                                         @csrf
                                                         <button type="submit" title="Check Now" class="rounded-lg border border-white/10 bg-slate-800 p-2 text-primary-400 transition hover:border-primary-500/30 hover:bg-primary-500/10 hover:text-primary-300">
                                                             <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                             </svg>
                                                         </button>
                                                     </form>
                                                     <form method="POST" action="{{ route('web.monitors.destroy', $monitor) }}" onsubmit="return confirm('Delete this monitor?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="rounded-lg border border-white/10 bg-slate-800 p-2 text-slate-400 transition hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-400">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="rounded-xl border border-dashed border-white/20 bg-slate-800/30 p-8 text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="mt-3 text-slate-400">No monitors yet. Create your first one!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Sidebar Content -->
                            <div class="space-y-6">
                                <!-- Recent Incidents -->
                                <div id="incidents-section" class="rounded-2xl border border-white/10 bg-slate-900/50 p-6">
                                    <h2 class="mb-4 text-lg font-semibold text-white">Recent Incidents</h2>
                                    <div class="space-y-3">
                                        @forelse($recentIncidents->take(5) as $incident)
                                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-slate-800/30 p-3">
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-white truncate">{{ $incident->monitor?->name ?? 'Unknown' }}</p>
                                                    <p class="text-xs text-slate-400">{{ $incident->created_at->diffForHumans() }}</p>
                                                </div>
                                                <span class="ml-2 rounded-full {{ $incident->status === 'RESOLVED' ? 'bg-secondary-500/10 text-secondary-300' : 'bg-red-500/10 text-red-300' }} px-2.5 py-1 text-[10px] font-medium">
                                                    {{ $incident->status }}
                                                </span>
                                            </div>
                                        @empty
                                            <div class="rounded-xl border border-dashed border-white/20 bg-slate-800/30 p-6 text-center text-sm text-slate-400">
                                                <svg class="mx-auto h-8 w-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="mt-2">No incidents - Great!</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Alert Channels -->
                                <div id="alerts-section" class="rounded-2xl border border-white/10 bg-slate-900/50 p-6">
                                    <div class="mb-4 flex items-center justify-between">
                                        <h2 class="text-lg font-semibold text-white">Alert Channels</h2>
                                        <button onclick="document.getElementById('addAlertForm').classList.toggle('hidden')" class="text-sm text-primary-400 hover:text-primary-300">
                                            + Add
                                        </button>
                                    </div>

                                    <!-- Add Alert Form -->
                                    <div id="addAlertForm" class="mb-4 hidden rounded-xl border border-white/10 bg-slate-800/50 p-4">
                                        <form method="POST" action="{{ route('web.alerts.store') }}" class="space-y-3">
                                            @csrf
                                            <input type="text" name="name" required placeholder="Channel name" class="w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-primary-500 focus:outline-none">
                                            <select name="type" required class="w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm text-white focus:border-primary-500 focus:outline-none">
                                                <option value="email">Email</option>
                                                <option value="slack">Slack</option>
                                                <option value="webhook">Webhook</option>
                                            </select>
                                            <input type="text" name="config[value]" required placeholder="email@example.com or webhook URL" class="w-full rounded-lg border border-white/10 bg-slate-900 px-3 py-2 text-sm text-white placeholder-slate-500 focus:border-primary-500 focus:outline-none">
                                            <button type="submit" class="w-full rounded-lg bg-primary-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-600">
                                                Create Alert Channel
                                            </button>
                                        </form>
                                    </div>

                                    <div class="space-y-2">
                                        @forelse($alertChannels as $channel)
                                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-slate-800/30 p-3">
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-medium text-white truncate">{{ $channel->name }}</p>
                                                    <p class="text-xs text-slate-400">{{ ucfirst($channel->type) }}</p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <form method="POST" action="{{ route('web.alerts.toggle', $channel) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="rounded-lg px-3 py-1.5 text-xs font-medium {{ $channel->is_enabled ? 'bg-secondary-500/10 text-secondary-300' : 'bg-slate-700 text-slate-400' }}">
                                                            {{ $channel->is_enabled ? 'ON' : 'OFF' }}
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('web.alerts.destroy', $channel) }}" onsubmit="return confirm('Delete this alert?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="rounded-lg border border-white/10 p-1.5 text-slate-400 transition hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-400">
                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="rounded-xl border border-dashed border-white/20 bg-slate-800/30 p-4 text-center text-sm text-slate-400">
                                                No alerts configured
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No Team State -->
                        <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-12 text-center">
                            <div class="mx-auto grid h-20 w-20 place-items-center rounded-2xl bg-primary-500/10">
                                <svg class="h-10 w-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h2 class="mt-6 text-2xl font-bold text-white">No Workspace Yet</h2>
                            <p class="mt-2 text-slate-400">Create a workspace through the API to get started with monitoring.</p>
                            <a href="#" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-primary-500 px-6 py-3 font-medium text-white transition hover:bg-primary-600">
                                View Documentation
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>

    <script>
        function scrollToSection(sectionId) {
            const el = document.getElementById(sectionId);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                el.classList.add('ring-2', 'ring-primary-500');
                setTimeout(() => el.classList.remove('ring-2', 'ring-primary-500'), 1500);
            }
        }
    </script>
</body>
</html>
