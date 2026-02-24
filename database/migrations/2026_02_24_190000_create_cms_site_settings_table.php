<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            // Hero
            ['key' => 'hero_badge', 'value' => 'Trusted by 10,000+ travelers', 'group' => 'hero'],
            ['key' => 'hero_title', 'value' => 'Find & Book <span>Best Flights</span><br>At Unbeatable Prices', 'group' => 'hero'],

            // Footer Contact
            ['key' => 'footer_phone', 'value' => '+880-XXXX-XXXXXX', 'group' => 'footer_contact'],
            ['key' => 'footer_email', 'value' => 'support@skytrip.com', 'group' => 'footer_contact'],
            ['key' => 'footer_address', 'value' => 'Dhaka, Bangladesh', 'group' => 'footer_contact'],

            // Footer Social
            ['key' => 'social_facebook', 'value' => '#', 'group' => 'footer_social'],
            ['key' => 'social_instagram', 'value' => '#', 'group' => 'footer_social'],
            ['key' => 'social_twitter', 'value' => '#', 'group' => 'footer_social'],
            ['key' => 'social_linkedin', 'value' => '#', 'group' => 'footer_social'],

            // Footer Description
            ['key' => 'footer_description', 'value' => 'Your dream destination is just a few clicks away. Book flights at the best price with instant confirmation.', 'group' => 'footer_contact'],
        ];

        $now = now();
        foreach ($defaults as &$d) {
            $d['created_at'] = $now;
            $d['updated_at'] = $now;
        }

        \Illuminate\Support\Facades\DB::table('cms_site_settings')->insert($defaults);
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_site_settings');
    }
};
