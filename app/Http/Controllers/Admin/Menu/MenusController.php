<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Menu;
use App\Rules\SanitizeHtml;

/**
 * Admin Menu Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Menu
 */
class MenusController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site();
        $query = $request->query();
        $perPage = 10;

        if (filled($query)) {
            if ($request->has('per_page')) {
                $perPage = strip_tags(trim($query['per_page']));

                if (is_numeric($perPage)) {
                    $perPage = safe_integer($perPage);
                } else {
                    $perPage = 10;
                    $query['per_page'] = $per_page;
                }
            }
        }

        $menus = $site->menus()->paginate($perPage);

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Menus'         => ['path' => route('admin.menus.index'),             'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.menus.index', compact('breadcrumbs', 'menus', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu_types = Menu::MENU_TYPES;
        $status_types = Menu::STATUS_TYPES;
        $location_types = Menu::LOCATION_TYPES;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                            'active' => false],
            'Menus'         => ['path' => route('admin.menus.index'),             'active' => false],
            'Add Menu'      => ['path' => route('admin.menus.create'),            'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.menus.create', compact('breadcrumbs', 'menu_types', 'location_types', 'status_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'label'         => ['required', 'string', 'unique:system.menus,label', 'max:150'],
                'type'          => ['required', 'string', Rule::in(Menu::MENU_TYPES)],
                'location'      => ['required', 'string', Rule::in(Menu::LOCATION_TYPES)],
                'css_classes'   => ['nullable', 'string', 'max:190'],
                'status'        => ['required', 'string', Rule::in(Menu::STATUS_TYPES)]
            ];

            $validateData = $request->validate($rules);

            $name = Str::slug(strip_tags($request->input('label')));

            $menu = new Menu;
            $menu->name = $name;
            $menu->label = $request->input('label');
            $menu->type = $request->input('type');
            $menu->location = $request->input('location');
            $menu->css_classes = $request->input('css_classes');
            $menu->status = $request->input('status');

            if ($menu->saveOrFail()) {
                $site = site();
                $site->assignMenu($menu);

                flash('Successfully added menu.');
                return redirect()->route('admin.menus.edit', $menu);
            }
        }

        flash('Invaild action');
        return redirect()->route('admin.menus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (Menu::where('id', $id)->exists()) {
            $site = site();
            $menu = $site->menus()->where('id', $id)->firstOrFail();
            $query = $request->query();
            $perPage = 10;

            if (filled($query)) {
                if ($request->has('per_page')) {
                    $perPage = strip_tags(trim($query['per_page']));

                    if (is_numeric($perPage)) {
                        $perPage = safe_integer($perPage);
                    } else {
                        $perPage = 10;
                        $query['per_page'] = $per_page;
                    }
                }
            }

            $menu_items = $menu->menuItems()->orderBy('position', 'asc')->paginate($perPage);

            $breadcrumbs = [
                'Dashboard'                         => ['path' => admin_url(),                            'active' => false],
                'Menus'                             => ['path' => route('admin.menus.index'),             'active' => false],
                ucwords(strip_tags($menu->label))   => ['path' => route('admin.menus.show', $menu),       'active' => false],
                'Menu Items'                        => ['path' => route('admin.menus.menu_items.index', $menu), 'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.menus.items.index', compact('breadcrumbs', 'menu', 'menu_items', 'query'));
        }

        flash('Menu does not exist');
        return redirect()->route('admin.menus.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (Menu::where('id', $id)->exists()) {
            $site = site();
            $menu_types = Menu::MENU_TYPES;
            $status_types = Menu::STATUS_TYPES;
            $location_types = Menu::LOCATION_TYPES;
            $menu = $site->menus()->where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                            'active' => false],
                'Menus'         => ['path' => route('admin.menus.index'),             'active' => false],
                'Edit Menu'     => ['path' => route('admin.menus.edit', $menu),       'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.menus.edit', compact('breadcrumbs', 'menu', 'menu_types', 'location_types', 'status_types'));
        }

        flash('Menu does not exist.');
        return redirect()->route('admin.menus.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isMethod('put') && Menu::where('id', $id)->exists()) {
            $rules = [
                'type'          => ['required', 'string', Rule::in(Menu::MENU_TYPES)],
                'location'      => ['required', 'string', Rule::in(Menu::LOCATION_TYPES)],
                'css_classes'   => ['nullable', 'string', 'max:190'],
                'status'        => ['required', 'string', Rule::in(Menu::STATUS_TYPES)]
            ];

            $site = site();
            $menu = $site->menus()->where('id', $id)->firstOrFail();
            $label = $menu->label;

            if ($label != $request->input('label')) {
                $rules['label'] = ['required', 'string', 'unique:system.menus,label', 'max:150'];
                $name = Str::slug(strip_tags($request->input('label')));
            }

            $validateData = $request->validate($rules);

            if ($label != $request->input('label')) {
                $menu->label = $request->input('label');
                $menu->name = $name;
            }

            $menu->type = $request->input('type');
            $menu->location = $request->input('location');
            $menu->css_classes = $request->input('css_classes');
            $menu->status = $request->input('status');

            $menu->saveOrFail();

            flash('Successfully updated menu');
            return redirect()->route('admin.menus.edit', $menu);
        }

        flash('Menu does not exist.');
        return redirect()->route('admin.menus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Menu::where('id', $id)->exists()) {
            $site = site();
            $menu = $site->menus()->where('id', $id)->firstOrFail();
            $menu_items = $menu->menuItems()->cursor();

            // Delete any menu items
            foreach ($menu_items as $item) {
                $menu->unassignMenuItem($item);
                $item->delete();
            }



            // Reset any other menus
            $menus = $site->menus()->cursor();

            foreach ($menus as $_menu) {
                if ($_menu->parent_id == $menu->id) {
                    $_menu->parent_id = null;
                    $_menu->save();
                }
            }

            $site->unassignMenu($menu);
            $menu->delete();

            flash('Successfully deleted menu.');

            return redirect()->route('admin.menus.index');
        }

        flash('Menu does not exist.');
        return redirect()->route('admin.menus.index');
    }
}
