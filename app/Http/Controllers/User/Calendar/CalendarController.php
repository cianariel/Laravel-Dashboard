<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Calendar;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\CalendarEvent;
use App\Models\System\Appointment;
use App\Models\System\Office;
use App\Rules\SanitizeHtml;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * CalendarsController
 *
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\User\Calendar
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
            // 'username'      => ['required', 'exists:system.users,username'],
            'title'         => ['required', 'string', 'max:100', new SanitizeHtml],
            'section'       => ['required', 'string', Rule::in(CalendarEvent::VISIT_TYPES)],
            'date'          => ['required', 'string', 'date_format:m/d/Y', 'after_or_equal:today'],
            'start_time'    => ['required', 'string', 'date_format:h:i A'],
            'end_time'      => ['nullable', 'string', 'date_format:h:i A', 'different:start_time'],
            'recurring'     => ['required', 'string', Rule::in(CalendarEvent::RECURRING_TYPES)],
            'repeat_type'   => ['required_if:recurring,true', 'string', Rule::in(CalendarEvent::REPEAT_TYPES)],
            'notes'         => ['nullable', 'string', 'max:250', new SanitizeHtml],
            'return_url'    => ['required', 'string', 'url']
        ];

        $validator = Validator::make($request->all(), $rules);

        $redirectUrl = $request->input('return_url');

        if($validator->passes()) {

            flash(__('Successfully added appointment.'));
            return redirect($redirectUrl);
        }

        return redirect("{$redirectUrl}?errors=true")->withInput()->withErrors($validator);
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
