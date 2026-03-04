<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleShow extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'host',
        'image',
        'day',
        'start_time',
        'end_time',
        'status',
        'order',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'order' => 'integer',
    ];

    public const DAYS = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('start_time');
    }

    public function scopeForDay($query, string $day)
    {
        return $query->where('day', $day);
    }

    public function getDayNameAttribute()
    {
        return self::DAYS[$this->day] ?? $this->day;
    }
}
