<?php

namespace Database\Seeders;

use App\Models\PricingConfig;
use Illuminate\Database\Seeder;

class PricingConfigSeeder extends Seeder
{
    public function run(): void
    {
        PricingConfig::updateOrCreate(
            ['channel' => 'b2c'],
            [
                'markup_type' => 'percentage',
                'markup_value' => 5.00,   // 5% markup for B2C
                'is_active' => true,
            ]
        );

        PricingConfig::updateOrCreate(
            ['channel' => 'b2b'],
            [
                'markup_type' => 'percentage',
                'markup_value' => 3.00,   // 3% markup for B2B
                'is_active' => true,
            ]
        );
    }
}
