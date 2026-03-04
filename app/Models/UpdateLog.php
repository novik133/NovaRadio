<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateLog extends Model
{
    use HasFactory;

    protected $table = 'updates';

    protected $fillable = [
        'version',
        'type',
        'status',
        'changelog',
        'message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public const TYPE_CHECK = 'check';
    public const TYPE_DOWNLOAD = 'download';
    public const TYPE_INSTALL = 'install';

    public const STATUS_SUCCESS = 'success';
    public const STATUS_ERROR = 'error';
    public const STATUS_PENDING = 'pending';
    public const STATUS_AVAILABLE = 'available';

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeLatestCheck($query)
    {
        return $query->where('type', self::TYPE_CHECK)->latest()->first();
    }

    public function scopeHasUpdateAvailable($query)
    {
        return $query->where('type', self::TYPE_CHECK)
            ->where('status', self::STATUS_AVAILABLE)
            ->exists();
    }
}
