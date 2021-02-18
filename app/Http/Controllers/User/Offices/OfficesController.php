<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Offices;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Office;
use Exception;

/**
 * OfficesController
 *
 * @author    Taylor <sykestaylor122@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\User\Offices
 */
class OfficesController extends BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('force.https');
        $this->middleware('auth');
        $this->middleware('role:' . Role::USER);
        $this->middleware('user:' . User::GUARD);

    }

    private function checkCompletedProfile()
    {
        $user = auth()->guard(User::GUARD)->user();

        if ($user->setup_completed != User::SETUP_COMPLETED) {
            flash(__('Unauthorized access.'));
            return redirect('/');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->checkCompletedProfile();

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('user.dashboard'),
                'active'    => false
            ],
            __('Offices')     => [
                'path'      => route('user.offices.index'),
                'active'    => true
            ]
        ]);

        return view('user.offices.index',
            compact('breadcrumbs')
        );

    }

    /**
     * Add an office page
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $this->checkCompletedProfile();
        $user = auth()->guard(User::GUARD)->user();

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('user.dashboard'),
                'active'    => false
            ],
            __('Offices')     => [
                'path'      => route('user.offices.index'),
                'active'    => false
            ],
            __('Add')     => [
                'path'      => route('user.offices.add'),
                'active'    => true
            ]
        ]);

        $offices = Office::whereDoesntHave('users', function($query) use($user) {
            $query->where('id', $user->id);
        })->get();

        return view('user.offices.add',
            compact('breadcrumbs', 'offices')
        );
    }

    
}
