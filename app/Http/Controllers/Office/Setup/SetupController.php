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
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requst)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER) && $user->setup_completed != User::SETUP_COMPLETED) {

            $countries = countries(false);
            $_countries = [];

            foreach ($countries as $country) {
                if ($countries->status = Country::ACTIVE) {
                    $_countries[$country->code] = $country->name;
                }
            }

            $countries = $_countries;

            return view('office/setup/profile',
                        compact('site', 'user', 'countries'));

        } elseif($user->hasRole(Role::GUEST)) {

            flash(__('Please contact office owner. Account requires profile setup.'));
            return redirect('/');
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }
}
