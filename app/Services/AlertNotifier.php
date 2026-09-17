<?php

namespace App\Services;

use App\Models\Monitor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AlertNotifier
{
    public function send(Monitor $m, string $event): void
    {
        $message = "[Pingsmith] {$m->name} is {$event}: {$m->url}";
        foreach ($m->team->alertChannels()->where('is_enabled', true)->get() as $c) {
            try {
                if ($c->type === 'email') {
                    Mail::raw($message, fn ($mail) => $mail->to($c->config_data['email'])->subject("Monitor {$event}"));
                }if ($c->type === 'telegram') {
                    Http::post("https://api.telegram.org/bot{$c->config_data['bot_token']}/sendMessage", ['chat_id' => $c->config_data['chat_id'], 'text' => $message]);
                }if ($c->type === 'discord') {
                    Http::post($c->config_data['webhook_url'], ['content' => $message]);
                }
            } catch (\Throwable $e) {
                Log::warning('Alert delivery failed', ['channel' => $c->id, 'error' => $e->getMessage()]);
            }
        }
    }
}
