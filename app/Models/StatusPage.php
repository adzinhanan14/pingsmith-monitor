<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusPage extends Model
{
    protected $fillable = [
        'team_id',
        'slug',
        'title',
        'description',
        'logo_url',
        'is_public',
        'custom_domain',
        'branding',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'custom_domain' => 'array',
        'branding' => 'array',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
