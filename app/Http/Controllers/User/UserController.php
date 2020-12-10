<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\User;
use App\Rules\SanitizeHtml;
use App\Rules\PhoneRule;
use Exception;

/**
 * UserController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\User
 */
class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if (
            $user->setup_completed != User::SETUP_COMPLETED
        ) {
            return redirect()->route('user.profile.edit');
        } else {
            $breadcrumbs = breadcrumbs([
                __('Dashboard')     => [
                    'path'          => route('user.dashboard'),
                    'active'        => false,
                ]
            ]);

            return view(
                'user.dashboard.index',
                compact('site', 'user', 'breadcrumbs')
            );
        }

        flash(__('Unauthorized Access.'))->error();
        return redirect()->route('login');
    }

    /**
     * Edit User Profile
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editProfile(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if (
            $user->setup_completed == User::SETUP_COMPLETED
            || $user->setup_completed == User::SETUP_IGNORED
        ) {
            $breadcrumbs = breadcrumbs([
                __('Dashboard')     => [
                    'path'          => route('user.dashboard'),
                    'active'        => false,
                ],
                __('Edit Profile')  => [
                    'path'          => route('user.profile.edit'),
                    'active'        => true,
                ]
            ]);

            return view(
                'user.profile.edit',
                compact('site', 'user', 'breadcrumbs')
            );
        }

        flash(__('Unauthorized Access.'))->error();
        return redirect()->route('login');
    }
}
