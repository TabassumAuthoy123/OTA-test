<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockingRule extends Model
{
    protected $fillable = [
        'name', 'gds', 'airline_code', 'route_from', 'route_to',
        'cabin_class', 'block_type', 'reason', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a specific flight combination is blocked.
     *
     * @return BlockingRule|null — returns the blocking rule if blocked, null if allowed
     */
    public static function isBlocked(string $gds, string $airlineCode, ?string $routeFrom = null, ?string $routeTo = null, ?string $cabinClass = null): ?self
    {
        $rules = static::active()->get();

        foreach ($rules as $rule) {
            // GDS match
            if ($rule->gds !== 'all' && strtolower($rule->gds) !== strtolower($gds)) continue;

            $matched = false;

            switch ($rule->block_type) {
                case 'airline':
                    // Block entire airline
                    if ($rule->airline_code && strtoupper($rule->airline_code) === strtoupper($airlineCode)) {
                        $matched = true;
                    }
                    break;

                case 'route':
                    // Block specific route (optionally on a specific airline)
                    $routeMatch = true;
                    if ($rule->route_from && strtoupper($rule->route_from) !== strtoupper($routeFrom ?? '')) $routeMatch = false;
                    if ($rule->route_to && strtoupper($rule->route_to) !== strtoupper($routeTo ?? '')) $routeMatch = false;
                    if ($rule->airline_code && strtoupper($rule->airline_code) !== strtoupper($airlineCode)) $routeMatch = false;
                    if ($routeMatch && ($rule->route_from || $rule->route_to)) $matched = true;
                    break;

                case 'class':
                    // Block specific cabin class on airline
                    if ($rule->cabin_class && strtoupper($rule->cabin_class) === strtoupper($cabinClass ?? '')) {
                        if (!$rule->airline_code || strtoupper($rule->airline_code) === strtoupper($airlineCode)) {
                            $matched = true;
                        }
                    }
                    break;

                case 'combo':
                    // All specified fields must match
                    $comboMatch = true;
                    if ($rule->airline_code && strtoupper($rule->airline_code) !== strtoupper($airlineCode)) $comboMatch = false;
                    if ($rule->route_from && strtoupper($rule->route_from) !== strtoupper($routeFrom ?? '')) $comboMatch = false;
                    if ($rule->route_to && strtoupper($rule->route_to) !== strtoupper($routeTo ?? '')) $comboMatch = false;
                    if ($rule->cabin_class && strtoupper($rule->cabin_class) !== strtoupper($cabinClass ?? '')) $comboMatch = false;
                    if ($comboMatch) $matched = true;
                    break;
            }

            if ($matched) return $rule;
        }

        return null;
    }
}
