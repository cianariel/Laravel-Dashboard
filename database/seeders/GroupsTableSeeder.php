<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use App\Models\System\Group;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Settings Groups
        $groups = [
            'user'          => 'User',
            'site'          => 'Site',
            'blog'          => 'Blog',
            'general'       => 'General',
            'theme'         => 'Theme',
            'media'         => 'Media',
            'social_media'  => 'Social Media',
            'payment'       => 'Payments',
            'privacy'       => 'Privacy',
            'security'      => 'Security',
        ];

        foreach ($groups as $name => $label) {
            if (!Group::where('name', $name)->exists()) {
                $group = new Group;
                $group->type = Group::SYSTEM;
                $group->name = $name;
                $group->label = $label;
                $group->visible = Group::VISIBLE;
                $group->lock = Group::LOCKED;
                $group->saveOrFail();
            }
        }
    }
}
