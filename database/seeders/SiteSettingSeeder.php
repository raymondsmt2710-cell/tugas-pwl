<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $siteSettings = [
            [
                'id' => 1,
                'site_name' => 'Autopahala',
                'email' => 'support@autopahala.com',
                'phone' => '+628123456789',
                'address' => 'Medan, Sumatera Utara, Indonesia',
                'social_media' => json_encode([
                    ['platform' => 'instagram', 'label' => '@autopahala', 'url' => 'https://instagram.com/autopahala'],
                    ['platform' => 'facebook', 'label' => 'AutoPahala Fans', 'url' => 'https://facebook.com/autopahala'],
                    ['platform' => 'twitter', 'label' => '@autopahala', 'url' => 'https://twitter.com/autopahala']
                ]),
                'footer_text' => 'Autopahala. All rights reserved.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('site_settings')->insert($siteSettings);
    }
}
