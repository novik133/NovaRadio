<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            if (!$setting) {
                return $default;
            }
            return $setting->castValue();
        });
    }

    public static function set(string $key, $value, ?string $type = 'string', ?string $group = 'general', ?string $label = null): void
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            // Update existing setting - preserve label if not provided
            $setting->value = is_array($value) ? json_encode($value) : $value;
            if ($type !== null) {
                $setting->type = $type;
            }
            if ($group !== null) {
                $setting->group = $group;
            }
            if ($label !== null) {
                $setting->label = $label;
            }
            $setting->save();
        } else {
            // Create new setting - label is required for new records
            self::create([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type ?? 'string',
                'group' => $group ?? 'general',
                'label' => $label ?? ucwords(str_replace('_', ' ', $key)),
            ]);
        }
        
        Cache::forget("setting.{$key}");
    }

    public function castValue()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
