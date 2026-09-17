"use client";

import { Activity, Clock3, Pause, TriangleAlert } from "lucide-react";
import { motion } from "framer-motion";

export type MonitorStatus = "UP" | "DOWN" | "PAUSED" | "PENDING";

export function Logo() {
  return <a href="#top" className="flex items-center gap-2 font-semibold tracking-tight" aria-label="Pingsmith Monitor home"><span className="grid size-8 place-items-center rounded-lg bg-cyan-400/10 text-cyan-300 shadow-[0_0_24px_rgba(0,242,254,.4)]"><Activity size={18} /></span><span>Pingsmith<span className="text-cyan-300"> Monitor</span></span></a>;
}

export function StatusBadge({ status }: { status: MonitorStatus }) {
  const config = {
    UP: ["bg-emerald-950 text-emerald-400 border-emerald-900/70", "bg-emerald-400", Activity],
    DOWN: ["bg-rose-950 text-rose-400 border-rose-900/70", "bg-rose-400", TriangleAlert],
    PAUSED: ["bg-amber-950/60 text-amber-400 border-amber-900/70", "bg-amber-400", Pause],
    PENDING: ["bg-slate-800 text-slate-300 border-slate-700", "bg-slate-400", Clock3],
  } as const;
  const [classes, dot, Icon] = config[status];
  return <span className={`inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ${classes}`}><span className={`size-1.5 rounded-full ${dot} ${status === "UP" ? "animate-ping-dot" : ""}`} /><Icon size={12} />{status}</span>;
}

export function Card({ children, className = "" }: { children: React.ReactNode; className?: string }) {
  return <motion.div initial={{ opacity: 0, y: 14 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: 0.2 }} className={`rounded-2xl border border-[#222938] bg-[#161B26] ${className}`}>{children}</motion.div>;
}
