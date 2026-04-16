<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        // Default config row
        DB::table('configs')->insertOrIgnore([
            ['id' => 1, 'search_results_view' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Default email config row
        DB::table('email_configures')->insertOrIgnore([
            ['id' => 1, 'host' => '', 'port' => 587, 'email' => '', 'password' => '',
             'mail_from_name' => '', 'mail_from_email' => '', 'encryption' => 0,
             'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3 SMS gateway rows (elitbuzz=1, revesms=2, khudebarta=3)
        DB::table('sms_gateways')->insertOrIgnore([
            ['id' => 1, 'provider_name' => 'ElitBuzz',   'api_endpoint' => '', 'api_key' => null, 'secret_key' => null, 'sender_id' => null, 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'provider_name' => 'ReveSMS',    'api_endpoint' => '', 'api_key' => null, 'secret_key' => null, 'sender_id' => null, 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'provider_name' => 'KhudeBarta', 'api_endpoint' => '', 'api_key' => null, 'secret_key' => null, 'sender_id' => null, 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
