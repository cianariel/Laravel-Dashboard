<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front\Index;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;

/**
 * IndexController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Front\Index
 */
class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('xss.sanitization');
        $this->middleware('user.subscribed');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site(config('app.base_domain'));

        if(auth()->check())
        {
            $user = auth()->guard(User::GUARD)->user();

            // If user is rep, redrects to user.setup.account
            if ($user->hasRole(Role::USER)) {
                return redirect()->route('user.setup.account');
            }

            // If user is office, redrects to user.setup.account
            elseif($user->hasRole(Role::OWNER)) {
                return redirect()->route('office.setup.account');
            }
        }


        return view('frontend.index.index', compact('site'));
    }
}
