<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Country;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Country;
use App\Models\System\State;
use App\Rules\SanitizeHtml;

class CountriesController extends AdminController
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
                $with_trashed  = strip_tags(trim($query['with_trashed']));

                if ($with_trashed == 'true') {
                    $withTrashed  = true;
                }
            }
        }

        if ($withTrashed === true) {
            $countries = Country::withTrashed()->paginate($perPage);
        } else {
            $countries = Country::paginate($perPage);
        }

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Countries'     => ['path' => route('admin.countries.index'),         'active' => true],
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.countries.index', compact('breadcrumbs', 'countries', 'query', 'withTrashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status_types = Country::STATUS_TYPES;

        $breadcrumbs = [
            'Dashboard'     => ['path' => admin_url(),                      'active' => false],
            'Countries'     => ['path' => route('admin.countries.index'),         'active' => false],
            'Add Country'   => ['path' => route('admin.countries.create'),        'active' => true]
        ];

        $breadcrumbs = breadcrumbs($breadcrumbs);

        return view('admin.countries.create', compact('breadcrumbs', 'status_types'));
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
            $rules = [
                'code'      => ['string', 'required', 'alpha' ,'max:2', 'unique:system.countries,code'],
                'name'      => ['string', 'required', 'max:190', 'unique:system.countries,name', new SanitizeHtml],
                'status'    => ['string', 'required', Rule::in(Country::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $country = new Country;
            $country->code = $request->input('code');
            $country->name = strip_tags($request->input('name'));
            $country->status = $request->input('status');
            $country->saveOrFail();

            flash('Successfully added country');
            return redirect()->route('admin.states.index', $country);
        }

        flash('Invaild action.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Country::where('id', $id)->exists()) {
            $country = Country::where('id', $id)->firstOrFail();

            return redirect()->route('admin.states.index', $country);
        } else {
            flash('Country does not exist.');
            return redirect()->route('admin.countries.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Country::where('id', $id)->exists()) {
            $status_types = Country::STATUS_TYPES;
            $country = Country::where('id', $id)->firstOrFail();

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                              'active' => false],
                'Countries'     => ['path' => route('admin.countries.index'),                 'active' => false],
                'Edit Country'   => ['path' => route('admin.countries.edit', $country),       'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.countries.edit', compact('breadcrumbs', 'country', 'status_types'));
        }

        flash('Country does not exist.');
        return redirect()->route('admin.countries.index');
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
        if ($request->isMethod('put') && Country::where('id', $id)->exists()) {
            $country = Country::where('id', $id)->firstOrFail();

            $rules = [
                'name'      => ['string', 'required', 'max:190', new SanitizeHtml],
                'status'    => ['string', 'required', Rule::in(Country::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $name = strip_tags($request->input('name'));

            if ($name != $country->name && !Country::where('name', $name)->exists()) {
                $country->name = $name;
            }

            $country->status = $request->input('status');
            $country->saveOrFail();

            flash('Successfully updated country');
            return redirect()->route('admin.states.index', $country);
        }

        flash('Invaild action.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->isMethod('delete') && Country::where('id', $id)->exists()) {
            $country = Country::where('id', $id)->firstOrFail();

            $country->status = Country::INACTIVE;
            $country->saveOrFail();
            $country->delete();

            flash('Successfully deleted country');
            return redirect()->route('admin.countries.index');
        }

        flash('Invaild action or country does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        if ($request->isMethod('put') && Country::where('id', $id)->withTrashed()->exists()) {
            $country = Country::where('id', $id)->withTrashed()->firstOrFail();

            $country->status = Country::INACTIVE;
            $country->delete();

            flash('Successfully deleted country.');
            return redirect()->route('admin.countries.index');
        }

        flash('Invaild action or country does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Force delete the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $id)
    {
        if ($request->isMethod('delete') && Country::where('id', $id)->withTrashed()->exists()) {
            $country = Country::where('id', $id)->withTrashed()->firstOrFail();
            $states = $country->states()->cursor();

            // Cleanup
            if ($states->count() !== 0) {
                foreach ($states as $state) {
                    $country->unassignState($state);
                    $state->forceDelete();
                }
            }

            $country->forceDelete();

            flash('Successfully deleted country forever.');
            return redirect()->route('admin.countries.index');
        }

        flash('Invaild action or country does not exist.');
        return redirect()->route('admin.countries.index');
    }
}
