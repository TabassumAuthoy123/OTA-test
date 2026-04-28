<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracking_configs', function (Blueprint $table) {
            $table->string('secret_key')->nullable()->after('tracking_code');
        });

        DB::statement("ALTER TABLE tracking_configs MODIFY COLUMN type ENUM('google_recaptcha','google_analytics','facebook_pixel','google_tag_manager','custom') NOT NULL DEFAULT 'custom'");

        // Seed the 4 fixed tracking records if they don't exist
        $types = [
            ['type' => 'google_recaptcha',   'name' => 'Google ReCaptcha'],
            ['type' => 'google_tag_manager', 'name' => 'Google Tag Manager'],
            ['type' => 'google_analytics',   'name' => 'Google Analytics'],
            ['type' => 'facebook_pixel',     'name' => 'Facebook Pixel'],
        ];
        foreach ($types as $t) {
            $exists = DB::table('tracking_configs')->where('type', $t['type'])->exists();
            if (!$exists) {
                DB::table('tracking_configs')->insert([
                    'name'          => $t['name'],
                    'type'          => $t['type'],
                    'tracking_code' => '',
                    'secret_key'    => null,
                    'is_active'     => 0,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('tracking_configs', function (Blueprint $table) {
            $table->dropColumn('secret_key');
        });
        DB::statement("ALTER TABLE tracking_configs MODIFY COLUMN type ENUM('google_analytics','facebook_pixel','google_tag_manager','custom') NOT NULL DEFAULT 'custom'");
    }
};
