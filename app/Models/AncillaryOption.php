<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AncillaryOption extends Model
{
    protected $fillable = [
        'type', 'name', 'description', 'weight_kg', 'price',
        'currency', 'airline_code', 'route_from', 'route_to', 'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'weight_kg' => 'decimal:1',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public static function forFlight(?string $airlineCode, ?string $from, ?string $to, string $type = 'baggage')
    {
        return static::active()->where('type', $type)
            ->where(function ($q) use ($airlineCode) {
                $q->whereNull('airline_code')
                  ->orWhere('airline_code', strtoupper($airlineCode ?? ''));
            })
            ->where(function ($q) use ($from) {
                $q->whereNull('route_from')
                  ->orWhere('route_from', strtoupper($from ?? ''));
            })
            ->where(function ($q) use ($to) {
                $q->whereNull('route_to')
                  ->orWhere('route_to', strtoupper($to ?? ''));
            })
            ->orderBy('price')
            ->get();
    }
}
