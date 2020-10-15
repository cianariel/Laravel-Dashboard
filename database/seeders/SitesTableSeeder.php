<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\System\Site;
use App\Models\System\Group;
use App\Models\System\Settings;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Site::where('domain', config('app.base_domain'))->exists()) {
            // New Site
            $site = new Site;
            $site->uuid = Str::uuid();
            $site->name = Str::slug(config('app.name'));
            $site->domain = env('APP_DOMAIN', 'geekbidz.com');
            $site->status = Site::ACTIVE;
            $site->created_at = now();
            $site->saveOrFail();
        }
    }
}
