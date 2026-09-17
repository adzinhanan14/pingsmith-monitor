<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlertChannelResource;
use App\Models\AlertChannel;
use App\Models\Team;
use Illuminate\Http\Request;

class AlertChannelController extends Controller
{
    public function index(Request $request, Team $team)
    {
        abort_unless($team->canManage($request->user()), 403);

        return AlertChannelResource::collection($team->alertChannels()->latest()->get());
    }

    public function store(Request $request, Team $team)
    {
        abort_unless($team->canManage($request->user()), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:email,telegram,discord'],
            'config_data' => ['required', 'array'],
            'is_enabled' => ['sometimes', 'boolean'],
        ]);

        $channel = $team->alertChannels()->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'config_data' => $data['config_data'],
            'is_enabled' => $data['is_enabled'] ?? true,
        ]);

        return (new AlertChannelResource($channel))->response()->setStatusCode(201);
    }

    public function update(Request $request, Team $team, AlertChannel $channel)
    {
        abort_unless($team->canManage($request->user()), 403);
        abort_unless($channel->team_id === $team->id, 404);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'type' => ['sometimes', 'in:email,telegram,discord'],
            'config_data' => ['sometimes', 'array'],
            'is_enabled' => ['sometimes', 'boolean'],
        ]);

        $channel->fill($data);
        $channel->save();

        return new AlertChannelResource($channel);
    }

    public function destroy(Request $request, Team $team, AlertChannel $channel)
    {
        abort_unless($team->canManage($request->user()), 403);
        abort_unless($channel->team_id === $team->id, 404);

        $channel->delete();

        return response()->json(['message' => 'Alert channel deleted']);
    }
}
