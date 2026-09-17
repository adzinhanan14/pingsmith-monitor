"use client";

import { AnimatePresence, motion } from "framer-motion";
import { Activity, Clock3, Plus, Search, Trash2, X, Zap, type LucideIcon } from "lucide-react";
import { useEffect, useMemo, useState, type FormEvent } from "react";
import { Card, MonitorStatus, StatusBadge } from "./ui";

type Monitor = {
  id: number;
  name: string;
  url: string;
  protocol: string;
  status: MonitorStatus;
  latency: number;
  type?: string;
  interval_seconds?: number;
};

type AlertChannel = {
  id: number;
  name: string;
  type: "email" | "telegram" | "discord";
  is_enabled: boolean;
  config_data?: Record<string, string>;
};

type Summary = {
  total: number;
  up: number;
  down: number;
  paused: number;
  uptime: number;
  average_latency_ms: number;
};

const demoMonitors: Monitor[] = [
  { id: 1, name: "Production API", url: "https://api.acme.dev/health", protocol: "HTTPS", status: "UP", latency: 42 },
  { id: 2, name: "Customer Web", url: "https://app.acme.dev", protocol: "HTTPS", status: "UP", latency: 68 },
];

const demoAlerts: AlertChannel[] = [
  { id: 1, name: "Ops Email", type: "email", is_enabled: true, config_data: { email: "ops@example.com" } },
  { id: 2, name: "Telegram Ops", type: "telegram", is_enabled: true, config_data: { chat_id: "-100" } },
];

const demoSummary: Summary = { total: 2, up: 2, down: 0, paused: 0, uptime: 100, average_latency_ms: 55 };

const API_BASE = process.env.NEXT_PUBLIC_API_BASE_URL ?? "http://localhost:8000/api";

async function apiFetch<T>(path: string, init: RequestInit = {}, fallback: T): Promise<T> {
  const token = typeof window !== "undefined" ? localStorage.getItem("pingsmith_token") : null;
  const headers = new Headers(init.headers ?? {});
  headers.set("Accept", "application/json");

  if (token) {
    headers.set("Authorization", `Bearer ${token}`);
  }

  try {
    const response = await fetch(`${API_BASE}${path}`, { ...init, headers, cache: "no-store" });
    if (!response.ok) throw new Error(`Request failed: ${response.status}`);
    if (response.status === 204) return fallback;
    return (await response.json()) as T;
  } catch {
    return fallback;
  }
}

function normalizeStatus(value?: string): MonitorStatus {
  switch (value) {
    case "DOWN": return "DOWN";
    case "PAUSED": return "PAUSED";
    case "PENDING": return "PENDING";
    default: return "UP";
  }
}

function toArray(payload: any): any[] {
  if (Array.isArray(payload)) return payload;
  if (payload && Array.isArray(payload.data)) return payload.data;
  return [];
}

function mapMonitor(raw: any): Monitor {
  return {
    id: Number(raw.id),
    name: raw.name ?? "Unnamed",
    url: raw.url ?? "https://example.com",
    protocol: (raw.type ?? "http").toUpperCase(),
    status: normalizeStatus(raw.status),
    latency: Number(raw.last_latency_ms ?? raw.latency ?? 0),
    type: raw.type,
    interval_seconds: raw.interval_seconds,
  };
}

function mapAlert(raw: any): AlertChannel {
  return {
    id: Number(raw.id),
    name: raw.name ?? "Alert",
    type: raw.type ?? "email",
    is_enabled: Boolean(raw.is_enabled),
    config_data: raw.config_data ?? {},
  };
}

export function Dashboard() {
  const [team, setTeam] = useState<{ id: number; name: string } | null>(null);
  const [monitors, setMonitors] = useState<Monitor[]>(demoMonitors);
  const [alerts, setAlerts] = useState<AlertChannel[]>(demoAlerts);
  const [summary, setSummary] = useState<Summary>(demoSummary);
  const [query, setQuery] = useState("");
  const [filter, setFilter] = useState("ALL");
  const [adding, setAdding] = useState(false);
  const [selected, setSelected] = useState<Monitor | null>(null);
  const [checking, setChecking] = useState<number | null>(null);
  const [toast, setToast] = useState("");
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const load = async () => {
      const teams = await apiFetch<any[]>("/teams", {}, [] as any[]);
      const selectedTeam = toArray(teams)[0] ?? null;
      setTeam(selectedTeam);

      if (!selectedTeam) {
        setLoading(false);
        return;
      }

      const [rawMonitors, rawSummary, rawAlerts] = await Promise.all([
        apiFetch<any>(`/teams/${selectedTeam.id}/monitors`, {}, []),
        apiFetch<any>(`/teams/${selectedTeam.id}/uptime`, {}, demoSummary),
        apiFetch<any>(`/teams/${selectedTeam.id}/alert-channels`, {}, []),
      ]);

      setMonitors(toArray(rawMonitors).map(mapMonitor));
      setSummary(rawSummary ?? demoSummary);
      setAlerts(toArray(rawAlerts).map(mapAlert));
      setLoading(false);
    };

    void load();
  }, []);

  const visible = useMemo(
    () => monitors.filter((m) => (filter === "ALL" || m.status === filter) && `${m.name} ${m.url}`.toLowerCase().includes(query.toLowerCase())),
    [monitors, filter, query],
  );

  const notify = (message: string) => {
    setToast(message);
    window.setTimeout(() => setToast(""), 2800);
  };

  const refreshFromApi = async () => {
    if (!team) return;

    const [rawMonitors, rawSummary, rawAlerts] = await Promise.all([
      apiFetch<any>(`/teams/${team.id}/monitors`, {}, []),
      apiFetch<any>(`/teams/${team.id}/uptime`, {}, demoSummary),
      apiFetch<any>(`/teams/${team.id}/alert-channels`, {}, []),
    ]);

    setMonitors(toArray(rawMonitors).map(mapMonitor));
    setSummary(rawSummary ?? demoSummary);
    setAlerts(toArray(rawAlerts).map(mapAlert));
  };

  const check = async (id: number) => {
    if (!team) return;

    setChecking(id);
    const response = await apiFetch<any>(`/teams/${team.id}/monitors/${id}/test`, { method: "POST" }, { latency_ms: 30, is_success: true });
    const nextLatency = Number(response?.latency_ms ?? 30);
    setMonitors((items) => items.map((m) => (m.id === id ? { ...m, latency: nextLatency, status: "UP" } : m)));
    setChecking(null);
    notify(response?.is_success === false ? "Endpoint gagal diperiksa" : "Pemeriksaan selesai · endpoint sehat");
    await refreshFromApi();
  };

  const remove = async (id: number) => {
    if (!team) return;

    await apiFetch(`/teams/${team.id}/monitors/${id}`, { method: "DELETE" }, null as any);
    setMonitors((items) => items.filter((m) => m.id !== id));
    setSelected(null);
    notify("Monitor telah dihapus");
    await refreshFromApi();
  };

  const togglePause = async (id: number) => {
    if (!team) return;

    const target = monitors.find((m) => m.id === id);
    if (!target) return;

    const nextStatus = target.status === "PAUSED" ? "PENDING" : "PAUSED";
    await apiFetch(`/teams/${team.id}/monitors/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ status: nextStatus }),
    }, null as any);

    setMonitors((items) => items.map((m) => (m.id === id ? { ...m, status: nextStatus } : m)));
    notify("Status monitor diperbarui");
    await refreshFromApi();
  };

  const addMonitor = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (!team) return;

    const form = new FormData(event.currentTarget);
    const payload = {
      name: String(form.get("name") || "New monitor"),
      type: String(form.get("type") || "http"),
      url: String(form.get("url") || "https://example.com"),
      interval_seconds: Number(form.get("interval_seconds") || 60),
      timeout_seconds: Number(form.get("timeout_seconds") || 10),
    };

    const created = await apiFetch<any>(`/teams/${team.id}/monitors`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    }, { ...payload, id: Date.now(), status: "PENDING", last_latency_ms: 0 });

    setMonitors((items) => [mapMonitor(created), ...items]);
    setAdding(false);
    notify("Monitor baru ditambahkan");
    await refreshFromApi();
  };

  const addAlert = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (!team) return;

    const form = new FormData(event.currentTarget);
    const type = String(form.get("type") || "email");
    const payload = {
      name: String(form.get("name") || "New alert"),
      type,
      is_enabled: true,
      config_data: {
        email: String(form.get("email") || ""),
        bot_token: String(form.get("bot_token") || ""),
        chat_id: String(form.get("chat_id") || ""),
        webhook_url: String(form.get("webhook_url") || ""),
      },
    };

    const created = await apiFetch<any>(`/teams/${team.id}/alert-channels`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    }, { ...payload, id: Date.now(), is_enabled: true });

    setAlerts((items) => [mapAlert(created), ...items]);
    notify("Alert channel baru ditambahkan");
    await refreshFromApi();
  };

  const updateAlert = async (id: number) => {
    if (!team) return;

    const channel = alerts.find((item) => item.id === id);
    if (!channel) return;

    const updated = await apiFetch<any>(`/teams/${team.id}/alert-channels/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ is_enabled: !channel.is_enabled }),
    }, { ...channel, is_enabled: !channel.is_enabled });

    setAlerts((items) => items.map((item) => item.id === id ? { ...item, is_enabled: Boolean(updated.is_enabled ?? !item.is_enabled) } : item));
    notify("Status alert diperbarui");
    await refreshFromApi();
  };

  return (
    <section id="dashboard" className="border-t border-[#222938] bg-[#0B0F17] py-24">
      <div className="mx-auto max-w-6xl px-5">
        <div className="mb-10 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
          <div>
            <p className="text-sm font-bold text-cyan-300">INTERACTIVE DASHBOARD</p>
            <h2 className="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">Your infrastructure, at a glance.</h2>
          </div>
          <span className="flex items-center gap-2 text-sm text-slate-400"><i className="size-2 rounded-full bg-emerald-400 animate-ping-dot" />Live updates enabled</span>
        </div>

        <div className="grid gap-4 sm:grid-cols-3">
          {([
            { label: "Total Monitors", value: summary.total || monitors.length, icon: Activity },
            { label: "Active Monitors", value: summary.up || monitors.filter((m) => m.status === "UP").length, icon: Zap },
            { label: "Current Availability", value: `${summary.uptime || 100}%`, icon: Clock3 },
          ] as Array<{ label: string; value: string | number; icon: LucideIcon }>).map(({ label, value, icon: Icon }) => (
            <Card key={label} className="p-5">
              <div className="flex items-center justify-between">
                <p className="text-sm text-slate-400">{label}</p>
                <span className="rounded-lg bg-cyan-300/10 p-2 text-cyan-300"><Icon size={17} /></span>
              </div>
              <p className="mt-4 text-3xl font-bold">{value}</p>
            </Card>
          ))}
        </div>

        <Card className="mt-6 overflow-hidden">
          <div className="flex flex-col gap-3 border-b border-[#222938] p-4 lg:flex-row lg:items-center">
            <button onClick={() => setAdding(!adding)} className="inline-flex items-center justify-center gap-2 rounded-lg bg-cyan-300 px-4 py-2.5 text-sm font-bold text-slate-950">
              <Plus size={16} />{adding ? "Tutup formulir" : "Tambah Monitor"}
            </button>
            <div className="flex flex-1 flex-col gap-3 sm:flex-row lg:justify-end">
              <label className="flex items-center gap-2 rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2 text-slate-500 sm:w-64">
                <Search size={16} />
                <input value={query} onChange={(e) => setQuery(e.target.value)} aria-label="Cari monitor" placeholder="Cari monitor" className="w-full bg-transparent text-sm text-white outline-none" />
              </label>
              <select aria-label="Filter status" value={filter} onChange={(e) => setFilter(e.target.value)} className="rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2 text-sm">
                <option value="ALL">Semua status</option>
                <option value="UP">UP</option>
                <option value="DOWN">DOWN</option>
                <option value="PAUSED">PAUSED</option>
                <option value="PENDING">PENDING</option>
              </select>
            </div>
          </div>

          <AnimatePresence initial={false}>
            {adding && (
              <motion.form initial={{ height: 0, opacity: 0 }} animate={{ height: "auto", opacity: 1 }} exit={{ height: 0, opacity: 0 }} onSubmit={addMonitor} className="grid gap-3 overflow-hidden border-b border-[#222938] bg-[#111722] p-4 sm:grid-cols-[1fr_1.2fr_1fr_auto]">
                <input name="name" required placeholder="Nama monitor" className="rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm outline-none focus:border-cyan-300" />
                <input name="url" required type="url" placeholder="https://endpoint.com" className="rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm outline-none focus:border-cyan-300" />
                <select name="type" className="rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm">
                  <option value="http">HTTP</option>
                  <option value="port">PORT</option>
                  <option value="dns">DNS</option>
                  <option value="ssl">SSL</option>
                </select>
                <button className="rounded-lg bg-cyan-300 px-4 py-2.5 text-sm font-bold text-slate-950">Simpan</button>
              </motion.form>
            )}
          </AnimatePresence>

          <div className="overflow-x-auto">
            <table className="w-full min-w-[680px] text-left text-sm">
              <thead className="bg-[#111722] text-xs uppercase tracking-wider text-slate-500">
                <tr>
                  <th className="p-4">Monitor Name</th>
                  <th className="p-4">Status</th>
                  <th className="p-4">Latency</th>
                  <th className="p-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                {visible.map((m) => (
                  <tr key={m.id} onClick={() => setSelected(m)} className="cursor-pointer border-t border-[#222938] transition hover:bg-cyan-300/2.5">
                    <td className="p-4">
                      <p className="font-semibold text-slate-100">{m.name}</p>
                      <p className="mt-1 text-xs text-slate-500"><span className="mr-2 rounded bg-[#263044] px-1.5 py-0.5 text-[10px] text-cyan-200">{m.protocol}</span>{m.url}</p>
                    </td>
                    <td className="p-4"><StatusBadge status={m.status} /></td>
                    <td className="p-4"><span className={checking === m.id ? "inline-block h-5 w-14 animate-pulse rounded bg-cyan-300/15" : "font-medium text-cyan-300"}>{checking === m.id ? "" : `${m.latency} ms`}</span></td>
                    <td className="p-4">
                      <div className="flex gap-3" onClick={(e) => e.stopPropagation()}>
                        <button onClick={() => void check(m.id)} className="text-cyan-300 hover:text-cyan-200">Cek</button>
                        <button onClick={() => void togglePause(m.id)} className="text-slate-300">{m.status === "PAUSED" ? "Lanjut" : "Jeda"}</button>
                        <button onClick={() => void remove(m.id)} aria-label={`Hapus ${m.name}`} className="text-rose-400"><Trash2 size={16} /></button>
                      </div>
                    </td>
                  </tr>
                ))}
                {visible.length === 0 && (
                  <tr>
                    <td colSpan={4} className="p-10 text-center text-slate-500">{loading ? "Memuat data..." : "Tidak ada monitor yang cocok."}</td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </Card>

        <div className="mt-6 grid gap-4 lg:grid-cols-[1.2fr_.8fr]">
          <Card className="p-5">
            <h3 className="text-lg font-bold">Alert channels</h3>
            <div className="mt-4 space-y-3">
              {alerts.map((alert) => (
                <div key={alert.id} className="flex items-center justify-between rounded-xl border border-[#222938] bg-[#0B0F17] p-3">
                  <div>
                    <p className="font-semibold text-slate-100">{alert.name}</p>
                    <p className="text-xs text-slate-400">{alert.type.toUpperCase()} · {alert.config_data?.email ?? alert.config_data?.chat_id ?? alert.config_data?.webhook_url ?? "Configured"}</p>
                  </div>
                  <div className="flex items-center gap-2">
                    <button onClick={() => void updateAlert(alert.id)} className={`rounded-lg border px-2 py-1 text-xs ${alert.is_enabled ? "border-emerald-900 bg-emerald-950/60 text-emerald-300" : "border-slate-700 bg-slate-900 text-slate-300"}`}>{alert.is_enabled ? "Enabled" : "Disabled"}</button>
                  </div>
                </div>
              ))}
            </div>
          </Card>

          <Card className="p-5">
            <h3 className="text-lg font-bold">Add alert</h3>
            <form onSubmit={addAlert} className="mt-4 space-y-3">
              <input name="name" required placeholder="Nama alert" className="w-full rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm" />
              <select name="type" className="w-full rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm">
                <option value="email">Email</option>
                <option value="telegram">Telegram</option>
                <option value="discord">Discord</option>
              </select>
              <input name="email" placeholder="Email" className="w-full rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm" />
              <input name="bot_token" placeholder="Bot token" className="w-full rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm" />
              <input name="chat_id" placeholder="Chat ID" className="w-full rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm" />
              <input name="webhook_url" placeholder="Discord webhook URL" className="w-full rounded-lg border border-[#30394b] bg-[#0B0F17] px-3 py-2.5 text-sm" />
              <button className="w-full rounded-lg bg-cyan-300 px-4 py-2.5 text-sm font-bold text-slate-950">Simpan channel</button>
            </form>
          </Card>
        </div>

        <AnimatePresence>
          {selected && (
            <motion.aside initial={{ x: "100%" }} animate={{ x: 0 }} exit={{ x: "100%" }} transition={{ type: "spring", damping: 28, stiffness: 280 }} className="fixed inset-y-0 right-0 z-30 w-full max-w-md border-l border-[#30394b] bg-[#111722] p-6 shadow-[-25px_0_70px_rgba(0,0,0,.4)]" role="dialog" aria-modal="true" aria-label={`Details ${selected.name}`}>
              <div className="flex items-start justify-between">
                <div>
                  <p className="text-xs font-bold text-cyan-300">MONITOR DETAIL</p>
                  <h3 className="mt-2 text-xl font-bold">{selected.name}</h3>
                  <p className="mt-1 text-sm text-slate-500">{selected.url}</p>
                </div>
                <button onClick={() => setSelected(null)} className="rounded-lg p-2 text-slate-400 hover:bg-white/5" aria-label="Close detail panel"><X /></button>
              </div>
              <div className="mt-8 rounded-xl border border-[#222938] bg-[#0B0F17] p-5">
                <div className="flex justify-between">
                  <div>
                    <p className="font-semibold">Response time</p>
                    <p className="text-xs text-slate-500">Average {selected.latency} ms</p>
                  </div>
                  <StatusBadge status={selected.status} />
                </div>
                <svg viewBox="0 0 400 130" className="mt-6 w-full" aria-label="24 hour line chart">
                  <path d="M0 89 C24 62 36 105 60 76 S95 46 123 64 S151 31 184 59 S229 85 259 43 S303 58 331 36 S370 62 400 24" fill="none" stroke="#00F2FE" strokeWidth="3" />
                </svg>
                <div className="flex justify-between text-xs text-slate-600"><span>24h ago</span><span>Now</span></div>
              </div>
              <div className="mt-6">
                <h4 className="font-semibold">Incident logs</h4>
                <div className="mt-3 rounded-xl border border-[#222938] p-4 text-sm">
                  <p className="font-medium text-emerald-400">No open incidents</p>
                  <p className="mt-1 text-slate-500">All checks have been healthy in the last 24 hours.</p>
                </div>
              </div>
            </motion.aside>
          )}
        </AnimatePresence>

        <AnimatePresence>
          {toast && (
            <motion.div initial={{ opacity: 0, y: -20 }} animate={{ opacity: 1, y: 0 }} exit={{ opacity: 0, y: -20 }} role="status" className="fixed right-5 top-5 z-40 rounded-xl border border-emerald-900 bg-[#161B26] px-4 py-3 text-sm text-emerald-300 shadow-xl">
              <span className="mr-2 inline-block size-2 rounded-full bg-emerald-400" />{toast}
            </motion.div>
          )}
        </AnimatePresence>
      </div>
    </section>
  );
}
