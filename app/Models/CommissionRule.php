<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionRule extends Model
{
    protected $fillable = [
        'name', 'gds', 'airline_code', 'route_from', 'route_to',
        'cabin_class', 'pax_type', 'agent_id',
        'commission_type', 'commission_value', 'is_active', 'priority',
    ];

    protected $casts = [
        'commission_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Scope: only active rules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Find the best matching commission rule for a given flight context.
     * Rules are scored by specificity — more specific match = higher score.
     * If agent_id is provided, agent-specific rules are checked first.
     */
    public static function findBestMatch(string $gds, string $airlineCode, ?string $routeFrom, ?string $routeTo, ?string $cabinClass, ?string $paxType, ?int $agentId = null): ?self
    {
        $rules = static::active()->orderBy('priority', 'asc')->get();

        $bestRule = null;
        $bestScore = -1;

        foreach ($rules as $rule) {
            $score = 0;

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

            // Agent match — agent-specific rules get bonus
            if ($rule->agent_id) {
                if ($rule->agent_id != $agentId) continue;
                $score += 50; // Agent-specific rules always win over global
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestRule = $rule;
            }
        }

        return $bestRule;
    }

    /**
     * Calculate commission amount from base fare.
     */
    public function calculate(float $baseFare): float
    {
        if ($this->commission_type === 'percentage') {
            return round(($baseFare * $this->commission_value) / 100, 2);
        }
        return (float) $this->commission_value;
    }
}
