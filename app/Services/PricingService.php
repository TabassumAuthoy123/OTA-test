<?php

namespace App\Services;

use App\Models\PricingConfig;

class PricingService
{
    /**
     * Apply markup to an array of search results based on channel.
     * Admin channel = no markup (raw GDS price).
     * B2C/B2B = dynamic markup from pricing_configs table.
     *
     * Adds three keys to each result:
     *   - gds_base_fare  (original GDS price)
     *   - markup_amount  (how much was added)
     *   - selling_fare   (what the user sees)
     */
    public static function applyMarkup(array $results, string $channel): array
    {
        // Admin sees raw GDS prices — no markup
        if ($channel === 'admin') {
            foreach ($results as &$result) {
                $result['gds_base_fare'] = $result['total_fare'];
                $result['markup_amount'] = 0;
                $result['selling_fare'] = $result['total_fare'];
            }
            return $results;
        }

        $config = PricingConfig::forChannel($channel);

        foreach ($results as &$result) {
            $baseFare = $result['total_fare'];
            $result['gds_base_fare'] = $baseFare;

            if ($config && $config->is_active) {
                $markupAmount = self::calculateMarkup($baseFare, $config);
                $result['markup_amount'] = round($markupAmount, 2);
                $result['selling_fare'] = round($baseFare + $markupAmount, 2);
            } else {
                // No config or inactive — show base price
                $result['markup_amount'] = 0;
                $result['selling_fare'] = $baseFare;
            }
        }

        return $results;
    }

    /**
     * Calculate markup amount for a single fare based on config.
     */
    public static function calculateMarkup(float $baseFare, PricingConfig $config): float
    {
        if ($config->markup_type === 'percentage') {
            return ($baseFare * $config->markup_value) / 100;
        }

        // Fixed amount
        return (float) $config->markup_value;
    }

    /**
     * Get selling price for a single fare on a given channel.
     */
    public static function getSellingPrice(float $baseFare, string $channel): float
    {
        if ($channel === 'admin') {
            return $baseFare;
        }

        $config = PricingConfig::forChannel($channel);
        if (!$config || !$config->is_active) {
            return $baseFare;
        }

        return round($baseFare + self::calculateMarkup($baseFare, $config), 2);
    }

    /**
     * Get active config for a channel (for admin UI display).
     */
    public static function getConfig(string $channel): ?PricingConfig
    {
        return PricingConfig::forChannel($channel);
    }
}
