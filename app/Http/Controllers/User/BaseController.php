<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\System\Role;
use App\Models\System\User;

/**
 * BaseController
 *
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\User
 */
class BaseController extends Controller
{
    /**
     * Constructor
     *
     * @return \App\Http\User\BaseController
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('verified');
        $this->middleware('user.subscribed', ['except' => ['selectSubscription']]);
        $this->middleware('xss.sanitization');
        $this->middleware('auth');
        $this->middleware('role:' . Role::USER);
        $this->middleware('user:' . User::GUARD);
    }
}
