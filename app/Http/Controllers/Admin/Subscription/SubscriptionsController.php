<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Subscription;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\Subscription;
use App\Rules\SanitizeHtml;

/**
 * SubscriptionsController
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Subscription
 */
class SubscriptionsController extends AdminController
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

        $subscriptions = $site->subscriptions()->paginate($perPage);

        $breadcrumbs = [
            'Dashboard'         => [
                'path'          => admin_url(),
                'active'        => false
            ],
            'Subscriptions'     => [
                'path'          => route('admin.subscriptions.index'),
                'active'        => true
            ]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.subscriptions.index', compact('site', 'user','breadcrumbs', 'subscriptions', 'perPage', 'query'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        $breadcrumbs = [
            'Dashboard'         => [
                'path'          => admin_url(),
                'active'        => false
            ],
            'Subscriptions'     => [
                'path'          => route('admin.subscriptions.index'),
                'active'        => true
            ]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.subscriptions.index', compact('site', 'user','breadcrumbs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
    }
}
