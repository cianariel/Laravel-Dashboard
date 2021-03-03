<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Setup;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Country;
use App\Models\System\State;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Office;
use App\Rules\CreditCardRule;
use App\Rules\PhoneRule;
use App\Rules\SanitizeHtml;
use Exception;

/**
 * SetupController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Office\Setup
 */
class SetupController extends BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('xss.sanitization');
        $this->middleware('force.https');
        $this->middleware('auth');
        $this->middleware('role:' . Role::OWNER);
        $this->middleware('user:' . User::GUARD);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->setup_completed != User::SETUP_COMPLETED) {
            $countries = countries(false);
            $_countries = [];

            foreach ($countries as $country) {
                if ($countries->status = Country::ACTIVE) {
                    $_countries[$country->code] = $country->name;
                }
            }

            $countries = $_countries;

            $breadcrumbs = breadcrumbs([
                __('Dashboard') => [
                    'path'      => route('office.dashboard'),
                    'active'    => false
                ],
                __('Setup')     => [
                    'path'      => route('office.setup.account'),
                    'active'    => true
                ]
            ]);

            return view('office.setup.profile',
                compact('site', 'user', 'countries', 'breadcrumbs')
            );
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Save office profile
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveOfficeProfile(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->setup_completed != User::SETUP_COMPLETED) {
            $rules = [
                'office'        => ['required', 'string', 'max:100', new SanitizeHtml()],
                'first_name'    => ['required', 'string', 'max:100', new SanitizeHtml()],
                'last_name'     => ['required', 'string', 'max:100', new SanitizeHtml()],
                'address'       => ['required', 'string', 'max:100', new SanitizeHtml()],
                'address_2'     => ['nullable', 'string', 'max:100', new SanitizeHtml()],
                'city'          => ['required', 'string', 'max:100', new SanitizeHtml()],
                'zipcode'       => ['required', 'string', 'max:25', new SanitizeHtml()],
                'state'         => ['required', 'string', 'max:100', new SanitizeHtml()],
                'country'       => ['required', 'string', 'exists:system.countries,code', Rule::in(['US'])],
                'phone'         => ['required', 'string', new PhoneRule(), new SanitizeHtml()],
                'mobile_phone'  => ['required', 'string', new PhoneRule(), new SanitizeHtml()]
            ];

            $validatedData = $request->validate($rules);

            $office = new Office();
            $office->uuid = Str::uuid();
            $office->name = unique_name('office', $request->input('office'));
            $office->label = $request->input('office');
            $office->status = Office::ACTIVE;
            $office->save();

            // References
            $user->assignOffice($office);
            $site->assignOffice($office);

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

            $user->setup_completed = User::SETUP_COMPLETED;
            $user->save();

            return redirect()->route('office.dashboard');
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }
}
