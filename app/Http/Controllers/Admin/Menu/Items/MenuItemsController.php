<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu\Items;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Menu;
use App\Models\System\MenuItem;
use App\Rules\SanitizeHtml;

/**
 * Admin Menu Items Resource Controller
 *
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\Menu\Items
 */
class MenuItemsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        return redirect()->route('admin.menus.show', $id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        if (Menu::where('id', $id)->exists()) {
            $site = site();
            $menu = $site->menus()->where('id', $id)->firstOrFail();
            $menu_item_types = MenuItem::MENU_ITEM_TYPES;
            $target_types = MenuItem::TARGET_TYPES;
            $parents = $menu->menuItems()->whereNull('parent_id')
                ->where('type', MenuItem::PARENT_ITEM)
                ->select(['id', 'label'])->cursor();
            $_parents = [];
            $_parents = [
                '' => ''
            ];

            foreach ($parents as $parent) {
                $_parents[$parent->id] = $parent->label;
            }

            $parents = $_parents;

            $breadcrumbs = [
                'Dashboard'                             => ['path' => admin_url(),                                'active' => false],
                'Menus'                                 => ['path' => route('admin.menus.index'),                 'active' => false],
                ucwords(strip_tags($menu->label))       => ['path' => route('admin.menus.menu_items.index', $menu),     'active' => false],
                'Add Menu Item'                         => ['path' => route('admin.menus.menu_items.create', $menu),    'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.menus.items.create', compact('breadcrumbs', 'menu', 'parents', 'menu_item_types', 'target_types'));
        }

        flash('Menu does not exist.');
        return redirect()->route('admin.menus.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        if (Menu::where('id', $id)->exists()) {
            $site = site();
            $menu = $site->menus()->where('id', $id)->firstOrFail();
            $css_classes = null;

            $rules = [
                'parent_id'     => ['nullable', 'integer', 'exists:system.menu_items,id'],
                'type'          => ['required', 'string', Rule::in(MenuItem::MENU_ITEM_TYPES)],
                'label'         => ['required', 'string', 'max:150', new SanitizeHtml()],
                'title'         => ['nullable', 'string', 'max:190', new SanitizeHtml()],
                'url'           => ['required', 'string'],
                'target'        => ['required', 'string', Rule::in(MenuItem::TARGET_TYPES)],
                'css_classes'   => ['nullable', 'string', 'max:190'],
                'position'      => ['nullable', 'integer']
            ];

            $validatedData = $request->validate($rules);

            $name = Str::slug(strip_tags($request->input('label')));
            $prefix = $name;
            $i = 0;

            while (MenuItem::where('name', $name)->exists()) {
                $name = $name . '-' . $i;
                $i++;
            }

            if (filled($request->input('css_classes'))) {
                $css_classes = strip_tags($request->input('css_classes'));
                $css_classes = str_replace(' ', '', $css_classes);
            }

            $menuItem = new MenuItem();
            $menuItem->name = $name;
            $menuItem->type = $request->input('type');
            $menuItem->label = $request->input('label');
            $menuItem->title = $request->input('title');
            $menuItem->url = $request->input('url');
            $menuItem->target = $request->input('target');
            $menuItem->css_classes = $css_classes;

            if (filled($request->input('parent_id')) && is_numeric($request->input('parent_id'))) {
                $menuItem->parent_id = safe_integer($request->input('parent_id'));
            }

            if (filled($request->input('position')) && is_numeric($request->input('position'))) {
                $menuItem->position = safe_integer($request->input('position'));
            }

            $menuItem->saveOrFail();
            $menu->assignMenuItem($menuItem);

            flash('Successfully added menu item.');
            return redirect()->route('admin.menus.menu_items.edit', [$menu, $menuItem]);
        }

        flash('Menu does not exist.');
        return redirect()->route('admin.menus.index', $menu);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $menu
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $menu, $id)
    {
        return redirect()->route('admin.menus.menu_items.edit', [$menu, $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $menu
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $menu, $id)
    {
        if (Menu::where('id', $menu)->exists() && MenuItem::where('id', $id)->exists()) {
            $site = site();
            $menu = $site->menus()->where('id', $menu)->firstOrFail();
            $menu_item = $menu->menuItems()->where('id', $id)->firstOrFail();

            $menu_item_types = MenuItem::MENU_ITEM_TYPES;
            $target_types = MenuItem::TARGET_TYPES;
            $parents = $menu->menuItems()
                ->whereNull('parent_id')
                ->where('type', MenuItem::PARENT_ITEM)
                ->where('id', '!=', $menu_item->id)
                ->select(['id', 'label'])->cursor();
            $_parents = [];
            $_parents = [
                '' => ''
            ];

            foreach ($parents as $parent) {
                $_parents[$parent->id] = $parent->label;
            }

            $parents = $_parents;

            $breadcrumbs = [
                'Dashboard'                             => ['path' => admin_url(),                                                  'active' => false],
                'Menus'                                 => ['path' => route('admin.menus.index'),                                   'active' => false],
                ucwords(strip_tags($menu->label))       => ['path' => route('admin.menus.menu_items.index', $menu),                 'active' => false],
                'Edit Menu Item'                        => ['path' => route('admin.menus.menu_items.edit', [$menu, $menu_item]),    'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.menus.items.edit', compact('breadcrumbs', 'menu', 'menu_item', 'menu_item_types', 'target_types', 'parents'));
        }

        flash('Menu or item does not exist.');
        return redirect()->route('admin.menus.index', $menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $menu, $id)
    {
        if ($request->isMethod('put') && Menu::where('id', $menu)->exists() && MenuItem::where('id', $id)->exists()) {
            $site = site();
            $menu = $site->menus()->where('id', $menu)->firstOrFail();
            $menuItem = $menu->menuItems()->where('id', $id)->firstOrFail();
            $css_classes = null;

            $rules = [
                'parent_id'     => ['nullable', 'integer', 'exists:system.menu_items,id'],
                'type'          => ['required', 'string', Rule::in(MenuItem::MENU_ITEM_TYPES)],
                'label'         => ['required', 'string', 'max:150', new SanitizeHtml()],
                'title'         => ['nullable', 'string', 'max:190', new SanitizeHtml()],
                'url'           => ['required', 'string'],
                'target'        => ['required', 'string', Rule::in(MenuItem::TARGET_TYPES)],
                'css_classes'   => ['nullable', 'string', 'max:190'],
                'position'      => ['nullable', 'integer']
            ];

            $validatedData = $request->validate($rules);

            if ($request->input('label') && $menuItem->label) {
                $name = Str::slug(strip_tags($request->input('label')));
                $prefix = $name;
                $i = 0;

                while (MenuItem::where('name', $name)->exists()) {
                    $name = $name . '-' . $i;
                    $i++;
                }

                $menuItem->name = $name;
            }

            if (filled($request->input('css_classes'))) {
                $css_classes = strip_tags($request->input('css_classes'));
                $css_classes = str_replace(' ', '', $css_classes);
            }

            $menuItem->type = $request->input('type');
            $menuItem->label = $request->input('label');
            $menuItem->title = $request->input('title');
            $menuItem->url = $request->input('url');
            $menuItem->target = $request->input('target');
            $menuItem->css_classes = $css_classes;

            if (filled($request->input('parent_id')) && is_numeric($request->input('parent_id'))) {
                $menuItem->parent_id = safe_integer($request->input('parent_id'));
            }

            if (filled($request->input('position')) && is_numeric($request->input('position'))) {
                $menuItem->position = safe_integer($request->input('position'));
            }

            $menuItem->saveOrFail();

            flash('Successfully updated menu item.');
            return redirect()->route('admin.menus.menu_items.edit', [$menu, $menuItem]);
        }

        flash('Invalid action or menu item does not exist.');
        return redirect()->route('admin.menus.index', $menu);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $menu
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $menu, $id)
    {
        if ($request->isMethod('delete') && Menu::where('id', $menu)->exists() && MenuItem::where('id', $id)->exists()) {
            $site = site();
            $menu = $site->menus()->where('id', $menu)->firstOrFail();
            $menu_item = $menu->menuItems()->where('id', $id)->firstOrFail();

            $menu_items = $menu->menuItems()->where('parent_id', $menu_item->id)->cursor();

            foreach ($menu_items as $item) {
                $item->parent_id = null;
                $item->saveOrFail();
            }

            $menu->unassignMenuItem($menu_item);
            $menu_item->delete();

            flash('Successfully deleted menu item.');
            return redirect()->route('admin.menus.menu_items.index', $menu);
        }

        flash('Invalid action or menu item does not exist.');
        return redirect()->route('admin.menus.index', $menu);
    }
}
