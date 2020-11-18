<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\System\Role;
use App\Models\System\User;

/**
 * Office BaseController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office
 */
class BaseController extends Controller
{
    /**
     * Constructor
     *
     * @return App\Http\Office\BaseController
     */
    public function __construct()
    {
        $roles = [Role::OWNER, Role::GUEST];
        $this->middleware('force.https');
        $this->middleware('auth');
        $this->middleware('role:' . implode('|', $roles));
        $this->middleware('user:' . User::GUARD);
    }
}
