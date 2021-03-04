<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front\Invitation;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\System\User;
use App\Models\System\Role;
use App\Rules\SanitizeHtml;
use App\Rules\PhoneRule;
use Exception;

/**
 * InvitationController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Front\Invitation
 */
class InvitationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('xss.sanitization');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $invite_code = '')
    {
        $site = site(config('app.base_domain'));

        if ($site->users()->where('invite_code', $invite_code)->exists()) {
            $user = $site->users()->where('invite_code', $invite_code)->first();
            $owner = office_owner($user);
            $office = $owner->offices()->first();

            if ($user && $owner) {
                if ($user->setup_completed == User::SETUP_INCOMPLETE) {
                    return view('frontend.invitation.show', compact('site', 'user', 'owner', 'office'));
                } else {
                    return redirect()->route('login');
                }
            }
        }

        return redirect('/');
    }

    /**
     * Accept Invitation
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, string $invite_code = '')
    {
        if ($site->users()->where('invite_code', $invite_code)->exists()) {
            $rules = [
                'first_name'    => ['required', 'string', 'max:50', new SanitizeHtml()],
                'last_name'     => ['required', 'string', 'max:50', new SanitizeHtml()],
                'address'       => ['required', 'string', 'max:100', new SanitizeHtml()],
                'address_2'     => ['required', 'string', 'max:100', new SanitizeHtml()],
                'city'          => ['required', 'string', 'max:50', new SanitizeHtml()],
                'state'         => ['required', 'string', 'max:50', new SanitizeHtml()],
                'zipcode'       => ['required', 'string', 'max:25', new SanitizeHtml()],
                'country'       => ['required', 'string', 'max:2', 'exists:countries,code'],
                'password'      => ['required', 'string', 'confirmed', 'max:16'],
                'phone'         => ['nullable', 'string', new PhoneRule()],
                'mobile_phone'  => ['nullable', 'string', new PhoneRule()]
            ];

            $validatedData = $request->validate($rules);

            $user = $site->users()->where('invite_code', $invite_code)->first();

            $user->password = Hash::make($request->input('password'));
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->address = $request->input('address');
            $user->address_2 = $request->input('address_2');
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->zipcode = $request->input('zipcode');
            $user->country = $request->input('country');
            $user->status = User::ACTIVE;
            $user->setup_completed = User::SETUP_COMPLETED;
            $user->invite_code = null;
            $user->user_agent = $request->userAgent();
            $user->ip_address = $request->ip();
            $user->email_verified_at = now();
            $user->last_activity_at = now();
            $user->save();

            return redirect()->route('login');
        }

        return redirect('/');
    }
}
