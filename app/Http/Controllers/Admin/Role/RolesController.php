<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Rules\SanitizeHtml;

/**
 * Admin Roles Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Role
 */
class RolesController extends AdminController
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

        $roles = Role::paginate($perPage);

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Roles'         => ['path' => route('admin.roles.index'),             'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.roles.index', compact('roles', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = Role::STATUS_TYPES;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Roles'         => ['path' => route('admin.roles.index'),             'active' => false],
            'Create Role'   => ['path' => route('admin.roles.create'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.roles.create', compact('status_types', 'breadcrumbs'));
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
            $roles = Role::ROLES;

            foreach ($roles as $index => $role) {
                if ($role == Role::SUPER_ADMIN) {
                    unset($roles[$index]);
                } elseif ($role == Role::USER) {
                    unset($roles[$index]);
                }
            }

            $rules = [
                'label'     => ['required', 'string', 'alpha_num', 'unique:system.roles,label', 'max:50'],
                'status'    => ['required', 'string', Rule::in(Role::STATUS_TYPES)]
            ];

            $validatData = $request->validate($rules);

            $role = new Role;
            $role->name = $name;
            $role->label = strip_tags($request->input('label'));
            $role->status = $request->input('status');

            if ($role->saveOrFail()) {
                flash('Successfully added role');
                return redirct()->route('admin.roles.edit', $role);
            }
        }

        flash('Invaild action');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.roles.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Role::where('id', $id)->exists()) {
            $role = Role::where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Roles'         => ['path' => route('admin.roles.index'),             'active' => false],
                'Edit Role'     => ['path' => route('admin.roles.edit', $role),       'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);
            return view('admin.roles.edit', compact('role', 'breadcrumbs'));
        }

        flash('Role does not exists.');
        return redirect()->route('admin.roles.index');
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
        if ($request->isMethod('put') && Role::where('id', $id)->exists()) {
            $role = Role::where('id', $id)->firstOrFail();

            $rules = [
                'label'     => ['required', 'string', 'max:100'],
                'status'    => ['required', 'string', Rule::in(Role::STATUS_TYPES)]
            ];

            if (!in_array($role->name, Role::ROLES)) {
                $validatData = $request->validate($rules);

                $role->label = strip_tags($request->input('label'));
                $role->status = $request->input('status');

                if ($role->saveOrFail()) {
                    flash('Successfully updated role.');
                    return redirect()->route('admin.roles.edit', $role);
                }
            } else {
                flash('Role is locked and can not be changed.');
                return redirect()->route('admin.roles.index');
            }
        }

        flash('Role does not exists.');
        return redirect()->route('admin.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Role::where('id', $id)->exists()) {
            $role = Role::where('id', $id)->firstOrFail();

            if (!in_array($role->name, Role::ROLES)) {
                $users = User::cursor();

                foreach ($users as $user) {
                    if ($user->hasRole($role->name)) {
                        $user->unassignrole($role->name);
                        $user->assignRole(Role::UNASSIGNED);
                        $user->save();
                    }
                }

                if ($role->delete()) {
                    flash('Successfully deleted role.');
                    return redirect()->route('admin.roles.index');
                }
            }
        }

        flash('Invaild action or role does not exist.');
        return redirect()->route('admin.roles.index');
    }
}
