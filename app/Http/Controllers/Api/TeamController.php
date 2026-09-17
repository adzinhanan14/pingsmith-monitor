<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index(Request $r)
    {
        return $r->user()->teams;
    }

    public function store(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:100']);
        $t = Team::create(['name' => $d['name'], 'slug' => Str::slug($d['name']).'-'.Str::lower(Str::random(5)), 'owner_id' => $r->user()->id]);
        $t->members()->attach($r->user(), ['role' => 'owner']);

        return $t;
    }

    public function invite(Request $r, Team $team)
    {
        abort_unless($team->canManage($r->user()), 403);
        $d = $r->validate(['email' => 'required|email', 'role' => 'required|in:admin,viewer']);
        $i = TeamInvitation::create(['team_id' => $team->id, 'invited_by' => $r->user()->id, 'email' => $d['email'], 'role' => $d['role'], 'token' => Str::random(64), 'expires_at' => now()->addDays(7)]);

        return response()->json(['invitation' => $i, 'accept_url' => url('/invitations/'.$i->token)], 201);
    }

    public function accept(Request $r, string $token)
    {
        $i = TeamInvitation::where('token', $token)->whereNull('accepted_at')->where('expires_at', '>', now())->firstOrFail();
        abort_unless($i->email === $r->user()->email, 403);
        $i->team->members()->syncWithoutDetaching([$r->user()->id => ['role' => $i->role]]);
        $i->update(['accepted_at' => now()]);

        return ['team' => $i->team];
    }
}
