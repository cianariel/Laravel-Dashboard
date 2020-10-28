<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Http\Request;
use App\Models\System\User;

/**
 * OfficeController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\Office
 */
class OfficeController extends BaseController
{
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

        if($user->setup_completed == User::SETUP_COMPLETED
            || $user->setup_completed == User::SETUP_IGNORED) {

            $breadcrumbs = breadcrumbs([
                __('Dashboard')     => [
                    'path'          => route('office.dashboard'),
                    'active'        => true
                ]
            ]);

            return view('office.dashboard.index', compact('site', 'user', 'breadcrumbs'));
        } else {
            return redirect()->route('office.setup.account');
        }

        flash(__('Unauthorized Access.'))->error();
        return redirect()->route('login');
    }
}
