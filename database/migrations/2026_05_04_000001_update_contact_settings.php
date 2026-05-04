<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $now = now();

        // Contact settings
        $settings = [
            ['key' => 'footer_phone',   'value' => '+880 18 9645 9490', 'group' => 'footer_contact'],
            ['key' => 'footer_phone_2', 'value' => '+880 18 9645 9492', 'group' => 'footer_contact'],
            ['key' => 'footer_email',   'value' => 'info@faithtrip.net', 'group' => 'footer_contact'],
            ['key' => 'footer_email_2', 'value' => 'marketing1@faithtrip.net', 'group' => 'footer_contact'],
            ['key' => 'footer_email_3', 'value' => 'director@faithtrip.net', 'group' => 'footer_contact'],
            ['key' => 'footer_email_4', 'value' => 'it@faithtrip.net', 'group' => 'footer_contact'],
            ['key' => 'footer_address', 'value' => 'Abedin Tower (Level 5), 35 Kamal Ataturk Avenue, Banani, Dhaka-1213', 'group' => 'footer_contact'],
        ];

        foreach ($settings as $s) {
            DB::table('cms_site_settings')->updateOrInsert(
                ['key' => $s['key']],
                ['value' => $s['value'], 'group' => $s['group'], 'updated_at' => $now, 'created_at' => $now]
            );
        }

        // Social media links — add Pinterest and TikTok if missing
        $socials = [
            ['name' => 'Pinterest', 'link' => '#'],
            ['name' => 'TikTok',    'link' => '#'],
        ];
        foreach ($socials as $s) {
            if (!DB::table('social_media_links')->where('name', $s['name'])->exists()) {
                DB::table('social_media_links')->insert([
                    'name'       => $s['name'],
                    'link'       => $s['link'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('cms_site_settings')->whereIn('key', [
            'footer_phone_2','footer_email_2','footer_email_3','footer_email_4',
        ])->delete();

        DB::table('social_media_links')->whereIn('name', ['Pinterest','TikTok'])->delete();
    }
};
