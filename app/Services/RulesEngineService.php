<?php

namespace App\Services;

use App\Models\CommissionRule;
use App\Models\MarkupRule;
use App\Models\BlockingRule;

class RulesEngineService
{
    /**
     * Calculate Commission based on the multi-level rules engine.
     * 
     * @return float The commission amount.
     */
    public static function calculateCommission(
        string $gds, 
        string $airlineCode, 
        string $routeFrom, 
        string $routeTo, 
        string $cabinClass, 
        string $paxType, 
        ?int $agentId, 
        float $baseFare
    ): float {
        $rule = CommissionRule::findBestMatch(
            $gds, 
            $airlineCode, 
            $routeFrom, 
            $routeTo, 
            $cabinClass, 
            $paxType, 
            $agentId
        );

        if (!$rule) {
            return 0.0;
        }

        if ($rule->commission_type === 'percentage') {
            return round(($baseFare * $rule->commission_value) / 100, 2);
        }

        return (float) $rule->commission_value;
    }

    /**
     * Calculate Markup based on the multi-level rules engine.
     * 
     * @return float The markup amount.
     */
    public static function calculateMarkup(
        string $channel,
        string $gds, 
        string $airlineCode, 
        string $routeFrom, 
        string $routeTo, 
        string $cabinClass, 
        string $paxType, 
        ?int $agentId, 
        float $baseFare
    ): float {
        $rule = MarkupRule::findBestMatch(
            $channel, 
            $gds, 
            $airlineCode, 
            $routeFrom, 
            $routeTo, 
            $cabinClass, 
            $paxType, 
            $agentId
        );

        if (!$rule) {
            return 0.0;
        }

        if ($rule->markup_type === 'percentage') {
            return round(($baseFare * $rule->markup_value) / 100, 2);
        }

        return (float) $rule->markup_value;
    }

    /**
     * Check if a specific flight scenario is blocked by the rules engine.
     * 
     * @return bool True if blocked, false otherwise.
     */
    public static function isBlocked(
        string $gds, 
        string $airlineCode, 
        string $routeFrom, 
        string $routeTo, 
        string $cabinClass
    ): bool {
        $rule = BlockingRule::isBlocked(
            $gds, 
            $airlineCode, 
            $routeFrom, 
            $routeTo, 
            $cabinClass
        );

        return $rule !== null;
    }
}
