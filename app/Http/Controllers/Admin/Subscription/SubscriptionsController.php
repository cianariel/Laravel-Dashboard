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
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Controllers\Admin\Subscription
 */
class SubscriptionsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
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

        $subscriptions = Subscription::paginate($perPage);

        $breadcrumbs = [
            __('Dashboard')     => [
                'path'          => admin_url(),
                'active'        => false
            ],
            __('Subscriptions')     => [
                'path'          => route('admin.subscriptions.index'),
                'active'        => true
            ]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.subscriptions.index', compact('site', 'user', 'breadcrumbs', 'subscriptions', 'perPage', 'query'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($site->subscriptions()->where('id', $id)->exists()) {

            $subscription = $site->subscriptions()->where('id', safe_integer($id))->firstOrFail();

            $breadcrumbs = [
                __('Dashboard')     => [
                    'path'          => admin_url(),
                    'active'        => false
                ],
                __('Subscriptions') => [
                    'path'          => route('admin.subscriptions.index'),
                    'active'        => false
                ],
                __('Show')          => [
                    'path'          => route('admin.subscriptions.show', $subscription),
                    'active'        => true
                ]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view(
                'admin.subscriptions.show',
                compact('site', 'user', 'breadcrumbs', 'subscription')
            );
        }

        flash(__('Subscription does not exist.'));
        return redirect()->route('admin.subscriptions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($site->subscriptions()->where('id', $id)->exists()) {

            $subscription = $site->subscriptions()->where('id', safe_integer($id))->firstOrFail();

            $breadcrumbs = [
                __('Dashboard')     => [
                    'path'          => admin_url(),
                    'active'        => false
                ],
                __('Subscriptions')     => [
                    'path'          => route('admin.subscriptions.index'),
                    'active'        => false
                ],
                __('Edit')          => [
                    'path'          => route('admin.subscriptions.edit', $subscription),
                    'active'        => true
                ]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view(
                'admin.subscriptions.edit',
                compact('site', 'user', 'breadcrumbs', 'subscription')
            );
        }

        flash(__('Subscription does not exist.'));
        return redirect()->route('admin.subscriptions.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if($site->subscriptions()->where('id', $id)->exists()) {
            $rules = [
                'status'    => ['required', 'string', Rule::in(Subscription::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $subscription = $site->subscriptions()->where('id', safe_integer($id))->firstOrFail();

            $subscription->status = $request->input('status');
            $subscription->save();

            flash(__('Subscription successfully updated.'));
            return redirect()->route('admin.subscriptions.index');
        }

        flash(__('Subscription does not exist.'));
        return redirect()->route('admin.subscriptions.index');
    }
}
