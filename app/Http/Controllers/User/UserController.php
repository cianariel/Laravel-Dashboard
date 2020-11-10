<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Exception;

/**
 * UserController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Controllers\User
 */
class UserController extends BaseController
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

            return redirect()->route('user.profile.edit');
        }

        flash(__('Unauthorized Access.'))->error();
        return redirect()->route('login');
    }

    /**
     * Edit User Profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->setup_completed == User::SETUP_COMPLETED
            || $user->setup_completed == User::SETUP_IGNORED) {

            $breadcrumbs = breadcrumbs([

            ]);

            return view('');
        }

        flash(__('Unauthorized Access.'))->error();
        return redirect()->route('login');
    }
}
