<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Rules\PhoneRule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Country;
use App\Models\System\State;
use App\Rules\SanitizeHtml;

/**
 * Edit Profile Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Profile
 */
class ProfileController extends AdminController
{
    /**
     * Show Edit User Profile Form
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = auth()->user(); // current user
        $status_types = User::STATUS_TYPES;

        if ($user->hasAnyRole([Role::SUPER_ADMIN])) {
            $roles = [Role::SUPER_ADMIN];
        } else {
            $roles = Role::where('status', Role::ACTIVE)->select(['name'])->cursor();
            $_roles = [];

            // Remove super admins or regular users roles.
            foreach ($roles as $role) {
                if ($role->name != Role::SUPER_ADMIN || $role->name != Role::USER) {
                    $_roles[] = $role->name;
                }
            }

            $roles = $_roles;
            unset($_roles);
        }

        $terms_types = User::TERMS_TYPES;
        $marketing_types = User::MARKETING_TYPES;
        $notification_types = User::NOTIFCATIONS;

        $countries = countries();
        $_countries = [];

        foreach ($countries as $country) {
            if ($countries->status = Country::ACTIVE) {
                $_countries[$country->code] = $country->name;
            }
        }

        $countries = $_countries;
        unset($_countries);


        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Edit Profile'  => ['path' => route('admin.profile.edit'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.profile.edit', compact('breadcrumbs', 'terms_types', 'marketing_types', 'status_types', 'notification_types', 'countries', 'user', 'roles'));
    }

    /**
     * Update Profile Profile
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->isMethod('put')) {
            $user = auth()->user(); // current user

            $rules = [
                'company'       => ['nullable', 'string', 'max:50', new SanitizeHtml],
                'first_name'    => ['required', 'string', 'max:50', new SanitizeHtml],
                'last_name'     => ['required', 'string', 'max:50', new SanitizeHtml],
                'address'       => ['nullable', 'string', 'max:100', new SanitizeHtml],
                'address_2'     => ['nullable', 'string', 'max:100', new SanitizeHtml],
                'city'          => ['nullable', 'string', 'max:50', new SanitizeHtml],
                'state'         => ['nullable', 'string', 'max:50', new SanitizeHtml],
                'zipcode'       => ['nullable', 'string', 'max:25', new SanitizeHtml],
                'country'       => ['nullable', 'string', 'max:2', new SanitizeHtml],
                'phone'         => ['nullable', 'string', new PhoneRule],
                'mobile_phone'  => ['required', 'string', new PhoneRule],
                'profile'       => ['nullable', 'string', 'max:2000', new SanitizeHtml],
                'skype'         => ['nullable', 'string', 'max:50', new SanitizeHtml],
                'linkedin'      => ['nullable', 'string', 'url'],
                'facebook'      => ['nullable', 'string', 'url'],
                'twitter'       => ['nullable', 'string', 'max:50', new SanitizeHtml],
                'notifications' => ['required', 'string', 'max:100', new SanitizeHtml],
                'terms'         => ['required', 'string', Rule::in(User::TERMS_TYPES)],
                'marketing'     => ['required', 'string', Rule::in(User::MARKETING_TYPES)]
            ];

            if (blank($request->input('email')) || $request->input('email') != $user->email) {
                $rules['email'] = ['required', 'email:rfc,dns', 'unique:system.users,email'];
            }

            if (filled($request->input('password'))) {
                $rules['password'] = ['required', 'string', 'confirmed', 'max:32'];
            }

            if (!$user->hasRole(Role::SUPER_ADMIN)) {
                $rules['status'] = ['required', 'string', Rule::in(User::STATUS_TYPES)];
                $rules['role']   = ['required', 'string', Rule::in($roles)];
            }

            if ($request->hasFile('profile_image')) {
                $rules['profile_image'] = ['nullable', 'file', 'image', 'dimensions:min_height=200', 'max:' . bit_convert(1, 'mb')];
            }


            $validateData = $request->validate($rules);

            $user->company = strip_tags($request->input('company'));
            $user->first_name = strip_tags($request->input('first_name'));
            $user->last_name = strip_tags($request->input('last_name'));
            $user->address = strip_tags($request->input('address'));
            $user->address_2 = strip_tags($request->input('address_2'));
            $user->city = strip_tags($request->input('city'));
            $user->state = strip_tags($request->input('state'));
            $user->country = strip_tags($request->input('country'));
            $user->zipcode = strip_tags($request->input('zipcode'));
            $user->setMetaField('profile', strip_tags($request->input('profile')), false);
            $user->setMetaField('skype', strip_tags($request->input('skype')), false);
            $user->setMetaField('linkedin', strip_tags($request->input('linkedin')), false);
            $user->setMetaField('facebook', strip_tags($request->input('facebook')), false);
            $user->setMetaField('twitter', strip_tags($request->input('twitter')), false);
            $user->notifications = strip_tags($request->input('notifications'));
            $user->terms = strip_tags($request->input('terms'));
            $user->marketing = strip_tags($request->input('marketing'));
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

            if ($request->hasFile('profile_image')) {
                $image = $user->getMedia('profile_image')->first();

                if ($image) {
                    $image->delete();
                }

                $file = $request->file('profile_image');

                $user->addMedia($file)
                     ->toMediaCollection('profile_image');
            }

            if ($user->saveOrFail()) {
                flash('Profile updated successfully.');
                return redirect()->route('admin.profile.edit');
            }
        }

        flash('Invaild Action.');
        return redirect()->route('admin.profile.edit');
    }


    /**
     * Delete Profile Image from storage resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMediaImage(Request $id)
    {
        $user = auth()->user()->select(['id'])->with(['media'])->firstOrFail();

        if ($profile_image = $user->getMedia('profile_image')->first()) {
            $profile_image->delete();
        }

        flash('Successfully deleted profile image');
        return redirect()->route('admin.profile.edit');
    }
}
