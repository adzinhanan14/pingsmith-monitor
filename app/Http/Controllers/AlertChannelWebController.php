<?php

namespace App\Http\Controllers;

use App\Models\AlertChannel;
use Illuminate\Http\Request;

class AlertChannelWebController extends Controller
{
    public function store(Request $request)
    {
        $team = $request->user()->teams()->firstOrFail();
        abort_unless($team->canManage($request->user()), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:email,telegram,discord'],
            'email' => ['nullable', 'required_if:type,email', 'email'],
            'bot_token' => ['nullable', 'required_if:type,telegram', 'string'],
            'chat_id' => ['nullable', 'required_if:type,telegram', 'string'],
            'webhook_url' => ['nullable', 'required_if:type,discord', 'url'],
            'is_enabled' => ['sometimes', 'boolean'],
        ]);

        $config = match ($data['type']) {
            'email' => ['email' => $data['email']],
            'telegram' => ['bot_token' => $data['bot_token'], 'chat_id' => $data['chat_id']],
            'discord' => ['webhook_url' => $data['webhook_url']],
            default => [],
        };

        $team->alertChannels()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'config_data' => $config,
            'is_enabled' => $data['is_enabled'] ?? true,
        ]);

        return back()->with('success', 'Alert channel berhasil ditambahkan.');
    }

    public function toggle(Request $request, AlertChannel $channel)
    {
        $team = $request->user()->teams()->whereKey($channel->team_id)->firstOrFail();
        abort_unless($team->canManage($request->user()), 403);

        $channel->update(['is_enabled' => ! $channel->is_enabled]);

        return back()->with('success', 'Status alert channel diperbarui.');
    }

    public function destroy(Request $request, AlertChannel $channel)
    {
        $team = $request->user()->teams()->whereKey($channel->team_id)->firstOrFail();
        abort_unless($team->canManage($request->user()), 403);

        $channel->delete();

        return back()->with('success', 'Alert channel dihapus.');
    }
}
