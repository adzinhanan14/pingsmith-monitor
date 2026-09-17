<?php

namespace Database\Seeders;

use App\Models\Team;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create(['name' => 'Demo Owner', 'email' => 'demo@pingsmith.test']);
        $team = Team::create(['name' => 'Demo Infrastructure', 'slug' => 'demo-infra', 'owner_id' => $user->id]);
        $team->members()->attach($user, ['role' => 'owner']);
        $team->monitors()->createMany([['name' => 'Laravel', 'url' => 'https://laravel.com', 'status' => 'UP', 'interval_seconds' => 60, 'last_latency_ms' => 86, 'last_checked_at' => now()], ['name' => 'Example API', 'url' => 'https://example.com/api', 'status' => 'PENDING', 'interval_seconds' => 300]]);
    }
}
