<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\System\Role;
use App\Models\System\User;
use App\Rules\SanitizeHtml;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'account_type'          => ['required', 'string', Rule::in(Role::OWNER, Role::USER)],
            'title'                 => ['required', 'string', 'max:100', new SanitizeHtml],
            'company'               => ['required', 'string', 'max:150', new SanitizeHtml],
            'first_name'            => ['required', 'string', 'max:50', new SanitizeHtml],
            'last_name'             => ['required', 'string', 'max:50', new SanitizeHtml],
            'email'                 => ['required', 'email', 'unique:system.users,email'],
            'password'              => ['required', 'max:16', 'confirmed'],
            'g-recaptcha-response'  => ['required', 'captcha']
        ];

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $site = site();

        $role = Role::where('name', $data['account_type'])->first();

        $user = new User;
        $user->uuid = Str::uuid();
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->username = unique_username($role->name);
        $user->company = $data['company'];
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->setMetaField('title', $data['title'], false);
        $user->status = User::ACTIVE;
        $user->setup_completed = User::SETUP_INCOMPLETE;
        $user->user_agent = request()->userAgent();
        $user->ip_address = request()->ip();
        $user->email_verified_at = now();
        $user->last_activity_at = now();
        $user->save();

        $user->assignRole($role);

        return $user;
    }
}
