<?php

namespace App\Services;

use App\Models\Incident;
use App\Models\Monitor;
use App\Models\PingLog;
use Illuminate\Support\Facades\Http;

class MonitorCheckService
{
    public function check(Monitor $monitor): PingLog
    {
        $started = microtime(true);
        $code = null;
        $error = null;
        $success = false;

        try {
            [$success, $code, $error] = match ($monitor->type ?? 'http') {
                'port' => $this->checkPort($monitor),
                'dns' => $this->checkDns($monitor),
                'ssl' => $this->checkSsl($monitor),
                default => $this->checkHttp($monitor),
            };
        } catch (\Throwable $e) {
            $error = mb_substr($e->getMessage(), 0, 1000);
        }

        $log = PingLog::create(['monitor_id' => $monitor->id, 'is_success' => $success, 'status_code' => $code, 'latency_ms' => (int) ((microtime(true) - $started) * 1000), 'error_message' => $error, 'checked_at' => now()]);
        $monitor->last_checked_at = now();
        $monitor->last_latency_ms = $log->latency_ms;
        $monitor->next_check_at = now()->addSeconds($monitor->interval_seconds);
        $event = null;
        if ($success) {
            $was = $monitor->status === 'DOWN';
            $monitor->consecutive_failures = 0;
            $monitor->status = 'UP';
            if ($was) {
                Incident::where('monitor_id', $monitor->id)->where('status', 'OPEN')->update(['status' => 'RESOLVED', 'resolved_at' => now()]);
                $event = 'RECOVERED';
            }
        } else {
            $monitor->consecutive_failures++;
            if ($monitor->consecutive_failures >= 2) {
                if ($monitor->status !== 'DOWN') {
                    Incident::create(['monitor_id' => $monitor->id, 'status' => 'OPEN', 'started_at' => now(), 'failure_reason' => $error ?: "HTTP {$code}"]);
                    $event = 'DOWN';
                }$monitor->status = 'DOWN';
            }
        }
        $monitor->save();
        if ($event) {
            app(AlertNotifier::class)->send($monitor, $event);
        }

        return $log;
    }

    /** @return array{bool, ?int, ?string} */
    private function checkHttp(Monitor $monitor): array
    {
        if (! filter_var($monitor->url, FILTER_VALIDATE_URL) || ! in_array(parse_url($monitor->url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            return [false, null, 'URL HTTP/HTTPS tidak valid.'];
        }

        $response = Http::withHeaders($monitor->headers ?? [])
            ->timeout($monitor->timeout_seconds ?? 10)
            ->send($monitor->method ?? 'GET', $monitor->url);
        $code = $response->status();

        return [in_array($code, $monitor->expected_status_codes ?: range(200, 399)), $code, null];
    }

    /** @return array{bool, ?int, ?string} */
    private function checkPort(Monitor $monitor): array
    {
        $target = str_contains($monitor->url, '://') ? parse_url($monitor->url, PHP_URL_HOST).':'.(parse_url($monitor->url, PHP_URL_PORT) ?: 80) : $monitor->url;
        [$host, $port] = array_pad(explode(':', $target, 2), 2, null);
        if (! $host || ! $port || ! ctype_digit((string) $port) || (int) $port > 65535) {
            return [false, null, 'Target port harus berbentuk host:port.'];
        }
        $socket = @fsockopen($host, (int) $port, $number, $message, $monitor->timeout_seconds ?? 10);
        if (! $socket) {
            return [false, null, $message ?: "Tidak dapat terhubung ke {$target}."];
        }
        fclose($socket);

        return [true, null, null];
    }

    /** @return array{bool, ?int, ?string} */
    private function checkDns(Monitor $monitor): array
    {
        $host = parse_url(str_contains($monitor->url, '://') ? $monitor->url : "//{$monitor->url}", PHP_URL_HOST);
        if (! $host || ! checkdnsrr($host, 'A') && ! checkdnsrr($host, 'AAAA')) {
            return [false, null, "DNS tidak dapat menemukan {$host}."];
        }

        return [true, null, null];
    }

    /** @return array{bool, ?int, ?string} */
    private function checkSsl(Monitor $monitor): array
    {
        $host = parse_url(str_contains($monitor->url, '://') ? $monitor->url : "https://{$monitor->url}", PHP_URL_HOST);
        if (! $host) {
            return [false, null, 'Host SSL tidak valid.'];
        }
        $context = stream_context_create(['ssl' => ['capture_peer_cert' => true, 'verify_peer' => true, 'verify_peer_name' => true]]);
        $socket = @stream_socket_client("ssl://{$host}:443", $number, $message, $monitor->timeout_seconds ?? 10, STREAM_CLIENT_CONNECT, $context);
        if (! $socket) {
            return [false, null, $message ?: "Sertifikat SSL {$host} tidak dapat diverifikasi."];
        }
        $params = stream_context_get_params($socket);
        fclose($socket);
        $certificate = openssl_x509_parse($params['options']['ssl']['peer_certificate'] ?? null);
        if (! $certificate || ($certificate['validTo_time_t'] ?? 0) <= time()) {
            return [false, null, "Sertifikat SSL {$host} sudah kedaluwarsa atau tidak valid."];
        }

        return [true, null, null];
    }
}
