<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\System\Site;
use App\Models\System\Post;
use App\Models\System\Group;
use App\Models\System\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Site settings.
        if (Site::where('domain', config('app.base_domain'))->exists()) {
            $site = Site::where('domain', config('app.base_domain'))->firstOrFail();
            $domain = Str::snake(config('app.base_domain'));

            // General settings.
            if (Group::where('name', 'site')->exists()) {
                $group = Group::where('name', 'site')->firstOrFail();

                $timezones = [];

                foreach (timezones() as $tz) {
                    $timezones[$tz->zone] = $tz->zone;
                }

                $statuses = [
                    Site::ACTIVE => ucfirst(Site::ACTIVE),
                    Site::INACTIVE => ucfirst(Site::INACTIVE)
                ];

                $settings = [
                    $domain . '_' . 'site_status'                => ['type' => Setting::INPUT_SELECT,        'required' => Setting::REQUIRED,        'value' => Site::ACTIVE, 'options' => serialize($statuses)],
                    $domain . '_' . 'site_title'                 => ['type' => Setting::INPUT_TEXT,          'required' => Setting::REQUIRED,        'value' => env('APP_NAME')],
                    $domain . '_' . 'site_meta_keywords'         => ['type' => Setting::INPUT_TEXTAREA,      'required' => Setting::NOT_REQUIRED,    'value' => ''],
                    $domain . '_' . 'site_meta_description'      => ['type' => Setting::INPUT_TEXTAREA,      'required' => Setting::NOT_REQUIRED,    'value' => ''],
                    $domain . '_' . 'site_meta_robots'           => ['type' => Setting::INPUT_MULTISELECT,   'required' => Setting::NOT_REQUIRED,    'value' => 'NONE', 'options' => serialize(Site::META_ROBOTS)],
                    $domain . '_' . 'site_tagline'               => ['type' => Setting::INPUT_TEXT,          'required' => Setting::NOT_REQUIRED,    'value' => ''],
                    $domain . '_' . 'site_admin_email'           => ['type' => Setting::INPUT_EMAIL,         'required' => Setting::REQUIRED,        'value' => env('APP_EMAIL')],
                    $domain . '_' . 'site_developer_email'       => ['type' => Setting::INPUT_EMAIL,         'required' => Setting::REQUIRED,        'value' => env('APP_DEVELOPER_EMAIL')],
                    $domain . '_' . 'site_language'              => ['type' => Setting::INPUT_SELECT,        'required' => Setting::REQUIRED,        'value' => env('DEFAULT_LOCALE'), 'options' => serialize(site_languages())],
                    $domain . '_' . 'site_time_zone'             => ['type' => Setting::INPUT_SELECT,        'required' => Setting::REQUIRED,        'value' => env('APP_TZ'), 'options' => serialize($timezones)],
                ];

                foreach ($settings as $key => $value) {
                    if (!Setting::where('key', $key)->exists()) {
                        $setting = new Setting;
                        $setting->key = $key;
                        $setting->value = $value['value'];
                        $setting->type = $value['type'];

                        if (array_key_exists('options', $value)) {
                            $setting->options = $value['options'];
                        }

                        $setting->status = Setting::LOCKED;
                        $setting->required = $value['required'];
                        $setting->saveOrFail();
                        $setting->assignGroup($group);
                        $site->assignSetting($setting);
                    }
                }
            }

            // Theme Settings
            if (Group::where('name', 'theme')->exists()) {
                $group = Group::where('name', 'theme')->firstOrFail();

                $settings = [
                    $domain . '_' . 'site_theme_name'             => ['type' => Setting::INPUT_SELECT,   'required' => Setting::REQUIRED,       'value' => 'default', 'options' => serialize(['default'])],
                    $domain . '_' . 'site_theme_logo'             => ['type' => Setting::INPUT_FILE,     'required' => Setting::NOT_REQUIRED,   'value' => null,      'options' => serialize(['jpeg','gif','png'])],
                    $domain . '_' . 'site_theme_css'              => ['type' => Setting::INPUT_TEXTAREA, 'required' => Setting::NOT_REQUIRED,   'value' => null],
                    $domain . '_' . 'site_theme_before_scripts'   => ['type' => Setting::INPUT_TEXTAREA, 'required' => Setting::NOT_REQUIRED,   'value' => null],
                    $domain . '_' . 'site_theme_after_scripts'    => ['type' => Setting::INPUT_TEXTAREA, 'required' => Setting::NOT_REQUIRED,   'value' => null],
                ];

                foreach ($settings as $key => $value) {
                    if (!Setting::where('key', $key)->exists()) {
                        $setting = new Setting;
                        $setting->key = $key;
                        $setting->value = $value['value'];
                        $setting->type = $value['type'];

                        if (array_key_exists('options', $value)) {
                            $setting->options = $value['options'];
                        }

                        $setting->status = Setting::LOCKED;
                        $setting->required = $value['required'];
                        $setting->saveOrFail();
                        $setting->assignGroup($group);
                        $site->assignSetting($setting);
                    }
                }
            }

            // Payment
            if (Group::where('name', 'payment')->exists()) {
                $group = Group::where('name', 'payment')->firstOrFail();

                // Credit Cards
                $credit_cards = [
                    'amex' => 'American Express',
                    'discover' => 'Discover',
                    'mastercard' => 'MasterCard',
                    'visa' => 'Visa'
                ];

                $settings = [
                    $domain . '_' . 'site_credit_cards_accepted' => ['type' => Setting::INPUT_MULTISELECT, 'required' => Setting::REQUIRED, 'value' => implode(',', array_keys($credit_cards)), 'options' => serialize($credit_cards)]
                ];

                foreach ($settings as $key => $value) {
                    if (!Setting::where('key', $key)->exists()) {
                        $setting = new Setting;
                        $setting->key = $key;
                        $setting->value = $value['value'];
                        $setting->type = $value['type'];

                        if (array_key_exists('options', $value)) {
                            $setting->options = $value['options'];
                        }

                        $setting->status = Setting::LOCKED;
                        $setting->required = $value['required'];
                        $setting->saveOrFail();
                        $setting->assignGroup($group);
                        $site->assignSetting($setting);
                    }
                }
            }

            // Social media settings.
            if (Group::where('name', 'social_media')->exists()) {
                $group = Group::where('name', 'social_media')->firstOrFail();

                $settings = [

                    $domain . '_' . 'site_social_linkedin'  => ['type' => Setting::INPUT_TEXT, 'required' => Setting::NOT_REQUIRED, 'value' => null],
                    $domain . '_' . 'site_social_twitter'   => ['type' => Setting::INPUT_TEXT, 'required' => Setting::NOT_REQUIRED, 'value' => null],
                    $domain . '_' . 'site_social_facebook'  => ['type' => Setting::INPUT_TEXT, 'required' => Setting::NOT_REQUIRED, 'value' => null],
                    $domain . '_' . 'site_social_instagram' => ['type' => Setting::INPUT_TEXT, 'required' => Setting::NOT_REQUIRED, 'value' => null],
                    $domain . '_' . 'site_social_youtube'   => ['type' => Setting::INPUT_TEXT, 'required' => Setting::NOT_REQUIRED, 'value' => null],
                ];

                foreach ($settings as $key => $value) {
                    if (!Setting::where('key', $key)->exists()) {
                        $setting = new Setting;
                        $setting->key = $key;
                        $setting->value = $value['value'];
                        $setting->type = $value['type'];

                        if (array_key_exists('options', $value)) {
                            $setting->options = $value['options'];
                        }

                        $setting->status = Setting::LOCKED;
                        $setting->required = $value['required'];
                        $setting->saveOrFail();
                        $setting->assignGroup($group);
                        $site->assignSetting($setting);
                    }
                }
            }
        }
    }
}
