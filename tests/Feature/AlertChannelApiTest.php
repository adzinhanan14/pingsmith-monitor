<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertChannelApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_manage_alert_channels(): void
    {
        $user = User::factory()->create();
        $team = Team::create(['name' => 'Ops', 'slug' => 'ops', 'owner_id' => $user->id]);
        $team->members()->attach($user, ['role' => 'owner']);

        $response = $this->actingAs($user, 'sanctum')->json('POST', "/api/teams/{$team->id}/alert-channels", [
            'name' => 'Ops email',
            'type' => 'email',
            'config_data' => ['email' => 'ops@example.test'],
            'is_enabled' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Ops email')
            ->assertJsonPath('data.type', 'email');

        $this->assertDatabaseHas('alert_channels', ['team_id' => $team->id, 'name' => 'Ops email']);

        $this->actingAs($user, 'sanctum')->json('GET', "/api/teams/{$team->id}/alert-channels")
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Ops email']);

        $channelId = $team->alertChannels()->first()->id;

        $this->actingAs($user, 'sanctum')->json('PATCH', "/api/teams/{$team->id}/alert-channels/{$channelId}", [
            'name' => 'Ops email updated',
            'is_enabled' => false,
        ])->assertStatus(200)
            ->assertJsonPath('data.name', 'Ops email updated');

        $this->actingAs($user, 'sanctum')->json('DELETE', "/api/teams/{$team->id}/alert-channels/{$channelId}")
            ->assertStatus(200);
    }
}
