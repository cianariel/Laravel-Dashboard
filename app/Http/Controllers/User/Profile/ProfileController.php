<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Country;
use App\Rules\SanitizeHtml;
use App\Rules\PhoneRule;

/**
 * ProfileController
 *
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\User\Profile
 */
class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('xss.sanitization');
    }


    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        $countries = countries();
        $_countries = [];

        foreach ($countries as $country) {
            if ($countries->status = Country::ACTIVE) {
                $_countries[$country->code] = $country->name;
            }
        }

        $countries = $_countries;
        unset($_countries);

        $breadcrumbs = breadcrumbs([
            __('Dashboard')     => [
                'path'          => route('user.dashboard'),
                'active'        => false
            ],
            __('Edit Profile')  => [
                'path'          => route('user.profile.edit'),
                'active'        => true
            ]
        ]);

        return view('user.profile.edit', compact('site', 'breadcrumbs', 'user', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'profile_image'     => ['file', 'nullable','image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
            'company'           => ['nullable', 'string', 'max:50', new SanitizeHtml],
            'first_name'        => ['required', 'string', 'max:50', new SanitizeHtml],
            'last_name'         => ['required', 'string', 'max:50', new SanitizeHtml],
            'address'           => ['required', 'string', 'max:100', new SanitizeHtml],
            'address_2'         => ['nullable', 'string', 'max:100', new SanitizeHtml],
            'city'              => ['required', 'string', 'max:50', new SanitizeHtml],
            'state'             => ['required', 'string', 'max:50'],
            'zipcode'           => ['required', 'string', 'max:25'],
            'country'           => ['required', 'string', 'max:2', 'exists:system.countries,code'],
            'phone'             => ['nullable', 'string', new PhoneRule],
            'mobile_phone'      => ['required', 'string', new PhoneRule]
        ];

        $validatedData = $request->validate($rules);

        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        $user->company = $request->input('company');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->address_2 = $request->input('address_2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zipcode = $request->input('zipcode');
        $user->country = $request->input('country');
        $user->phone = $request->input('phone');
        $user->mobile_phone = $request->input('mobile_phone');
        $user->setup_completed = User::SETUP_COMPLETED;
        $user->save();

        if ($request->hasFile('profile_image')) {
            $image = $user->getMedia('profile_image')->first();

            if ($image) {
                $image->delete();
            }

            $file = $request->file('profile_image');

            $user->addMedia($file)
                ->toMediaCollection('profile_image');
        }

        flash(__('Successfully updated profile.'));
        return redirect()->route('user.profile.edit');
    }

    /**
     * Delete Profile Image from storage resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteMediaImage(Request $request, $id)
    {
        $user = auth()->user(User::GUARD)->select(['id'])->with(['media'])->firstOrFail();

        if ($profile_image = $user->getMedia('profile_image')->first()) {
            $profile_image->delete();
        }

        flash(__('Successfully deleted profile image'));
        return redirect()->route('user.profile.edit');
    }
}
