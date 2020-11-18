<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\System\User;
use App\Models\System\Role;
use App\Events\Office\Staff\InviteUser;
use App\Rules\SanitizeHtml;
use Exception;

/**
 * StaffsController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office\Staff
 */
class StaffsController extends Controller
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

        if($user->hasRole(Role::OWNER)) {

            $query = $request->query();
            $perPage = 10;
            $withTrashed = false;

            if (filled($query) && isset($query['per_page'])) {
                $perPage = strip_tags(trim($query['per_page']));

                if (is_numeric($perPage)) {
                    $perPage = safe_integer($perPage);
                } else {
                    $perPage = 10;
                    $query['per_page'] = $per_page;
                }
            }

            if(filled($query) && isset($query['with_trashed'])) {
                $withTrashed = strip_tags(trim($query['with_trashed']));

                if($withTrashed == 'true') {
                    $withTrashed = true;
                }
            }

            if($withTrashed === true) {
                $users = $site->users()->where('meta_fields->owner_id', $user->id)->withTrashed()->paginate($perPage);
            } else {
                $users = $site->users()->where('meta_fields->owner_id', $user->id)->paginate($perPage);
            }

            $breadcrumbs = breadcrumbs(
                [
                __('Dashboard') => [
                    'path'      => route('office.dashboard'),
                    'active'    => false,
                ],
                __('Staff')     => [
                    'path'      => route('office.staff.index'),
                    'active'    => true
                ]
                ]
            );

            return view('office.staff.index', compact('site', 'user', 'breadcrumbs', 'users', 'withTrashed', 'query'));
        }

        flash(__('Unauthorized Access.'));
        return redirect()->route('office.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER)) {

            $breadcrumbs = breadcrumbs(
                [
                __('Dashboard') => [
                    'path'      => route('office.dashboard'),
                    'active'    => false,
                ],
                __('Staff')     => [
                    'path'      => route('office.staff.index'),
                    'active'    => false
                ],
                __('Invite')    => [
                    'path'      => route('office.staff.create'),
                    'active'    => true
                ]
                ]
            );

            return view('office.staff.create', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized Access.'));
        return redirect()->route('office.dashboard');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($user->hasRole(Role::OWNER)) {

            $rules = [
                'first_name'    => ['required', 'string', 'max:100', new SanitizeHtml],
                'last_name'     => ['required', 'string', 'max:100', new SanitizeHtml],
                'email'         => ['required', 'email', 'unique:system.users,email', new SanitizeHtml],
            ];

            $validatedData = $request->validate($rules);

            $guestUser = new User;
            $guestUser->uuid = Str::uuid();
            $guestUser->username = unique_username(Role::GUEST);
            $guestUser->email = $request->input('email');
            $guestUser->password = Hash::make(Str::random(16));
            $guestUser->first_name = $request->input('first_name');
            $guestUser->last_name = $request->input('last_name');
            $guestUser->status = User::INACTIVE;
            $guestUser->setMetaField('owner_id', $user->id, false);
            $guestUser->invite_code = unique_invite_code();
            $guestUser->save();

            $guestUser->assignRole(Role::GUEST);
            $site->assignUser($guestUser);

            event(new InviteUser($user, $guestUser));
            flash(__('Successfully invited user.'));
            return redirect()->route('office.staff.index');
        }

        flash(__('Unauthorized Access.'));
        return redirect()->route('office.dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
    }
}
