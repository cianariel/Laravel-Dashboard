<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Models\System\User;

/**
 * Dashboard Controller
 *
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\Dashboard
 */
class DashboardController extends AdminController
{
    /**
     * Dashboard Index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.dashboard.index');
    }
}
