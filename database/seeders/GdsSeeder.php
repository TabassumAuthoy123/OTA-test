<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GdsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gds')->insertOrIgnore([
            [
                'id' => 1, 'name' => 'Sabre', 'code' => 'sabre',
                'logo' => 'gds_logo/sabre.jpg',
                'description' => 'Sabre Global Distribution System',
                'serial' => 1, 'status' => 1, 'is_archived' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'id' => 2, 'name' => 'FlyHub', 'code' => 'flyhub',
                'logo' => 'gds_logo/flyhub.jpg',
                'description' => 'FlyHub Flight Search API',
                'serial' => 2, 'status' => 0, 'is_archived' => 0,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        DB::table('sabre_gds_configs')->insertOrIgnore([
            [
                'id' => 1, 'gds_id' => 1,
                'pcc' => 'S00L',
                'user_id' => 'V1:hxp6cy145bjv5hy9:DEVCENTER:EXT',
                'password' => 'Hp8tT6iN',
                'production_user_id' => null,
                'production_password' => null,
                'is_production' => 0,
                'description' => null,
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);

        DB::table('flyhub_gds_configs')->insertOrIgnore([
            [
                'id' => 1, 'gds_id' => 2,
                'api_endpoint' => 'https://apitest.flyhub.com/api',
                'api_key' => null,
                'secret_code' => null,
                'is_production' => 0,
                'description' => 'FlyHub Test Config',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
