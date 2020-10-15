<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use App\Models\System\Menu;
use App\Models\System\MenuItem;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Main Site
        $site = site(config('app.base_domain'));

        // Header Menu
        //------------------------------------------------//

        /**
        $menu = new Menu;
        $menu->type = Menu::NAVIGATION;
        $menu->name = 'primary-menu';
        $menu->label = 'Primary Menu';
        $menu->location = Menu::HEADER;
        $menu->status = Menu::ACTIVE;
        $menu->saveOrFail();

        $site->assignMenu($menu); // Add to site

        // Menu Items
        $items = [
            'Pricing'   => ['type' => MenuItem::PARENT_ITEM, 'name' => 'pricing',   'title' => 'Pricing',   'label' => 'Pricing',   'url' => '#',               'target' => MenuItem::TARGET_SELF],
            'Support'   => ['type' => MenuItem::PARENT_ITEM, 'name' => 'support',   'title' => 'Support',   'label' => 'Support',   'url' => '#',               'target' => MenuItem::TARGET_SELF],
            'Login'     => ['type' => MenuItem::PARENT_ITEM, 'name' => 'login',     'title' => 'Login',     'label' => 'Login',     'url' => route('login'),    'target' => MenuItem::TARGET_SELF],
            'Sign Up'   => ['type' => MenuItem::PARENT_ITEM, 'name' => 'sign-up',   'title' => 'Sign Up',   'label' => 'Sign Up',   'url' => route('register'), 'target' => MenuItem::TARGET_SELF]
        ];

        foreach ($items as $index => $item) {
            $menuItem = new MenuItem;
            $menuItem->type = $item['type'];
            $menuItem->name = $item['name'];
            $menuItem->title = $item['title'];
            $menuItem->label = $item['label'];
            $menuItem->url = $item['url'];
            $menuItem->target = $item['target'];
            $menuItem->saveOrFail();
            $menu->assignMenuItem($menuItem);
        }**/
    }
}
