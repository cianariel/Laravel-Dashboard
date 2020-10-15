<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Redirect;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Role;
use App\Models\System\Redirect;
use App\Rules\SanitizeHtml;

/**
 * Admin Redirect Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Http\Controllers\Admin\Redirect
 */
class RedirectsController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

        $site = site();
        $redirects = $site->redirects()->paginate($perPage);

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Redirects'     => ['path' => route('admin.redirects.index'),         'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.redirects.index', compact('breadcrumbs', 'redirects', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $redirect_codes = Redirect::REDIRECT_CODES;
        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Redirects'     => ['path' => route('admin.redirects.index'),         'active' => false],
            'Add Redirect'  => ['path' => route('admin.redirects.create'),        'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);
        return view('admin.redirects.create', compact('breadcrumbs', 'redirect_codes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $domains = [config('app.base_domain'), config('app.www_domain')];
            $excluded = [
                '{any}',
                'api/user',
                'api/*',
                '/',
                'stripe*',
                'users*',
                'login',
                'logout',
                'register',
                'password/*',
                '_ignition*',
                '*{slug}'
            ];

            $routes = routes($domains, $excluded);

            $rules = [
                'name'          =>  ['required', 'string', 'unique:system.redirects,name', 'max:100'],
                'path'          =>  ['required', 'string'],
                'redirect_path' =>  ['required', 'string'],
                'code'          =>  ['required', 'integer', Rule::in(array_keys(Redirect::REDIRECT_CODES))],
            ];

            $validatedData = $request->validate($rules);

            $redirect = new Redirect();
            $redirect->name = Str::slug($request->input('name'));
            $redirect->path = $request->input('path');
            $redirect->code = $request->input('code');
            $redirect->redirect_path = $request->input('redirect_path');
            $redirect->saveOrFail();

            $site = site();
            $site->assignRedirect($redirect);

            flash('Successfully created redirect.');
            return redirect()->route('admin.redirects.edit', $redirect);
        }

        flash('Invaild action.');
        return redirect()->route('admin.redirects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.redirects.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Redirect::where('id', $id)->exists()) {
            $site = site();
            $redirect = $site->redirects()->where('id', $id)->firstOrFail();
            $redirect_codes = Redirect::REDIRECT_CODES;

            $breadcrumbs = [
                'Dashboard'         => ['path' => admin_url(),                                  'active' => false],
                'Redirects'         => ['path' => route('admin.redirects.index'),                     'active' => false],
                'Edit Redirect'     => ['path' => route('admin.redirects.edit', $redirect),           'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);
            return view('admin.redirects.edit', compact('breadcrumbs', 'redirect', 'redirect_codes'));
        }

        flash('Redirect does not exist.');
        return redirect()->route('admin.redirects.index');
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
        if ($request->isMethod('put') && Redirect::where('id', $id)->exists()) {
            $site = site();
            $redirect = $site->redirects()->where('id', $id)->firstOrFail();

            $domains = [config('app.base_domain'), config('app.www_domain')];
            $excluded = [
                '{any}',
                'api/user',
                'api/*',
                '/',
                'stripe*',
                'users*',
                'login',
                'logout',
                'register',
                'password/*',
                '_ignition*',
                '*{slug}'
            ];

            $routes = routes($domains, $excluded);

            $rules = [
                'path'          =>  ['required', 'string'],
                'redirect_path' =>  ['required', 'string'],
                'code'          =>  ['required', 'integer', Rule::in(array_keys(Redirect::REDIRECT_CODES))],
            ];

            $validatedData = $request->validate($rules);

            $redirect->path = $request->input('path');
            $redirect->redirect_path = $request->input('redirect_path');
            $redirect->code = $request->input('code');
            $redirect->saveOrFail();

            flash('Successfully updated redirect.');
            return redirect()->route('admin.redirects.edit', $redirect);
        }

        flash('Invaild action or redirect does not exist.');
        return redirect()->route('admin.redirects.index');
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
        if ($request->isMethod('delete') && Redirect::where('id', $id)->exists()) {
            $site = site();
            $redirect = $site->redirects()->where('id', $id)->firstOrFail();

            $site->unassignRedirect($redirect);
            $redirect->delete();

            flash('Successfully deleted redirect.');
            return redirect()->route('admin.redirects.index');
        }

        flash('Invaild action or redirect does not exist.');
        return redirect()->route('admin.redirects.index');
    }
}
