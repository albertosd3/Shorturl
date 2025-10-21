<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Click extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_code',
        'click_type',
        'short_url_id',
        'rotator_group_id',
        'ip_address',
        'user_agent',
        'country',
        'city',
        'device',
        'browser',
        'os',
        'referer',
        'is_bot',
        'is_blocked',
        'stopbot_data',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'is_blocked' => 'boolean',
        'stopbot_data' => 'array',
    ];

    public function shortUrl(): BelongsTo
    {
        return $this->belongsTo(ShortUrl::class);
    }

    public function rotatorGroup(): BelongsTo
    {
        return $this->belongsTo(RotatorGroup::class);
    }
}