<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Setting;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Country;
use App\Models\System\Office;
use App\Models\System\State;
use App\Models\System\Role;
use App\Models\System\User;
use App\Rules\SanitizeHtml;
use App\Rules\PhoneRule;
use Exception;

/**
 * SettingsController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office\Setting
 */
class SettingsController extends BaseController
{
    /**
     * Edit office settings.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER) ) {

            if($user->setup_completed == User::SETUP_COMPLETED) {

                return redirect()->route('office.settings.edit.general.section', ['section' => 'office_info']);
            }

            return redirect()->route('office.setup.account');
        }

        flash(__('Unauthorized access.'));
        return redirect()->route('office.dashboard');
    }

    /**
     * Save office settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER)) {

            $rules = [
                'office'        => ['required', 'string', 'max:100', new SanitizeHtml],
                'first_name'    => ['required', 'string', 'max:100', new SanitizeHtml],
                'last_name'     => ['required', 'string', 'max:100', new SanitizeHtml],
                'address'       => ['required', 'string', 'max:100', new SanitizeHtml],
                'address_2'     => ['nullable', 'string', 'max:100', new SanitizeHtml],
                'city'          => ['required', 'string', 'max:100', new SanitizeHtml],
                'zipcode'       => ['required', 'string', 'max:25', new SanitizeHtml],
                'state'         => ['required', 'string', 'max:100', new SanitizeHtml],
                'country'       => ['required', 'string', 'exists:system.countries,code', Rule::in(['US'])],
                'phone'         => ['required', 'string', new PhoneRule, new SanitizeHtml],
                'mobile_phone'  => ['required', 'string', new PhoneRule, new SanitizeHtml]
            ];

            $validatedData = $request->validate($rules);

            $office = $user->offices()->first();

            // Add office profile details
            $office->setMetaField('owner', ['first_name' => $request->input('first_name'), 'last_name' => $request->input('last_name')], false);
            $address = [
                'address'   => $request->input('address'),
                'address_2' => $request->input('address_2'),
                'city'      => $request->input('city'),
                'zipcode'   => $request->input('zipcode'),
                'state'     => $request->input('state'),
                'country'   => $request->input('country')
            ];

            $office->setMetaField('location', $address, false);
            $office->setMetaField('phone', clean_phone($request->input('phone')), false);
            $office->setMetaField('mobile_phone', clean_phone($request->input('mobile_phone')), false);
            $office->save();
            $user->save();

            flash(__('Successfully updated settings.'));
            return redirect()->route('office.settings.edit');
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Edit rep visits settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editRepVisitsSettings(Request $request, string $view = '')
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER) ) {

            $office = $user->offices()->first();

            if(filled($section)) {
                $section = Str::lower(strip_tags(trim($section)));
            }

            $breadcrumbs = breadcrumbs(
                [
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('Rep Visits')  => [
                    'path'          => route('office.settings.edit.general'),
                    'active'        => true
                ]
                ]
            );

            return view(
                'office.settings.rep_visits',
                compact('site', 'user', 'breadcrumbs', 'section', 'office')
            );
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }


    /**
     * Edit offices general settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editGeneralSettings(Request $request, string $section = '')
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER) ) {

            $breadcrumbs = breadcrumbs(
                [
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('General')  => [
                    'path'          => route('office.settings.edit.general'),
                    'active'        => true
                ]
                ]
            );

            if(filled($section)) {
                $section = Str::lower(strip_tags(trim($section)));
            }

            $office = $user->offices()->first();

            switch($section) {
            case 'office_info':
                $countries = countries(false);
                $_countries = [];

                foreach ($countries as $country) {
                    if ($countries->status = Country::ACTIVE) {
                        $_countries[$country->code] = $country->name;
                    }
                }

                $countries = $_countries;

                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office', 'countries')
                );
            case 'holidays':
                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office')
                );
            case 'appointments':
                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office')
                );
            case 'office_hours':
                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office')
                );
            case 'visitation_rules':
                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office')
                );
            case 'rep_assignment':
                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office')
                );
            case 'recurring_appointments':
                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office')
                );
            default:
                $countries = countries(false);
                $_countries = [];

                foreach ($countries as $country) {
                    if ($countries->status = Country::ACTIVE) {
                        $_countries[$country->code] = $country->name;
                    }
                }

                $countries = $_countries;

                return view(
                    'office.settings.general',
                    compact('site', 'user', 'breadcrumbs', 'section', 'office', 'countries')
                );
            }
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Edit offices settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editOfficesSettings(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER) ) {

            $breadcrumbs = breadcrumbs(
                [
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('Offices')       => [
                    'path'          => route('office.settings.edit.offices'),
                    'active'        => true
                ]
                ]
            );

            return view('office.settings.offices', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Edit office calendar settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editCalendarSettings(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER)) {

            $breadcrumbs = breadcrumbs(
                [
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('Calendar')      => [
                    'path'          => route('office.settings.edit.calendar'),
                    'active'        => true
                ]
                ]
            );

            return view('office.settings.calendar', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Edit office settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editSubscriptionSettings(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER)) {

            $breadcrumbs = breadcrumbs(
                [
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('Subscription')  => [
                    'path'          => route('office.settings.edit.subscription'),
                    'active'        => true
                ]
                ]
            );

            return view('office.settings.subscription', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }
}
