<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Rules\PhoneRule;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\Blog;
use App\Models\System\Post;
use App\Models\System\Page;
use App\Models\System\Setting;
use App\Rules\SanitizeHtml;

/**
 * Admin Users Resource Controller
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\User
 */
class UsersController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site();
        $query = $request->query();
        $perPage = 10;
        $withTrashed = false;

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

            if ($request->has('with_trashed')) {
                $with_trashed  = strip_tags(trim($query['with_trashed']));

                if ($with_trashed == 'true') {
                    $withTrashed  = true;
                }
            }
        }

        if ($withTrashed === true) {
            $users = $site->users()->withTrashed()->paginate($perPage);
        } else {
            $users = $site->users()->paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Users'         => ['path' => route('admin.users.index'),             'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.users.index', compact('users', 'breadcrumbs', 'query', 'withTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = User::STATUS_TYPES;

        $site = site();
        $roles = Role::where('status', Role::ACTIVE)->select(['name'])->cursor();

        // Remove super admins or regular users roles.
        foreach ($roles as $role) {
            if ($role->name != Role::SUPER_ADMIN || $role->name != Role::USER) {
                $_roles[] = $role->name;
            }
        }

        $roles = $_roles;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Users'         => ['path' => route('admin.users.index'),             'active' => false],
            'Add User'      => ['path' => route('admin.users.create'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.users.create', compact('roles', 'breadcrumbs', 'status_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $site = site();
            $roles = Role::where('status', Role::ACTIVE)->select(['name'])->cursor();

            // Remove super admins or regular users roles.
            foreach ($roles as $role) {
                if ($role->name != Role::SUPER_ADMIN || $role->name != Role::USER) {
                    $_roles[] = $role->name;
                }
            }

            $roles = $_roles;
            unset($_roles); // cleanup

            $rules = [
                'email'         => ['required', 'email:rfc,dns', 'unique:system.users,email'],
                'password'      => ['required', 'string', 'confirmed', 'max:32'],
                'company'       => ['nullable', 'string', 'max:50', new SanitizeHtml()],
                'first_name'    => ['required', 'string', 'max:50', new SanitizeHtml()],
                'last_name'     => ['required', 'string', 'max:50', new SanitizeHtml()],
                'phone'         => ['nullable', 'string', new PhoneRule()],
                'mobile_phone'  => ['nullable', 'string', new PhoneRule()],
                'role'          => ['required', 'string', Rule::in($roles)],
                'status'        => ['required', 'string', Rule::in(User::STATUS_TYPES)],
            ];

            $validateData = $request->validate($rules);

            $role = $request->input('role');
            $user = new User();
            $user->uuid = Str::uuid();
            $user->username = unique_username($role);
            $user->email = strtolower($request->input('email'));
            $user->password = Hash::make($request->input('password'));
            $user->company = strip_tags($request->input('company'));
            $user->first_name = strip_tags($request->input('first_name'));
            $user->last_name = strip_tags($request->input('last_name'));
            $user->phone = strip_tags($request->input('phone'));
            $user->mobile_phone = strip_tags($request->input('mobile_phone'));
            $user->status = $request->input('status');
            $user->terms = User::TERMS_ACCEPTED;

            if ($user->save()) {
                // Assign role to user
                $user->assignRole($role);

                // Assign user to site.
                $site->assignUser($user);

                return redirect()->route('admin.users.edit', $user);
            } else {
                logger('Unexpected error occured. Failed to create user in admin.');
                flash('Unexpected error occured. Failed to create user');
                return redirect()->route('admin.users.index');
            }
        }

        flash('Invaild action.');
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (User::where('id', $id)->exists()) {
            $site = site();
            $user = $site->users()->where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Users'         => ['path' => route('admin.users.index'),             'active' => false],
                'User Details'  => ['path' => route('admin.users.show', $user),       'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.users.show', compact('user', 'breadcrumbs'));
        }

        flash('User does not exist.');
        return redirect()->route('admin.users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (User::where('id', $id)->exists()) {
            $site = site();
            $roles = Role::where('status', Role::ACTIVE)->select(['name'])->cursor();
            $status_types = User::STATUS_TYPES;
            $site = site();
            $user = $site->users()->where('id', $id)->firstOrFail();

            // Remove super admins or regular users roles.
            foreach ($roles as $role) {
                if ($role->name != Role::SUPER_ADMIN || $role->name != Role::USER) {
                    $_roles[] = $role->name;
                }
            }

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                      'active' => false],
                'Users'         => ['path' => route('admin.users.index'),             'active' => false],
                'Edit User'     => ['path' => route('admin.users.edit', $user),       'active' => true],
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.users.edit', compact('user', 'breadcrumbs', 'roles', 'status_types'));
        }

        flash('User does not exist.');
        return redirect()->route('admin.users.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->isMethod('put') && User::where('id', $id)->exists()) {
            $roles = Role::ROLES;

            foreach ($roles as $index => $role) {
                if ($role == Role::SUPER_ADMIN) {
                    unset($roles[$index]);
                } elseif ($role == Role::USER) {
                    unset($roles[$index]);
                }
            }

            // Rules.
            $rules = [
                'email'         => ['required', 'email:rfc,dns', 'exists:system.users,email'],
                'company'       => ['nullable', 'string', 'max:50', new SanitizeHtml()],
                'first_name'    => ['required', 'string', 'max:50', new SanitizeHtml()],
                'last_name'     => ['required', 'string', 'max:50', new SanitizeHtml()],
                'phone'         => ['nullable', 'string', 'max:16', new PhoneRule()],
                'mobile_phone'  => ['nullable', 'string', 'max:16', new PhoneRule()],
            ];

            if (filled($request->input('password'))) {
                $rules['password'] = ['required', 'string', 'confirmed', 'max:32'];
            }

            $site = site();
            $user = $site->users()->where('id', $id)->firstOrFail();

            if (!$user->hasRole(Role::SUPER_ADMIN)) {
                $rules['status'] = ['required', 'string', Rule::in(User::STATUS_TYPES)];
                $rules['role']   = ['required', 'string', Rule::in($roles)];
            }

            $validateData = $request->validate($rules);

            $user->company = strip_tags($request->input('company'));
            $user->first_name = strip_tags($request->input('first_name'));
            $user->last_name = strip_tags($request->input('last_name'));
            $user->phone = strip_tags($request->input('phone'));
            $user->mobile_phone = strip_tags($request->input('mobile_phone'));

            // Roles and status.
            if (!$user->hasRole(Role::SUPER_ADMIN)) {
                // Update Role
                if (!$user->hasRole($request->input('role'))) {
                    foreach ($user->roles()->cursor() as $role) {
                        $user->removeRole($role);
                    }

                    $user->username = unique_username($request->input('role'));
                    $user->assignRole($request->input('role'));
                }

                $user->status = $request->input('status');
            }

            if (filled($request->input('password'))) {
                $user->password = Hash::make($request->input('password'));
            }

            if ($user->saveOrFail()) {
                flash('User updated successfully.');
                return redirect()->route('admin.users.edit', $user);
            }
        }

        flash('User does not exist or invalid action');
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && User::where('id', $id)->exists()) {
            $site = site();
            $user = $site->users()->where('id', $id)->firstOrFail();


            if (!$user->hasRole(Role::SUPER_ADMIN)) {
                if ($user->delete()) {
                    flash('Successfully deleted user.');
                    return redirect()->route('admin.users.index');
                } else {
                    flash('Error occured deleting user.');
                    return redirect()->route('admin.users.index');
                }
            } else {
                flash('Sorry, super admin user can not deleted.');
                return redirect()->route('admin.users.index');
            }
        }

        flash('User does not exist or invalid action');
        return redirect()->route('admin.users.index');
    }

    /**
     * Restores user resource from storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->isMethod('put') && User::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $user = $site->users()->withTrashed()->where('id', $id)->firstOrFail();

            if ($user) {
                $user->restore();

                flash('Successfully restored user from trash.');
                return redirect()->route('admin.users.edit', $user);
            }
        }

        flash('Invaild action or user does not exist.');
        return redirect()->route('admin.users.index');
    }

    /**
     * Deletes user from storage forever
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->isMethod('delete') && User::withTrashed()->where('id', $id)->exists()) {
            $site = site();
            $user = $site->users()->withTrashed()->where('id', $id)->firstOrFail();

            if ($user->hasRole(Role::SUPER_ADMIN)) {
                flash('Sorry, super admin user can not deleted.');
                return redirect()->route('admin.users.index');
            } else {
                // Can only delete regular users.
                if (!$user->hasRole(Role::USER)) {
                    // Cleanup
                    $blogs = $site->blogs()->cursor();
                    $pages = $site->pages()->withTrashed()->where('user_id', $user->id)->cursor();
                    $settings = $user->settings()->cursor();
                    $images = $user->getMedia('images');
                    $roles = $user->roles()->cursor();

                    // Blogs and Posts
                    if ($blogs->count() !== 0) {
                        foreach ($blogs as $blog) {
                            $posts = $blog->posts()->withTrashed()->where('user_id', $user->id)->cursor();

                            foreach ($posts as $post) {
                                $_images = $post->getMedia('images');

                                if ($_images->count() !== 0) {
                                    foreach ($_images as $_image) {
                                        $_image->delete();
                                    }
                                }

                                $blog->unassignPost($post);
                                $post->forceDelete();
                            }
                        }
                    }

                    // Pages
                    if ($pages->count() != 0) {
                        foreach ($pages as $page) {
                            $_images = $page->getMedia('images');

                            if ($_images->count() !== 0) {
                                foreach ($_images as $_image) {
                                    $_image->delete();
                                }
                            }

                            $site->unassignPage($page);
                            $page->forceDelete();
                        }
                    }

                    // Settings
                    if ($settings->count() !== 0) {
                        foreach ($settings as $setting) {
                            $user->unassignSetting($setting);
                            $setting->forceDelete();
                        }
                    }

                    // Images
                    if ($images->count() !== 0) {
                        foreach ($images as $image) {
                            $image->delete();
                        }
                    }

                    // Roles.
                    foreach ($roles as $role) {
                        $user->removeRole($role);
                    }

                    // Remove user forever.
                    $site->unassignUser($user);
                    $user->forceDelete();

                    flash('Successfully deleted user forever');
                    return redirect()->route('admin.users.index');
                } else {
                    flash('Sorry can not delete regular user accounts.');
                    return redirect()->route('admin.users.index');
                }
            }
        }

        flash('Invaild action or user does not exist.');
        return redirect()->route('admin.users.index');
    }
}
