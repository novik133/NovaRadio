<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'start_date', 'end_date',
        'venue', 'address', 'city', 'ticket_price', 'ticket_url',
        'status', 'featured_dj_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];

    public function featuredDj()
    {
        return $this->belongsTo(TeamMember::class, 'featured_dj_id');
    }

    public function djs()
    {
        return $this->belongsToMany(TeamMember::class, 'event_dj', 'event_id', 'dj_id')
                    ->withPivot('set_time', 'order')
                    ->orderBy('event_dj.order');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now())
                     ->where('status', 'upcoming')
                     ->orderBy('start_date');
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now())
                     ->where('status', 'ongoing');
    }

    public function getIsFreeAttribute()
    {
        return is_null($this->ticket_price) || $this->ticket_price == 0;
    }
}
