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
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Office\Setting
 */
class SettingsController extends BaseController
{
    /**
     * Edit office settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $requst)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER) ) {

            if($user->setup_completed == User::SETUP_COMPLETED) {

                $office = $user->offices()->first();

                $countries = countries(false);
                $_countries = [];

                foreach ($countries as $country) {
                    if ($countries->status = Country::ACTIVE) {
                        $_countries[$country->code] = $country->name;
                    }
                }

                $countries = $_countries;

                $breadcrumbs = breadcrumbs([
                    __('Dashboard')        => [
                        'path'          => route('office.dashboard'),
                        'active'        => false
                    ],
                    __('Settings')     => [
                        'path'          => route('office.settings.edit'),
                        'active'        => true
                    ]
                ]);

                return view('office.settings.edit',
                            compact('site', 'user', 'breadcrumbs', 'countries', 'office'));
            }

            return redirect()->route('office.setup.account');
        }

        flash(__('Unauthorized access.'));
        return redirect()->route('office.dashboard');
    }
}
