<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = ['monitor_id', 'status', 'started_at', 'resolved_at', 'failure_reason'];

    protected function casts(): array
    {
        return ['started_at' => 'datetime', 'resolved_at' => 'datetime'];
    }

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
