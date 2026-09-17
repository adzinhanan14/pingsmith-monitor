<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Masuk · Pingsmith</title>
</head>
<body class="min-h-screen bg-[#070b12] text-slate-100 antialiased">
<div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.18),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.16),_transparent_35%)]"></div>
<main class="relative grid min-h-screen place-items-center p-6">
    <div class="grid w-full max-w-6xl overflow-hidden rounded-[32px] border border-white/10 bg-[#0b1220]/80 shadow-[0_35px_120px_rgba(15,118,110,0.18)] backdrop-blur-xl lg:grid-cols-[1.1fr_.9fr]">
        <section class="hidden bg-[linear-gradient(135deg,rgba(8,15,25,0.95),rgba(15,23,42,0.88),rgba(34,211,238,0.08))] p-10 lg:flex lg:flex-col lg:justify-between">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1.5 text-xs font-semibold tracking-[0.2em] text-cyan-200">PINGSMITH MONITOR</div>
                <h1 class="mt-8 max-w-md text-4xl font-black leading-tight text-white">Monitor uptime, latency, dan incident tanpa blind spot.</h1>
                <p class="mt-4 max-w-md text-slate-300">Platform observability modern untuk API, website, server, dan layanan kritikal Anda.</p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                    <span class="grid size-10 place-items-center rounded-xl bg-emerald-500/10 text-emerald-300">●</span>
                    <div>
                        <p class="font-semibold text-white">99.99% uptime visibility</p>
                        <p class="text-sm text-slate-400">Pantau dari global probes dalam real-time</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                    <span class="grid size-10 place-items-center rounded-xl bg-cyan-500/10 text-cyan-300">◎</span>
                    <div>
                        <p class="font-semibold text-white">Multi-channel alerting</p>
                        <p class="text-sm text-slate-400">Email, Telegram, Discord, webhook</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="p-6 sm:p-8 lg:p-10">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-[0.22em] text-cyan-300">WELCOME BACK</p>
                    <h2 class="mt-2 text-3xl font-bold text-white">Masuk ke dashboard</h2>
                </div>
                <div class="rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1.5 text-xs text-cyan-100">Secure</div>
            </div>

            <div class="mb-6 rounded-2xl border border-white/10 bg-gradient-to-r from-cyan-500/10 via-sky-500/5 to-transparent p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">System status</p>
                <div class="mt-3 flex items-center gap-3 text-sm text-emerald-300">
                    <span class="inline-block size-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    All checks are operational
                </div>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <label class="block text-sm text-slate-300">
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-2 w-full rounded-2xl border border-white/10 bg-[#0b1220] px-3 py-3 text-white outline-none transition focus:border-cyan-400/40">
                    @error('email')
                        <span class="mt-2 block text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </label>

                <label class="block text-sm text-slate-300">
                    Kata sandi
                    <input type="password" name="password" required class="mt-2 w-full rounded-2xl border border-white/10 bg-[#0b1220] px-3 py-3 text-white outline-none transition focus:border-cyan-400/40">
                </label>

                <div class="flex items-center justify-between gap-3">
                    <label class="flex items-center gap-2 text-sm text-slate-400">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-white/10 bg-[#0b1220] text-cyan-400">
                        Ingat saya
                    </label>
                    <a href="#" class="text-sm text-cyan-300 hover:text-cyan-200">Lupa password?</a>
                </div>

                <button class="w-full rounded-2xl bg-gradient-to-r from-cyan-300 via-sky-400 to-blue-500 px-4 py-3.5 font-semibold text-slate-950 shadow-[0_0_30px_rgba(34,211,238,0.35)] transition hover:brightness-110">Masuk</button>
            </form>

            <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-400">
                Demo account:
                <span class="font-medium text-slate-200">demo@pingsmith.test</span>
                <span class="mx-2 text-slate-500">/</span>
                <span class="font-medium text-slate-200">password</span>
            </div>
        </section>
    </div>
</main>
</body>
</html>
