<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

/**
 * System Controller
 *
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\System
 */
class SystemController extends AdminController
{
    /**
     * Show Systems CPU/Memory usage
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function showUsage(Request $request)
    {

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'System'        => ['path' => route('admin.system.usage'),            'active' => true],
            'Usage'         => ['path' => route('admin.system.usage'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.system.usage', compact('breadcrumbs'));
    }

    /**
     * Show System Caches
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function showCache(Request $request)
    {
        $query = $request->query();
        $perPage = 10;

        if (filled($query)) {
            if ($request->has('per_page')) {
                $perPage = strip_tags(trim($query['per_page']));

                if (is_numeric($perPage)) {
                    $perPage = safe_integer($perPage);
                } else {
                    $perPage = 10;
                    $query['per_page'] = $per_page;
                }
            }
        }

        $caches = DB::table('cache')
            ->select(['key', 'expiration'])
            ->paginate($perPage);

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'System'        => ['path' => route('admin.system.usage'),            'active' => true],
            'Cache'         => ['path' => route('admin.system.cache'),            'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.system.caches', compact('breadcrumbs', 'caches', 'query'));
    }

    /**
     * Show System Logs
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function showLogs(Request $request)
    {
        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'System'        => ['path' => route('admin.system.usage'),            'active' => true],
            'Logs'          => ['path' => route('admin.system.logs'),             'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.system.logs', compact('breadcrumbs'));
    }

    /**
     * Flush System Cache
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function flushCache(Request $request)
    {
        if ($request->isMethod('delete')) {
            Cache::flush();

            flash('Successfully flushed system cache.');
            return redirect()->route('admin.system.cache');
        }

        flash('Invaild action.');
        return redirect()->route('admin.system.cache');
    }
}
