<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_and_pause_a_monitor_from_dashboard(): void
    {
        $user = User::factory()->create();
        $team = Team::create(['name' => 'Operations', 'slug' => 'operations', 'owner_id' => $user->id]);
        $team->members()->attach($user, ['role' => 'owner']);

        $this->actingAs($user)->post(route('web.monitors.store'), [
            'name' => 'Homepage',
            'type' => 'http',
            'url' => 'https://example.test',
            'interval_seconds' => 60,
            'timeout_seconds' => 10,
        ])->assertRedirect(route('dashboard'));

        $monitor = $team->monitors()->firstOrFail();
        $this->assertDatabaseHas('monitors', ['id' => $monitor->id, 'status' => 'PENDING', 'type' => 'http']);
        $this->actingAs($user)->patch(route('web.monitors.pause', $monitor))->assertRedirect();
        $this->assertDatabaseHas('monitors', ['id' => $monitor->id, 'status' => 'PAUSED']);
    }
}
