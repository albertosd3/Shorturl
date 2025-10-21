<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RotatorUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'rotator_group_id',
        'url',
        'weight',
        'clicks',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'weight' => 'integer',
        'clicks' => 'integer',
    ];

    public function rotatorGroup(): BelongsTo
    {
        return $this->belongsTo(RotatorGroup::class);
    }

    public function incrementClicks(): void
    {
        $this->increment('clicks');
    }
}