<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\System\Role;
use App\Models\System\User;

/**
 * Admin Controller
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin
 */
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     * @access public
     */
    public function __construct()
    {
        $roles = [Role::ADMIN];
        $this->middleware('force.https');
        $this->middleware('auth');
        $this->middleware('role:' . implode('|', $roles));
        $this->middleware('user:' . User::GUARD);
    }
}
