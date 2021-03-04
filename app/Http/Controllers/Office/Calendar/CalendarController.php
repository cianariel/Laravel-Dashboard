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
use App\Models\System\Appointment;
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
            'end_time'      => ['nullable', 'string', 'date_format:g:i A', 'different:start_time'],
            'recurring'     => ['required', 'string', Rule::in(CalendarEvent::RECURRING_TYPES)],
            'repeat_type'   => ['required_if:recurring,true', 'string', Rule::in(CalendarEvent::REPEAT_TYPES)],
            'notes'         => ['nullable', 'string', 'max:250', new SanitizeHtml]
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->passes()) {

            if(!$user->hasRole(Role::OWNER)) {
                $userOwner = office_owner($user);
            } else {
                $userOwner = $user;
            }

            $office = $userOwner->offices()->first();
            $holidaysClosedList = $office->getMetaField('holidays_closed', []);

            $dialogMessage = '';
            $appointmentDate = carbon($request->input('date'));
            $appointmentDateYear = $appointmentDate->format('Y');
            $currentDate = now();

            $holidays = CalendarEvent::HOLIDAYS;

            if(count($holidaysClosedList) !== 0) {

                foreach($holidaysClosedList as $closedDay => $value ) {

                    if($value == 'on') {

                        if(isset($holidays[$closedDay])) {
                            $holidayDate = $holidays[$closedDay];
                            $holidayDate = carbon(strtotime($holidayDate))->format('m/d');
                            $holidayDate = carbon(strtotime($holidayDate . '/'. $appointmentDateYear));
                            $closeDayLabel = ucwords(str_replace('_', ' ', $closedDay));

                            if($holidayDate->format('m/d/Y') == $appointmentDate->format('m/d/Y')) {
                                $dialogMessage = __("Office is closed on {$closeDayLabel}.");
                                return redirect()->route('office.dashboard', ['errors' => true, 'dialog_message' => $dialogMessage])->withInput()->withErrors($validator);
                            }

                        }
                    }

                }
            }

            if(
                $repUser = User::role(Role::USER)->where('username', $request->input('username'))
                                ->where('status', User::ACTIVE)->first()
            ) {

                if(carbon($appointmentDate)->greaterThanOrEqualTo(now())) {
                    $calendarEvent = new CalendarEvent;
                    $calendarEvent->uuid = Str::uuid();
                    $calendarEvent->reference = unique_reference('calendar_event');
                    $calendarEvent->title = $request->input('title');
                    $calendarEvent->start_at = carbon(strtotime($request->input('start_time')));

                    if(filled($request->input('end_time'))) {
                        $calendarEvent->ends_at = carbon(strtotime($request->input('end_time')));
                    }

                    if($request->input('recurring') == CalendarEvent::RECURRING) {
                        $calendarEvent->setMetaField('repeat', $request->input('repeat_type'), false);
                    }

                    $calendarEvent->setMetaField('section', $request->input('section'), false);
                    $calendarEvent->setMetaField('date', $appointmentDate->format('m/d/Y'), false);
                    $calendarEvent->setMetaField($request->input('notes'), null);

                    $calendarEvent->save();
                    $user->assignCalendarEvent($calendarEvent);
                    $site->assignCalendarEvent($calendarEvent);

                    // Appointment
                    $appointment = new Appointment;
                    $appointment->uuid = Str::uuid();
                    $appointment->reference = unique_reference('appointment');
                    $appointment->description = $calendarEvent->setMetaField('notes', '');
                    $appointment->status = Appointment::SCHEDULED;
                    $appointment->scheduled_on = carbon(strtotime($calendarEvent->getMetaField('date')));
                    $appointment->setMetaField('rep_username', $repUser->username);
                    $appointment->setMetaField('created_by_username'.  $user->username);
                    $appointment->save();

                    $site->assignAppointment($appointment);
                    $appointment->assignCalendarEvent($calendarEvent);
                    $appointment->assignUser($repUser);
                    $appointment->assignOffice($office);

                    if($user->id == $userOwner->id) {
                        $appointment->assignUser($user);
                    } else {
                        $appointment->assignUser($user);
                        $appointment->assignUser($userOwner);
                    }

                    flash(__('Successfully added appointment.'));
                    return back();
                } else {
                    return redirect()->route('office.dashboard', ['errors'])->withInput()->withErrors($validator);
                }
            }
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
