<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonitorGroupFactory extends Factory
{
    public function definition(): array
    {
        $colors = ['#6366f1', '#14b8a6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
        
        return [
            'team_id' => Team::factory(),
            'name' => fake()->randomElement(['Infrastructure', 'API Services', 'Web Applications', 'Databases', 'External Services']),
            'description' => fake()->sentence(),
            'color' => fake()->randomElement($colors),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
