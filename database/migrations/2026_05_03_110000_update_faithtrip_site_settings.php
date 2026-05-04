<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $updates = [
            // Footer contact — FaithTrip real values
            'footer_phone'       => '+880 9678 189188',
            'footer_email'       => 'info@faithtrip.net',
            'footer_address'     => 'Abedin Tower (Level 5), 35 Kamal Ataturk Avenue, Banani, Dhaka-1213',
            'office_name'        => 'FaithTrip Office (Dhaka)',
            'iata_number'        => '42344724',

            // Footer description
            'footer_description' => 'FaithTrip is committed to delivering exceptional travel experiences through our expert services and unwavering dedication to customer satisfaction. As an IATA-affiliated travel agency, we offer flights, tours, visa, hotels and more — ensuring the best deals and highest quality service.',

            // Social links (admin can override from CMS)
            'social_facebook'    => '#',
            'social_twitter'     => '#',
            'social_instagram'   => '#',
            'social_youtube'     => '#',
            'social_pinterest'   => '#',
            'social_tiktok'      => '#',

            // App download
            'app_store_url'      => '#',
            'play_store_url'     => '#',
        ];

        $now = now();
        foreach ($updates as $key => $value) {
            DB::table('cms_site_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'group' => str_starts_with($key, 'social_') ? 'footer_social' : (str_starts_with($key, 'footer_') || in_array($key, ['office_name','iata_number']) ? 'footer_contact' : 'general'), 'updated_at' => $now, 'created_at' => $now]
            );
        }

        // Seed default social_media_links if empty — admin can update from CMS
        if (DB::table('social_media_links')->count() === 0) {
            $links = [
                ['name' => 'Facebook',  'link' => '#', 'logo' => null],
                ['name' => 'Twitter',   'link' => '#', 'logo' => null],
                ['name' => 'Instagram', 'link' => '#', 'logo' => null],
                ['name' => 'YouTube',   'link' => '#', 'logo' => null],
                ['name' => 'Pinterest', 'link' => '#', 'logo' => null],
                ['name' => 'TikTok',    'link' => '#', 'logo' => null],
            ];
            foreach ($links as &$l) {
                $l['created_at'] = $now;
                $l['updated_at'] = $now;
            }
            DB::table('social_media_links')->insert($links);
        }
    }

    public function down(): void
    {
        // Restore old placeholder values
        DB::table('cms_site_settings')->where('key', 'footer_email')->update(['value' => 'support@skytrip.com']);
        DB::table('cms_site_settings')->where('key', 'footer_address')->update(['value' => 'Dhaka, Bangladesh']);
        DB::table('cms_site_settings')->where('key', 'footer_phone')->update(['value' => '+880-XXXX-XXXXXX']);
    }
};
