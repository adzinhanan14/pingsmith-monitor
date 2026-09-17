<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitorGroup extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'description',
        'color',
        'sort_order',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function monitors()
    {
        return $this->hasMany(Monitor::class, 'group', 'name');
    }
}
