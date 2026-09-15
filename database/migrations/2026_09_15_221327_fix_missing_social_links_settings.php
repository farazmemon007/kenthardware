<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'key' => 'facebook_link',
                'type' => 'string',
                'group' => 'company',
                'label' => 'Facebook Link',
                'description' => 'Facebook page URL for receipts',
            ],
            [
                'key' => 'tiktok_link',
                'type' => 'string',
                'group' => 'company',
                'label' => 'TikTok Link',
                'description' => 'TikTok profile URL for receipts',
            ],
            [
                'key' => 'instagram_link',
                'type' => 'string',
                'group' => 'company',
                'label' => 'Instagram Link',
                'description' => 'Instagram profile URL for receipts',
            ],
            [
                'key' => 'website_link',
                'type' => 'string',
                'group' => 'company',
                'label' => 'Website Link',
                'description' => 'Website URL for receipts',
            ]
        ];

        foreach ($settings as $setting) {
            \Illuminate\Support\Facades\DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'updated_at' => now(),
                ])
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not dropping them because this is a fix migration to ensure they exist.
    }
};
