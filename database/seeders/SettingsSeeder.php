<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Setting\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // Nhóm General
            ['group' => 'general', 'key' => 'timezone', 'label' => 'Timezone', 'value' => 'Asia/Ho_Chi_Minh', 'type' => 'timezone'],
            ['group' => 'general', 'key' => 'site_title', 'label' => 'Title Website', 'value' => 'Thành Aloha', 'type' => 'text'],

            // Nhóm Theme
            ['group' => 'theme', 'key' => 'logo', 'label' => 'Logo', 'value' => '', 'type' => 'image'],
            ['group' => 'theme', 'key' => 'favicon', 'label' => 'Favicon', 'value' => '', 'type' => 'image'],
            ['group' => 'theme', 'key' => 'footer_text', 'label' => 'Text Footer', 'value' => 'All rights reserved. Version:', 'type' => 'text'],
            
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
