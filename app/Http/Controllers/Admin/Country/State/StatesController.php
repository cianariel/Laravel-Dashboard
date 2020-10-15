<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Country\State;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\Country;
use App\Models\System\State;
use App\Rules\SanitizeHtml;

class StatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $country
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $country)
    {
        if (Country::where('id', $country)->exists()) {
            $query = $request->query();
            $perPage = 10;
            $withTrashed = false;

            $country = Country::where('id', $country)->firstOrFail();

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
                $states = $country->states()->withTrashed()->orderBy('code', 'asc')->paginate($perPage);
            } else {
                $states = $country->states()->orderBy('code', 'asc')->paginate($perPage);
            }


            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                          'active' => false],
                'Countries'     => ['path' => route('admin.countries.index'),             'active' => false],
                $country->name  => ['path' => route('admin.countries.edit', $country),    'active' => false],
                'States'        => ['path' => route('admin.states.index', $country),      'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.countries.states.index', compact('breadcrumbs', 'country', 'states', 'query', 'withTrashed'));
        }

        flash('Country does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $country
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $country)
    {
        if (Country::where('id', $country)->exists()) {
            $country = Country::where('id', $country)->firstOrFail();
            $status_types = State::STATUS_TYPES;

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                          'active' => false],
                'Countries'     => ['path' => route('admin.countries.index'),             'active' => false],
                $country->name  => ['path' => route('admin.states.index', $country),      'active' => false],
                'Add State'     => ['path' => route('admin.states.index', $country),      'active' => true]
            ];


            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.countries.states.create', compact('breadcrumbs', 'country', 'status_types'));
        }

        flash('Country does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $country
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $country)
    {
        if (Country::where('id', $country)->exists()) {
            $country = Country::where('id', $country)->firstOrFail();

            $rules = [
                'code'      => ['required', 'string', 'alpha', 'max:2'],
                'name'      => ['required', 'string', 'max:50', new SanitizeHtml],
                'status'    => ['required', 'string', Rule::in(State::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $state = new State;
            $state->code = $request->input('code');
            $state->name = strip_tags($request->input('name'));
            $state->status = $request->input('status');

            $state->saveOrFail();
            $country->assignState($state);

            flash('Successfully added state.');
            return redirect()->route('admin.states.edit', ['country' => $country, 'state' => $state]);
        }

        flash('Country does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $country
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($country, $id)
    {
        return redirect()->route('admin.states.edit', ['country' => $country, 'state' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $country
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $country, $id)
    {
        if (Country::where('id', $country)->exists() && State::where('id', $id)->exists()) {
            $country = Country::where('id', $country)->firstOrFail();
            $state = $country->states()->where('id', $id)->firstOrFail();
            $status_types = State::STATUS_TYPES;

            $breadcrumbs = [
                'Dashboard'     => ['path' => admin_url(),                          'active' => false],
                'Countries'     => ['path' => route('admin.countries.index'),             'active' => false],
                $country->name  => ['path' => route('admin.states.index', $country),      'active' => false],
                'Edit State'    => ['path' => route('admin.states.index', $country),      'active' => true]
            ];

            $breadcrumbs = breadcrumbs($breadcrumbs);

            return view('admin.countries.states.edit', compact('breadcrumbs', 'country', 'state', 'status_types'));
        }

        flash('Country or state does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $country, $id)
    {
        if ($request->isMethod('put') && Country::where('id', $country)->exists() && State::where('id', $id)->exists()) {
            $country = Country::where('id', $country)->firstOrFail();
            $state = $country->states()->where('id', $id)->firstOrFail();

            $rules = [
                'code'      => ['required', 'string', 'alpha', 'max:2'],
                'name'      => ['required', 'string', 'max:50', new SanitizeHtml],
                'status'    => ['required', 'string', Rule::in(State::STATUS_TYPES)]
            ];

            $validatedData = $request->validate($rules);

            $state->code = $request->input('code');
            $state->name = strip_tags($request->input('name'));
            $state->status = $request->input('status');
            $state->saveOrFail();

            flash('Successfully updated state');
            return redirect()->route('admin.states.edit', ['country' => $country, 'state' => $id]);
        }

        flash('Country or state does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $country, $id)
    {
        if ($request->isMethod('delete') && Country::where('id', $country)->exists() && State::where('id', $id)->exists()) {
            $country = Country::where('id', $country)->firstOrFail();
            $state = $country->states()->where('id', $id)->firstOrFail();

            $state->status = State::INACTIVE;
            $state->saveOrFail();

            $state->delete();

            flash('Successfully deleted state from country.');
            return redirect()->route('admin.states.index', $country);
        }

        flash('Country or state does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $country, $id)
    {
        if ($request->isMethod('put') && Country::where('id', $country)->exists() && State::withTrashed()->where('id', $id)->exists()) {
            $country = Country::where('id', $country)->firstOrFail();
            $state = $country->states()->where('id', $id)->withTrashed()->firstOrFail();

            $state->restore();

            flash('Successfully restored state to country from trash.');
            return redirect()->route('admin.states.edit', ['country' => $country, 'state' => $state]);
        }

        flash('Country or state does not exist.');
        return redirect()->route('admin.countries.index');
    }

    /**
     * Force delete the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete(Request $request, $country, $id)
    {
        if ($request->isMethod('delete') && Country::where('id', $country)->exists() && State::withTrashed()->where('id', $id)->exists()) {
            $country = Country::where('id', $country)->firstOrFail();
            $state = $country->states()->where('id', $id)->withTrashed()->firstOrFail();

            $country->unassignState($state);
            $state->forceDelete();

            flash('Successfully deleted state from country forever');
            return redirect()->route('admin.states.index', $country);
        }

        flash('Country or state does not exist.');
        return redirect()->route('admin.countries.index');
    }
}
