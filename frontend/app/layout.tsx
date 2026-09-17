import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "Pingsmith Monitor | Uptime & Incident Tracking",
  description: "Real-time infrastructure uptime monitoring for APIs, websites, and critical services.",
  keywords: ["uptime monitoring", "incident tracking", "status checks", "Pingsmith"],
  metadataBase: new URL("https://pingsmith.dev"),
  openGraph: {
    title: "Pingsmith Monitor",
    description: "Real-time infrastructure uptime monitoring for APIs, websites, and critical services.",
    type: "website",
  },
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <html lang="id"><body>{children}</body></html>;
}
