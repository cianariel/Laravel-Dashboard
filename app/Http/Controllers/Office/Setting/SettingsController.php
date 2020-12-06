<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office\Setting;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\System\CalendarEvent;
use App\Models\System\Country;
use App\Models\System\Office;
use App\Models\System\State;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Site;
use App\Rules\SanitizeHtml;
use App\Rules\PhoneRule;
use Exception;

/**
 * SettingsController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office\Setting
 */
class SettingsController extends BaseController
{
    /**
     * Edit office settings.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            if ($user->setup_completed == User::SETUP_COMPLETED) {
                return redirect()->route('office.settings.edit.general.section', ['section' => 'office_info']);
            }

            return redirect()->route('office.setup.account');
        }

        flash(__('Unauthorized access.'));
        return redirect()->route('office.dashboard');
    }

    /**
     * Save office settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            $rules = [
                'office'        => ['required', 'string', 'max:100', new SanitizeHtml()],
                'first_name'    => ['required', 'string', 'max:100', new SanitizeHtml()],
                'last_name'     => ['required', 'string', 'max:100', new SanitizeHtml()],
                'address'       => ['required', 'string', 'max:100', new SanitizeHtml()],
                'address_2'     => ['nullable', 'string', 'max:100', new SanitizeHtml()],
                'city'          => ['required', 'string', 'max:100', new SanitizeHtml()],
                'zipcode'       => ['required', 'string', 'max:25', new SanitizeHtml()],
                'state'         => ['required', 'string', 'max:100', new SanitizeHtml()],
                'country'       => ['required', 'string', 'exists:system.countries,code', Rule::in(['US'])],
                'phone'         => ['required', 'string', new PhoneRule(), new SanitizeHtml()],
                'mobile_phone'  => ['required', 'string', new PhoneRule(), new SanitizeHtml()]
            ];

            $validatedData = $request->validate($rules);

            $office = $user->offices()->first();

            // Add office profile details
            $office->setMetaField('owner', ['first_name' => $request->input('first_name'), 'last_name' => $request->input('last_name')], false);
            $address = [
                'address'   => $request->input('address'),
                'address_2' => $request->input('address_2'),
                'city'      => $request->input('city'),
                'zipcode'   => $request->input('zipcode'),
                'state'     => $request->input('state'),
                'country'   => $request->input('country')
            ];

            $office->setMetaField('location', $address, false);
            $office->setMetaField('phone', clean_phone($request->input('phone')), false);
            $office->setMetaField('mobile_phone', clean_phone($request->input('mobile_phone')), false);
            $office->save();
            $user->save();

            flash(__('Successfully updated settings.'));
            return redirect()->route('office.settings.edit');
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Edit offices general settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editGeneralSettings(Request $request, string $section = '')
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            $breadcrumbs = breadcrumbs([
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('General')  => [
                    'path'          => route('office.settings.edit.general'),
                    'active'        => true
                ]
            ]);

            if (filled($section)) {
                $section = Str::lower(strip_tags(trim($section)));
            }

            $office = $user->offices()->first();

            switch ($section) {
                case 'office_info':
                    $countries = countries(false);
                    $_countries = [];

                    foreach ($countries as $country) {
                        if ($countries->status = Country::ACTIVE) {
                            $_countries[$country->code] = $country->name;
                        }
                    }

                    $countries = $_countries;

                    return view(
                        'office.settings.general',
                        compact('site', 'user', 'breadcrumbs', 'section', 'office', 'countries')
                    );
                case 'holidays':
                    return view(
                        'office.settings.general',
                        compact('site', 'user', 'breadcrumbs', 'section', 'office')
                    );
                case 'appointments':
                    return view(
                        'office.settings.general',
                        compact('site', 'user', 'breadcrumbs', 'section', 'office')
                    );
                case 'office_hours':
                    return view(
                        'office.settings.general',
                        compact('site', 'user', 'breadcrumbs', 'section', 'office')
                    );
                case 'visitation_rules':
                    return view(
                        'office.settings.general',
                        compact('site', 'user', 'breadcrumbs', 'section', 'office')
                    );
                case 'recurring_appointments':
                    return view(
                        'office.settings.general',
                        compact('site', 'user', 'breadcrumbs', 'section', 'office')
                    );
                default:
                    $countries = countries(false);
                    $_countries = [];

                    foreach ($countries as $country) {
                        if ($countries->status = Country::ACTIVE) {
                            $_countries[$country->code] = $country->name;
                        }
                    }

                    $countries = $_countries;

                    return view(
                        'office.settings.general',
                        compact('site', 'user', 'breadcrumbs', 'section', 'office', 'countries')
                    );
            }
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Update general setting handler
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $section
     * @return \Illuminate\Http\Response
     */
    public function updateGeneralSettings(Request $request, string $section = '')
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            if (filled($section)) {
                $section = Str::lower(strip_tags(trim($section)));
            }

            $office = $user->offices()->first();

            switch ($section) {
                case 'holidays':
                    return $this->updateHoldaySettings($request, $site, $user, $office);
                case 'visitation_rules':
                    return $this->updateVisitationSettings($request, $site, $user, $office);
                case 'office_hours':
                    return $this->updateOfficeHours($request, $site, $user, $office);
            }
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Saves holiday settings for office.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\System\Site
     * @param  \App\Models\System\User
     * @param  \App\Models\System\Office
     * @return \Illuminate\Http\Response
     */
    private function updateHoldaySettings(Request $request, Site $site, User $user, Office $office)
    {
        $rules = [
            'holidays'      => ['nullable', 'array'],
            'holidays.*'    => ['nullable', 'string', Rule::in(['on'])]
        ];

        $validatedData = $request->validate($rules);

        if (filled($request->input('holidays'))) {
            $holidays = $request->input('holidays');

            $office->setMetaField('holidays_closed', $holidays);
            $office->save();

            flash(__('Successfully saved'));
            return redirect()->route('office.settings.edit.general.section', 'holidays');
        }
    }

    /**
     * Saves office hours settings for office.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\System\Site
     * @param  \App\Models\System\User
     * @param  \App\Models\System\Office
     * @return \Illuminate\Http\Response
     */
    private function updateOfficeHours(Request $request, Site $site, User $user, Office $office)
    {
        $rules = [
            'days'                                  => ['nullable', 'array'],
            'days.monday'                           => ['nullable', 'array'],
            'days.tuesday'                          => ['nullable', 'array'],
            'days.wednesday'                        => ['nullable', 'array'],
            'days.thursday'                         => ['nullable', 'array'],
            'days.friday'                           => ['nullable', 'array'],
            'days.saturday'                         => ['nullable', 'array'],
            'days.sunday'                           => ['nullable', 'array'],
            'days.monday.enabled'                   => ['nullable', 'string', new SanitizeHtml()],
            'days.tuesday.enabled'                  => ['nullable', 'string', new SanitizeHtml()],
            'days.wednesday.enabled'                => ['nullable', 'string', new SanitizeHtml()],
            'days.thursday.enabled'                 => ['nullable', 'string', new SanitizeHtml()],
            'days.friday.enabled'                   => ['nullable', 'string', new SanitizeHtml()],
            'days.saturday.enabled'                 => ['nullable', 'string', new SanitizeHtml()],
            'days.sunday.enabled'                   => ['nullable', 'string', new SanitizeHtml()],
        ];

        $days = $request->input('days');

        if (filled($days)) {
            foreach ($days as $key => $day) {
                switch ($key) {
                    case 'monday':
                        if (isset($day['enabled'])) {
                            if ($day['enabled'] == 'on') {
                                $rules['days.monday.start_hour'] = ['required','date_format:h:i'];
                                $rules['days.monday.start_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                                $rules['days.monday.end_hour'] = ['required','date_format:h:i'];
                                $rules['days.monday.end_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                            }
                        }
                        break;
                    case 'tuesday':
                        if (isset($day['enabled'])) {
                            if ($day['enabled'] == 'on') {
                                $rules['days.tuesday.start_hour'] = ['required','date_format:h:i'];
                                $rules['days.tuesday.start_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                                $rules['days.tuesday.end_hour'] = ['required','date_format:h:i'];
                                $rules['days.tuesday.end_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                            }
                        }
                        break;
                    case 'wednesday':
                        if (isset($day['enabled'])) {
                            if ($day['enabled'] == 'on') {
                                $rules['days.wednesday.start_hour'] = ['required','date_format:h:i'];
                                $rules['days.wednesday.start_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                                $rules['days.wednesday.end_hour'] = ['required','date_format:h:i'];
                                $rules['days.wednesday.end_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                            }
                        }
                        break;
                    case 'thursday':
                        if (isset($day['enabled'])) {
                            if ($day['enabled'] == 'on') {
                                $rules['days.thursday.start_hour'] = ['required','date_format:h:i'];
                                $rules['days.thursday.start_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                                $rules['days.thursday.end_hour'] = ['required','date_format:h:i'];
                                $rules['days.thursday.end_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                            }
                        }
                        break;
                    case 'friday':
                        if (isset($day['enabled'])) {
                            if ($day['enabled'] == 'on') {
                                $rules['days.friday.start_hour'] = ['required','date_format:h:i'];
                                $rules['days.friday.start_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                                $rules['days.friday.end_hour'] = ['required','date_format:h:i'];
                                $rules['days.friday.end_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                            }
                        }
                        break;
                    case 'saturday':
                        if (isset($day['enabled'])) {
                            if ($day['enabled'] == 'on') {
                                $rules['days.saturday.start_hour'] = ['required','date_format:h:i'];
                                $rules['days.saturday.start_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                                $rules['days.saturday.end_hour'] = ['required','date_format:h:i'];
                                $rules['days.saturday.end_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                            }
                        }
                        break;
                    case 'sunday':
                        if (isset($day['enabled'])) {
                            if ($day['enabled'] == 'on') {
                                $rules['days.sunday.start_hour'] = ['required','date_format:h:i'];
                                $rules['days.sunday.start_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                                $rules['days.sunday.end_hour'] = ['required','date_format:h:i'];
                                $rules['days.sunday.end_hour_meridiem'] = ['required', 'string', Rule::in(['am', 'pm'])];
                            }
                        }
                        break;
                }
            }

            $validatedData = $request->validate($rules);

            foreach ($days as $key => $day) {
                $office->setMetaField('office_hours->' . $key, $day);
            }

            $office->save();

            flash(__('Successfully saved'));
            return redirect()->route('office.settings.edit.general.section', 'office_hours');
        }
    }

    /**
     * Create recurring calendar event
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createRecurringCalendarEvent(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            $rules = [
                'recurring_appointments_type'   => ['required', 'string', 'max:50', new SanitizeHtml()],
                'section_type'                  => ['required', 'string', Rule::in(CalendarEvent::VISIT_TYPES)],
                'start_time'                    => ['required', 'date_format:h:i A', 'before:end_time'],
                'end_time'                      => ['required', 'date_format:h:i A', 'after:start_time'],
                'repeat_type'                   => ['required', 'string', Rule::in(CalendarEvent::REPEAT_TYPES)],
                'repeat_day'                    => ['required_if:repeat_type,weekly', 'string', Rule::in(CalendarEvent::DAYS)],
                'repeat_month_day'              => ['required_if:repeat_type,monthly', 'string', 'date_format:m/d/Y']
            ];



            $validator = Validator::make($request->all(), $rules);

            if ($validator->passes()) {
                $office = $user->offices()->first();
                $title = $request->input('recurring_appointments_type');
                $sectionType = $request->input('section_type');
                $startTime = $request->input('start_time');
                $endTime = $request->input('end_time');
                $repeatType = $request->input('repeat_type');
                $repeatDay = $request->input('repeat_day');
                $repeatMonthDay = $request->input('repeat_month_day');
                $startTimeFormated = \Carbon\Carbon::parse($startTime);
                $endTimeFormated = \Carbon\Carbon::parse($endTime);

                if ($calendarEvents = $office->calendarEvents()->where('recurring', CalendarEvent::RECURRING)->cursor()) {
                    if ($calendarEvents->count() !== 0) {
                    } else {
                        $calendarEvent = new CalendarEvent();
                        $calendarEvent->uuid = Str::uuid();
                        $calendarEvent->title = $title;
                        $calendarEvent->recurring = CalendarEvent::RECURRING;
                        $calendarEvent->status = CalendarEvent::ACTIVE;
                        $calendarEvent->start_at = $startTimeFormated;
                        $calendarEvent->ends_at = $endTimeFormated;
                        $calendarEvent->setMetaField('type', $sectionType);
                        $calendarEvent->setMetaField('repeat_type', $repeatType);
                        $calendarEvent->save();

                        if($repeatType == CalendarEvent::REPEAT_WEEKLY) {
                            $calendarEvent->setMetaField('repeat_day', $repeatDay);
                        }else {
                            $calendarEvent->setMetaField('repeat_month_day', $repeatMonthDay);
                        }

                        $office->assignCalendarEvent($calendarEvent);
                        $site->assignCalendarEvent($calendarEvent);

                        flash(__('Successfully saved recurring appointment.'));

                        return redirect()->route('office.settings.edit.general.section', [
                                    'section'   => 'recurring_appointments'
                        ]);
                    }
                }
            }

            return redirect()->route('office.settings.edit.general.section', [
                                    'section'   => 'recurring_appointments',
                                    'modal'     => 'true'
                            ])->withErrors($validator)
                              ->withInput();
        }

        flash(__('Unauthorized Access'));
        return redirect('/');
    }

    /**
     * Saves visitation settings for office.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\System\Site
     * @param  \App\Models\System\User
     * @param  \App\Models\System\Office
     * @return \Illuminate\Http\Response
     */
    private function updateVisitationSettings(Request $request, Site $site, User $user, Office $office)
    {
         $rules = [
            'require_approve_appointments'  => ['nullable', 'string', Rule::in('on')]
         ];

         $validatedData = $request->validate($rules);

         if ($request->input('require_approve_appointments') == 'on') {
             $office->setMetaField('visitation_rules->require_approve_appointments', 'on');
         } else {
             $office->setMetaField('visitation_rules->require_approve_appointments', 'off');
         }

         $office->save();

         flash(__('Successfully saved'));
         return redirect()->route('office.settings.edit.general.section', 'visitation_rules');
    }

    /**
     * Edit offices settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editOfficesSettings(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            $breadcrumbs = breadcrumbs([
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('Offices')       => [
                    'path'          => route('office.settings.edit.offices'),
                    'active'        => true
                ]
            ]);

            return view('office.settings.offices', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Edit office calendar settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editCalendarSettings(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            $breadcrumbs = breadcrumbs([
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('Calendar')      => [
                    'path'          => route('office.settings.edit.calendar'),
                    'active'        => true
                ]
            ]);

            return view('office.settings.calendar', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Edit office settings
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function editSubscriptionSettings(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->hasRole(Role::OWNER)) {
            $breadcrumbs = breadcrumbs([
                __('Dashboard')        => [
                    'path'          => route('office.dashboard'),
                    'active'        => false
                ],
                __('Settings')      => [
                    'path'          => route('office.settings.edit'),
                    'active'        => false
                ],
                __('Subscription')  => [
                    'path'          => route('office.settings.edit.subscription'),
                    'active'        => true
                ]
            ]);

            return view('office.settings.subscription', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }
}
