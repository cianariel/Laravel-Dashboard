<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\System\Database;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;

/**
 * DatabasesController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\System\Database
 */
class DatabasesController extends AdminController
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
        $query = $request->query();
        $perPage = 10;

        $breadcrumbs = [
            __('Dashboard')     => [
                'path'          => admin_url(),
                'active'        => false
            ],
            __('System')        => [
                'path'          => admin_url(),
                'active'        => false
            ],
            __('Databases')     => [
                'path'          => route('admin.system.databases.index'),
                'active'        => true,
            ]
        ];

        $databases  = DB::select('SHOW DATABASES');

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.system.databases', compact('site', 'user', 'breadcrumbs', 'databases', 'perPage', 'query'));
    }
}
