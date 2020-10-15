<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Module;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\System\Module;
use App\Models\System\Package;
use App\Rules\SanitizeHtml;

/**
 * Modules Resource Controller
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package App\Http\Controllers\Admin\Module
 */
class ModulesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site();
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

        $modules = $site->modules()->paginate($perPage);

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                            'active' => false],
            'Modules'       => ['path' => route('admin.modules.index'),           'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.modules.index', compact('breadcrumbs', 'modules', 'query'));
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
        $site = site(config('app.base_domain'));

        if ($site->modules()->where('id', $id)->exists()) {
            $module = $site->modules()->where('id', safe_integer($id))->firstOrFail();
            $_packages = $site->packages()->where('status', Package::ACTIVE)->select(['id', 'label'])->cursor();
            $packages = [];

            if ($_packages->count() !== 0) {
                foreach ($_packages as $package) {
                    $packages[$package->id] = $package->label;
                }
            }


            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                                  'active' => false],
                'Modules'       => ['path' => route('admin.modules.index'),                 'active' => false],
                'Edit Module'   => ['path' => route('admin.modules.edit', $module),         'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.modules.edit', compact('breadcrumbs', 'module', 'packages'));
        }

        flash(__('Module does not exist.'));
        return redirect()->route('admin.modules.index');
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
        $site = site(config('app.base_domain'));

        if ($request->isMethod('put') && $site->modules()->where('id', $id)->exists()) {
            $rules = [
                'package_id'    => ['required', 'integer', 'exists:system.packages,id'],
                'label'         => ['required', 'string', 'max:150' , new SanitizeHtml],
                'status'        => ['required', 'string', Rule::in(Module::STATUS_TYPES)]
            ];


            $validatedData = $request->validate($rules);
            $module = $site->modules()->where('id', safe_integer($id))->firstOrFail();

            $module->package_id = safe_integer($request->input('package_id'));
            $module->label = $request->input('label');
            $module->status = $request->input('status');
            $module->saveOrFail();


            flash(__('Successfully updated module.'));
            return redirect()->route('admin.modules.edit', $module);
        }

        flash(__('Module does not exist or invaild action'));
        return redirect()->route('admin.modules.index');
    }
}
