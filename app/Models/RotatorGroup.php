<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RotatorGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_code',
        'description',
        'rotation_type',
        'is_active',
        'clicks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'clicks' => 'integer',
    ];

    public function rotatorUrls(): HasMany
    {
        return $this->hasMany(RotatorUrl::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function getNextUrl(): ?string
    {
        $urls = $this->rotatorUrls()->where('is_active', true)->get();
        
        if ($urls->isEmpty()) {
            return null;
        }

        switch ($this->rotation_type) {
            case 'sequential':
                return $this->getSequentialUrl($urls);
            case 'weighted':
                return $this->getWeightedUrl($urls);
            default: // random
                return $urls->random()->url;
        }
    }

    private function getSequentialUrl($urls): string
    {
        $lastClickedUrl = $this->clicks()
            ->latest()
            ->with('rotatorUrl')
            ->first()?->rotatorUrl;

        if (!$lastClickedUrl) {
            return $urls->first()->url;
        }

        $currentIndex = $urls->search(function ($url) use ($lastClickedUrl) {
            return $url->id === $lastClickedUrl->id;
        });

        $nextIndex = ($currentIndex + 1) % $urls->count();
        return $urls[$nextIndex]->url;
    }

    private function getWeightedUrl($urls): string
    {
        $totalWeight = $urls->sum('weight');
        $random = rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($urls as $url) {
            $currentWeight += $url->weight;
            if ($random <= $currentWeight) {
                return $url->url;
            }
        }

        return $urls->first()->url;
    }

    public function incrementClicks(): void
    {
        $this->increment('clicks');
    }

    public static function generateShortCode(): string
    {
        do {
            $code = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
        } while (self::where('short_code', $code)->exists() || ShortUrl::where('short_code', $code)->exists());

        return $code;
    }
}