<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkupRule extends Model
{
    protected $fillable = [
        'name', 'channel', 'gds', 'airline_code', 'route_from', 'route_to',
        'cabin_class', 'pax_type', 'agent_id',
        'markup_type', 'markup_value', 'is_active', 'priority',
    ];

    protected $casts = [
        'markup_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find the best matching markup rule for a given flight + channel context.
     */
    public static function findBestMatch(string $channel, string $gds, string $airlineCode, ?string $routeFrom, ?string $routeTo, ?string $cabinClass, ?string $paxType, ?int $agentId = null): ?self
    {
        $rules = static::active()->orderBy('priority', 'asc')->get();

        $bestRule = null;
        $bestScore = -1;

        foreach ($rules as $rule) {
            $score = 0;

            // Channel match
            if ($rule->channel !== 'all' && $rule->channel !== strtolower($channel)) continue;
            if ($rule->channel !== 'all') $score += 2;

            // GDS match
            if ($rule->gds !== 'all' && $rule->gds !== strtolower($gds)) continue;
            if ($rule->gds !== 'all') $score += 1;

            // Airline match
            if ($rule->airline_code && strtoupper($rule->airline_code) !== strtoupper($airlineCode)) continue;
            if ($rule->airline_code) $score += 10;

            // Route match
            if ($rule->route_from && strtoupper($rule->route_from) !== strtoupper($routeFrom ?? '')) continue;
            if ($rule->route_from) $score += 20;

            if ($rule->route_to && strtoupper($rule->route_to) !== strtoupper($routeTo ?? '')) continue;
            if ($rule->route_to) $score += 20;

            // Cabin class match
            if ($rule->cabin_class && strtoupper($rule->cabin_class) !== strtoupper($cabinClass ?? '')) continue;
            if ($rule->cabin_class) $score += 5;

            // PAX type match
            if ($rule->pax_type && strtoupper($rule->pax_type) !== strtoupper($paxType ?? '')) continue;
            if ($rule->pax_type) $score += 5;

            // Agent-specific
            if ($rule->agent_id) {
                if ($rule->agent_id != $agentId) continue;
                $score += 50;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRule = $rule;
            }
        }

        return $bestRule;
    }

    /**
     * Calculate markup amount from base fare.
     */
    public function calculate(float $baseFare): float
    {
        if ($this->markup_type === 'percentage') {
            return round(($baseFare * $this->markup_value) / 100, 2);
        }
        return (float) $this->markup_value;
    }
}
