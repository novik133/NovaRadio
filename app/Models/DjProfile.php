<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DjProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_member_id',
        'stage_name',
        'genre',
        'biography',
        'equipment',
        'mixcloud_url',
        'soundcloud_url',
        'spotify_url',
        'apple_music_url',
        'years_experience',
        'is_resident',
        'top_tracks',
    ];

    protected $casts = [
        'is_resident' => 'boolean',
        'years_experience' => 'integer',
        'top_tracks' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }
}
