<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')->withPivot('role')->withTimestamps();
    }

    public function monitors()
    {
        return $this->hasMany(Monitor::class);
    }

    public function alertChannels()
    {
        return $this->hasMany(AlertChannel::class);
    }

    public function roleFor(User $user): ?string
    {
        return $this->members()->whereKey($user)->first()?->pivot->role;
    }

    public function canManage(User $user): bool
    {
        return in_array($this->roleFor($user), ['owner', 'admin']);
    }
}
