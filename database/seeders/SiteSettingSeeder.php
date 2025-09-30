<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'visitor_count',
                'value' => '1250',
            ],
            [
                'key' => 'site_title',
                'value' => 'Travel History Blog',
            ],
            [
                'key' => 'site_description',
                'value' => 'Discover the world\'s most amazing historical monuments and travel destinations.',
            ],
            [
                'key' => 'contact_email',
                'value' => 'contact@travelhistoryblog.com',
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/travelhistoryblog',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/travelhistoryblog',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/travelhistoryblog',
            ],
            [
                'key' => 'ticker_config',
                'value' => json_encode([
                    'enabled' => true,
                    'speed' => 50,
                    'messages' => [
                        'Welcome to Travel History Blog!',
                        'Discover amazing historical monuments',
                        'Explore cultural heritage sites',
                        'Learn about world history'
                    ]
                ]),
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
            ],
            [
                'key' => 'analytics_code',
                'value' => '',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
