<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel',
        'markup_type',
        'markup_value',
        'is_active',
    ];

    protected $casts = [
        'markup_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get active config for a specific channel
     */
    public static function forChannel(string $channel): ?self
    {
        return static::where('channel', $channel)
            ->where('is_active', true)
            ->first();
    }
}
