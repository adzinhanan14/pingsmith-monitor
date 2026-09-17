<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertChannel extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'name', 'type', 'config_data', 'is_enabled'];

    protected function casts(): array
    {
        return ['config_data' => 'array', 'is_enabled' => 'boolean'];
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
