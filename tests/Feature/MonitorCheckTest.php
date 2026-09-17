<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use App\Services\MonitorCheckService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MonitorCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_monitor_opens_after_two_failures_and_recovers(): void
    {
        $user = User::factory()->create();
        $team = Team::create(['name' => 'Test', 'slug' => 'test', 'owner_id' => $user->id]);
        $team->members()->attach($user, ['role' => 'owner']);
        $monitor = $team->monitors()->create(['name' => 'API', 'url' => 'https://example.test', 'interval_seconds' => 60]);
        $service = app(MonitorCheckService::class);

        $attempt = 0;
        Http::fake(function () use (&$attempt) {
            $attempt++;

            return Http::response($attempt < 3 ? 'error' : 'ok', $attempt < 3 ? 500 : 200);
        });
        $service->check($monitor);
        $this->assertSame('PENDING', $monitor->fresh()->status);
        $service->check($monitor->fresh());
        $this->assertSame('DOWN', $monitor->fresh()->status);
        $this->assertDatabaseHas('incidents', ['monitor_id' => $monitor->id, 'status' => 'OPEN']);

        $service->check($monitor->fresh());
        $this->assertSame('UP', $monitor->fresh()->status);
        $this->assertDatabaseHas('incidents', ['monitor_id' => $monitor->id, 'status' => 'RESOLVED']);
    }
}
