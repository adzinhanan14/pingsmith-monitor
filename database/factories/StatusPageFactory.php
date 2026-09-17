<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StatusPageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->company() . ' Status';
        
        return [
            'team_id' => Team::factory(),
            'slug' => Str::slug($title) . '-' . fake()->unique()->randomNumber(4),
            'title' => $title,
            'description' => fake()->sentence(),
            'logo_url' => null,
            'is_public' => true,
            'custom_domain' => null,
            'branding' => [
                'primary_color' => '#6366f1',
                'secondary_color' => '#14b8a6',
                'accent_color' => '#f59e0b',
            ],
        ];
    }
}
