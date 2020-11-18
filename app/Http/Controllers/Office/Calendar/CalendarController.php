<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Calendar;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\CalendarEvent;
use App\Models\System\Office;

/**
 * CalendarsController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office\Calendar
 */
class CalendarController extends Controller
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
        $perPage = 10;

        $calendarEvents = $office->calendarEvents()->latest()->paginate($perPage);

        $breadcrumbs = breadcrumbs(
            [
            __('Dashboard') => [
                'path'      => route('office.dashboard'),
                'active'    => false
            ],
            __('Calendar')  => [
                'path'      => route('office.calendar.index'),
                'active'    => true
            ]
            ]
        );

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
