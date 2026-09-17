<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'name', 'type', 'url', 'method', 'status', 'interval_seconds', 'timeout_seconds', 'headers', 'expected_status_codes', 'next_check_at', 'last_latency_ms', 'last_checked_at', 'consecutive_failures', 'is_paused', 'show_on_status_page', 'is_maintenance', 'maintenance_starts_at', 'maintenance_ends_at', 'group', 'sort_order'];

    protected function casts(): array
    {
        return [
            'headers' => 'array',
            'expected_status_codes' => 'array',
            'last_checked_at' => 'datetime',
            'next_check_at' => 'datetime',
            'is_paused' => 'boolean',
            'show_on_status_page' => 'boolean',
            'is_maintenance' => 'boolean',
            'maintenance_starts_at' => 'datetime',
            'maintenance_ends_at' => 'datetime',
        ];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function logs()
    {
        return $this->hasMany(PingLog::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function monitorGroup()
    {
        return $this->belongsTo(MonitorGroup::class, 'group', 'name');
    }

    public function isInMaintenance()
    {
        if (!$this->is_maintenance) {
            return false;
        }

        $now = now();
        
        if ($this->maintenance_starts_at && $this->maintenance_ends_at) {
            return $now->between($this->maintenance_starts_at, $this->maintenance_ends_at);
        }

        return $this->is_maintenance;
    }
}
