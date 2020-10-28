<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\System\Page;
use App\Models\System\Role;
use App\Models\System\User;

/**
 * PagesTableSeeder Seeder
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 */
class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $site = site(config('app.base_domain'));
        $user = User::role(Role::SUPER_ADMIN)->first();
        $pages = [
            [
                'title'     => 'Company',
                'content'   => '<p>As we transcend further into the 21st century, it seems that almost every faucet of life has been disrupted by the digital world. The best digital solutions almost give us back what we value the most… TIME!</p><p>Office staff can use extra time to focus on caring for their patients and improving outcomes. Representatives can spend less time setting appointments and more time conversing with providers.</p><p>We knew that there had to be a better way to create more meaningful visits from industry representatives. Through a survey, we found that offices can spend as much as 10 hours a week managing representatives! There is so much time wasted on both sides trying to set up meetings/ managing industry representatives, so we thought "Lets change that!"</p><p>Insert MD REP TIME. A digital communication platform that connects medical providers to industry representatives.</p><p>Our goal is simple: Provide you a platform that simplifies your daily interactions and gives you back your time.</p>'
            ],
            [
                'title'     => 'Representatives',
                'content'   => '<ul><li>Become discoverable in rep database.</li><li>Have access to the provider’s calendar.</li><li>Request meetings based on availability and interest level.</li><li>Easy messaging platform (no more texting).</li><li>More meaningful meetings=Increase in sales.</li><li>Automatically track office staff for Sunshine Act reporting.</li><li>Less time setting meetings -> More time selling.</li></ul>'
            ],
            [
                'title'     => 'Offices',
                'content'   => '<ul><li>Completely free software.</li><li>Simplify rep appointments to online platform.</li><li>Search rep database for contact information.</li><li>Easy messaging platform (no more texting).</li><li>Ensure reps are compliant with office rules.</li><li>Sign up in 2 minutes!</li></ul>'
            ]
        ];

        if(count($pages) !== 0) {
            foreach($pages as $page) {
                $_page = new Page;
                $_page->uuid = Str::uuid();
                $_page->user_id = $user->id;
                $_page->slug = unique_name('page', $page['title']);
                $_page->title = $page['title'];
                $_page->content = $page['content'];
                $_page->meta_robots = Page::DEFAULT_META_ROBOTS;
                $_page->template = Page::TEMPLATE_DEFAULT;
                $_page->status = Page::ACTIVE;
                $_page->visible = Page::VISIBLE;

                if($_page->save()) {
                    $site->assignPage($_page);
                }
            }
        }
    }
}
