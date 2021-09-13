<?php

declare(strict_types=1);

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Office\BaseController;
use Illuminate\Http\Request;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\CalendarEvent;
use App\Models\System\Appointment;

/**
 * OfficeController
 *
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\Office
 */
class OfficeController extends BaseController
{

    public function __construct() {
        parent::__construct();
    }

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
        $events = collect([]);

        if($user->hasRole(Role::OWNER)) {
            $officeOwner = $user;
        } else {
            $officeOwner = office_owner($user);
        }

        $office = $officeOwner->offices()->first();


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
                $appointments = $office->appointments()
                                         ->where('status', Appointment::SCHEDULED)
                                         ->cursor()
            ) {

                if($appointments->count() !== 0) {
                    foreach($appointments as $appointment) {

                        if(
                            $repUser = $site->users()
                                        ->where('id', $appointment->user_id)
                                        ->where('status', User::ACTIVE)
                                        ->first()
                        ) {

                            if($repUser->subscribed('default') === true) {

                                $name = $repUser->company?? "{$repUser->first_name} {$repUser->last_name}";

                                $description = ( $name . ' - ' . (carbon($appointment->scheduled_on)->format('g:i A')) . ' to ' . carbon($appointment->scheduled_end)->format('g:i A'));

                                $events->push( [
                                    'id'        => $appointment->uuid,
                                    'title'     => $description,
                                    'start'     => carbon($appointment->scheduled_on)->format('Y-m-d'),
                                    'end'       => carbon($appointment->scheduled_end)->format('Y-m-d'),
                                    'editable'  => false,
                                ]);
                            }

                        }
                    }
                }
            }

            $reps = $site->users()->role(Role::USER)->where('status', User::ACTIVE)->get();

            $repUsers = [];

            if($office->getMetaField('visitation_rules->require_approve_appointments', 'off') == 'on') {
                $approvedReps = $office->getMetaField('approved_users', []);

                if($reps->count() !== 0 && filled($approvedReps)) {
                    foreach($reps as $rep) {
                        if($rep->subscribed('default') === true) {
                            if(in_array($rep->username, $approvedReps) === true) {
                                $repUsers[$rep->username] = ('(' . $rep->company?? Str::upper($rep->username)) . ')' . " {$rep->first_name} {$rep->last_name}";
                            }

                        }
                    }
                }
            } else {
                foreach($reps as $rep) {
                    $repUsers[$rep->username] = ('(' . $rep->company?? Str::upper($rep->username)) . ')' . " {$rep->first_name} {$rep->last_name}";
                }
            }


            $calendarOptions = [
                'themeSystem'   => 'bootstrap',
                'initialView'   => 'dayGridMonth',
                'events'        => $events
            ];

            return view('office.dashboard.index', compact('site', 'user', 'breadcrumbs', 'calendarOptions', 'repUsers', 'events'));
        } else {
            return redirect()->route('office.setup.account');
        }

        flash(__('Unauthorized Access.'))->error();
        return redirect()->route('login');
    }
}
