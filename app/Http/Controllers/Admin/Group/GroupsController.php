<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Group;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Group;
use App\Rules\SanitizeHtml;

/**
 * Groups Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Group
 */
class GroupsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->query();
        $perPage = 10;

        if (filled($query) && array_key_exists('per_page', $query)) {
            $perPage = strip_tags(trim($query['per_page']));

            if (is_numeric($perPage)) {
                $perPage = safe_integer($perPage);
            } else {
                $perPage = 10;
                $query['per_page'] = $per_page;
            }
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Groups'        => ['path' => route('admin.groups.index'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        $groups = Group::paginate($perPage);
        return view('admin.groups.index', compact('groups', 'breadcrumbs', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $visible_types = Group::VISIBLE_TYPES;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Groups'        => ['path' => route('admin.groups.index'),            'active' => false],
            'New Group'     => ['path' => route('admin.groups.create'),           'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.groups.create', compact('visible_types', 'breadcrumbs'));
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
                'label'     => ['required', 'string', 'alpha_num', 'unique:system.groups,label' ,'max:100', new SanitizeHtml],
                'visible'   => ['required', 'string', Rule::in(Group::VISIBLE_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $group = new Group;
            $group->name = Str::slug($request->input('label'));
            $group->label = $request->input('label');
            $group->visible = $request->input('visible');
            $group->lock = Group::UNLOCKED;
            $group->save();

            flash('Successfully created group.');
            return redirect()->route('admin.groups.edit', $group);
        }

        flash('Invaild action');
        return redirect()->route('admin.groups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.groups.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Group::where('id', $id)->exists()) {
            $lock_types = Group::LOCK_TYPES;
            $visible_types = Group::VISIBLE_TYPES;
            $group = Group::where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Groups'        => ['path' => route('admin.groups.index'),            'active' => false],
                'Edit Group'    => ['path' => route('admin.groups.edit', $group),     'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);
            return view('admin.groups.edit', compact('group', 'breadcrumbs', 'lock_types', 'visible_types'));
        }

        flash('Group does not exist.');
        return redirect()->route('admin.groups.index');
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
        if ($request->isMethod('put') && Group::where('id', $id)->exists()) {
            $rules = [
                'label'     => ['required', 'string', 'alpha_num', 'max:100', new SanitizeHtml],
                'visible'   => ['required', 'string', Rule::in(Group::VISIBLE_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $group = Group::where('id', $id)->firstOrFail();

            $group->label = $request->input('label');
            $group->visible = $request->input('visible');
            $group->saveOrFail();

            flash('Successfully updated group.');
            return redirect()->route('admin.groups.edit', $group);
        }

        flash('Invaild action or group does not exist.');
        return redirect()->route('admin.groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Group::where('id', $id)->exists()) {
            $group = Group::where('id', $id)->firstOrFail();

            if ($group->lock == Group::UNLOCKED) {
                $group->delete();

                flash('Group deleted successfully.');
                return redirect()->route('admin.groups.index');
            } else {
                flash('Group is locked and can not be deleted.');
                return redirect()->route('admin.groups.index');
            }
        }

        flash('Invaild action or group does not exist.');
        return redirect()->route('admin.groups.index');
    }
}
