<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\TimeZone;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\TimeZone;
use App\Rules\SanitizeHtml;

class TimeZonesController extends AdminController
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
        $withTrashed = false;

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

            if ($request->has('with_trashed')) {
                $with_trashed  = strip_tags($query['with_trashed']);

                if ($with_trashed == 'true') {
                    $withTrashed  = true;
                }
            }
        }

        if ($withTrashed === true) {
            $timezones = TimeZone::withTrashed()->orderBy('zone', 'asc')->paginate($perPage);
        } else {
            $timezones = TimeZone::orderBy('zone', 'asc')->paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Time Zones'    => ['path' => route('admin.timezones.index'),         'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.timezones.index', compact('breadcrumbs', 'timezones', 'withTrashed', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Timezones'     => ['path' => route('admin.timezones.index'),         'active' => false],
            'Add Timezone'  => ['path' => route('admin.timezones.create'),        'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        $status_types = TimeZone::STATUS_TYPES;

        return view('admin.timezones.create', compact('breadcrumbs', 'status_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'zone'      => ['string', 'required', 'timezone', 'unique:system.timezones,zone'],
                'status'    => ['string', 'required', Rule::in(TimeZone::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $timezone = new TimeZone;
            $timezone = $request->input('zone');
            $timezone = $request->input('status');
            $timezone->saveOrFail();

            flash('Successfully added timezone.');
            return redirect()->route('admin.timezones.edit', $timezone);
        }

        flash('Invalid action.');
        return redirect()->route('admin.currencies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.timezones.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (TimeZone::where('id', $id)->exists()) {
            $timezone = TimeZone::where('id', $id)->firstOrFail();

            $status_types = TimeZone::STATUS_TYPES;

            $breadcrumbs = [
                'Dashboard'         => ['path' => admin_url(),                          'active' => false],
                'Timezones'         => ['path' => route('admin.timezones.index'),             'active' => false],
                'Edit Timezone'     => ['path' => route('admin.timezones.edit', $timezone),   'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.timezones.edit', compact('breadcrumbs', 'timezone', 'status_types'));
        }

        flash('Timezone does not exist.');
        return redirect()->route('admin.timezones.index');
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
        if ($request->isMethod('put') && TimeZone::where('id', $id)->exists()) {
            $rules = [
                'zone'      => ['string', 'required', 'timezone'],
                'status'    => ['string', 'required', Rule::in(TimeZone::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $timezone = TimeZone::where('id', $id)->firstOrFail();

            if ($request->input('zone') != $timezone->zone && !TimeZone::where('zone', $request->input('zone')->exists())) {
                $timezone = $request->input('zone');
                $timezone->saveOrFail();
            }

            flash('Successfully updated timezone');
            return redirect()->route('admin.timezones.edit', $timezone);
        }

        flash('Invalid action. Timezone does not exist.');
        return redirect()->route('admin.timezones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && TimeZone::where('id', $id)->exists()) {
            $timezone = TimeZone::where('id', $id)->firstOrFail();

            if ($timezone) {
                $timezone->status = TimeZone::INACTIVE;
                $timezone->saveOrFail();
                $timezone->delete();

                flash('Successfully deleted timezone.');
                return redirect()->route('admin.timezones.index');
            }
        }

        flash('Invalid action or timezone does not exist.');
        return redirect()->route('admin.timezones.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->isMethod('put') && TimeZone::where('id', $id)->withTrashed()->exists()) {
            $timezone = TimeZone::where('id', $id)->withTrashed()->firstOrFail();

            if ($timezone) {
                $timezone->restore();

                flash('Successfully restored timezone');
                return redirect()->route('admin.timezones.edit', $timezone);
            }
        }

        flash('Invalid action or timezone does not exist.');
        return redirect()->route('admin.timezones.index');
    }

    /**
     * Permanently the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->isMethod('delete') && TimeZone::where('id', $id)->withTrashed()->exists()) {
            $timezone = TimeZone::where('id', $id)->withTrashed()->firstOrFail();

            if ($timezone) {
                $timezone->forceDelete();

                flash('Successfully deleted timezone forever.');
                return redirect()->route('admin.timezones.index');
            }
        }

        flash('Invalid action or timezone does not exist.');
        return redirect()->route('admin.timezones.index');
    }
}
