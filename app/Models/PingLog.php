<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PingLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['monitor_id', 'is_success', 'status_code', 'latency_ms', 'error_message', 'checked_at'];

    protected function casts(): array
    {
        return ['checked_at' => 'datetime', 'is_success' => 'boolean'];
    }

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
