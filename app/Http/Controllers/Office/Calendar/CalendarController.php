<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Calendar;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\CalendarEvent;
use App\Models\System\Office;
use App\Rules\SanitizeHtml;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * CalendarsController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office\Calendar
 */
class CalendarController extends BaseController
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
        $office = office_owner($user)->offices()->first();
        $perPage = 25;
        $currentMonth = current_month();

        $calendarEvents = $office->calendarEvents()->latest()->paginate($perPage);

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('office.dashboard'),
                'active'    => false
            ],
            __('Calendar')  => [
                'path'      => route('office.calendar.index'),
                'active'    => true
            ]
        ]);

        return view(
            'office.calendar.index',
            compact('site', 'user', 'office', 'breadcrumbs', 'calendarEvents', 'perPage')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        $rules = [
            'username'      => ['required', 'exists:system.users,username'],
            'title'         => ['required', 'string', 'max:100', new SanitizeHtml],
            'section'       => ['required', 'string', Rule::in(CalendarEvent::VISIT_TYPES)],
            'date'          => ['required', 'string', 'date_format:m/d/Y', 'after_or_equal:today'],
            'start_time'    => ['required', 'string', 'date_format:g:i A'],
            'end_time'      => ['nullable', 'string', 'date_format:g:i A'],
            'recurring'     => ['required', 'string', Rule::in(CalendarEvent::RECURRING_TYPES)],
            'repeat_type'   => ['nullable', 'string', Rule::in(CalendarEvent::REPEAT_TYPES)],
            'notes'         => ['nullable', 'string', 'max:250', new SanitizeHtml]
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

            if(!$user->hasRole(Role::OWNER)) {
                $userOwner = office_owner($user);
            } else {
                $userOwner = $user;
            }

            flash('Successfully added appointment.');
            return back();
        }

        return redirect()->route('office.dashboard', ['errors'])->withInput()->withErrors($validator);
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
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();
    }
}
