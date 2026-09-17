<?php

namespace Tests\Feature;

use App\Models\Incident;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UptimeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_can_fetch_summary_and_recent_incidents(): void
    {
        $user = User::factory()->create();
        $team = Team::create(['name' => 'Ops', 'slug' => 'ops', 'owner_id' => $user->id]);
        $team->members()->attach($user, ['role' => 'owner']);

        $monitor = $team->monitors()->create([
            'name' => 'API',
            'type' => 'http',
            'url' => 'https://example.test',
            'interval_seconds' => 60,
            'status' => 'UP',
            'last_latency_ms' => 120,
        ]);

        Incident::create([
            'monitor_id' => $monitor->id,
            'status' => 'OPEN',
            'started_at' => now()->subMinutes(5),
            'failure_reason' => 'Timeout',
        ]);

        $this->actingAs($user, 'sanctum')->json('GET', "/api/teams/{$team->id}/uptime")
            ->assertStatus(200)
            ->assertJsonPath('uptime', 100)
            ->assertJsonPath('average_latency_ms', 120);

        $this->actingAs($user, 'sanctum')->json('GET', "/api/teams/{$team->id}/incidents")
            ->assertStatus(200)
            ->assertJsonFragment(['failure_reason' => 'Timeout']);
    }
}
