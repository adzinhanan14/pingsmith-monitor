<?php

namespace Database\Seeders;

use App\Models\Monitor;
use App\Models\MonitorGroup;
use App\Models\StatusPage;
use App\Models\Team;
use Illuminate\Database\Seeder;

class StatusPageSeeder extends Seeder
{
    public function run(): void
    {
        $team = Team::first();
        
        if (!$team) {
            $this->command->warn('No team found. Please create a team first.');
            return;
        }

        // Create monitor groups
        $groups = [
            ['name' => 'API Services', 'color' => '#6366f1', 'description' => 'Backend API endpoints', 'sort_order' => 1],
            ['name' => 'Web Applications', 'color' => '#14b8a6', 'description' => 'Frontend web applications', 'sort_order' => 2],
            ['name' => 'Infrastructure', 'color' => '#f59e0b', 'description' => 'Core infrastructure services', 'sort_order' => 3],
        ];

        foreach ($groups as $groupData) {
            MonitorGroup::firstOrCreate(
                ['name' => $groupData['name'], 'team_id' => $team->id],
                $groupData + ['team_id' => $team->id]
            );
        }

        // Create status page
        StatusPage::firstOrCreate(
            ['team_id' => $team->id],
            [
                'slug' => 'demo-status',
                'title' => $team->name . ' Status',
                'description' => 'Real-time status and uptime monitoring',
                'is_public' => true,
                'branding' => [
                    'primary_color' => '#6366f1',
                    'secondary_color' => '#14b8a6',
                    'accent_color' => '#f59e0b',
                ],
            ]
        );

        // Update some monitors to have groups
        $monitors = Monitor::where('team_id', $team->id)->get();
        
        if ($monitors->isNotEmpty()) {
            $apiGroup = MonitorGroup::where('name', 'API Services')->where('team_id', $team->id)->first();
            $webGroup = MonitorGroup::where('name', 'Web Applications')->where('team_id', $team->id)->first();

            $monitors->take(2)->each(function ($monitor) use ($apiGroup) {
                $monitor->update(['group' => $apiGroup->name, 'show_on_status_page' => true]);
            });

            $monitors->skip(2)->take(2)->each(function ($monitor) use ($webGroup) {
                $monitor->update(['group' => $webGroup->name, 'show_on_status_page' => true]);
            });
        }

        $this->command->info('Status page and monitor groups created successfully!');
        $this->command->info('View your status page at: /status/demo-status');
    }
}
