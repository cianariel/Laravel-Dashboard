<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Http\Request;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\CalendarEvent;

/**
 * OfficeController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office
 */
class OfficeController extends BaseController
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
        $events = [];

        $office = office_owner($user)->offices()->first();

        if (
            $user->setup_completed == User::SETUP_COMPLETED
            || $user->setup_completed == User::SETUP_IGNORED
        ) {
            $breadcrumbs = breadcrumbs([
                __('Dashboard')     => [
                    'path'          => route('office.dashboard'),
                    'active'        => true
                ]
            ]);

            if(
                $calendarEvents = $office->calendarEvents()
                                         ->where('status', CalendarEvent::ACTIVE)
                                         ->whereMonth('start_at', now()->format('m'))
                                         ->select(CalendarEvent::SIMPLE_QUERY_SELECT)
                                         ->cursor()
            ) {

                if($calendarEvents->count() != 0) {
                    foreach($calendarEvents as $calendarEvent) {
                        $events[] = [
                            'title'     => $calendarEvent->title,
                            'start'     => carbon($calendarEvent->start_at)->format('Y-m-d'),
                            'end'       => carbon($calendarEvent->ends_at)->format('Y-m-d'),
                            'editable'  => false,
                        ];
                    }
                }
            }

            $reps = $site->users()->role(Role::USER)->where('status', User::ACTIVE)->cursor();
            $repUsers = [];
            $approvedReps = $office->getMetaField('approved_users', []);

            if($reps->count() !== 0) {
                foreach($reps as $rep) {
                    if($rep->subscribed('default') === true) {
                        if(in_array($rep->username, $approvedReps) === true) {
                            $repUsers[$rep->username] = ('(' . $rep->company?? Str::upper($rep->username)) . ')' . " {$rep->first_name} {$rep->last_name}";
                        }

                    }
                }
            }

            $calendarOptions = [
                'themeSystem'   => 'bootstrap',
                'initialView'   => 'dayGridMonth',
                'events'        => $events
            ];

            return view('office.dashboard.index', compact('site', 'user', 'breadcrumbs', 'calendarOptions', 'repUsers'));
        } else {
            return redirect()->route('office.setup.account');
        }

        flash(__('Unauthorized Access.'))->error();
        return redirect()->route('login');
    }
}
